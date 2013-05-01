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

/*
 * Needed SQL:
 *
    CREATE DATABASE IF NOT EXISTS `phpids` DEFAULT CHARACTER 
        SET utf8 COLLATE utf8_general_ci;
    DROP TABLE IF EXISTS `intrusions`;
    CREATE TABLE IF NOT EXISTS `intrusions` (
      `id` int(11) unsigned NOT null auto_increment,
      `name` varchar(128) NOT null,
      `value` text NOT null,
      `page` varchar(255) NOT null,
      `ip` varchar(15) NOT null,
      `impact` int(11) unsigned NOT null,
      `origin` varchar(15) NOT null,
      `created` datetime NOT null,
      PRIMARY KEY  (`id`)
    ) ENGINE=MyISAM ;
 *
 *
 *
 */

/**
 * Database logging wrapper
 *
 * The database wrapper is designed to store reports into an sql database. It 
 * implements the singleton pattern and is based in PDO, supporting 
 * different database types.
 *
 * @category  Security
 * @package   PHPIDS
 * @author    Christian Matthies <ch0012@gmail.com>
 * @author    Mario Heiderich <mario.heiderich@gmail.com>
 * @author    Lars Strojny <lars@strojny.net>
 * @copyright 2007 The PHPIDS Group
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL
 * @version   Release: $Id:Database.php 517 2007-09-15 15:04:13Z mario $
 * @link      http://php-ids.org/
 */
class IDS_Log_Database implements IDS_Log_Interface
{

    /**
     * Database wrapper
     *
     * @var string
     */
    private $wrapper = null;

    /**
     * Database user
     *
     * @var string
     */
    private $user = null;

    /**
     * Database password
     *
     * @var string
     */
    private $password = null;

    /**
     * Database table
     *
     * @var string
     */
    private $table = null;

    /**
     * Database handle
     *
     * @var object  PDO instance
     */
    private $handle    = null;

    /**
     * Prepared SQL statement
     *
     * @var string
     */
    private $statement = null;

    /**
     * Holds current remote address
     *
     * @var string
     */
    private $ip = 'local/unknown';

    /**
     * Instance container
     *
     * Due to the singleton pattern this class allows to initiate only one instance
     * for each database wrapper.
     *
     * @var array
     */
    private static $instances = array();

    /**
     * Constructor
     *
     * Prepares the SQL statement
     *
     * @param mixed $config IDS_Init instance | array
     * 
     * @return void
     */
    protected function __construct($config) 
    {

        if ($config instanceof IDS_Init) {
            $this->wrapper  = $config->config['Logging']['wrapper'];
            $this->user     = $config->config['Logging']['user'];
            $this->password = $config->config['Logging']['password'];
            $this->table    = $config->config['Logging']['table'];

        } elseif (is_array($config)) {
            $this->wrapper  = $config['wrapper'];
            $this->user     = $config['user'];
            $this->password = $config['password'];
            $this->table    = $config['table'];
        }

        // determine correct IP address
        if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
            $this->ip = $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $this->ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        try {
            $this->handle = new PDO(
                $this->wrapper,
                $this->user,
                $this->password
            );

            $this->statement = $this->handle->prepare('
                INSERT INTO ' . $this->table . ' (
                    name,
                    value,
                    page,
                    ip,
                    impact,
                    origin,
                    created
                )
                VALUES (
                    :name,
                    :value,
                    :page,
                    :ip,
                    :impact,
                    :origin,
                    now()
                )
            ');

        } catch (PDOException $e) {
            die('PDOException: ' . $e->getMessage());
        }
    }

    /**
     * Returns an instance of this class
     *
     * This method allows the passed argument to be either an instance of IDS_Init or
     * an array.
     *
     * @param mixed $config IDS_Init | array
     * 
     * @return object $this
     */
    public static function getInstance($config)
    {
        if ($config instanceof IDS_Init) {
            $wrapper = $config->config['Logging']['wrapper'];
        } elseif (is_array($config)) {
            $wrapper = $config['wrapper'];
        }

        if (!isset(self::$instances[$wrapper])) {
            self::$instances[$wrapper] = new IDS_Log_Database($config);
        }

        return self::$instances[$wrapper];
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
     * Stores given data into the database
     *
     * @param object $data IDS_Report instance
     * 
     * @throws Exception if db error occurred
     * @return boolean
     */
    public function execute(IDS_Report $data) 
    {
        if (!isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);
            if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) { 
                $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING']; 
            } 
        }     	

        foreach ($data as $event) {
            $page = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            $ip   = $this->ip;

            $this->statement->bindParam('name', $event->getName());
            $this->statement->bindParam('value', $event->getValue());
            $this->statement->bindParam('page', $page);
            $this->statement->bindParam('ip', $ip);
            $this->statement->bindParam('impact', $data->getImpact());
            $this->statement->bindParam('origin', $_SERVER['SERVER_ADDR']);

            if (!$this->statement->execute()) {

                $info = $this->statement->errorInfo();
                throw new Exception(
                    $this->statement->errorCode() . ', ' . $info[1] . ', ' . $info[2]
                );
            }
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
