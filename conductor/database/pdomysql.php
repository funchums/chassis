<?php

namespace Conductor\database;

if (!defined('LINKED') && LINKED === TRUE) {
    http_response_code(404);
    include_once 'index.html';
    exit;
}
/*
 * Document   : pdomysql.php
 * Created on : Apr 17, 2014, 01:04:00 AM
 * Author     : Carlos Romulo T. Suarez
 * Description: PDO MySQL database driver class for the MVC Framework.
 */
use PDO;

class pdomysql extends settings{

        protected $dbhandler;
        protected $set=array();

        function __construct($connection){
            
            $this->set = $this->dbsettings();

            $host = $this->set["{$connection}"]["hostname"];
            $usern = $this->set["{$connection}"]["username"];
            $passw = $this->set["{$connection}"]["password"];
            $dbase = $this->set["{$connection}"]["dbname"];
            $charset = $this->set["{$connection}"]["charset"];

            try{
            $mysql = new PDO("mysql:host={$host};dbname={$dbase};charset={$charset}", $usern, $passw);
            $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbhandler = $mysql;
            } catch(PDOException $e) {
              print "Error!: " . $e->getMessage();
              exit;
            }

        }

        function on(){
            return($this->dbhandler);
        }
        
        /*** disconnect database ***/

        function off(){
            $this->dbhandler = NULL;
            return(TRUE);
        }

}

