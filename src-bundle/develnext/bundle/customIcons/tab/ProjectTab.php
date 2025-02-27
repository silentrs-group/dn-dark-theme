<?php

namespace develnext\bundle\customIcons\tab;

use develnext\bundle\customIcons\AbstractFindTab;
use gui;
use ide\Logger;

class ProjectTab extends AbstractFindTab
{

    function getTab()
    {
        return $this->getIDETab()->tabs[0];
    }

    function applyIcon()
    {
        $this->listViewItems();

        if ($this->getTab()->graphic instanceof UXHBox) return;

        $this->getTab()->graphic = new UXHBox();
        $this->getTab()->graphic->classes->add("tab-project");
    }

    /**
     * @return void
     */
    public function listViewItems(): void
    {
        $listView = $this->getLayout()->lookup("#fileTabPane")->lookupAll('.list-view .list-cell');

        // project tab
        $this->getLayout()->lookup("#fileTabPane")->tabs[0]->style
            = '-fx-font-size: 12px; -fx-padding: 3 3 4 3; -fx-border-radius: 3 3 0 0; -fx-border-color: transparent !important; -fx-font-weight: bold;';

        // create\open tab button
        $this->getLayout()->lookup("#fileTabPane")->tabs[$this->getLayout()->lookup("#fileTabPane")->tabs->count() - 1]->style
            = '-fx-cursor: hand; -fx-padding: 0 !important; -fx-background-color: transparent; -fx-border-color: transparent !important; -fx-border-radius: 50 !important; -fx-tab-min-width: 1px !important;';

        foreach ($listView as $item) {
            if (!$item->graphic || !$item) continue;

            if (!isset($item->graphic->children[1]->children[0]->children[0])) continue;

            if ($item->graphic->children[0] instanceof UXHBox) continue;

            if ($item->graphic->children[1]->children[0]->children[0]->text == "Проект") {
                $item->graphic->children[0] = new UXHBox();
                $item->graphic->children[0]->classes->add("list-item-project");
                $item->graphic->children[0]->classes->add("list-item-icon");
            } else if ($item->graphic->children[1]->children[0]->children[0]->text == "Внешний вид") {
                $item->graphic->children[0] = new UXHBox();
                $item->graphic->children[0]->classes->add("list-item-style");
                $item->graphic->children[0]->classes->add("list-item-icon");
            } else if ($item->graphic->children[1]->children[0]->children[0]->text == "Пакеты") {
                $item->graphic->children[0] = new UXHBox();
                $item->graphic->children[0]->classes->add("list-item-extension");
                $item->graphic->children[0]->classes->add("list-item-icon");
            } else if ($item->graphic->children[1]->children[0]->children[0]->text == "Архив проекта") {
                $item->graphic->children[0] = new UXHBox();
                $item->graphic->children[0]->classes->add("list-item-backup");
                $item->graphic->children[0]->classes->add("list-item-icon");
            } else if ($item->graphic->children[1]->children[0]->children[0]->text == "Спрайты") {
                $item->graphic->children[0] = new UXHBox();
                $item->graphic->children[0]->classes->add("tab-sprite");
                $item->graphic->children[0]->classes->add("list-item-icon");
            }

        }
    }
}