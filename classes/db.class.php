<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db
 *
 * @author Tom.Yum.
 */
class Db {
       /**
     *
     * @var db Ссылка на объект класса 
     */
    static private $instance = null;
    
    
    static $host = null;
    static $user = null;
    static $password = null;
    static $database = null;
    static $port = null;
    static $socket = null;
    
    const LOCALE_CP_1251 = 'cp1251';
    const LOCALE_UTF_8   = 'utf8';
    
    private $dbConnection = null;
    /**
     * 
     * @return Db
     */
    static function getInstance(){
        return (self::$instance) ? self::$instance : new Db();
    }
    
        
    function __construct() {
        try {
            
            if ( !self::$database ) throw new Exception ( 'Не задана bd');
            if ( !self::$user ) throw new Exception ( 'Не задан пользователь бд');
            if ( !self::$host ) throw new Exception ( 'Не задан хост');
            if ( !self::$password == null ) throw new Exception ( 'Не задан пароль');            
            
	 	
                
            $this->dbConnection =  mysqli_connect( self::$host, 
                                                   self::$user, 
                                                   self::$password, 
                                                   self::$database, 
                                                   self::$port );
            
            if ( isset( $this->dbConnection ) and !empty( $this->dbConnection ) ) {
                self::$instance = $this;                
            } else {
                throw new Exception('Не удалось подключиться к БД!');
            }
            
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
            die();
        }
        
    }
   /**
    * 
    * @return mysqli
    */
    public function getDbConnection( $locale = 'cp1251' ){
        $this->setLocale( $locale );
        return $this->dbConnection;
    }
    
    public function setLocale( $locale = 'cp1251' ){
        mysqli_query( $this->dbConnection ,"SET NAMES '{$locale}'");        
    }
}



class dbc {
    static private $instance = null;
    static private $dbInstance = null;
    
    static $host = null;
    static $user = null;
    static $password = null;
    static $database = null;
    static $port = null;
    static $socket = null;
    
    const LOCALE_CP_1251 = 'cp1251';
    const LOCALE_UTF_8   = 'utf8';
    
    static function getInstance(){
        return (self::$instance) ? self::$instance : new dbc();
    }
    static function getDbInstance(){
        self::getInstance();
        return (self::$dbInstance) ? self::$dbInstance : new mysqli(self::$host, self::$user, self::$password, self::$database, self::$port, self::$socket);
    }
    
    public function query($sql,$params){}
    
    public function quoteIdent($field) {
        return "`".str_replace("`","``",$field)."`";
    }
    
    public function toString(string $str) {        
        $str = str_replace("'","/'",$str);
        return $str;
    }
    
    public function toInt($int){
        
    }
    
}