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
 * @version	SVN: $Id:allTests.php 515 2007-09-15 13:43:40Z christ1an $
 */
error_reporting(E_ALL | E_STRICT | @E_DEPRECATED);
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Util/Filter.php';

PHPUnit_Util_Filter::addDirectoryToFilter(dirname(__FILE__));
PHPUnit_Util_Filter::addDirectoryToFilter(dirname(__FILE__) . '/../lib/IDS/vendors');


if (!defined('PHPUnit_MAIN_METHOD')) {
	define('PHPUnit_MAIN_METHOD', 'allTests');
}


class allTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}

	public static function suite()
	{
        $suite = new PHPUnit_Framework_TestSuite('PHPIDS');
        require_once 'IDS/MonitorTest.php';
        $suite->addTestSuite('IDS_MonitorTest');
        require_once 'IDS/ReportTest.php';
        $suite->addTestSuite('IDS_ReportTest');
        require_once 'IDS/InitTest.php';
        $suite->addTestSuite('IDS_InitTest');
        require_once 'IDS/ExceptionTest.php';
        $suite->addTestSuite('IDS_ExceptionTest');
        require_once 'IDS/FilterTest.php';
        $suite->addTestSuite('IDS_FilterTest');
        require_once 'IDS/CachingTest.php';
        $suite->addTestSuite('IDS_CachingTest');
        require_once 'IDS/EventTest.php';
        $suite->addTestSuite('IDS_EventTest');
        return $suite;
	}
}

if (PHPUnit_MAIN_METHOD == 'allTests') {
	allTests::main();
}

/**
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 expandtab
 */
