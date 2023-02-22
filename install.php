<?php

$sql = rex_sql::factory();

// Install database
$sql->setQuery('CREATE TABLE IF NOT EXISTS `'. rex::getTablePrefix() .'d2u_guestbook` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`email` varchar(255) NOT NULL,
	`description` text NOT NULL,
	`clang_id` int(10) NOT NULL,
	`rating` tinyint(1) DEFAULT 0,
	`recommendation` tinyint(1) DEFAULT 0,
	`privacy_policy_accepted` tinyint(1) DEFAULT 0,
	`online_status` varchar(10) DEFAULT NULL,
	`create_date` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;');

// Standard settings
if (!$this->hasConfig()) {
    $this->setConfig('guestbook_article_id', rex_article::getSiteStartArticleId());
    $this->setConfig('allow_answer', 'false');
    $this->setConfig('no_entries_page', 10);
}
