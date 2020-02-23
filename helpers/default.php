<?php
spl_autoload_register(function ($classname) {
    $path = str_replace('\\', '/', APP_DIR . '/' . $classname . '.php');
    if (file_exists($path)) {
        require_once($path);
    } else {
        echo "Class $classname cannot be connected";
    }
});

