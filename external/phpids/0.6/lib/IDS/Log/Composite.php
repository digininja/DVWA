<?php

/**
 * PHPIDS
 * 
 * Requirements: PHP5, SimpleXML
 *
 * Copyright (c) 2008 PHPIDS group (http://php-ids.org)
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
 * Log Composite
 *
 * This class implements the composite pattern to allow to work with multiple
 * logging wrappers at once.
 *
 * @category  Security
 * @package   PHPIDS
 * @author    Christian Matthies <ch0012@gmail.com>
 * @author    Mario Heiderich <mario.heiderich@gmail.com>
 * @author    Lars Strojny <lars@strojny.net>
 * @copyright 2007 The PHPIDS Group
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL 
 * @version   Release: $Id:Composite.php 517 2007-09-15 15:04:13Z mario $
 * @link      http://php-ids.org/
 */
class IDS_Log_Composite
{

    /**
     * Holds registered logging wrapper
     *
     * @var array
     */
    public $loggers = array();

    /**
     * Iterates through registered loggers and executes them
     *
     * @param object $data IDS_Report object
     * 
     * @return void
     */
    public function execute(IDS_Report $data) 
    {
    	// make sure request uri is set right on IIS
        if (!isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);
            if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) { 
                $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING']; 
            } 
        } 
        
        // make sure server address is set right on IIS
        if (isset($_SERVER['LOCAL_ADDR'])) {
            $_SERVER['SERVER_ADDR'] = $_SERVER['LOCAL_ADDR'];
        } 
    	
        foreach ($this->loggers as $logger) {
            $logger->execute($data);
        }
    }

    /**
     * Registers a new logging wrapper
     *
     * Only valid IDS_Log_Interface instances passed to this function will be 
     * registered
     *
     * @return void
     */
    public function addLogger() 
    {

        $args = func_get_args();

        foreach ($args as $class) {
            if (!in_array($class, $this->loggers) && 
                ($class instanceof IDS_Log_Interface)) {
                $this->loggers[] = $class;
            }
        }
    }

    /**
     * Removes a logger
     *
     * @param object $logger IDS_Log_Interface object
     * 
     * @return boolean
     */
    public function removeLogger(IDS_Log_Interface $logger) 
    {
        $key = array_search($logger, $this->loggers);

        if (isset($this->loggers[$key])) {
            unset($this->loggers[$key]);
            return true;
        }

        return false;
    }
}

/**
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 expandtab
 */
