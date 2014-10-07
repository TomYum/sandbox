<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './classes/db.class.php';
require_once './classes/Session.class.php';
require_once './classes/user.class.php';


Db::$database = 'sandbox_git_bd';
Db::$host = 'localhost';
Db::$user = 'root';
Db::$password = '';

$session = Session::getInstance();
//echo $session->getSid();
$session->clean();

