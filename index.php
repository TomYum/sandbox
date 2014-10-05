<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './tags/simpleTags.class.php';

$dsn = 'mysql:dbname=sandbox.git;host=localhost';
$username = 'root';
$passwd = '';

try {
    simpleTagsModel::initDb(new PDO($dsn, $username, $passwd) ); 
   
    try {    
        $tagsModel = new simpleTagsModel($dsn, $username, $passwd);
        $tagsModel->addTag('Test1');
    } catch (PDOException  $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
   
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}






