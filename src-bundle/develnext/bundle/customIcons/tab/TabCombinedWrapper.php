<?php

namespace develnext\bundle\customIcons\tab;

use develnext\bundle\customIcons\AbstractFindTab;

class TabCombinedWrapper
{
    /**
     * @var \develnext\bundle\customIcons\AbstractFindTab[]
     */
    private static $finders = [];

    public static function applyIcon()
    {
        foreach (self::$finders as $finder) {
            $finder->applyIcon();
        }

    }


    public static function registerFinder(AbstractFindTab $finder)
    {
        self::$finders[get_class($finder)] = $finder;
    }

    public static function unregisterFinder(AbstractFindTab $finder)
    {
        unset(self::$finders[get_class($finder)]);
    }
}