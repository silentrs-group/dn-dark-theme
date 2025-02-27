<?php

namespace develnext\bundle\customIcons\tab;

use develnext\bundle\customIcons\AbstractFindTab;
use ide\Logger;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use php\lib\fs;
use gui;

class ModuleTab extends AbstractFindTab
{

    function getTab()
    {
        $tabs = [];

        if (GuiFrameworkProjectBehaviour::get() == null) return;

        foreach (GuiFrameworkProjectBehaviour::get()->getModuleEditors() as $module) {
            if ($module->getLeftPaneUi() == null) continue;

            $fileName = fs::nameNoExt($module->getFile());

            $module->getLeftPaneUi()->addCustomNode($container = new UXHBox());
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
            $tab->graphic->classes->add("tab-module");
        }
    }

    private function changeMenuIcon($button)
    {
        $button->graphic = new UXHBox();
        $button->graphic->classes->add("property-menu-button");
    }
}