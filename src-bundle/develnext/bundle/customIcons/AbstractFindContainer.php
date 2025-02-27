<?php

namespace develnext\bundle\customIcons;

use ide\Ide;
use gui;

class AbstractFindContainer
{
    /**
     * @return UXTabPane
     */
    protected function getIDETab()
    {
        return $this->getLayout()->lookup("#fileTabPane");
    }

    /**
     * @return UXTreeView
     */
    protected function getIDEFilesTree()
    {
        return $this->getLayout()->lookup("#directoryTree");
    }


    /**
     * @return UXForm
     */
    protected function getMainForm()
    {
        return Ide::get()->getMainForm();
    }

    /**
     * @return mixed
     */
    protected function getLayout()
    {
        return $this->getMainForm()->layout;
    }
}