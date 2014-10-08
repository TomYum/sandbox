<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Session
 *
 * @author Tom.Yum.l
 */

define('SESSION_EXP_TIME', '10368000');
define('SESSION_COKIE_URI', '.basicdecor.ru');



/**
 * 
 */
class Session {
    
    static private $_SESSION_DB_NAME;
    
    static private $instance = null;
    
    
    private $sid = null;
    
    public $uid = null;
    public $firstVisit = null;
    public $lastVisit = null;
    public $visitDelta = null;
    public $visitCount = 0;
    public $ip = null;
    public $browserUserAgent = null;
    public $cityReal = null;
    public $cityGet = null;
    
    
    /**
     * 
     * @return Session 
     */    
    static function getInstance(){        
        if ( !is_a(self::$instance,'Session' ) ) self::$instance =  new Session();
        return self::$instance;
    }    
    
    static function setDbName( $dbName ){
        if (self::$_SESSION_DB_NAME) throw new Exception ( 'БД уже заданна' );
        self::$_SESSION_DB_NAME = $dbName;
    }
    
    function __construct(){
        
        if (!isset($_COOKIE['SID_ID_LOCAL'])){
            $this->createSession();            
        }else{
            
            $this->sid = base64_decode( $_COOKIE['SID_ID_LOCAL'] );
            session_id( $this->sid );
            session_start();
            $this->loadSession();
            
            
            $this->lastVisit = time();            
            
            $this->updateSession();            
        }       
    }
    function __get( $name ){}
    function __set( $name, $val ){
        throw new Exception("Нельзя задать параметр $name в Session." );
    }
    
      
    private function loadSession ( $sid = null ){
        $db = Db::getInstance();
        $dbc = $db->getDbInstance( self::$_SESSION_DB_NAME );
        
        if ( $sid ){
            $this->sid = $dbc->real_escape_string($sid);
        }        
        $sql = "SELECT * FROM `sessions` WHERE `s_id` LIKE '{$this->sid}'";        
        
        $query = $dbc->query($sql);
        $result = $query->fetch_array();
                
        $this->uid = $result['s_u_id'];
        $this->firstVisit = $result['s_dt_visit_first'];
        $this->lastVisit = $result['s_dt_visit_last'];
        $this->visitDelta = time() - $result['s_dt_visit_last'];
        $this->visitCount = ++$result['s_visit_count'] ;
        $this->ip = $result['s_ip'];
        $this->browserUserAgent = $result['s_user_agent'];
        $this->cityReal = $result['s_city_real'];
        $this->cityGet = $result['s_city_get'];       
    }
    
    private function createSession(){
        
        $db = Db::getInstance();
        $dbc = $db->getDbInstance( self::$_SESSION_DB_NAME );
        
        session_start();
        $this->sid = session_id();
        setcookie("SID_ID_LOCAL", base64_encode( $this->sid ), time() + SESSION_EXP_TIME );
        
        $currentTime = time();
        $this->uid = -1;
        $this->browserUserAgent = $dbc->real_escape_string( $_SERVER['HTTP_USER_AGENT'] );
        $this->ip = $_SERVER["REMOTE_ADDR"];
        $this->lastVisit = $currentTime;
        $this->firstVisit = $currentTime;
        $this->visitCount = 1;
        $this->visitDelta = 0;
        $this->cityReal = 0;
        $this->cityGet = 0;
        
        $sql = "INSERT INTO `sessions`
                (
                    `s_id` ,
                    `s_u_id` ,
                    `s_dt_visit_first` ,
                    `s_dt_visit_last` ,
                    `s_dt_visit_delta` ,
                    `s_visit_count` ,
                    `s_ip` ,
                    `s_user_agent` ,
                    `s_city_real` ,
                    `s_city_get`
                )
                VALUES (
                    '{$this->sid}',
                     {$this->uid},
                     {$this->firstVisit},
                     {$this->lastVisit},
                     {$this->visitDelta},
                     {$this->visitCount},
                     '{$this->ip}',
                     '{$this->browserUserAgent}',
                     {$this->cityReal},
                     {$this->cityGet}
                )";
        
        return $dbc->query($sql);        
    }
    
    private function updateSession ( $sid = null ){
        $db = Db::getInstance();
        $dbc = $db->getDbInstance( self::$_SESSION_DB_NAME );
        
        $sql = "UPDATE `sessions` SET
                    `s_u_id` = {$this->uid},
                    `s_dt_visit_last` = {$this->lastVisit},
                    `s_dt_visit_delta` = {$this->visitDelta},
                    `s_visit_count` = {$this->visitCount},
                    `s_ip` = '{$this->ip}',
                    `s_user_agent` = '{$this->browserUserAgent}',
                    `s_city_real` = {$this->cityReal},
                    `s_city_get` = {$this->cityGet}
                WHERE 
                    `s_id` LIKE '{$this->sid}'
                ";
                    
         $dbc->query($sql);           
    }
   
    
    
    public function clean(){
        $db = Db::getInstance();
        $dbc = $db->getDbInstance( self::$_SESSION_DB_NAME );
        
        $expTime = time() - SESSION_EXP_TIME;
        $sql = "DELETE FROM `sessions` WHERE `s_dt_visit_last` < {$expTime}";        
        return $dbc->query($sql);
    }
            
    
        
    function getSid(){
        return $this->sid;
    }
    function setUid( $uid ){
        $this->uid = $uid; 
    }
    function update(){
        $this->updateSession();
    }
    
    
    
}
