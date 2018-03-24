<?php
/**
 * Offers helper functions for language issues
 */
class d2u_guestbook_lang_helper {
	/**
	 * @var string[] Array with english replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_english = [
		'd2u_guestbook_form_captcha' => 'To prevent abuse, please enter captcha.',
		'd2u_guestbook_form_email' => 'E-mail address',
		'd2u_guestbook_form_message' => 'Message',
		'd2u_guestbook_form_name' => 'Name',
		'd2u_guestbook_form_rating' => 'How satisfied were you with our service?',
		'd2u_guestbook_form_recommendation' => 'Would you recommend us?',
		'd2u_guestbook_form_required' => 'Required',
		'd2u_guestbook_form_send' => 'Send',
		'd2u_guestbook_form_thanks' => 'Thank you for your guestbook entry. Our administrator will check the entry for spam or offending speech. This may take some time.',
		'd2u_guestbook_form_title' => 'Request form',
		'd2u_guestbook_form_validate_captcha' => 'The Captcha was not read correctly.',
		'd2u_guestbook_form_validate_description' => 'Please enter a message for the guestbook.',
		'd2u_guestbook_form_validate_name' => 'Please enter your full name.',
		'd2u_guestbook_form_validate_title' => 'Failure sending message:',
		'd2u_guestbook_no' => 'No',
		'd2u_guestbook_oclock' => 'h',
		'd2u_guestbook_page' => 'Page',
		'd2u_guestbook_rating' => 'Customer quality and service rating',
		'd2u_guestbook_ratings' => 'Customer ratings',
		'd2u_guestbook_recommended_pre' => 'Recommended by',
		'd2u_guestbook_recommended_post' => 'customers',
		'd2u_guestbook_tab_title' => 'Entries',
		'd2u_guestbook_tab_write' => 'Write new entry',
		'd2u_guestbook_yes' => 'Yes',
	];
	/**
	 * @var string[] Array with german replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_german = [
		'd2u_guestbook_form_captcha' => 'Um Missbrauch vorzubeugen bitten wir Sie das Captcha einzugeben.',
		'd2u_guestbook_form_email' => 'E-Mail Adresse',
		'd2u_guestbook_form_message' => 'Nachricht',
		'd2u_guestbook_form_name' => 'Name',
		'd2u_guestbook_no' => 'Nein',
		'd2u_guestbook_form_rating' => 'Wie zufrieden waren Sie mit unserem Service?',
		'd2u_guestbook_form_recommendation' => 'Würden Sie uns weiterempfehlen?',
		'd2u_guestbook_form_required' => 'Pflichtfelder',
		'd2u_guestbook_form_send' => 'Abschicken',
		'd2u_guestbook_form_thanks' => 'Danke für Ihren Eintrag. Unser Eintrag wird von unserem Administrator auf Spam und inakzeptable Sprache geprüft. Das kann einige Zeit in Anspruch nehmen. Es besteht kein Anspruch auf eine Veröffentlichung.',
		'd2u_guestbook_form_title' => 'Anfrage zum Objekt',
		'd2u_guestbook_form_validate_captcha' => 'Bitte geben Sie erneut das Captcha ein.',
		'd2u_guestbook_form_validate_description' => 'Bitte geben Sie noch eine Nachricht fürs Gästebuch ein.',
		'd2u_guestbook_form_validate_name' => 'Um Sie korrekt ansprechen zu können, geben Sie bitte Ihren vollständigen Namen an.',
		'd2u_guestbook_form_validate_title' => 'Fehler beim Senden:',
		'd2u_guestbook_no' => 'Nein',
		'd2u_guestbook_oclock' => 'Uhr',
		'd2u_guestbook_page' => 'Seite',
		'd2u_guestbook_rating' => 'Kundenbewertung in Qualität, Service und Zuverlässigkeit',
		'd2u_guestbook_ratings' => 'Kundenbewertungen',
		'd2u_guestbook_recommended_pre' => 'Von',
		'd2u_guestbook_recommended_post' => 'Kunden empfohlen',
		'd2u_guestbook_tab_title' => 'Einträge',
		'd2u_guestbook_tab_write' => 'Neuen Eintrag Schreiben',
		'd2u_guestbook_yes' => 'Ja',
	];
	
	/**
	 * Factory method.
	 * @return d2u_guestbook_lang_helper Object
	 */
	public static function factory() {
		return new d2u_guestbook_lang_helper();
	}
	
	/**
	 * Installs the replacement table for this addon.
	 */
	public function install() {
		$d2u_guestbook = rex_addon::get('d2u_guestbook');
		
		foreach($this->replacements_english as $key => $value) {
			$addWildcard = rex_sql::factory();

			foreach (rex_clang::getAllIds() as $clang_id) {
				// Load values for input
				if($d2u_guestbook->hasConfig('lang_replacement_'. $clang_id) && $d2u_guestbook->getConfig('lang_replacement_'. $clang_id) == 'german') {
					$value = $this->replacements_german[$key];
				}
				else { 
					$value = $this->replacements_english[$key];
				}

				if(rex_addon::get('sprog')->isAvailable()) {
					$select_pid_query = "SELECT pid FROM ". rex::getTablePrefix() ."sprog_wildcard WHERE wildcard = '". $key ."' AND clang_id = ". $clang_id;
					$select_pid_sql = rex_sql::factory();
					$select_pid_sql->setQuery($select_pid_query);
					if($select_pid_sql->getRows() > 0) {
						// Update
						$query = "UPDATE ". rex::getTablePrefix() ."sprog_wildcard SET "
							."`replace` = '". addslashes($value) ."', "
							."updatedate = '". rex_sql::datetime() ."', "
							."updateuser = '". rex::getUser()->getValue('login') ."' "
							."WHERE pid = ". $select_pid_sql->getValue('pid');
						$sql = rex_sql::factory();
						$sql->setQuery($query);						
					}
					else {
						$id = 1;
						// Before inserting: id (not pid) must be same in all langs
						$select_id_query = "SELECT id FROM ". rex::getTablePrefix() ."sprog_wildcard WHERE wildcard = '". $key ."' AND id > 0";
						$select_id_sql = rex_sql::factory();
						$select_id_sql->setQuery($select_id_query);
						if($select_id_sql->getRows() > 0) {
							$id = $select_id_sql->getValue('id');
						}
						else {
							$select_id_query = "SELECT MAX(id) + 1 AS max_id FROM ". rex::getTablePrefix() ."sprog_wildcard";
							$select_id_sql = rex_sql::factory();
							$select_id_sql->setQuery($select_id_query);
							if($select_id_sql->getValue('max_id') != NULL) {
								$id = $select_id_sql->getValue('max_id');
							}
						}
						// Save
						$query = "INSERT INTO ". rex::getTablePrefix() ."sprog_wildcard SET "
							."id = ". $id .", "
							."clang_id = ". $clang_id .", "
							."wildcard = '". $key ."', "
							."`replace` = '". addslashes($value) ."', "
							."createdate = '". rex_sql::datetime() ."', "
							."createuser = '". rex::getUser()->getValue('login') ."', "
							."updatedate = '". rex_sql::datetime() ."', "
							."updateuser = '". rex::getUser()->getValue('login') ."'";
						$sql = rex_sql::factory();
						$sql->setQuery($query);
					}
				}
			}
		}
	}

	/**
	 * Uninstalls the replacement table for this addon.
	 * @param int $clang_id Redaxo language ID, if 0, replacements of all languages
	 * will be deleted. Otherwise only one specified language will be deleted.
	 */
	public function uninstall($clang_id = 0) {
		foreach($this->replacements_english as $key => $value) {
			if(rex_addon::get('sprog')->isAvailable()) {
				// Delete 
				$query = "DELETE FROM ". rex::getTablePrefix() ."sprog_wildcard WHERE wildcard = '". $key ."'";
				if($clang_id > 0) {
					$query .= " AND clang_id = ". $clang_id;
				}
				$select = rex_sql::factory();
				$select->setQuery($query);
			}
		}
	}
}