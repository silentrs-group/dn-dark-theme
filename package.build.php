<?php

use compress\ZipArchive;
use compress\ZipArchiveEntry;
use compress\ZipArchiveInput;
use php\io\File;
use php\io\MiscStream;
use php\lib\fs;
use packager\cli\Console;

$path = './src-style';
$file = 'jars/dn-dark.dntheme';

Console::info("Make theme file");

Tasks::createFile($file);

$theme = new ZipArchive($file);
$theme->open();

fs::scan($path, function (File $file) use ($theme) {
    if ($file->isFile()) {
        Console::info("-> Add file {$file}");
        $theme->addFile($file, fs::relativize($file, 'src-style'));
    }
});

$theme->close();