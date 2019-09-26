<?php
// Update language replacements
if(!class_exists('d2u_guestbook_lang_helper')) {
	// Load class in case addon is deactivated
	require_once 'lib/d2u_guestbook_lang_helper.php';
}
d2u_guestbook_lang_helper::factory()->install();

// Update modules
if(class_exists('D2UModuleManager')) {
	$modules = [];
	$modules[] = new D2UModule("60-1",
		"D2U Guestbook - Gästebuch mit Bootstrap 4 Tabs",
		10);
	$modules[] = new D2UModule("60-2",
		"D2U Guestbook - Infobox Bewertung",
		2);
	$modules[] = new D2UModule("60-3",
		"D2U Guestbook - Gästebuch ohne Tabs",
		9);
	$d2u_module_manager = new D2UModuleManager($modules, "", "d2u_address");
	$d2u_module_manager->autoupdate();
}

// remove default lang setting
if (!$this->hasConfig()) {
	$this->removeConfig('default_lang');
    $this->setConfig('allow_answer', 'false');
    $this->setConfig('no_entries_page', 10);
}

$sql = rex_sql::factory();
// 1.0.3 Update database
$sql->setQuery("SHOW COLUMNS FROM ". \rex::getTablePrefix() ."d2u_guestbook LIKE 'privacy_policy_accepted';");
if($sql->getRows() == 0) {
	$sql->setQuery("ALTER TABLE ". \rex::getTablePrefix() ."d2u_guestbook "
		. "ADD privacy_policy_accepted tinyint(1) DEFAULT 0 AFTER recommendation;");
}
// 1.0.6 Update database
if (rex_string::versionCompare($this->getVersion(), '1.0.6', '<')) {
	$sql->setQuery("UPDATE ". \rex::getTablePrefix() ."d2u_guestbook SET privacy_policy_accepted = '0' WHERE privacy_policy_accepted = 'no';");
	$sql->setQuery("UPDATE ". \rex::getTablePrefix() ."d2u_guestbook SET privacy_policy_accepted = '1' WHERE privacy_policy_accepted = 'yes';");
	$sql->setQuery("ALTER TABLE ". \rex::getTablePrefix() ."d2u_guestbook CHANGE privacy_policy_accepted privacy_policy_accepted tinyint(1) DEFAULT 0;");
}

// Update database to 1.0.7
$sql->setQuery("ALTER TABLE `". rex::getTablePrefix() ."d2u_guestbook` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
if (rex_string::versionCompare($this->getVersion(), '1.0.7', '<')) {
	$sql->setQuery("ALTER TABLE ". \rex::getTablePrefix() ."d2u_guestbook ADD COLUMN `create_date_new` DATETIME NOT NULL AFTER `create_date`;");
	$sql->setQuery("UPDATE ". \rex::getTablePrefix() ."d2u_guestbook SET `create_date_new` = FROM_UNIXTIME(`create_date`);");
	$sql->setQuery("ALTER TABLE ". \rex::getTablePrefix() ."d2u_guestbook DROP create_date;");
	$sql->setQuery("ALTER TABLE ". \rex::getTablePrefix() ."d2u_guestbook CHANGE `create_date_new` `create_date` DATETIME NOT NULL;");
}