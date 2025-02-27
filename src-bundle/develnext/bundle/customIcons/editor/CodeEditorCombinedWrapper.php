<?php

namespace develnext\bundle\customIcons\editor;

use develnext\bundle\customIcons\AbstractFindButtonContainer;

class CodeEditorCombinedWrapper
{
    /**
     * @var \develnext\bundle\customIcons\AbstractFindButtonContainer[]
     */
    private static $finders = [];

    public static function applyStyle()
    {
        foreach (self::$finders as $finder) {
            $finder->applyCssToButtonContainer();
        }

    }


    public static function registerFinder(AbstractFindButtonContainer $finder)
    {
        self::$finders[get_class($finder)] = $finder;
    }

    public static function unregisterFinder(AbstractFindButtonContainer $finder)
    {
        unset(self::$finders[get_class($finder)]);
    }

}