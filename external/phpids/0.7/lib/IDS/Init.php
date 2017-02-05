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

/**
 * Framework initiation
 *
 * This class is used for the purpose to initiate the framework and inhabits
 * functionality to parse the needed configuration file.
 *
 * @category  Security
 * @package   PHPIDS
 * @author    Christian Matthies <ch0012@gmail.com>
 * @author    Mario Heiderich <mario.heiderich@gmail.com>
 * @author    Lars Strojny <lars@strojny.net>
 * @copyright 2007-2009 The PHPIDS Groupup
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL
 * @version   Release: $Id:Init.php 517 2007-09-15 15:04:13Z mario $
 * @link      http://php-ids.org/
 * @since     Version 0.4
 */
class IDS_Init
{

    /**
     * Holds config settings
     *
     * @var array
     */
    public $config = array();

    /**
     * Instance of this class depending on the supplied config file
     *
     * @var array
     * @static
     */
    private static $instances = array();

    /**
     * Path to the config file
     *
     * @var string
     */
    private $configPath = null;

    /**
     * Constructor
     *
     * Includes needed classes and parses the configuration file
     *
     * @param string $configPath the path to the config file
     * 
     * @return object $this
     */
    private function __construct($configPath = null) 
    {
        include_once 'IDS/Monitor.php';
        include_once 'IDS/Filter/Storage.php';

        if ($configPath) {
            $this->setConfigPath($configPath);
            $this->config = parse_ini_file($this->configPath, true);
        }
    }

    /**
     * Permitting to clone this object
     *
     * For the sake of correctness of a singleton pattern, this is necessary
     * 
     * @return void
     */
    public final function __clone() 
    {
    }

    /**
     * Returns an instance of this class. Also a PHP version check 
     * is being performed to avoid compatibility problems with PHP < 5.1.6
     *
     * @param string $configPath the path to the config file
     * 
     * @return object
     */
    public static function init($configPath = null)
    {
        if (!isset(self::$instances[$configPath])) {
            self::$instances[$configPath] = new IDS_Init($configPath);
        }

        return self::$instances[$configPath];
    }

    /**
     * Sets the path to the configuration file
     *
     * @param string $path the path to the config
     * 
     * @throws Exception if file not found
     * @return void
     */
    public function setConfigPath($path) 
    {
        if (file_exists($path)) {
            $this->configPath = $path;
        } else {
            throw new Exception(
                'Configuration file could not be found at ' .
                htmlspecialchars($path, ENT_QUOTES, 'UTF-8')
            );
        }
    }

    /**
     * Returns path to configuration file
     *
     * @return string the config path
     */
    public function getConfigPath() 
    {
        return $this->configPath;
    }

    /**
     * This method checks if a base path is given and usage is set to true. 
     * If all that tests succeed the base path will be returned as a string - 
     * else null will be returned.
     *
     * @return string the base path or null
     */
    public function getBasePath() {
    	
    	return ((isset($this->config['General']['base_path']) 
            && $this->config['General']['base_path'] 
            && isset($this->config['General']['use_base_path']) 
            && $this->config['General']['use_base_path']) 
                ? $this->config['General']['base_path'] : null);
    }
    
    /**
     * Merges new settings into the exsiting ones or overwrites them
     *
     * @param array   $config    the config array
     * @param boolean $overwrite config overwrite flag
     * 
     * @return void
     */
    public function setConfig(array $config, $overwrite = false) 
    {
        if ($overwrite) {
            $this->config = $this->_mergeConfig($this->config, $config);
        } else {
            $this->config = $this->_mergeConfig($config, $this->config);
        }
    }

    /**
     * Merge config hashes recursivly
     *
     * The algorithm merges configuration arrays recursively. If an element is
     * an array in both, the values will be appended. If it is a scalar in both,
     * the value will be replaced.
     *
     * @param  array $current The legacy hash
     * @param  array $successor The hash which values count more when in doubt
     * @return array Merged hash
     */
    protected function _mergeConfig($current, $successor)
    {
        if (is_array($current) and is_array($successor)) {
            foreach ($successor as $key => $value) {
                if (isset($current[$key])
                    and is_array($value)
                    and is_array($current[$key])) {

                    $current[$key] = $this->_mergeConfig($current[$key], $value);
                } else {
                    $current[$key] = $successor[$key];
                }
            }
        }
        return $current;
    }

    /**
     * Returns the config array
     *
     * @return array the config array
     */
    public function getConfig() 
    {
        return $this->config;
    }
}

/**
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 expandtab
 */
