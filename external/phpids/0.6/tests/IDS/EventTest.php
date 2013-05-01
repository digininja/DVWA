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
 *
 * @package	PHPIDS tests
 * @version	SVN: $Id:EventTest.php 515 2007-09-15 13:43:40Z christ1an $
 */

require_once 'PHPUnit/Framework/TestCase.php';
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/../../lib');
require_once 'IDS/Event.php';
require_once 'IDS/Filter.php';

class IDS_EventTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->event = new IDS_Event("handled_key", "my val",
			array(
				new IDS_Filter(1, '^test$', 'my description', array('tag1', 'tag2'), 10),
				new IDS_Filter(1, '^test2$', 'my other desc', array('tag2', 'tag3'), 4)
			)
		);
	}

	public function testName()
	{
		$this->assertEquals('handled_key', $this->event->getName());
		$this->assertEquals("my val", $this->event->getValue());
	}

	public function testValueAggregation()
	{
		$this->assertEquals(14, $this->event->getImpact());
		$this->assertEquals(array('tag1', 'tag2', 'tag3'), $this->event->getTags());
	}

	public function testIterator()
	{
		$regexps = array('^test$', '^test2$');
		foreach ($this->event as $key => $filter)
			$this->assertEquals($regexps[$key], $filter->getRule());
		foreach ($this->event->getFilters() as $key => $filter)
			$this->assertEquals($regexps[$key], $filter->getRule());
	}

	public function testCount()
	{
		$this->assertEquals(2, count($this->event));
	}

	public function testCopy()
	{
		$filters = $this->event->getFilters();
		$filter[] = "foo";
		$this->assertEquals(2, count($this->event));
	}

	public function testIteratorAggregate()
	{
		$this->assertType('IteratorAggregate', $this->event);
		$this->assertType('IteratorAggregate', $this->event->getIterator());
	}
}

/**
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 expandtab
 */
