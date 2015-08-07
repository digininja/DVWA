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
 * File logging wrapper
 *
 * The file wrapper is designed to store data into a flatfile. It implements the
 * singleton pattern.
 *
 * @category  Security
 * @package   PHPIDS
 * @author    Christian Matthies <ch0012@gmail.com>
 * @author    Mario Heiderich <mario.heiderich@gmail.com>
 * @author    Lars Strojny <lars@strojny.net>
 * @copyright 2007-2009 The PHPIDS Group
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL
 * @version   Release: $Id:File.php 517 2007-09-15 15:04:13Z mario $
 * @link      http://php-ids.org/
 */
class IDS_Log_File implements IDS_Log_Interface
{

    /**
     * Path to the log file
     *
     * @var string
     */
    private $logfile = null;

    /**
     * Instance container
     *
     * Due to the singleton pattern this class allows to initiate only one 
     * instance for each file.
     *
     * @var array
     */
    private static $instances = array();

    /**
     * Holds current remote address
     *
     * @var string
     */
    private $ip = 'local/unknown';

    /**
     * Constructor
     *
     * @param string $logfile path to the log file
     * 
     * @return void
     */
    protected function __construct($logfile) 
    {

        // determine correct IP address and concat them if necessary
        $this->ip = $_SERVER['REMOTE_ADDR'] .
            (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ?
                ' (' . $_SERVER['HTTP_X_FORWARDED_FOR'] . ')' : '');

        $this->logfile = $logfile;
    }

    /**
     * Returns an instance of this class
     *
     * This method allows the passed argument to be either an instance of 
     * IDS_Init or a path to a log file. Due to the singleton pattern only one 
     * instance for each file can be initiated.
     *
     * @param  mixed  $config    IDS_Init or path to a file
     * @param  string $classname the class name to use
     * 
     * @return object $this
     */
    public static function getInstance($config, $classname = 'IDS_Log_File') 
    {
        if ($config instanceof IDS_Init) {
            $logfile = $config->getBasePath() . $config->config['Logging']['path'];
        } elseif (is_string($config)) {
            $logfile = $config;
        }
        
        if (!isset(self::$instances[$logfile])) {
            self::$instances[$logfile] = new $classname($logfile);
        }

        return self::$instances[$logfile];
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
     * Prepares data
     *
     * Converts given data into a format that can be stored into a file. 
     * You might edit this method to your requirements.
     *
     * @param mixed $data incoming report data
     * 
     * @return string
     */
    protected function prepareData($data) 
    {

        $format = '"%s",%s,%d,"%s","%s","%s","%s"';

        $attackedParameters = '';
        foreach ($data as $event) {
            $attackedParameters .= $event->getName() . '=' .
                rawurlencode($event->getValue()) . ' ';
        }

        $dataString = sprintf($format,
            urlencode($this->ip),
            date('c'),
            $data->getImpact(),
            join(' ', $data->getTags()),
            urlencode(trim($attackedParameters)),
            urlencode($_SERVER['REQUEST_URI']),
            $_SERVER['SERVER_ADDR']
        );

        return $dataString;
    }

    /**
     * Stores given data into a file
     *
     * @param  object $data IDS_Report
     * 
     * @throws Exception if the logfile isn't writeable
     * @return boolean
     */
    public function execute(IDS_Report $data) 
    {

        /*
         * In case the data has been modified before it might  be necessary 
         * to convert it to string since we can't store array or object 
         * into a file
         */
        $data = $this->prepareData($data);

        if (is_string($data)) {

            if (file_exists($this->logfile)) {
                $data = trim($data);

                if (!empty($data)) {
                    if (is_writable($this->logfile)) {

                        $handle = fopen($this->logfile, 'a');
                        fwrite($handle, trim($data) . "\n");
                        fclose($handle);

                    } else {
                        throw new Exception(
                            'Please make sure that ' . $this->logfile . 
                                ' is writeable.'
                        );
                    }
                }
            } else {
                throw new Exception(
                    'Given file does not exist. Please make sure the
                    logfile is present in the given directory.'
                );
            }
        } else {
            throw new Exception(
                'Please make sure that data returned by
                IDS_Log_File::prepareData() is a string.'
            );
        }

        return true;
    }
}

/**
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 expandtab
 */
