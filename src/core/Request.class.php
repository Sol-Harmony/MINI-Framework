<?php

/**
 * This project is licensed under the GNU LGPL v2.1.
 * You may use, modify, and distribute it under the terms of this license.
 * Modifications must remain open-source under the same license.
 * 
 * Copyright (C) 2025 Hamzah Mansor 
 **/
class Request
{
    protected $data = [];
    public function getParam($name, $default = null) //function to check if exists a get or post with the parameter name exists, and then applies the method toget 
    {
        if (array_key_exists($name, $_GET)) {
            return mb_convert_encoding($_GET[$name], 'UTF-8');
        } elseif (array_key_exists($name, $_POST)) {
            return mb_convert_encoding($_POST[$name], 'UTF-8');
        } elseif (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return $default;
    }

    public function getUrl()
    { //function to get the url whenever you want
        return $_SERVER['REQUEST_URI'];
    }

    public function isSubmit()    //function that returns true when a submit button is used
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            return true;
        } elseif ($_SERVER['REQUEST_METHOD'] === "GET") {
            return true;
        } else {
            return false;
        }
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
}
