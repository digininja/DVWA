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
 * PHPIDS event object
 *
 * This class represents a certain event that occured while applying the filters 
 * to the supplied data. It aggregates a bunch of IDS_Filter implementations and 
 * is a assembled in IDS_Report.
 *
 * Note that this class implements both Countable and IteratorAggregate
 *
 * @category  Security
 * @package   PHPIDS
 * @author    Christian Matthies <ch0012@gmail.com>
 * @author    Mario Heiderich <mario.heiderich@gmail.com>
 * @author    Lars Strojny <lars@strojny.net>
 * @copyright 2007 The PHPIDS Group
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL
 * @version   Release: $Id:Event.php 517 2007-09-15 15:04:13Z mario $
 * @link      http://php-ids.org/
 */
class IDS_Event implements Countable, IteratorAggregate
{

    /**
     * Event name
     *
     * @var scalar
     */
    protected $name = null;

    /**
     * Value of the event
     *
     * @var scalar
     */
    protected $value = null;

    /**
     * List of filter objects
     *
     * Filter objects in this array are those that matched the events value
     *
     * @var array
     */
    protected $filters = array();

    /**
     * Calculated impact
     *
     * Total impact of the event
     *
     * @var integer
     */
    protected $impact = 0;

    /**
     * Affecte tags
     *
     * @var array
     */
    protected $tags = array();

    /**
     * Constructor
     *
     * Fills event properties
     *
     * @param scalar $name    the event name
     * @param scalar $value   the event value
     * @param array  $filters the corresponding filters
     * 
     * @return void
     */
    public function __construct($name, $value, Array $filters) 
    {
        if (!is_scalar($name)) {
            throw new InvalidArgumentException(
                'Expected $name to be a scalar,' . gettype($name) . ' given'
            );
        }

        if (!is_scalar($value)) {
            throw new InvalidArgumentException('
                Expected $value to be a scalar,' . gettype($value) . ' given'
            );
        }

        $this->name  = $name;
        $this->value = $value;

        foreach ($filters as $filter) {
            if (!$filter instanceof IDS_Filter) {
                throw new InvalidArgumentException(
                    'Filter must be derived from IDS_Filter'
                );
            }

            $this->filters[] = $filter;
        }
    }

    /**
     * Returns event name
     *
     * The name of the event usually is the key of the variable that was 
     * considered to be malicious
     *
     * @return scalar
     */
    public function getName() 
    {
        return $this->name;
    }

    /**
     * Returns event value
     *
     * @return scalar
     */
    public function getValue() 
    {
        return $this->value;
    }

    /**
     * Returns calculated impact
     *
     * @return integer
     */
    public function getImpact() 
    {
        if (!$this->impact) {
            $this->impact = 0;
            foreach ($this->filters as $filter) {
                $this->impact += $filter->getImpact();
            }
        }

        return $this->impact;
    }

    /**
     * Returns affected tags
     *
     * @return array
     */
    public function getTags() 
    {
        $filters = $this->getFilters();

        foreach ($filters as $filter) {
            $this->tags = array_merge($this->tags,
                                      $filter->getTags());
        }

        $this->tags = array_values(array_unique($this->tags));

        return $this->tags;
    }

    /**
     * Returns list of filter objects
     *
     * @return array
     */
    public function getFilters() 
    {
        return $this->filters;
    }

    /**
     * Returns number of filters
     *
     * To implement interface Countable this returns the number of filters
     * appended.
     *
     * @return integer
     */
    public function count() 
    {
        return count($this->getFilters());
    }

    /**
     * IteratorAggregate iterator getter
     *
     * Returns an iterator to iterate over the appended filters.
     *
     * @return Iterator|IteratorAggregate
     */
    public function getIterator() 
    {
        return new ArrayObject($this->getFilters());
    }
}

/**
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 expandtab
 */
