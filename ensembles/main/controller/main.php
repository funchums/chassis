<?php
namespace Ensembles\main\controller;

use Ensembles\main\model as mods;

class main {
    
    private $controller;
    
    function __construct($controller) {
        $this->controller = $controller;
    }
            
    function index() {
        $input1 = 1;
        $input2 = 2;
            return $this->controller->with("input2",$input1 ." ". $input2)
            ->show();
    }
    
    function index2($input1,$input2) {
            return $this->controller->with("input2",$input1 ." ". $input2)
            ->show("index");
    }
    
    function xert($input1,$input2,$input3){
        return $this->controller->with("input2",$input1." ".$input2." ".$input3)
        ->show("index");
    }
    
    function persons(){
        $people = new mods\testmodel;
        $result = $people->getPersons();
//        $this->controller->_header("json");
        return $this->controller->with("title","Persons List.")
        ->with("result",$result)
        ->show();
    }
    
    function checkhash(){
        if($this->controller->_authorized()){
            echo "Authorized";
        }else{
            echo "Unauthorized";
        }
    }
    
    function tstchain(){
         return $this->controller->with("datavar",["Aida","Lorna","Feh"])
                ->with("test1","Hello")
                ->show();  
    }
    
    function tstarr(){
//        $arr = [["a",["b","c"]],["d",["e","f"]]];
        $arr = ["x"=>"a","b","c"];
        
//        foreach ($arr as list($a, $b)) {
//        // $a contains the first element of the nested array,
//        // and $b contains the second element.
//        echo "A: $a;";
//        }
        
        foreach ($arr as $key => $val) {
            if (is_int($key)) {
              ${"ID_".$key} = $val;
            }else{
                ${$key} = $val;
            }
        }
        echo $x;
        echo $ID_0;
        echo $ID_1;
    }
    
}
