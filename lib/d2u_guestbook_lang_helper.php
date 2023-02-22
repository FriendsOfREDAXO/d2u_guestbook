<?php
/**
 * Offers helper functions for language issues.
 */
class d2u_guestbook_lang_helper extends \D2U_Helper\ALangHelper
{
    /**
     * @var array<string, string> Array with english replacements. Key is the wildcard,
     * value the replacement.
     */
    public $replacements_english = [
        'd2u_guestbook_form_email' => 'E-mail address',
        'd2u_guestbook_form_message' => 'Message',
        'd2u_guestbook_form_name' => 'Name',
        'd2u_guestbook_form_privacy_policy' => 'I consent to the storage and processing of my contact and usage data by the owner of the guestbook. I\'ve learned about the scope of data processing <a href="+++LINK_PRIVACY_POLICY+++" target="_blank">here</a>. I have the right to object to such use at any time under the contact details provided in the <a href="+++LINK_IMPRESS+++" target="_blank">imprint</a>.',
        'd2u_guestbook_form_rating' => 'How satisfied were you with our service?',
        'd2u_guestbook_form_recommendation' => 'Would you recommend us?',
        'd2u_guestbook_form_required' => 'Required',
        'd2u_guestbook_form_send' => 'Send',
        'd2u_guestbook_form_thanks' => 'Thank you for your guestbook entry. Our administrator will check the entry for spam or offending speech. This may take some time.',
        'd2u_guestbook_form_title' => 'Request form',
        'd2u_guestbook_form_validate_description' => 'Please enter a message for the guestbook.',
        'd2u_guestbook_form_validate_name' => 'Please enter your full name.',
        'd2u_guestbook_form_validate_privacy_policy' => 'It\'s necessary to accept the privacy policy.',
        'd2u_guestbook_form_validate_spambots' => 'Too fast - it seems you are a spambot. Please take your time to fill out all all fields.',
        'd2u_guestbook_form_validate_spam_detected' => 'Your message was classified as spam, because not visible fields were filled in.',
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
     * @var array<string, string> Array with german replacements. Key is the wildcard,
     * value the replacement.
     */
    protected array $replacements_german = [
        'd2u_guestbook_form_email' => 'E-Mail-SAdresse',
        'd2u_guestbook_form_message' => 'Nachricht',
        'd2u_guestbook_form_name' => 'Name',
        'd2u_guestbook_form_privacy_policy' => 'Ich willige in die Speicherung und Verarbeitung meiner Kontakt- und Nutzungsdaten durch den Betreiber des Gästebuches ein. Über den Umfang der Datenverarbeitung habe ich mich  <a href="+++LINK_PRIVACY_POLICY+++" target="_blank">hier</a> informiert. Ich habe das Recht dieser Verwendung jederzeit unter den im <a href="+++LINK_IMPRESS+++" target="_blank">Impressum</a> angegebenen Kontaktdaten zu widersprechen.',
        'd2u_guestbook_form_rating' => 'Wie zufrieden waren Sie mit unserem Service?',
        'd2u_guestbook_form_recommendation' => 'Würden Sie uns weiterempfehlen?',
        'd2u_guestbook_form_required' => 'Pflichtfelder',
        'd2u_guestbook_form_send' => 'Abschicken',
        'd2u_guestbook_form_thanks' => 'Danke für Ihren Eintrag. Unser Eintrag wird von unserem Administrator auf Spam und inakzeptable Sprache geprüft. Das kann einige Zeit in Anspruch nehmen. Es besteht kein Anspruch auf eine Veröffentlichung.',
        'd2u_guestbook_form_title' => 'Anfrage zum Objekt',
        'd2u_guestbook_form_validate_description' => 'Bitte geben Sie noch eine Nachricht fürs Gästebuch ein.',
        'd2u_guestbook_form_validate_name' => 'Um Sie korrekt ansprechen zu können, geben Sie bitte Ihren vollständigen Namen an.',
        'd2u_guestbook_form_validate_privacy_policy' => 'Der Datenschutzerklärung muss zugestimmt werden.',
        'd2u_guestbook_form_validate_spambots' => 'Sie haben das Formular so schnell ausgefüllt, wie es nur ein Spambot tun kann. Bitte lassen Sie sich beim ausfüllen der Felder etwas mehr Zeit.',
        'd2u_guestbook_form_validate_spam_detected' => 'Ihr Eintrag wurde als Spam eingestuft, da nicht sichtbare Felder ausgefüllt wurden.',
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
    public static function factory()
    {
        return new self();
    }

    /**
     * Installs the replacement table for this addon.
     */
    public function install(): void
    {
        foreach ($this->replacements_english as $key => $value) {
            foreach (rex_clang::getAllIds() as $clang_id) {
                $lang_replacement = rex_config::get('d2u_guestbook', 'lang_replacement_'. $clang_id, '');

                // Load values for input
                if ('german' === $lang_replacement && isset($this->replacements_german) && isset($this->replacements_german[$key])) {
                    $value = $this->replacements_german[$key];
                } else {
                    $value = $this->replacements_english[$key];
                }

                $overwrite = 'true' === rex_config::get('d2u_guestbook', 'lang_wildcard_overwrite', false) ? true : false;
                parent::saveValue($key, $value, $clang_id, $overwrite);
            }
        }
    }
}
