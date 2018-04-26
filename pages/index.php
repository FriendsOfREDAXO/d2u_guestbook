<?php
echo rex_view::title($this->i18n('d2u_guestbook'));

if (rex_config::get('d2u_helper', 'article_id_privacy_policy', 0) == 0 || rex_config::get('d2u_helper', 'article_id_impress', 0) == 0) {
	print rex_view::warning(rex_i18n::msg('d2u_helper_gdpr_warning'));
}

rex_be_controller::includeCurrentPageSubPath();