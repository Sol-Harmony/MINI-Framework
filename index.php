<?php

/**
 * This project is licensed under the GNU LGPL v2.1.
 * You may use, modify, and distribute it under the terms of this license.
 * Modifications must remain open-source under the same license.
 * 
 * Copyright (C) 2025 Hamzah Mansor 
 **/

//error reporting for developers
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//error reporting for live website
// ini_set('display_errors', 0);
// ini_set('log_errors ', 1);

include('src/core/Autoloader.php'); //include the autoloader class to load other classes automatically

define("BASEPATH", __DIR__);

try {
    $autoloader = new Autoloader(BASEPATH);      //autoloader class to load other classes automatically  
    $autoloader->trigger();                     //spl autoload register to do the funtion inside(findfile) each time a class is called

    $request = new Request();                   //get the request class and pass it to router to work with it there
    new Router($request);                       //go to router with request

} catch (\Throwable $th) {
    echo $th->getMessage();
}
