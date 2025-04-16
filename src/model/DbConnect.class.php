<?php

/**
 * This project is licensed under the GNU LGPL v2.1.
 * You may use, modify, and distribute it under the terms of this license.
 * Modifications must remain open-source under the same license.
 * 
 * Copyright (C) 2025 Hamzah Mansor 
 **/
class dbConnect
{
    private $servername;
    private $username;
    private $password;
    private $database;
    private $charset;

    //connect to server with pdo

    public function connect()
    {
        $this->charset  =  "utf8mb4";
        $config = BASEPATH . '/config.ini';

        if (file_exists($config)) {
            $dbData = parse_ini_file($config);
            foreach ($dbData as $key => $value) {
                $this->$key = $value;
            }
            try {
                $dsn = "mysql:host=" . $this->servername . ";dbname=" . $this->database . ";charset=" . $this->charset;
                $pdo = new PDO($dsn, $this->username, $this->password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "Connected to Database<br/>";
                return $pdo;
            } catch (PDOException $th) {
                echo "OOPS! Connection Faild: " . $th->getCode() . "<br/>";
                exit;
            }
        } else {
            echo "No database data available";
            exit;
        }
    }
}
