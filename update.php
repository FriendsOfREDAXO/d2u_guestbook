<?php
// Update language replacements
d2u_guestbook_lang_helper::factory()->install();

// Update modules
if(class_exists(D2UModuleManager)) {
	$modules = [];
	$modules[] = new D2UModule("60-1",
		"D2U Guestbook - Gästebuch mit Bootstrap 4 Tabs",
		4);
	$modules[] = new D2UModule("60-2",
		"D2U Guestbook - Infobox Bewertung",
		2);
	$modules[] = new D2UModule("60-3",
		"D2U Guestbook - Gästebuch ohne Tabs",
		3);
	$d2u_module_manager = new D2UModuleManager($modules, "", "d2u_address");
	$d2u_module_manager->autoupdate();
}

// remove default lang setting
if (!$this->hasConfig()) {
	$this->removeConfig('default_lang');
    $this->setConfig('allow_answer', 'false');
    $this->setConfig('no_entries_page', 10);
}