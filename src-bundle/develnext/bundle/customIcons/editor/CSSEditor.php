<?php

namespace develnext\bundle\customIcons\editor;

use develnext\bundle\customIcons\AbstractFindButtonContainer;
use gui;

class CSSEditor extends AbstractFindButtonContainer
{

    public function getButtonContainer()
    {
        if (!isset($this->getIDETab()->selectedTab->text)) return null;

        if (
            $this->getIDETab()->selectedTab->text == "Проект" ||
            $this->getIDETab()->selectedTab->text == "Project"
        ) {
            $vbox = $this->getIDETab()->selectedTab->content->items[1]->children->offsetGet(0);
            if ($vbox instanceof UXVBox) {
                $hbox = $vbox->children->offsetGet(0);

                if ($hbox instanceof UXHBox) {
                    if ($vbox->children->offsetGet(2) instanceof UXVBox) {
                        return $vbox->children->offsetGet(2)->children->offsetGet(0);
                    }
                }
            }
        }

        return null;
    }

    public function applyCssToButtonContainer()
    {
        if (!(($container = $this->getButtonContainer()) instanceof UXHBox)) {
            return;
        }

        if ($container->classes->has('mark-item')) {
            return;
        }

        $key = -1;

        foreach ($container->children as $item) {

            if ($item instanceof UXButton) $key++;
            else continue;

            $item->graphic = new UXHbox();
            $item->classes->add('editor-buttons');

            switch ($key) {
                case 0: $item->graphic->classes->add("button-tab");     break;
                case 1: $item->graphic->classes->add("button-save");    break;
                case 2: $item->graphic->classes->add("button-undo");    break;
                case 3: $item->graphic->classes->add("button-redo");    break;
                case 4: $item->graphic->classes->add("button-cut");     break;
                case 5: $item->graphic->classes->add("button-copy");    break;
                case 6: $item->graphic->classes->add("button-insert");  break;
                case 7: $item->graphic->classes->add("button-find");    break;
                case 8: $item->graphic->classes->add("button-replace"); break;
                case 9: $item->graphic->classes->add("button-settings");break;
            }
        }

        $container->classes->add('mark-item');
    }
}