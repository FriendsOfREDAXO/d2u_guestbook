<h2>Changelog</h2>
<p>2.1.1-DEV:</p>
<ul>
	<li>Backend: CSRF-Schutz fuer Speichern-, Loesch- und Statusaktionen der Eintragsverwaltung ergaenzt.</li>
	<li>Backend: CSRF-Schutz fuer Modul-Installation, -Update und -Deinstallation auf der Setup-Seite ergaenzt.</li>
	<li>Backend-Liste sortierbar gemacht und Standardsortierung von SQL-Query auf <code>rex_list</code>-<code>defaultSort</code> umgestellt.</li>        <li>Security: Die <code>save()</code>-Methode in <code>lib/Entry.php</code> verwendet jetzt gebundene Parameter statt SQL-String-Konkatenation mit <code>addslashes()</code>.</li>
        <li>Security: Modul-Ausgaben (<code>modules/60/1/output.php</code>, <code>modules/60/3/output.php</code>, <code>modules/60/4/output.php</code>, <code>modules/60/6/output.php</code>) härten von Besuchern eingereichte Inhalte (Name, E-Mail) gegen XSS via <code>rex_escape()</code> in HTML- und <code>mailto:</code>-Attributausgaben.</li></ul>
<p>2.1.0:</p>
<ul>
	<li>Neue Module 60-4 bis 60-6 als Bootstrap-5-Varianten der bestehenden Beispielmodule hinzugefügt.</li>
	<li>Module 60-1 bis 60-3 als "(BS4, deprecated)" markiert. Die BS4-Varianten werden im nächsten Major Release entfernt.</li>
	<li>Die neue BS5-Ausgabe ersetzt jQuery-basierte Tab- und Paging-Interaktionen durch BS5-/Vanilla-JS-Komponenten.</li>
	<li>Benötigt d2u_helper &gt;= 2.1.0.</li>
</ul>
<p>2.0.1:</p>
<ul>
	<li>Alle Module an FontAwesome in aktuellen Redaxo Versionen angepasst um Bewertungssterne korrekt anzuzeigen.</li>
</ul>
<p>2.0.0:</p>
<ul>
	<li>Vorbereitung auf R6: Folgende Klassen wurden umbenannt:
		<ul>
			<li><code>D2U_Guestbook\d2u_guestbook_backend_helper</code> wird zu <code>FriendsOfRedaxo\D2UGuestbook\BackendHelper</code>.</li>
			<li><code>d2u_guestbook_lang_helper</code> wird zu <code>FriendsOfRedaxo\D2UGuestbook\LangHelper</code>.</li>
			<li><code>D2UGuestbookModules</code> wird zu <code>FriendsOfRedaxo\D2UGuestbook\Module</code>.</li>
			<li><code>D2U_Guestbook\Entry</code> wird zu <code>FriendsOfRedaxo\D2UGuestbook\Entry</code>.</li>
		</ul>
	</li>
	<li>Projekt an FriendsOfRedaxo übergeben.</li>
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