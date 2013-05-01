<?php

/**
 * PHP IDS
 *
 * Requirements: PHP5, SimpleXML
 *
 * Copyright (c) 2007 PHPIDS (http://php-ids.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @package PHPIDS
 */

/**
 * HOWTO - CakePHP with PHPIDS in ten steps
 *
 * 0. Install the intrusion table with the intrusions.sql
 * 1. Place the phpids core files (see IDS/lib) in you vendors folder:
 *      vendors/
 *          phpids/
 *              IDS/
 *                 Caching/..
 *                 Config/..
 *                 Filter/...
 *                 Log/...
 *                 tmp/... (this folder needs write access!)
 *                 Converter.php
 *                 Event.php
 *                 Monitor.php
 *                 Report.php
 *                 default_filter.xml
 *
 * 2. Place the intrusion.php in your model folder
 * 3. Add component ids to app_controller.php components like this:
 *
 *    var $components = array('RequestHandler', 'Ids', ...);
 *
 * 4. Place the ids.php in you controllers/components folder
 * 5. Add the following code to the app_controller.php - right
 *    beneath the function head of beforeFilter():
 *
 *    //BOF
 *    $bare = isset($this->params['bare'])?$this->params['bare']:0;
 *    if(($bare === 0 || $this->RequestHandler->isAjax()) && DEBUG == 0 && ADMIN == 0) {
 *        $this->Ids->detect($this);
 *    }
 *    //EOF
 *
 * 6. Make sure DEBUG and ADMIN are 0 if you want to test
 * 7. Customize some stuff in the Config/Config.ini if you like
 * 8. Inject some XSS via URL or an arbitrary form of your webapp
 * 9. Please make sure you tested the use of the PHPIDS before you go live
 *
 * If you have problems getting the PHPIDS to work just drop us a line via our forum
 *
 * http://forum.php-ids.org/
 *
 */

/**
 * The CakePHP IDS component
 *
 * @package PHPIDS
 */
class IdsComponent extends Object {

    /**
     * define the threshold for the ids reactions
     */
    private $threshold = array(
        'log'      => 3,
        'mail'     => 9,
        'warn'     => 27,
        'kick'     => 81
    );

    /**
     * define the email addresses for idsmail
     */
    private $email = array(
        'address1@what.ever',
        'address2@what.ever'
    );

    /**
     * the init object
     */
    private $init = NULL;

    /**
     * This function includes the IDS vendor parts and runs the
     * detection routines on the request array.
     *
     * @param object cake controller object
     * @return boolean
     */
    public function detect(&$controller) {

        $this->controller = &$controller;
        $this->name = Inflector::singularize($this->controller->name);

        #set include path for IDS  and store old one
        $path = get_include_path();
        set_include_path( VENDORS . 'phpids/');

        #require the needed files
        vendor('phpids/IDS/Init');

        #add request url and user agent
        $_REQUEST['IDS_request_uri'] = $_SERVER['REQUEST_URI'];
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $_REQUEST['IDS_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        }

        #init the PHPIDS and pass the REQUEST array
        $this->init = IDS_Init::init();
        $ids        = new IDS_Monitor($this->init, $_REQUEST);
        $result     = $ids->run();

        // Re-set include path
        set_include_path($path);

        if (!$result->isEmpty()) {
            $this->react($result);
        }

        return true;
    }

    /**
     * This function rects on the values in
     * the incoming results array.
     *
     * Depending on the impact value certain actions are
     * performed.
     *
     * @param IDS_Report $result
     * @return boolean
     */
    private function react(IDS_Report $result) {

        $new = $this->controller
                ->Session
                ->read('IDS.Impact') + $result->getImpact();


        $this->controller->Session->write('IDS.Impact', $new);
        $impact = $this->controller->Session->read('IDS.Impact');

        if ($impact >= $this->threshold['kick']) {
            $this->idslog($result, 3, $impact);
            $this->idsmail($result);
            $this->idskick($result);
            return true;
        } else if ($impact >= $this->threshold['warn']) {
            $this->idslog($result, 2, $impact);
            $this->idsmail($result);
            $this->idswarn($result);
            return true;
        } else if ($impact >= $this->threshold['mail']) {
            $this->idslog($result, 1, $impact);
            $this->idsmail($result);
            return true;
        } else if ($impact >= $this->threshold['log']) {
            $this->idslog($result, 0, $impact);
            return true;
        } else {
            return true;
        }
    }

    /**
     * This function writes an entry about the intrusion
     * to the intrusion database
     *
     * @param array $results
     * @return boolean
     */
    private function idslog($result, $reaction = 0) {

        $user = $this->controller
            ->Session->read('User.id') ?
                $this->controller->Session->read('User.id') :
                0;

        $ip = ($_SERVER['SERVER_ADDR'] != '127.0.0.1') ?
                    $_SERVER['SERVER_ADDR'] :
                        (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ?
                            $_SERVER['HTTP_X_FORWARDED_FOR'] :
                                 '127.0.0.1');

        foreach ($result as $event) {
            $data = array(
                'Intrusion' => array(
                    'name'      => $event->getName(),
                    'value'     => stripslashes($event->getValue()),
                    'page'      => $_SERVER['REQUEST_URI'],
                    'userid'    => $user,
                    'session'   => session_id() ? session_id() : '0',
                    'ip'        => $ip,
                    'reaction'  => $reaction,
                    'impact'    => $result->getImpact()
                )
            );
        }

        loadModel('Intrusion');
        $intrusion = new Intrusion;
        $saveable  = array('name', 'value', 'page', 'userid', 'session', 'ip', 'reaction', 'impact');
        $intrusion->save($data, false, $saveable);

        return true;
    }

    /**
     * This function sends out a mail
     * about the intrusion including the intrusion details
     *
     * @param array $results
     * @return boolean
     */
    private function idsmail($result) {

        vendor('phpids/IDS/Log/Email.php');
        vendor('phpids/IDS/Log/Composite.php');

        $compositeLog = new IDS_Log_Composite();
        $compositeLog->addLogger(
           IDS_Log_Email::getInstance($this->init->config['IDS_Logging']['recipient'],
                                      $this->config['IDS_Logging']['subject'],
                                      NULL, //optional headers
                                      $this->init->config['IDS_Logging']['safemode'],
                                      $this->init->config['IDS_Logging']['allowed_rate'],
                                      $this->init->config['IDS_Basic']['tmp_path'])
        );

        if (!$result->isEmpty()) {
            $compositeLog->execute($result);
        }

        return true;
    }

    /**
     * //todo
     *
     *
     */
    private function idswarn($result) {
        return $result;
    }

    /**
     *  //todo
     *
     *
     */
    private function idskick($result) {
        return $result;
    }
}
