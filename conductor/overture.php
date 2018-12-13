<?php

namespace Conductor;

if (!defined('LINKED') && LINKED === TRUE) {
    http_response_code(404);
    include_once 'index.html';
    exit;
}

/**
 * Description of controller
 * conductor.php controller class of the MVC framework.
 * @author Carlos Romulo T. Suarez
 */

class overture {
    
    private $application;
    private $method;
    private $eggs = 10;
    private $key;
    private $_objects = array();
    private $_routes = array();
    private $_method;
    private $file = NULL;
    private $request;
    
/* initialize framework */
    public function __construct($request) {
        
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        
        $request = ltrim($request, FS);
        $request = rtrim($request, FS);
        
        $this->request = $request;        
        
/* checks if session has started */
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
            
/* checks if we have a set session */
            if(!isset($_SESSION["sera"])){
                $_SESSION["sera"] = $this->scramble($this->eggs);
            }
        }

        $this->_action(getenv('REQUEST_METHOD'));

    }
    
/* check CORS and FORM methods and sanitize inputs */
    private function _action($method) {
        
        $this->_method = $method;
        
        if ($method == 'POST') {
            
            if(array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
                if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                    $this->_method = 'DELETE';
                } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                    $this->_method = 'PUT';
                    $this->_objects = $this->_cleanInputs($_GET);
                    $this->file = file_get_contents("php://input");
                } else {
                    throw new Exception("Unexpected Header");
                }
            }
            
            $this->_objects = $this->_cleanInputs($_POST);
            
            if(array_key_exists("_method", $this->_objects)){
                switch($this->_objects["_method"]){
                    case "DELETE" :
                        $this->_method = "DELETE";
                        break;
                    case "PUT" :
                        $this->_method = "PUT";
                        break;
                    default :
                        $this->alert();
                        break;
                }
            }
            
        }else{
            $this->_objects = $this->_cleanInputs($_GET);
            $this->_method = "GET";
        }
    }

/* check if registered on rest route */
    private function _isRest($request){
        if(array_key_exists($request, $this->_routes)){
            if($this->_routes[$request]["method"]!==$this->_method){
                return FALSE;
            }else{
                return TRUE;
            }
        }
    }
    
/* executes the framework */
    public function play($rest=NULL) {
        
        $request = $this->request;
        
/* create elements variable array */
        $elements = explode(FS, $request);
        
/* clean the elements variable array */
        for ($row = 0; $row < count($elements); $row++) {
            if ($elements[$row] === NULL || $elements[$row] === "") {
                unset($elements[$row]);
            }
        }
         
/* returns all the values from the array and reset indexes. */
        $elements = array_values($elements);

/* create parameter variable array */
        $param = array_slice($elements, 3);
        
/* sanitize the parameters */
        $param = $this->_cleanInputs($param);

/* check isset for all elements */
        isset($elements[0]) ? $application = $elements[0] : $application = NULL;
        isset($elements[1]) ? $class = $elements[1] : $class = NULL;
        isset($elements[2]) ? $method = $elements[2] : $method = NULL;

/* check for application variable */
        isset($application) ? $application : $application = BASEAPP;

/* check for class vriable and check for seeded classes */
        if (isset($class)) {
            if (substr_count($class, "-", 1) > 0) {
                $class = str_replace("-", BS, $class);
                $clsdir = str_replace("-", FS, $class);
            } else {
                $clsdir = $class;
            }
        } else {
            $class = BASECLS;
            $clsdir = BASECLS;
        }

/* check for method variable */
        isset($method) ? $method : $method = BASEMET;

/* complete class string */
        $route = 'Ensembles' . BS . $application . BS . 'controller' . BS . $class;
        $routedir = 'ensembles' . FS . $application . FS . 'controller' . FS . $clsdir . EXT;

/* check if controller class file is readable */
        if (!is_readable($routedir)) {
            $this->alert();
        }
        
/* check if class exist */
        if (!class_exists($route, TRUE)) {
            $this->alert();
        } else {
            
/* initialize controller class */
            $this->application = $application;
            $this->method = $method;
            $this->key = password_hash($_SESSION['sera'], PASSWORD_DEFAULT);
            $controller = new $route($this);
        }

/* check if method exist in class */
        if (!method_exists($controller, $method)) {
            $this->alert();
        } else {
            
/* check for rest request */
            if(isset($rest)){
                if(!$this->$rest($application.FS.$clsdir.FS.$method)){
                    $this->alert("Method Not Allowed.", 405);
                }
            }
            
/* check the number parameters of a method in a class */
            $chkmeth = new \ReflectionMethod($controller, $method);
            $cntparam = $chkmeth->getNumberOfParameters();
            if ($cntparam > 0) {
                
/* check the parameters if equal or greater than the method's parameters */
                if (is_array($param) && count($param) >= $cntparam) {
                    $response = call_user_func_array(array($controller, $method), $param);
                } else {
                    $this->alert();
                }
            } else {
                $response = $controller->{$method}();
            }
            if(!empty($response)){
                if (file_exists($response)) {
                    $key = $this->key;
                    foreach ($this->_objects as $index => $value) {
                        if (is_int($index)) {
                            ${"ID_".$index} = $value;
                        }else{
                            ${$index} = $value;
                        }
                    }
                    include $response;
                } else {
                    $this->alert();
                }
            }
        }
    }
    
/* cache rest routes methods */
    public function _routes($method,$request) {
        $this->_routes[$request] = ["method"=>$method];
    }
    
/* call to show the view page */
    public function show($name=NULL,$dir=NULL){
        
        if (!isset($name)&&!isset($dir)){
            $VP = 'ensembles' . FS . $this->application . FS . 'view' . FS . $this->method . EXT;
        } elseif (isset($name)&&!isset($dir)) {
            $VP = 'ensembles' . FS . $this->application . FS . 'view' . FS . $name . EXT;
        } else {
            $VP = 'ensembles' . FS . $this->application . FS . 'view' . FS . $dir . FS . $name . EXT;
        }
        return $VP;
    }
    
/* type of header to output */
    
    public function _header($type){
        switch ($type) {
            case 'json':
                header("Content-Type: application/json");
                break;
            case 'xml':
                header('Content-Type: text/xml');
                break;
            case 'text':
                header("Content-Type: text/plain");
                break;
            case 'pdf':
                header("Content-Type: application/pdf");
                break;
            default:
                break;
        }
    }
    
/* clean inputs */
    private function _cleanInputs($data) {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $i => $v) {
                $clean_input[$i] = $this->_cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }
        return $clean_input;
    }
    
/* chaining variables for the view */
    public function with($index,$value=NULL){
        if(is_array($index)){
            $this->_objects=$index;
        } else {
            $this->_objects[$index]=$value;
        }
        return $this;
    }
    
/* create a random character string */
    public function scramble($strsize=0,$strpool=""){
		$strpool != "" ? $strpool : $strpool = 'abcdefghijklmnopqrstuvwxyz'
                                                        .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                                                        .'0123456789';
		$randstr = "";
		$poollen = strlen($strpool);
		for ($x=0; $x<$strsize; $x++){
			$randpos = rand(0,$poollen);
			$randstr .= substr($strpool,$randpos,1);
		}
		return $randstr;
    }
    
/* check the session key passed by a secured form */
    public function _authorized(){
        //$key = $this->_objects["key"];
        isset($this->_objects["key"]) ? $key = $this->_objects["key"] : $key = NULL;
        return password_verify($_SESSION["sera"], $key);
    }

/* alert message */
    public static function alert($message=NULL,$code=404) {
        http_response_code($code);
        
//        $status = array(  
//            200 => 'OK',
//            404 => 'Page Not Found',   
//            405 => 'Method Not Allowed',
//            500 => 'Internal Server Error',
//        );
        
        if(!isset($message) && $code===404){
            include_once 'index.html';
        } else {
            echo $message;
        }
        exit;
    }

}
