<?php

function autoLoad($name)
{
    $name = str_replace("\\", "/", $name);
    require_once("classes/$name.php");
}

spl_autoload_register("autoLoad");