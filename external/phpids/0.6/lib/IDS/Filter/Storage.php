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

/**
 * Filter Storage
 *
 * This class provides various default functions for gathering filter patterns 
 * to be used later on by the detection mechanism. You might extend this class 
 * to your requirements.
 *
 * @category  Security
 * @package   PHPIDS
 * @author    Christian Matthies <ch0012@gmail.com>
 * @author    Mario Heiderich <mario.heiderich@gmail.com>
 * @author    Lars Strojny <lars@strojny.net>
 * @copyright 2007 The PHPIDS Group
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL 
 * @version   Release: $Id:Storage.php 517 2007-09-15 15:04:13Z mario $
 * @link      http://php-ids.org/
 */
class IDS_Filter_Storage
{

    /**
     * Filter source file
     *
     * @var string
     */
    protected $source = null;

    /**
     * Holds caching settings
     *
     * @var array
     */
    protected $cacheSettings = null;

    /**
     * Cache container
     *
     * @var object IDS_Caching wrapper
     */
    protected $cache = null;

    /**
     * Filter container
     *
     * @var array
     */
    protected $filterSet = array();

    /**
     * Constructor
     *
     * Loads filters based on provided IDS_Init settings.
     *
     * @param object $init IDS_Init instance
     * 
     * @throws Exception if unsupported filter type is given
     * @return void
     */
    public final function __construct(IDS_Init $init) 
    {
        if ($init->config) {

            $caching = isset($init->config['Caching']['caching']) ? 
                $init->config['Caching']['caching'] : 'none';
                
            $type         = $init->config['General']['filter_type'];
            $this->source = $init->getBasePath() 
                . $init->config['General']['filter_path'];

            if ($caching && $caching != 'none') {
                $this->cacheSettings = $init->config['Caching'];
                include_once 'IDS/Caching/Factory.php';
                $this->cache = IDS_Caching::factory($init, 'storage');
            }

            switch ($type) {
            case 'xml' :
                $this->getFilterFromXML();
                break;
            case 'json' :
                $this->getFilterFromJson();
                break;
            default :
                throw new Exception('Unsupported filter type.');
            }
        }
    }

    /**
     * Sets the filter array
     *
     * @param array $filterSet array containing multiple IDS_Filter instances
     * 
     * @return object $this
     */
    public final function setFilterSet($filterSet) 
    {
        foreach ($filterSet as $filter) {
            $this->addFilter($filter);
        }

        return $this;
    }

    /**
     * Returns registered filters
     *
     * @return array
     */
    public final function getFilterSet() 
    {
        return $this->filterSet;
    }

    /**
     * Adds a filter
     *
     * @param object $filter IDS_Filter instance
     * 
     * @return object $this
     */
    public final function addFilter(IDS_Filter $filter) 
    {
        $this->filterSet[] = $filter;
        return $this;
    }

    /**
     * Checks if any filters are cached
     *
     * @return mixed $filters cached filters or false
     */
    private function _isCached() 
    {
        $filters = false;

        if ($this->cacheSettings) {
        
            if ($this->cache) {
                $filters = $this->cache->getCache();
            }
        }

        return $filters;
    }

    /**
     * Loads filters from XML using SimpleXML
     *
     * This function parses the provided source file and stores the result. 
     * If caching mode is enabled the result will be cached to increase 
     * the performance.
     *
     * @throws Exception if problems with fetching the XML data occur
     * @return object $this
     */
    public function getFilterFromXML() 
    {

        if (extension_loaded('SimpleXML')) {

            /*
             * See if filters are already available in the cache
             */
            $filters = $this->_isCached();

            /*
             * If they aren't, parse the source file
             */
            if (!$filters) {
                if (file_exists($this->source)) {
                    if (LIBXML_VERSION >= 20621) {
                        $filters = simplexml_load_file($this->source,
                                                       null,
                                                       LIBXML_COMPACT);
                    } else {
                        $filters = simplexml_load_file($this->source);
                    }
                }
            }

            /*
             * In case we still don't have any filters loaded and exception
             * will be thrown
             */
            if (empty($filters)) {
                throw new Exception(
                    'XML data could not be loaded.' . 
                        ' Make sure you specified the correct path.'
                );
            }

            /*
             * Now the storage will be filled with IDS_Filter objects
             */
            $data    = array();
            $nocache = $filters instanceof SimpleXMLElement;
            $filters = $nocache ? $filters->filter : $filters;

            include_once 'IDS/Filter.php';

            foreach ($filters as $filter) {

                $id          = $nocache ? (string) $filter->id : 
                    $filter['id'];
                $rule        = $nocache ? (string) $filter->rule : 
                    $filter['rule'];
                $impact      = $nocache ? (string) $filter->impact : 
                    $filter['impact'];
                $tags        = $nocache ? array_values((array) $filter->tags) : 
                    $filter['tags'];
                $description = $nocache ? (string) $filter->description : 
                    $filter['description'];

                $this->addFilter(new IDS_Filter($id,
                                                $rule,
                                                $description,
                                                (array) $tags[0],
                                                (int) $impact));

                $data[] = array(
                    'id'          => $id, 
                    'rule'        => $rule,
                    'impact'      => $impact,
                    'tags'        => $tags,
                    'description' => $description
                );
            }

            /*
             * If caching is enabled, the fetched data will be cached
             */
            if ($this->cacheSettings) {

                $this->cache->setCache($data);
            }

        } else {
            throw new Exception(
                'SimpleXML not loaded.'
            );
        }

        return $this;
    }

    /**
     * Loads filters from Json file using ext/Json
     *
     * This function parses the provided source file and stores the result. 
     * If caching mode is enabled the result will be cached to increase 
     * the performance.
     *
     * @throws Exception if problems with fetching the JSON data occur
     * @return object $this
     */
    public function getFilterFromJson() 
    {

        if (extension_loaded('Json')) {

            /*
             * See if filters are already available in the cache
             */
            $filters = $this->_isCached();

            /*
             * If they aren't, parse the source file
             */
            if (!$filters) {
                if (file_exists($this->source)) {
                    $filters = json_decode(file_get_contents($this->source));
                } else {
                    throw new Exception(
                        'JSON data could not be loaded.' . 
                            ' Make sure you specified the correct path.'
                    );
                }
            }

            if (!$filters) {
                throw new Exception(
                    'JSON data could not be loaded.' . 
                        ' Make sure you specified the correct path.'
                );
            }

            /*
             * Now the storage will be filled with IDS_Filter objects
             */
            $data    = array();
            $nocache = !is_array($filters);
            $filters = $nocache ? $filters->filters->filter : $filters;

            include_once 'IDS/Filter.php';

            foreach ($filters as $filter) {

                $id          = $nocache ? (string) $filter->id : 
                    $filter['id'];            	
                $rule        = $nocache ? (string) $filter->rule : 
                    $filter['rule'];
                $impact      = $nocache ? (string) $filter->impact : 
                    $filter['impact'];
                $tags        = $nocache ? array_values((array) $filter->tags) : 
                    $filter['tags'];
                $description = $nocache ? (string) $filter->description : 
                    $filter['description'];

                $this->addFilter(new IDS_Filter($id,
                                                $rule,
                                                $description,
                                                (array) $tags[0],
                                                (int) $impact));

                $data[] = array(
                    'id'          => $id,
                    'rule'        => $rule,
                    'impact'      => $impact,
                    'tags'        => $tags,
                    'description' => $description
                );
            }

            /*
             * If caching is enabled, the fetched data will be cached
             */
            if ($this->cacheSettings) {
                $this->cache->setCache($data);
            }

        } else {
            throw new Exception(
                'ext/json not loaded.'
            );
        }

        return $this;
    }
}

/**
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 expandtab
 */
