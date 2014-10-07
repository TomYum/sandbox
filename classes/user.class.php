<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author Tom.Yum.
 */
class User {
    
    static protected  $instance = null;
    
    private $uid    = -1;
    private $login  = null;
    private $passwd = null;
    private $name = null;
    private $lastName = null;
    private $fatherName = null;
    private $email = null;
            
    
        
    static function getInstance(){
        return (self::$instance) ?  self::$instance : new User();
    }
    
    function __construct() {        
        
    }
    
    
    function __get( $name ){}
    function __set( $name, $value ) {}
    
    public function isAnonymous(){
        return ( $this->uid != -1 ) ? false : true;            
    }
    /**
     * Авторизиция пользователя
     * @param Authentication $auth объект метода аутентификации
     * @throws Exception
     */
    public function login( Authentication $auth ) {
        if ( !$this->login OR ! $this->passwd ){ 
            throw new Exception ( 'Не зада логин или пароль');
            return FALSE;
        }else{
            $userArray = $auth->authenticate($this->login, $this->passwd );
            if (  is_array( $userArray )) {
                return $this->parsArray ( $userArray );            
            }else{
                return false;
            }
        }        
    }
    
    private function parsArray( array $userArray ){
        $this->name = $userArray['u_name'];
        $this->name = $userArray['u_last_name'];
        $this->name = $userArray['u_father_name'];
        $this->name = $userArray['u_email'];
        return true;
    }
    
    
    function create(){
        $sql = "INSERT INTO `users` (
                    `u_login`,
                    `u_passwd`,
                    `u_name`,
                    `u_last_name`,
                    `u_father_name`,
                    `u_email`
                )
                VALUES(
                    '{$this->login}',
                    '{$this->passwd}',
                    '{$this->name}',
                    '{$this->lastName}',    
                    '{$this->fatherName}',
                    '{$this->email}'
                )
                ";
        $db  = Db::getInstance();
        $dbc = $db->getDbConnection();
        $query = $dbc->query($sql);
        if ( $query ) {
            $this->uid = $dbc->insert_id();
            return true;
        }
    }
    
    
}
