<?php

namespace develnext\bundle\customIcons\editor;

use develnext\bundle\customIcons\AbstractFindButtonContainer;
use gui;
use php\gui\designer\UXPhpCodeArea;

class CustomClassEditor extends AbstractFindButtonContainer
{

    public function getButtonContainer()
    {
        if (get_class($this->getLayout()->lookup('.styled-text-area')) == null) {
            return null;
        }

        foreach ($this->getLayout()->lookupAll(".styled-text-area") as $editor) {
            $container = $editor->parent->parent;

            if ($container == null) continue;

            if (!$container->children->offsetGet(0)->classes->has("mark-item")) {
                if ($container->children->offsetGet(0)->children == null || $container->children->offsetGet(0)->children->count() != 13) {
                    return null;
                }

                return $container->children->offsetGet(0);
            }
        }

        return null;
    }

    public function applyCssToButtonContainer()
    {
        if (($container = $this->getButtonContainer()) == null) {
            return;
        }

        if ($container->classes->has('mark-item')) {
            return;
        }

        $key = -1;

        foreach ($container->children as $item) {
            if ($item instanceof UXButton) $key++;
            else continue;

            $item->graphic = new UXHBox();
            $item->classes->add('editor-buttons');


            switch ($key) {
                case 0: $item->graphic->classes->add("button-tab");      break;
                case 1: $item->graphic->classes->add("button-undo");     break;
                case 2: $item->graphic->classes->add("button-redo");     break;
                case 3: $item->graphic->classes->add("button-cut");      break;
                case 4: $item->graphic->classes->add("button-copy");     break;
                case 5: $item->graphic->classes->add("button-insert");   break;
                case 6: $item->graphic->classes->add("button-find");     break;
                case 7: $item->graphic->classes->add("button-replace");  break;
                case 8: $item->graphic->classes->add("button-settings"); break;
            }
        }

        $container->classes->add("mark-item");
    }
}