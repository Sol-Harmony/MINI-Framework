<?php

/**
 * This project is licensed under the GNU LGPL v2.1.
 * You may use, modify, and distribute it under the terms of this license.
 * Modifications must remain open-source under the same license.
 * 
 * Copyright (C) 2025 Hamzah Mansor 
 **/
class Router
{
    public function __construct($request)
    {
        session_start();
        session_write_close();
        $url = $request->getUrl();      //get the url from request class
        if ($url == '/') {
            $url = "home/show";
        }

        $matches = [];
        $url = trim($url, '/');

        $matches =  explode('/', $url); //save in array matches every element from URL that's splitted with (/) 

        $class = ucfirst($matches[0]);           //first word from array (z.b home), gets the class with that name
        $classname = ucfirst($matches[0]);       //classname gonna be used in controller to "show" the phtml page
        if (isset($matches[1])) {
            $method = lcfirst($matches[1]) . 'Action';      //second word form url is the called method name
        } else {
            $method = "showAction";
        }

        try {
            $myclass = new $class($classname, $request);

            if (!method_exists($myclass, $method)) {
                throw new Exception("Method $method does not exist in class $class.");
            }

            $myclass->$method();
            $myclass->respond($method); // decides whether to show view or return JSON
        } catch (\Throwable $th) {
            http_response_code(404);
            echo "Error 404: Invalid page request.<br/>";
            echo $th->getMessage();
        }
    }
}
