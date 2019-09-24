<?php
include 'classes/AbstractClass.php';
include 'classes/prep_statements.php';
include 'glob_defines.php';
include 'new_glob_defines.php';
include 'achiev_defines.php';
include 'map_defines.php';
include 'instance_defines.php';
include 'battle_defines.php';

spl_autoload_register(function ($class_name) {
    include 'classes/'.$class_name . '.php';
});

$locationMgr=new LocationMgr(); //ok that's hack, but i dont see better way. that's dont have constructor