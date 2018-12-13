<?php

namespace Conductor\database;

if (!defined('LINKED') && LINKED === TRUE) {
    http_response_code(404);
    include_once 'index.html';
    exit;
}
/*
 * Document   : settings.php
 * Created on : Oct 10, 2012, 05:39:00 PM
 * Author     : Carlos Romulo T. Suarez
 * Description: Configuration class for the MVC Framework.
 */

class settings{
            
    function dbsettings(){
                
        $properties = [
                    
            "testdb" => [
                    
                "dbtype"    =>  "mysql",
                "hostname"  =>  "127.0.0.1",
                "port"      =>  "3306",
                "dbname"    =>  "testdb",
                "username"  =>  "root",
                "password"  =>  "",
                "charset"   =>  "utf8"
                    
            ]
                    
        ];

        return $properties;
        
    }
 	
}