<?php

// remove old default lang setting
if (rex_config::has('d2u_guestbook', 'default_lang')) {
    rex_config::remove('d2u_guestbook', 'default_lang');
}

$sql = rex_sql::factory();
// 1.0.6 Update database
if (rex_version::compare($this->getVersion(), '1.0.6', '<')) { /** @phpstan-ignore-line */
    $sql->setQuery('UPDATE '. \rex::getTablePrefix() ."d2u_guestbook SET privacy_policy_accepted = '0' WHERE privacy_policy_accepted = 'no';");
    $sql->setQuery('UPDATE '. \rex::getTablePrefix() ."d2u_guestbook SET privacy_policy_accepted = '1' WHERE privacy_policy_accepted = 'yes';");
    $sql->setQuery('ALTER TABLE '. \rex::getTablePrefix() .'d2u_guestbook CHANGE privacy_policy_accepted privacy_policy_accepted TINYINT(1) DEFAULT 0;');
}

// Update database to 1.0.7
$sql->setQuery('ALTER TABLE `'. rex::getTablePrefix() .'d2u_guestbook` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
if (rex_version::compare($this->getVersion(), '1.0.7', '<')) { /** @phpstan-ignore-line */
    $sql->setQuery('ALTER TABLE '. \rex::getTablePrefix() .'d2u_guestbook ADD COLUMN `create_date_new` DATETIME NOT NULL AFTER `create_date`;');
    $sql->setQuery('UPDATE '. \rex::getTablePrefix() .'d2u_guestbook SET `create_date_new` = FROM_UNIXTIME(`create_date`);');
    $sql->setQuery('ALTER TABLE '. \rex::getTablePrefix() .'d2u_guestbook DROP create_date;');
    $sql->setQuery('ALTER TABLE '. \rex::getTablePrefix() .'d2u_guestbook CHANGE `create_date_new` `create_date` DATETIME NOT NULL;');
}

// use path relative to __DIR__ to get correct path in update temp dir
$this->includeFile(__DIR__.'/install.php'); /** @phpstan-ignore-line */
