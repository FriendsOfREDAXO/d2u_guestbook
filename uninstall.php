<?php

$sql = \rex_sql::factory();

// Delete tables
$sql->setQuery('DROP TABLE IF EXISTS ' . rex::getTablePrefix() . 'd2u_guestbook');

// Delete language replacements
if (!class_exists(FriendsOfREDAXO\D2UGuestbook\LangHelper::class)) {
    // Load class in case addon is deactivated
    require_once 'lib/LangHelper.php';
}
FriendsOfREDAXO\D2UGuestbook\LangHelper::factory()->uninstall();
