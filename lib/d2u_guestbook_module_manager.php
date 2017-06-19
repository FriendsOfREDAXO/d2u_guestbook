<?php
/**
 * Class managing modules published by www.design-to-use.de
 *
 * @author Tobias Krais
 */
class D2UGuestbookModules {
	/**
	 * Get modules offered by this addon.
	 * @return D2UModule[] Modules offered by this addon
	 */
	public static function getD2UGuestbookModules() {
		$modules = [];
		$modules[] = new D2UModule("60-1",
			"D2U Guestbook - Gästebuch",
			1);
		$modules[] = new D2UModule("60-2",
			"D2U Guestbook - Infobox Bewertung",
			1);
		return $modules;
	}
}