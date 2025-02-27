<?php

namespace develnext\bundle\customIcons;

abstract class AbstractFindTab extends AbstractFindContainer
{
    abstract function getTab();

    abstract function applyIcon();
}