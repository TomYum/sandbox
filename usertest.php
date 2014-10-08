<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './classes/db.class.php';
require_once './classes/Session.class.php';
require_once './classes/user.class.php';

/*
Db::$database = 'sandbox_git_bd';
Db::$host = 'localhost';
Db::$user = 'root';
Db::$password = '';
/**/

/** CONFIG
 **/
Session::setDbName('sandbox_git_bd');



$db = Db::getInstance();
$db->setDbConfig('sandbox_git_bd', 'localhost', 'root');
$db->setDbConfig('u304043', 'localhost', 'root');

try{
    $session = Session::getInstance();
    echo $session->getSid();
    $session->clean();
}  catch (dbException $e){
    echo $e->getMessage();
}catch (Exception $e){
    echo $e->getMessage();
}

/**/