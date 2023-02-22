<?php

echo rex_view::title(rex_i18n::msg('d2u_guestbook'));

if (0 == rex_config::get('d2u_helper', 'article_id_privacy_policy', 0) || 0 == rex_config::get('d2u_helper', 'article_id_impress', 0)) {
    echo rex_view::warning(rex_i18n::msg('d2u_helper_gdpr_warning'));
}

rex_be_controller::includeCurrentPageSubPath();
