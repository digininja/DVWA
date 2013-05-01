<?php

/**
 * PHPIDS
 * Requirements: PHP5, SimpleXML
 *
 * Copyright (c) 2007 PHPIDS group (http://php-ids.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

// set the include path properly for PHPIDS
set_include_path(
    get_include_path()
    . PATH_SEPARATOR
    . '../../lib/'
);

if (!session_id()) {
    session_start();
}

require_once 'IDS/Init.php';

try {

    /*
    * It's pretty easy to get the PHPIDS running
    * 1. Define what to scan
    * 
    * Please keep in mind what array_merge does and how this might interfer 
    * with your variables_order settings
    */
    $request = array(
        'REQUEST' => $_REQUEST,
        'GET' => $_GET,
        'POST' => $_POST,
        'COOKIE' => $_COOKIE
    );

    $init = IDS_Init::init(dirname(__FILE__) . '/../../lib/IDS/Config/Config.ini');

    /**
     * You can also reset the whole configuration
     * array or merge in own data
     *
     * This usage doesn't overwrite already existing values
     * $config->setConfig(array('General' => array('filter_type' => 'xml')));
     *
     * This does (see 2nd parameter)
     * $config->setConfig(array('General' => array('filter_type' => 'xml')), true);
     *
     * or you can access the config directly like here:
     */
    
    $init->config['General']['base_path'] = dirname(__FILE__) . '/../../lib/IDS/';
    $init->config['General']['use_base_path'] = true;
    $init->config['Caching']['caching'] = 'none';

    // 2. Initiate the PHPIDS and fetch the results
    $ids = new IDS_Monitor($request, $init);
    $result = $ids->run();

    /*
    * That's it - now you can analyze the results:
    *
    * In the result object you will find any suspicious
    * fields of the passed array enriched with additional info
    *
    * Note: it is moreover possible to dump this information by
    * simply echoing the result object, since IDS_Report implemented
    * a __toString method.
    */
    if (!$result->isEmpty()) {
        echo $result;

        /*
        * The following steps are optional to log the results
        */
        require_once 'IDS/Log/File.php';
        require_once 'IDS/Log/Composite.php';

        $compositeLog = new IDS_Log_Composite();
        $compositeLog->addLogger(IDS_Log_File::getInstance($init));

        /*
        * Note that you might also use different logging facilities
        * such as IDS_Log_Email or IDS_Log_Database
        *
        * Just uncomment the following lines to test the wrappers
        */
        /*
        *
        require_once 'IDS/Log/Email.php';
        require_once 'IDS/Log/Database.php';

        $compositeLog->addLogger(
            IDS_Log_Email::getInstance($init),
            IDS_Log_Database::getInstance($init)
        );
        */
        $compositeLog->execute($result);
        

    } else {
        echo '<a href="?test=%22><script>eval(window.name)</script>">No attack detected - click for an example attack</a>';
    }
} catch (Exception $e) {
    /*
    * sth went terribly wrong - maybe the
    * filter rules weren't found?
    */
    printf(
        'An error occured: %s',
        $e->getMessage()
    );
}
