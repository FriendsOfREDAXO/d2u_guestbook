<?php
/*
 * Modules
 */
$d2u_module_manager = new \TobiasKrais\D2UHelper\ModuleManager(\FriendsOfREDAXO\D2UGuestbook\Module::getModules(), 'modules/', 'd2u_guestbook');

// \TobiasKrais\D2UHelper\ModuleManager actions
$d2u_module_id = rex_request('d2u_module_id', 'string');
$paired_module = (int) rex_request('pair_'. $d2u_module_id, 'int');
$function = rex_request('function', 'string');
if ('' !== $d2u_module_id) {
    $d2u_module_manager->doActions($d2u_module_id, $function, $paired_module);
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
	<li>Gästebuch Addon mit Bootstrap 4 Tabs: <a href="https://immobiliengaiser.de/home/unser-gaestebuch/" target="_blank">
		ImmobilienGaiser</a>.</li>
	<li>Gästebuch Addon mit Bootstrap 4 Tabs: <a href="https://www.optik-muelhaupt.de/gaestebuch/" target="_blank">
		Optik Mülhaupt</a>.</li>
	<li>Gästebuch Addon ohne Tabs: <a href="http://www.hotel-albatros.de" target="_blank">
		Hotel Albatros</a>.</li>
</ul>
<h2>Support</h2>
<p>Fehlermeldungen bitte im <a href="https://github.com/FriendsOfREDAXO/d2u_guestbook" target="_blank">GitHub Repository</a> melden.</p>
<h2>Changelog</h2>
<p>2.0.0:</p>
<ul>
	<li>Vorbereitung auf R6: Folgende Klassen wurden umbenannt:
		<ul>
			<li><code>D2U_Guestbook\d2u_guestbook_backend_helper</code> wird zu <code>FriendsOfREDAXO\D2UGuestbook\BackendHelper</code>.</li>
			<li><code>d2u_guestbook_lang_helper</code> wird zu <code>FriendsOfREDAXO\D2UGuestbook\LangHelper</code>.</li>
			<li><code>D2UGuestbookModules</code> wird zu <code>FriendsOfREDAXO\D2UGuestbook\Module</code>.</li>
			<li><code>D2U_Guestbook\Entry</code> wird zu <code>FriendsOfREDAXO\D2UGuestbook\Entry</code>.</li>
		</ul>
	</li>
	<li>Projekt an FriendsOfREDAXO übergeben.</li>
	<li>Modul "60-2 D2U Guestbook - Infobox Bewertung": Berechnung der Sterne korrigiert.</li>
	<li>Anpassungen an kommende d2u_helper 2.x Version</li>
	<li>Import aus TVSGB von Redaxo 4 entfernt.</li>
</ul>
<p>1.0.12:</p>
<ul>
	<li>Modul "60-1 D2U Guestbook - Gästebuch mit Bootstrap 4 Tabs": Fehler im Spamschutz und CSRF Schutz behoben.</li>
	<li>Modul "60-3 D2U Guestbook - Gästebuch ohne Tabs": Fehler im Spamschutz und CSRF Schutz behoben.</li>
</ul>
<p>1.0.11:</p>
<ul>
	<li>Readme Datei überarbeitet.</li>
	<li>Restliche rexstan Verbesserungen.</li>
	<li>Modul "60-1 D2U Guestbook - Gästebuch mit Bootstrap 4 Tabs": Einige CSS auf Modul beschränkt und Bugfix Paginierung.</li>
</ul>
<p>1.0.10:</p>
<ul>
	<li>PHP-CS-Fixer Code Verbesserungen.</li>
	<li>Anpassungen an Publish Github Release to Redaxo.</li>
	<li>Abhängigkeit zum emailobfuscator Addon entfernt.</li>
	<li>Erste rexstan Verbesserungen.</li>
	<li>install.php und update.php vereinheitlicht.</li>
	<li>Modul "60-1 D2U Guestbook - Gästebuch mit Bootstrap 4 Tabs": Gesamtbreite kann eingestellt werden und Formularname hinzugefügt, damit mehrere Formulare auf einer Webseite it YFrom Spamprotection funktionieren. Außerdem Darstellungsfehler bei Anzeige der Tabs behoben.</li>
	<li>Modul "60-2 D2U Guestbook - Infobox Bewertung": Wenn keine Bewertungen vorliegen, werden keine Sterne ausgegeben. Und CSS Verbesserungen.</li>
	<li>Modul "60-3 D2U Guestbook - Gästebuch ohne Tabs": Formularname hinzugefügt, damit mehrere Formulare auf einer Webseite mit YFrom Spamprotection funktionieren.</li>
</ul>
<p>1.0.9:</p>
<ul>
	<li>Fehler in der Updatedatei hat die Module nicht aktualisiert und zu einem fatalen Fehler führen können.</li>
	<li>Benötigt Redaxo >= 5.10, da die neue Klasse rex_version verwendet wird.</li>
	<li>Backend: Einstellungen und Setup Tabs rechts eingeordnet um sie vom Inhalt besser zu unterscheiden.</li>
	<li>Modul "60-1 D2U Guestbook - Gästebuch mit Bootstrap 4 Tabs": Kundenbewertungen können optional ausgeblendet werden.</li>
</ul>
<p>1.0.8:</p>
<ul>
	<li>Bugfix: Bestätigte Datenschutzerklärung wurde im Backend nicht korrekt angezeigt.</li>
	<li>Bugfix: Datum in Übersichtsliste korrekt dargestellt.</li>
	<li>Bugfix: Datum des Eintrags wird wieder korrekt gespeichert.</li>
	<li>Honeypot als Spamschutz Maßnahme für die Module hinzugefügt.</li>
</ul>
<p>1.0.7:</p>
<ul>
	<li>Listen im Backend werden jetzt nicht mehr in Seiten unterteilt.</li>
	<li>YRewrite Multidomain support.</li>
	<li>Datenbankfeld "create_date" in Redaxo Standard DATETIME umgewandelt. Beispielmodule sind angepasst.</li>
	<li>Konvertierung der Datenbanktabellen zu utf8mb4.</li>
</ul>
<p>1.0.6:</p>
<ul>
	<li>Anpassungen der Module an YForm 3, welches jetzt Voraussetzung für die Version ist.</li>
</ul>
<p>1.0.5:</p>
<ul>
	<li>Bugfix: SProg Sprachersetzungen werden beim deinstallieren entfernt.</li>
	<li>In den Einstellungen gibt es jetzt eine Option, eigene Übersetzungen in SProg dauerhaft zu erhalten.</li>
	<li>CSS Captcha Reload Button verbessert.</li>
</ul>
<p>1.0.4:</p>
<ul>
	<li>Spamschutz Timer von 10 Sekunden in Beispielmodule eingefügt.</li>
	<li>Mail an Admin wenn ein neuer Eintrag erstellt wird.</li>
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