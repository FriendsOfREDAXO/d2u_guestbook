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
if (class_exists('D2UModuleManager')) {
    $modules = [];
    $modules[] = new D2UModule('60-1',
        'D2U Guestbook - Gästebuch mit Bootstrap 4 Tabs',
        15);
    $modules[] = new D2UModule('60-2',
        'D2U Guestbook - Infobox Bewertung',
        5);
    $modules[] = new D2UModule('60-3',
        'D2U Guestbook - Gästebuch ohne Tabs',
        12);
    $d2u_module_manager = new D2UModuleManager($modules, '', 'd2u_guestbook');
    $d2u_module_manager->autoupdate();
}

// Update language replacements
if (!class_exists('d2u_guestbook_lang_helper')) {
    // Load class in case addon is deactivated
    require_once 'lib/d2u_guestbook_lang_helper.php';
}
d2u_guestbook_lang_helper::factory()->install();
