<?php

namespace develnext\bundle\customIcons\tab;

use develnext\bundle\customIcons\AbstractFindTab;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use php\lib\fs;
use gui;

class FormTab extends AbstractFindTab
{

    function getTab()
    {
        $tabs = [];

        if (GuiFrameworkProjectBehaviour::get() == null) return;

        foreach (GuiFrameworkProjectBehaviour::get()->getFormEditors() as $form) {
            if ($form->getLeftPaneUi() == null) continue;

            $fileName = fs::nameNoExt($form->getFile());

            $form->getLeftPaneUi()->addCustomNode($container = new UXHBox());
            $propertyList = $container->parent;
            $container->free();

            $this->changeMenuIcon($propertyList->children[0]->children[0]);

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
            $tab->graphic = new UXHBox();
            $tab->graphic->classes->add("tab-form");
        }
    }

    private function changeMenuIcon($button)
    {
        $button->graphic = new UXHBox();
        $button->graphic->classes->add("property-menu-button");
    }
}