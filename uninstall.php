<?php

$sql = \rex_sql::factory();

// Delete tables
$sql->setQuery('DROP TABLE IF EXISTS ' . rex::getTablePrefix() . 'd2u_guestbook');

// Delete language replacements
if (!class_exists('d2u_guestbook_lang_helper')) {
    // Load class in case addon is deactivated
    require_once 'lib/d2u_guestbook_lang_helper.php';
}
d2u_guestbook_lang_helper::factory()->uninstall();
