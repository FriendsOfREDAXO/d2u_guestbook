<?php

// Install database
\rex_sql_table::get(\rex::getTable('d2u_guestbook'))
    ->ensureColumn(new rex_sql_column('id', 'INT(11) unsigned', false, null, 'auto_increment'))
    ->setPrimaryKey('id')
    ->ensureColumn(new \rex_sql_column('name', 'VARCHAR(255)', true))
    ->ensureColumn(new \rex_sql_column('email', 'VARCHAR(255)', true))
    ->ensureColumn(new \rex_sql_column('description', 'TEXT', true))
    ->ensureColumn(new \rex_sql_column('clang_id', 'INT(11)', true))
    ->ensureColumn(new \rex_sql_column('rating', 'TINYINT(1)', true))
    ->ensureColumn(new \rex_sql_column('recommendation', 'TINYINT(1)', true))
    ->ensureColumn(new \rex_sql_column('privacy_policy_accepted', 'TINYINT(1)', true))
    ->ensureColumn(new \rex_sql_column('online_status', 'VARCHAR(10)', true))
    ->ensureColumn(new \rex_sql_column('create_date', 'DATETIME'))
    ->ensure();

// Standard settings
if (!rex_config::has('d2u_guestbook', 'guestbook_article_id')) {
    rex_config::set('d2u_guestbook', 'guestbook_article_id', rex_article::getSiteStartArticleId());
}

// Update modules
include __DIR__ . DIRECTORY_SEPARATOR .'lib'. DIRECTORY_SEPARATOR .'Module.php';
$d2u_module_manager = new \TobiasKrais\D2UHelper\ModuleManager(\FriendsOfRedaxo\D2UGuestbook\Module::getModules(), '', 'd2u_guestbook');
$d2u_module_manager->autoupdate();

// Update language replacements
if (!class_exists(FriendsOfRedaxo\D2UGuestbook\LangHelper::class)) {
    // Load class in case addon is deactivated
    require_once 'lib/LangHelper.php';
}
FriendsOfRedaxo\D2UGuestbook\LangHelper::factory()->install();
