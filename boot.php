<?php

if (rex::isBackend() && is_object(rex::getUser())) {
    rex_perm::register('d2u_guestbook[]', rex_i18n::msg('d2u_guestbook_rights'));
    rex_perm::register('d2u_guestbook[settings]', rex_i18n::msg('d2u_guestbook_rights_settings'), rex_perm::OPTIONS);
}

if (rex::isBackend()) {
    rex_extension::register('CLANG_DELETED', 'rex_d2u_guestbook_clang_deleted');
}

/**
 * Deletes language specific configurations and objects.
 * @param rex_extension_point<array<string>> $ep Redaxo extension point
 * @return array<string> Warning message as array
 */
function rex_d2u_guestbook_clang_deleted(rex_extension_point $ep)
{
    $warning = $ep->getSubject();
    $params = $ep->getParams();
    $clang_id = $params['id'];

    // Delete
    $entries = FriendsOfREDAXO\D2UGuestbook\Entry::getAll(false);
    foreach ($entries as $entry) {
        if ($entry->clang_id === $clang_id) {
            $entry->delete();
        }
    }

    // Delete language settings
    if (rex_config::has('d2u_guestbook', 'lang_replacement_'. $clang_id)) {
        rex_config::remove('d2u_guestbook', 'lang_replacement_'. $clang_id);
    }
    // Delete language replacements
    FriendsOfREDAXO\D2UGuestbook\LangHelper::factory()->uninstall($clang_id);

    return $warning;
}
