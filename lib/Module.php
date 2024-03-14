<?php

namespace FriendsOfREDAXO\D2UGuestbook;

/**
 * Class managing modules published by www.design-to-use.de.
 *
 * @author Tobias Krais
 */
class Module
{
    /**
     * Get modules offered by this addon.
     * @return \TobiasKrais\D2UHelper\Module[] Modules offered by this addon
     */
    public static function getModules()
    {
        $modules = [];
        $modules[] = new \TobiasKrais\D2UHelper\Module('60-1',
            'D2U Guestbook - Gästebuch mit Bootstrap 4 Tabs',
            16);
        $modules[] = new \TobiasKrais\D2UHelper\Module('60-2',
            'D2U Guestbook - Infobox Bewertung',
            5);
        $modules[] = new \TobiasKrais\D2UHelper\Module('60-3',
            'D2U Guestbook - Gästebuch ohne Tabs',
            13);
        return $modules;
    }
}