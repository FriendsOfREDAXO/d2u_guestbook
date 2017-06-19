<?php
if(rex::isBackend() && is_object(rex::getUser())) {
	rex_perm::register('d2u_guestbook[]', rex_i18n::msg('d2u_guestbook_rights'));
	rex_perm::register('d2u_guestbook[settings]', rex_i18n::msg('d2u_guestbook_rights_settings'));	
}