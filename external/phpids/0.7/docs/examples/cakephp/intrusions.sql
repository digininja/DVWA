/**
 * PHP IDS
 * 
 * Requirements: PHP5, SimpleXML
 *
 * Copyright (c) 2010 PHPIDS (https://phpids.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the license.
 *
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

CREATE TABLE `intrusions` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(128) NOT NULL,
  `value` text NOT NULL,
  `page` varchar(255) NOT NULL,
  `userid` int(11) unsigned NOT NULL,
  `session` varchar(32) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `reaction` tinyint(3) unsigned NOT NULL COMMENT '0 = log; 1 = mail; 2 = warn; 3 = kick;',
  `impact` int(11) unsigned NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
);
