<?php

namespace develnext\bundle\darktheme;

use ide\Ide;
use ide\bundle\AbstractJarBundle;
use ide\library\IdeLibraryBundleResource;
use ide\Logger;
use ide\TestExtension;

class DarkThemeBundle extends AbstractJarBundle
{
    private $theme;

    public function onRegister(IdeLibraryBundleResource $resource)
    {
        parent::onRegister($resource);

        $this->theme = new IDETheme($resource);

        Ide::get()->bind('shutdown', function () {
            $this->theme->off();
        });
    }

}