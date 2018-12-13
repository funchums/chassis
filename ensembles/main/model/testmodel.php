<?php
 namespace Ensembles\main\model;

 use Conductor\database as db;
 
 class testmodel extends db\pdomysql{
     
    protected $db;
    protected $conn;
    
    public function __construct() {
        $this->conn = new db\pdomysql('testdb');
        $this->db = $this->conn->on();
    }
    
    function getPersons(){
        
        //mysql and postgre
        $stmt = $this->db->prepare("SELECT * FROM persons;");
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        $this->conn->off();
        return($result);
        
    }
    
 }
