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
 * @version	SVN: $Id:ExceptionTest.php 517 2007-09-15 15:04:13Z mario $
 */

require_once 'PHPUnit/Framework/TestCase.php';
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/../../lib');
require_once 'IDS/Init.php';
require_once 'IDS/Caching/Factory.php';
require_once 'IDS/Report.php';
require_once 'IDS/Event.php';
require_once 'IDS/Filter.php';
require_once 'IDS/Monitor.php';
require_once 'IDS/Filter/Storage.php';

class IDS_ExceptionTest extends PHPUnit_Framework_TestCase
{
    public function setUp() {
        $this->report = new IDS_Report(array(
            new IDS_Event("key_a", 'val_b',
                array(
                    new IDS_Filter(1, '^test_a1$', 'desc_a1', array('tag_a1', 'tag_a2'), 1),
                    new IDS_Filter(1, '^test_a2$', 'desc_a2', array('tag_a2', 'tag_a3'), 2)
                )
            ),
            new IDS_Event('key_b', 'val_b',
                array(
                    new IDS_Filter(1, '^test_b1$', 'desc_b1', array('tag_b1', 'tag_b2'), 3),
                    new IDS_FIlter(1, '^test_b2$', 'desc_b2', array('tag_b2', 'tag_b3'), 4),
                )
            )
        ));

        $this->path = dirname(__FILE__) . '/../../lib/IDS/Config/Config.ini';
        $this->init = IDS_Init::init($this->path);
    }

    public function testEventConstructorExceptions1() {
        $this->setExpectedException('InvalidArgumentException');
        new IDS_Event(array(1,2), 'val_b',
                array(
                    new IDS_Filter(1, '^test_a1$', 'desc_a1', array('tag_a1', 'tag_a2'), 1),
                    new IDS_Filter(1, '^test_a2$', 'desc_a2', array('tag_a2', 'tag_a3'), 2)
                )
        );
    }

    public function testEventConstructorExceptions2() {
        $this->setExpectedException('InvalidArgumentException');
        new IDS_Event("key_a", array(1,2),
                array(
                    new IDS_Filter(1, '^test_a1$', 'desc_a1', array('tag_a1', 'tag_a2'), 1),
                    new IDS_Filter(1, '^test_a2$', 'desc_a2', array('tag_a2', 'tag_a3'), 2)
                )
        );
    }

    public function testEventConstructorExceptions3() {
        $this->setExpectedException('InvalidArgumentException');
        new IDS_Event("key_a", 'val_b', array(1,2));
    }

    public function testGetEventException() {
        $this->setExpectedException('InvalidArgumentException');
        $this->assertEquals($this->report->getEvent(array(1,2,3)), $this->getExpectedException());
    }

    public function testHasEventException() {
        $this->setExpectedException('InvalidArgumentException');
        $this->assertEquals($this->report->hasEvent(array(1,2,3)), $this->getExpectedException());
    }

    public function testInitConfigWrongPathException() {
        $this->setExpectedException('Exception');
        $this->assertEquals(IDS_Init::init('IDS/Config/Config.ini.wrong'), $this->getExpectedException());
    }

    public function testWrongXmlFilterPathException() {
        $this->setExpectedException('Exception');
        $this->init->config['General']['filter_type'] = 'xml';
        $this->init->config['General']['filter_path'] = 'IDS/wrong_path';
        $this->assertEquals(new IDS_Monitor(array('test', 'bla'), $this->init), $this->getExpectedException());
    }

    public function tearDown() {
    	$this->init->config['General']['filter_type'] = 'xml';
    }
}

/**
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 expandtab
 */
