<?php

namespace develnext\bundle\customIcons\tab;

use develnext\bundle\customIcons\AbstractFindTab;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use php\lib\fs;
use gui;

class SpriteTab extends AbstractFindTab
{

    function getTab()
    {
        $tabs = [];

        if (GuiFrameworkProjectBehaviour::get() == null) return;

        foreach (GuiFrameworkProjectBehaviour::get()->getSpriteEditors() as $form) {
            if ($form->getLeftPaneUi() == null) continue;

            $fileName = fs::nameNoExt($form->getFile());

            $form->getLeftPaneUi()->addCustomNode($container = new UXHBox());
            $propertyList = $container->parent;
            $container->free();

            // $this->changeMenuIcon($propertyList->children[0]->children[0]);

            $container = $propertyList->parent->parent->parent->parent;

            foreach ($this->getIDETab()->tabs as $tab) {
                if ($tab->graphic instanceof UXHBox) continue;

                if ($tab->text == $fileName) {
                    if ($container == $tab->content->items[0]) {
                        $tabs[] = $tab;
                    }
                }
            }
        }

        return $tabs;
    }

    function applyIcon()
    {
        foreach ($this->getTab() as $tab) {
            $tab->graphic = $st = new UXStackPane();
            $tab->graphic->add($sg1 = new UXHBox());
            $tab->graphic->add($sg2 = new UXHBox());
            $tab->graphic->add($sg3 = new UXHBox());
            $sg1->classes->add('tab-sprite-segment1');
            $sg2->classes->add('tab-sprite-segment2');
            $sg3->classes->add('tab-sprite-segment3');
            $tab->graphic->classes->add("tab-sprite");
        }
    }

    private function changeMenuIcon($button)
    {
        $button->graphic = new UXHBox();
        $button->graphic->classes->add("property-menu-button");
    }
}