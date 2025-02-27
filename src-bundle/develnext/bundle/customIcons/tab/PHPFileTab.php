<?php

namespace develnext\bundle\customIcons\tab;

use develnext\bundle\customIcons\AbstractFindTab;
use gui;
use php\lib\fs;
use php\lib\str;

class PHPFileTab extends AbstractFindTab
{

    function getTab()
    {
        $temp = [];
        foreach ($this->getIDETab()->tabs as $tab) {
            if ($tab->graphic instanceof UXHBox) continue;

            if (str::lower(fs::ext($tab->text)) == 'php') {
                $temp[] = $tab;
            }
        }

        return $temp;
    }

    function applyIcon()
    {
        foreach ($this->getTab() as $tab) {
            $tab->graphic = new UXHBox();
            $tab->graphic->classes->add("tab-php");
        }
    }
}