<?php

spl_autoload_register('autoLoader');

function autoLoader($className){
    $path = 'functions/test112320/classes/';
    $ext = '.php';
    $fullPath = $path . $className . $ext;

    include_once $fullPath;
}