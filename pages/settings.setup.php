<?php
$showChangelogOnly = defined('D2U_GUESTBOOK_SHOW_CHANGELOG');

/*
 * Modules
 */
$d2u_module_manager = new \TobiasKrais\D2UHelper\ModuleManager(\FriendsOfRedaxo\D2UGuestbook\Module::getModules(), 'modules/', 'd2u_guestbook');

// \TobiasKrais\D2UHelper\ModuleManager actions
$d2u_module_id = rex_request('d2u_module_id', 'string');
$paired_module = rex_request('pair_'. $d2u_module_id, 'int');
$function = rex_request('function', 'string');
if ('' !== $d2u_module_id) {
    if (!\TobiasKrais\D2UHelper\BackendHelper::getPageCsrfToken()->isValid()) {
        echo rex_view::error(rex_i18n::msg('csrf_token_invalid'));
    } else {
        $d2u_module_manager->doActions($d2u_module_id, $function, $paired_module);
    }
}

// \TobiasKrais\D2UHelper\ModuleManager show list
$d2u_module_manager->showManagerList();

?>
<h2>Installation der Module</h2>
<p>Die zu den obigen Modulen gehörenden CSS Vorlagen befinden sich im Addon
	Verzeichnis im Pfad modules/60/<i>Nummer des Moduls</i>/styles.css.</p>
<h2>Beispielseiten</h2>
<ul>

	<li>Generelle Demoseite: <a href="https://test.design-to-use.de/de/addontests/d2u-gaestebuch/" target="_blank">
		design-to-use.de Testseite</a>.</li>
	<li>Gästebuch Addon mit Bootstrap 5 Tabs: <a href="https://immobiliengaiser.de/home/unser-gaestebuch/" target="_blank">
		ImmobilienGaiser</a>.</li>
	<li>Gästebuch Addon mit Bootstrap 4 Tabs: <a href="https://www.optik-muelhaupt.de/gaestebuch/" target="_blank">
		Optik Mülhaupt</a>.</li>
</ul>
<h2>Support</h2>
<p>Fehlermeldungen bitte im <a href="https://github.com/FriendsOfRedaxo/d2u_guestbook" target="_blank">GitHub Repository</a> melden.</p>