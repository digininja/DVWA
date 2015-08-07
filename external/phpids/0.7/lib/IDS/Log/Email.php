<?php

/**
 * PHPIDS
 *
 * Requirements: PHP5, SimpleXML
 *
 * Copyright (c) 2008 PHPIDS group (https://phpids.org)
 *
 * PHPIDS is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, version 3 of the License, or 
 * (at your option) any later version.
 *
 * PHPIDS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with PHPIDS. If not, see <http://www.gnu.org/licenses/>. 
 *
 * PHP version 5.1.6+
 *
 * @category Security
 * @package  PHPIDS
 * @author   Mario Heiderich <mario.heiderich@gmail.com>
 * @author   Christian Matthies <ch0012@gmail.com>
 * @author   Lars Strojny <lars@strojny.net>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 * @link     http://php-ids.org/
 */

require_once 'IDS/Log/Interface.php';

/**
 * Email logging wrapper
 *
 * The Email wrapper is designed to send reports via email. It implements the
 * singleton pattern.
 *
 * @category  Security
 * @package   PHPIDS
 * @author    Christian Matthies <ch0012@gmail.com>
 * @author    Mario Heiderich <mario.heiderich@gmail.com>
 * @author    Lars Strojny <lars@strojny.net>
 * @copyright 2007-2009 The PHPIDS Group
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL
 * @version   Release: $Id:Email.php 517 2007-09-15 15:04:13Z mario $
 * @link      http://php-ids.org/
 */
class IDS_Log_Email implements IDS_Log_Interface
{

    /**
     * Recipient container
     *
     * @var array
     */
    protected $recipients    = array();

    /**
     * Mail subject
     *
     * @var string
     */
    protected $subject = null;

    /**
     * Additional mail headers
     *
     * @var string
     */
    protected $headers = null;

    /**
     * Safemode switch
     *
     * Using this switch it is possible to enable safemode, which is a spam
     * protection based on the alert frequency.
     *
     * @var boolean
     */
    protected $safemode = true;

    /**
     * Urlencode for result strings
     *
     * This switch is true by default. Setting it to false removes 
     * the 'better safe than sorry' urlencoding for the result string in 
     * the report mails. Enhances readability but maybe XSSes email clients.
     *
     * @var boolean
     */
    protected $urlencode = true;

    /**
     * Send rate
     *
     * If safemode is enabled, this property defines how often reports will be
     * sent out. Default value is 15, which means that a mail will be sent on
     * condition that the last email has not been sent earlier than 15 seconds ago.
     *
     * @var integer
     */
    protected $allowed_rate = 15;

    /**
     * PHPIDS temp directory
     *
     * When safemod is enabled, a path to a temp directory is needed to
     * store some information. Default is IDS/tmp/
     *
     * @var string
     */
    protected $tmp_path = 'IDS/tmp/';

    /**
     * File prefix for tmp files
     *
     * @var string
     */
    protected $file_prefix = 'PHPIDS_Log_Email_';

    /**
     * Holds current remote address
     *
     * @var string
     */
    protected $ip = 'local/unknown';

    /**
     * Instance container
     *
     * @var array
     */
    protected static $instance = array();

    /**
     * Constructor
     *
     * @param mixed $config IDS_Init instance | array
     *
     * @return void
     */
    protected function __construct($config)
    {

        if ($config instanceof IDS_Init) {
            $this->recipients   = $config->config['Logging']['recipients'];
            $this->subject      = $config->config['Logging']['subject'];
            $this->headers      = $config->config['Logging']['header'];
            $this->envelope     = $config->config['Logging']['envelope'];
            $this->safemode     = $config->config['Logging']['safemode'];
            $this->urlencode    = $config->config['Logging']['urlencode'];
            $this->allowed_rate = $config->config['Logging']['allowed_rate'];
            $this->tmp_path     = $config->getBasePath() 
                . $config->config['General']['tmp_path'];

        } elseif (is_array($config)) {
            $this->recipients[]      = $config['recipients'];
            $this->subject           = $config['subject'];
            $this->additionalHeaders = $config['header'];
        }

        // determine correct IP address and concat them if necessary
        $this->ip = $_SERVER['REMOTE_ADDR'] .
            (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ?
            ' (' . $_SERVER['HTTP_X_FORWARDED_FOR'] . ')' : '');
    }

    /**
     * Returns an instance of this class
     *
     * This method allows the passed argument to be either an instance of
     * IDS_Init or an array.
     *
     * @param  mixed  $config    IDS_Init | array
     * @param  string $classname the class name to use
     *
     * @return object $this
     */
    public static function getInstance($config, $classname = 'IDS_Log_Email')
    {
        if (!self::$instance) {
            self::$instance = new $classname($config);
        }

        return self::$instance;
    }

    /**
     * Permitting to clone this object
     *
     * For the sake of correctness of a singleton pattern, this is necessary
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Detects spam attempts
     *
     * To avoid mail spam through this logging class this function is used
     * to detect such attempts based on the alert frequency.
     *
     * @return boolean
     */
    protected function isSpamAttempt()
    {

        /*
        * loop through all files in the tmp directory and
        * delete garbage files
        */
        $dir = $this->tmp_path;
        $numPrefixChars = strlen($this->file_prefix);
        $files = scandir($dir);
        foreach ($files as $file) {
            if (is_file($dir . DIRECTORY_SEPARATOR . $file)) {
                if (substr($file, 0, $numPrefixChars) == $this->file_prefix) {
                    $lastModified = filemtime($dir . DIRECTORY_SEPARATOR . $file);
                    if ((time() - $lastModified) > 3600) {
                        unlink($dir . DIRECTORY_SEPARATOR . $file);
                    }
                }
            }
        }

        /*
        * end deleting garbage files
        */
        $remoteAddr = $this->ip;
        $userAgent  = $_SERVER['HTTP_USER_AGENT'];
        $filename   = $this->file_prefix . md5($remoteAddr.$userAgent) . '.tmp';
        $file       = $dir . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($file)) {
            $handle = fopen($file, 'w');
            fwrite($handle, time());
            fclose($handle);

            return false;
        }

        $lastAttack = file_get_contents($file);
        $difference = time() - $lastAttack;
        if ($difference > $this->allowed_rate) {
            unlink($file);
        } else {
            return true;
        }

        return false;
    }

    /**
     * Prepares data
     *
     * Converts given data into a format that can be read in an email.
     * You might edit this method to your requirements.
     *
     * @param mixed $data the report data
     *
     * @return string
     */
    protected function prepareData($data)
    {

        $format  = "The following attack has been detected by PHPIDS\n\n";
        $format .= "IP: %s \n";
        $format .= "Date: %s \n";
        $format .= "Impact: %d \n";
        $format .= "Affected tags: %s \n";

        $attackedParameters = '';
        foreach ($data as $event) {
            $attackedParameters .= $event->getName() . '=' .
                ((!isset($this->urlencode) ||$this->urlencode) 
                    ? urlencode($event->getValue()) 
                    : $event->getValue()) . ", ";
        }

        $format .= "Affected parameters: %s \n";
        $format .= "Request URI: %s \n";
        $format .= "Origin: %s \n";

        return sprintf($format,
                       $this->ip,
                       date('c'),
                       $data->getImpact(),
                       join(' ', $data->getTags()),
                       trim($attackedParameters),
                       htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8'),
                       $_SERVER['SERVER_ADDR']);
    }

    /**
     * Sends the report to registered recipients
     *
     * @param object $data IDS_Report instance
     *
     * @throws Exception if data is no string
     * @return boolean
     */
    public function execute(IDS_Report $data)
    {

        if ($this->safemode) {
            if ($this->isSpamAttempt()) {
                return false;
            }
        }

        /*
        * In case the data has been modified before it might
        * be necessary to convert it to string since it's pretty
        * senseless to send array or object via e-mail
        */
        $data = $this->prepareData($data);

        if (is_string($data)) {
            $data = trim($data);

            // if headers are passed as array, we need to make a string of it
            if (is_array($this->headers)) {
                $headers = "";
                foreach ($this->headers as $header) {
                    $headers .= $header . "\r\n";
                }
            } else {
                $headers = $this->headers;
            }

            if (!empty($this->recipients)) {
                if (is_array($this->recipients)) {
                    foreach ($this->recipients as $address) {
                        $this->send(
                            $address,
                            $data,
                            $headers,
                            $this->envelope
                        );
                    }
                } else {
                    $this->send(
                        $this->recipients,
                        $data,
                        $headers,
                        $this->envelope
                    );
                }
            }

        } else {
            throw new Exception(
                'Please make sure that data returned by
                 IDS_Log_Email::prepareData() is a string.'
            );
        }

        return true;
    }

    /**
     * Sends an email
     *
     * @param string $address  email address
     * @param string $data     the report data
     * @param string $headers  the mail headers
     * @param string $envelope the optional envelope string
     *
     * @return boolean
     */
    protected function send($address, $data, $headers, $envelope = null)
    {
        if (!$envelope || strpos(ini_get('sendmail_path'),' -f') !== false) {
            return mail($address,
                $this->subject,
                $data,
                $headers);
        } else {
            return mail($address,
                $this->subject,
                $data,
                $headers,
                '-f' . $envelope);
        }
    }
}

/**
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 expandtab
 */
