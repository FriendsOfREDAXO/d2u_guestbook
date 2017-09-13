<?php
/*
 * Modules
 */
$d2u_module_manager = new D2UModuleManager(D2UGuestbookModules::getD2UGuestbookModules(), "modules/", "d2u_guestbook");

// D2UModuleManager actions
$d2u_module_id = rex_request('d2u_module_id', 'string');
$paired_module = rex_request('pair_'. $d2u_module_id, 'int');
$function = rex_request('function', 'string');
if($d2u_module_id != "") {
	$d2u_module_manager->doActions($d2u_module_id, $function, $paired_module);
}

// D2UModuleManager show list
$d2u_module_manager->showManagerList();

/*
 * Templates
 */
?>
<h2>Installation der Module</h2>
<p>Die zu den obigen Modulen gehörenden CSS Vorlagen befinden sich im Addon
	Verzeichnis im Pfad modules/60/<i>Nummer des Moduls</i>/styles.css.</p>
<h2>Beispielseiten</h2>
<ul>
	<li>Gästebuch Addon mit Bootstrap 4 Tabs: <a href="https://www.immobiliengaiser.de" target="_blank">
		ImmobilienGaiser</a>.</li>
	<li>Gästebuch Addon ohne Tabs: <a href="http://www.hotel-albatros.de" target="_blank">
		Hotel Albatros</a>.</li>
</ul>
<h2>Support</h2>
<p>Fehlermeldungen bitte im <a href="https://github.com/TobiasKrais/d2u_guestbook" target="_blank">GitHub Repository</a> melden.</p>
<h2>Changelog</h2>
<p>1.0.1:</p>
<ul>
	<li>Neues Modul ohne Tabs.</li>
</ul>
<p>1.0.0:</p>
<ul>
	<li>Initiale Version.</li>
</ul>