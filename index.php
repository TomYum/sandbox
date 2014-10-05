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
    $pdo = new PDO($dsn, $username, $passwd);
    $tagsModel = new simpleTagsModel( $pdo );
    $tagsModel->addTag('Test112345');
    $tagsModel->rmTag(7);
    $tagsModel->rmTag(8);
    $tagsModel->rmTag(9);
    $tagsModel->rmTag(10);
    $tagsModel->rmTag(11);
   
    
   
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}






