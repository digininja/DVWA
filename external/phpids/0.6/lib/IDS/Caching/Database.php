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

require_once 'IDS/Caching/Interface.php';

/**
 * Needed SQL:
 *

    #create the database

    CREATE DATABASE IF NOT EXISTS `phpids` DEFAULT CHARACTER 
        SET utf8 COLLATE utf8_general_ci;
    DROP TABLE IF EXISTS `cache`;

    #now select the created datbase and create the table

    CREATE TABLE `cache` (
        `type` VARCHAR( 32 ) NOT null ,
        `data` TEXT NOT null ,
        `created` DATETIME NOT null ,
        `modified` DATETIME NOT null
    ) ENGINE = MYISAM ;
 */

/**
 * Database caching wrapper
 *
 * This class inhabits functionality to get and set cache via a database.
 *
 * @category  Security
 * @package   PHPIDS
 * @author    Christian Matthies <ch0012@gmail.com>
 * @author    Mario Heiderich <mario.heiderich@gmail.com>
 * @author    Lars Strojny <lars@strojny.net>
 * @copyright 2007 The PHPIDS Groupup
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL
 * @version   Release: $Id:Database.php 517 2007-09-15 15:04:13Z mario $
 * @link      http://php-ids.org/
 * @since     Version 0.4
 */
class IDS_Caching_Database implements IDS_Caching_Interface
{

    /**
     * Caching type
     *
     * @var string
     */
    private $type = null;

    /**
     * Cache configuration
     *
     * @var array
     */
    private $config = null;

    /**
     * DBH
     *
     * @var object
     */
    private $handle = null;

    /**
     * Holds an instance of this class
     *
     * @var object
     */
    private static $cachingInstance = null;

    /**
     * Constructor
     *
     * Connects to database.
     *
     * @param string $type caching type
     * @param array  $init the IDS_Init object
     * 
     * @return void
     */
    public function __construct($type, $init) 
    {
    
        $this->type   = $type;
        $this->config = $init->config['Caching'];
        $this->handle = $this->_connect();
    }

    /**
     * Returns an instance of this class
     *
     * @param string $type caching type
     * @param array  $init the IDS_Init object
     * 
     * @return object $this
     */
    public static function getInstance($type, $init)
    {

        if (!self::$cachingInstance) {
            self::$cachingInstance = new IDS_Caching_Database($type, $init);
        }
        return self::$cachingInstance;
    }

    /**
     * Writes cache data into the database
     *
     * @param array $data the caching data
     * 
     * @throws PDOException if a db error occurred
     * @return object $this
     */
    public function setCache(array $data) 
    {

        $handle = $this->handle;
        
        $rows = $handle->query('SELECT created FROM `' . 
            $handle->quote($this->config['table']).'`');
            
        if (!$rows || $rows->rowCount() === 0) {
        
            $this->_write($handle, $data);             
        } else {

            foreach ($rows as $row) {
                
                if ((time()-strtotime($row['created'])) > 
                  $this->config['expiration_time']) {
                  
                    $this->_write($handle, $data);  
                }
            }        
        }

        return $this;
    }

    /**
     * Returns the cached data
     *
     * Note that this method returns false if either type or file cache is 
     * not set
     *
     * @throws PDOException if a db error occurred
     * @return mixed cache data or false
     */
    public function getCache() 
    {

        try{
            $handle = $this->handle;
            $result = $handle->prepare('SELECT * FROM ' . 
                $handle->quote($this->config['table']) . 
                ' where type=?');
            $result->execute(array($this->type));

            foreach ($result as $row) {
                return unserialize($row['data']);
            }

        } catch (PDOException $e) {
            die('PDOException: ' . $e->getMessage());
        }
        return false;
    }

    /**
     * Connect to database and return a handle
     *
     * @return object dbh
     * @throws PDOException if a db error occurred
     */
    private function _connect() 
    {

        // validate connection parameters
        if (!$this->config['wrapper']
            || !$this->config['user']
                || !$this->config['password']
                    || !$this->config['table']) {

            throw new Exception('
                Insufficient connection parameters'
            );
        }

        // try to connect
        try {
            $handle = new PDO(
                $this->config['wrapper'],
                $this->config['user'],
                $this->config['password']
            );

        } catch (PDOException $e) {
            die('PDOException: ' . $e->getMessage());
        }
        return $handle;
    }
    
    /**
     * Write the cache data to the table
     * 
     * @param object $handle the database handle
     * @param array  $data   the caching data
     * 
     * @return object dbh
     * @throws PDOException if a db error occurred
     */    
    private function _write($handle, $data) 
    {
        
        try {
            $handle->query('TRUNCATE ' . 
                $this->config['table'].'');
            $statement = $handle->prepare('
                INSERT INTO `' . 
                $this->config['table'].'` (
                    type,
                    data,
                    created,
                    modified
                )
                VALUES (
                    :type,
                    :data,
                    now(),
                    now()
                )
            ');
    
            $statement->bindParam('type', 
                $handle->quote($this->type));
            $statement->bindParam('data', serialize($data));
    
            if (!$statement->execute()) {
                throw new PDOException($statement->errorCode());
            }
    
        } catch (PDOException $e) {
            die('PDOException: ' . $e->getMessage());
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
