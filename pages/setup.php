<?php
/*
 * Modules
 */
$d2u_module_manager = new D2UModuleManager(D2UGuestbookModules::getModules(), "modules/", "d2u_guestbook");

// D2UModuleManager actions
$d2u_module_id = rex_request('d2u_module_id', 'string');
$paired_module = rex_request('pair_'. $d2u_module_id, 'int');
$function = rex_request('function', 'string');
if($d2u_module_id != "") {
	$d2u_module_manager->doActions($d2u_module_id, $function, $paired_module);
}

// D2UModuleManager show list
$d2u_module_manager->showManagerList();

// Import from TVS Guestbook
$sql = rex_sql::factory();
$sql->setQuery("SHOW TABLES LIKE '". rex::getTablePrefix() ."771_entries'");
$tvsgb_available = $sql->getRows() > 0 ? TRUE : FALSE;
if(rex_request('import', 'string') == "tvsgb" && $tvsgb_available) {
	$sql->setQuery("UPDATE `". rex::getTablePrefix() ."771_entries` SET description = REPLACE(description, '\r\n', '<br>');
		INSERT INTO ". rex::getTablePrefix() ."d2u_guestbook (`name`, `email`, `description`, `clang_id`, `online_status`, `create_date`)
			SELECT `create_user`, `email`, `description`, `clang`, `status`, `create_date` FROM ". rex::getTablePrefix() ."771_entries;
		UPDATE `". rex::getTablePrefix() ."d2u_guestbook` SET `online_status` = 'online' WHERE `online_status` = '1';
		UPDATE `". rex::getTablePrefix() ."d2u_guestbook` SET `online_status` = 'offline' WHERE `online_status` = '0';
		UPDATE `". rex::getTablePrefix() ."d2u_guestbook` SET rating = 0 WHERE rating = NULL;
		DROP TABLE `". rex::getTablePrefix() ."771_entries`;");
	if($sql->hasError()) {
		print rex_view::error('Fehler beim Import: '. $sql->getError());
	}
	else {
		print rex_view::success('Daten aus TVS Gästebucherfolgreich importiert und alte Tabelle gelöscht');
	}
}
else if($tvsgb_available) {
	print "<h2>Import aus Redaxo 4 TVS Gästebuch</h2>";
	print "<p>Es wurde eine TVS Gästebuch Tabelle aus Redaxo 4 in der Datenbank gefunden."
	. "Sollen die Daten importiert werden und die alte Tabelle gelöscht werden?</p>";
	print '<a href="'. rex_url::currentBackendPage(["import" => "tvsgb"], FALSE) .'"><button class="btn btn-save">Import</button></a>';
}
?>
<h2>Installation der Module</h2>
<p>Die zu den obigen Modulen gehörenden CSS Vorlagen befinden sich im Addon
	Verzeichnis im Pfad modules/60/<i>Nummer des Moduls</i>/styles.css.</p>
<h2>Beispielseiten</h2>
<ul>
	<li>Gästebuch Addon mit Bootstrap 4 Tabs: <a href="https://www.immobiliengaiser.de/home/unser-gaestebuch/" target="_blank">
		ImmobilienGaiser</a>.</li>
	<li>Gästebuch Addon ohne Tabs: <a href="http://www.hotel-albatros.de" target="_blank">
		Hotel Albatros</a>.</li>
</ul>
<h2>Support</h2>
<p>Fehlermeldungen bitte im <a href="https://github.com/TobiasKrais/d2u_guestbook" target="_blank">GitHub Repository</a> melden.</p>
<h2>Changelog</h2>
<p>1.0.4-DEV:</p>
<ul>
	<li>Datum in Übersichtsliste eingefügt.</li>
	<li>Hinweis Datenschutzerklärung in Modulen verbessert.</li>
</ul>
<p>1.0.3:</p>
<ul>
	<li>Feld Datenschutzerklärung akzeptiert im Frontend Formular hinzugefügt.</li>
	<li>Bugfix: TVSGB Import wenn Tabellen Prefix von Standard abweicht.</li>
	<li>Bugfix: Fehler beim Speichern von Namen mit einfachem Anführungszeichen behoben.</li>
	<li>Englische Übersetzung fürs Backend hinzugefügt.</li>
	<li>Aktuelle Seite in Gästebuch Modulen farblich markiert.</li>
</ul>
<p>1.0.2:</p>
<ul>
	<li>Bugfix: Löschen Button in Gästebuch Backend hat fatal error erzeugt.</li>
	<li>Einträge können in mehrere JQuery Seiten unterteilt werden.</li>
	<li>Einstellungsoption um auf Einträge antworten zu können.</li>
	<li>Namespace "D2U_Guestbook" für Klasse "Entry" hinzugefügt: Achtung! Module müssen angepasst / aktualisiert werden.</li>
	<li>Modul 60-1 CSS verbessert.</li>
</ul>
<p>1.0.1:</p>
<ul>
	<li>Neues Modul ohne Tabs.</li>
	<li>Import aus Redaxo 4 TVS Gästebuch, sofern Tabelle in Datenbank vorhanden ist.</li>
	<li>Bugfix wenn Sprache gelöscht wird.</li>
</ul>
<p>1.0.0:</p>
<ul>
	<li>Initiale Version.</li>
</ul>