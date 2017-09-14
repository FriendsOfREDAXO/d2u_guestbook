<?php
$sql = rex_sql::factory();

// Install database
$sql->setQuery("CREATE TABLE IF NOT EXISTS `". rex::getTablePrefix() ."d2u_guestbook` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`email` varchar(255) NOT NULL,
	`description` text NOT NULL,
	`clang_id` int(10) NOT NULL,
	`rating` tinyint(1) DEFAULT 0,
	`recommendation` tinyint(1) DEFAULT 0,
	`online_status` varchar(10) DEFAULT NULL,
	`create_date` int(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");