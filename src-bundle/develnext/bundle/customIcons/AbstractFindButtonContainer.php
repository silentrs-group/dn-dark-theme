<?php

namespace develnext\bundle\customIcons;

use ide\Ide;
use gui;

abstract class AbstractFindButtonContainer extends AbstractFindContainer
{
    abstract public function getButtonContainer();

    abstract public function applyCssToButtonContainer ();
}