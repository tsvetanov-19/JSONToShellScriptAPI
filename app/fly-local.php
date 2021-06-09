<?php

require __DIR__.'/../vendor/autoload.php';

use League\Flysystem\Filesystem;
//use League\Flysystem\Adapter\Local;

$adapter = new League\Flysystem\Adapter\Local(__DIR__.'/../files');
$filesystem = new Filesystem($adapter);
