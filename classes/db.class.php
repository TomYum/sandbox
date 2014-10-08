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
    
    private $dbInstances = array();
    private $dbConfig = array();
    
    const LOCALE_CP_1251 = 'cp1251';
    const LOCALE_UTF_8   = 'utf8';
    
    
    /**
     * 
     * @return Db
     */
    static function getInstance(){
        if ( !is_a( self::$instance, 'Db' ) ) {
            self::$instance = new Db();
        }
        return self::$instance;
    }
    
    /**
     * Возвращает указатель на объект MySQLi
     * @param string $dbname
     * @return mysqli
     * @throws dbException
     */
    public function getDbInstance( $dbname ) {
       
        if (  is_a( $this->dbInstances[ $dbname ], 'mysqli' )){
            return $this->dbInstances[ $dbname ];
        }else{                       
            if( isset($this->dbConfig[ $dbname ]) ) {                      
                $this->dbInstances[ $dbname ] = new mysqli(
                                                    $this->dbConfig[ $dbname ]['host'], 
                                                    $this->dbConfig[ $dbname ]['user'], 
                                                    $this->dbConfig[ $dbname ]['passwd'], 
                                                    $dbname, 
                                                    $this->dbConfig[ $dbname ]['port'], 
                                                    $this->dbConfig[ $dbname ]['socket']
                                                );/**/
                                               
                return $this->dbInstances[ $dbname ];
            }else{
                throw new dbException('Не получилось подключиться к БД. Не заданы настройки подключения!');            
            }        
        }
    }
    
    public function setDbConfig($database, $host, $user, $password = '', $port = null, $socket = null ){
        
        $this->dbConfig[$database] = array(
            'host'      => $host,
            'user'      => $user,
            'passwd'    => $password,
            'port'      => $port,
            'socket'    => $socket
        );        
        
    }
    
    public function setLocale( $dbName, $locale = 'cp1251' ){
        $dbi = $this->getDbInstance($dbName);
        $dbi->query("SET NAMES '{$locale}'");        
    }
    
    function __get( $name ) {}
    function __set( $name, $value ) {
        throw new Exception("Fuck off! а не $name = $value!");
    }
}



/**
 * Обработчик исключений
 */
class dbException extends Exception{
   
}


