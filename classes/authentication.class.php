<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Authentication
 *
 * @author Tom.Yum.
 */
abstract class Authentication {
    
    abstract function authenticate( $login, $passwd );
        
}

class BaseAuthentication extends Authentication {
    
    function authenticate( $login, $passwd ){
        $db = Db::getInstance();
        $dbc = $db->getDbConnection();
        
        $passwd = sha1(md5( $passwd ));        
        $login = $dbc->real_escape_string( $login );
        
        $sql = "SELECT * FROM `users` WHERE `u_login` LIKE '{$login}' AND `u_passwd` LIKE '{$passwd}'";
        $result = $dbc->query( $sql );
        if ( $result ){
            
            $result = $result->fetch_array();            
            $uid = $result['u_id'];
            if ( $uid ) {
                $this->setSessionsUid ( $uid );                    
            }else{
                throw new Exception('UID не найден!');
            }
            
            return $result;
            
        }else{
            throw new Exception('Неверные логи или пароль');
        }
    }
    
    protected function setSessionsUid( $uid ){
        $session = Session::getInstance();
        $session->setUid($uid);
        $session->update();
    }    
}



