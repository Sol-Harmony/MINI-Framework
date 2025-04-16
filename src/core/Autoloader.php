<?php

/**
 * This project is licensed under the GNU LGPL v2.1.
 * You may use, modify, and distribute it under the terms of this license.
 * Modifications must remain open-source under the same license.
 * 
 * Copyright (C) 2025 Hamzah Mansor 
 **/
class Autoloader
{
    protected $DIR;
    public function __construct($path)
    {
        $this->DIR = $path . '/src';
    }

    public function findfile($classname)
    {   //classname is passed through spl autoload register
        $paths = [     //array with paths, where the program is going to search for the php files
            $this->DIR . DIRECTORY_SEPARATOR . 'model'      . DIRECTORY_SEPARATOR . ucfirst($classname) . '.class.php',
            $this->DIR . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . ucfirst($classname) . '.class.php',
            $this->DIR . DIRECTORY_SEPARATOR . 'core'       . DIRECTORY_SEPARATOR . ucfirst($classname) . '.class.php',
            $this->DIR . DIRECTORY_SEPARATOR . $classname   . '.php',
        ];
        foreach ($paths as $value) {
            if (file_exists($value)) {
                include_once($value);       //go throgh every path. if the file is availabe, include it and get out of the loop 
                break;
            }
        }
    }
    public function trigger()
    {
        spl_autoload_register([$this, 'findfile']);  //does the funtion inside(findfile) each time a class is called.classname is passed automatically to the function findfile with ($this)
    }
}
