<?php

namespace Conductor\database;

if (!defined('LINKED') && LINKED === TRUE) {
    http_response_code(404);
    include_once 'index.html';
    exit;
}
/*
 * Document   : pdopgsql.php
 * Created on : Apr 17, 2014, 01:04:00 AM
 * Author     : Carlos Romulo T. Suarez
 * Description: PDO for PostgreSQL database driver class for the MVC Framework.
 */

    if (!defined('BASEDIR')) {die ("ERROR 404. Page Not Found.");}

use PDO;

class pdopgsql extends settings{

        protected $dbhandler;
        protected $set=array();

        function __construct($connection){
            
            $this->set = $this->dbsettings();

            $host = $this->set["{$connection}"]["hostname"];
            $usern = $this->set["{$connection}"]["username"];
            $passw = $this->set["{$connection}"]["password"];
            $dbase = $this->set["{$connection}"]["dbname"];
            $port = $this->set["{$connection}"]["port"];
            
            try{
            $pgsql = new PDO("pgsql:host={$host};port={$port};dbname={$dbase};user={$usern};password={$passw}");
            $pgsql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbhandler = $pgsql;
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

