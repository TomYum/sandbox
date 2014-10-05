<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of simpleTags
 *
 * @author TomYum
 */

class simpleTagsTag{
    function __construct() {
        ;
    }
    
}

class simpleTagsModel {
    
    private $_pdoRef = null;
    
    static function initDb( PDO $pdoRef ) {
        $sql = "CREATE TABLE IF NOT EXISTS `simpleTags` (
                    `tagId` int(12) NOT NULL AUTO_INCREMENT,
                    `name` varchar(250) NOT NULL,
                    `enabled` tinyint(1) NOT NULL DEFAULT '0',
                    PRIMARY KEY (`tagId`)
                ) 
                ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        $pdoRef->exec( $sql );
        
        $sql = "CREATE TABLE IF NOT EXISTS `simpleTagsRelations` (
                        `ancestor` int(10) NOT NULL,
                        `descendant` int(10) NOT NULL,
                         `weight` int(6) NOT NULL,
                        UNIQUE KEY `ancestor` (`ancestor`,`descendant`)
                ) 
                ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $pdoRef->exec( $sql );
        
    }
        
    function __construct( $dsn, $username, $passwd, $options = NULL ) {
        $this->_pdoRef = new PDO($dsn, $username, $passwd, $options);        
    }
    
    function __construct2( PDO $pdoRef ) {
        $this->_pdoRef = $pdoRef;        
    }
    
    
    function addTag( $tagName,$enabled = true ) {
        $input_parameters = array(
            'tagName' => strip_tags( $tagName ),
            'tagEnabled' => boolval( $enabled )    
        );
        
        $sql = "INSERT INTO `simpleTags`(`tagId`, `name`, `enabled`) "
                . "VALUES ('',:tagName,:tagEnabled)";
        
        $request = $this->_pdoRef->prepare($sql);
        $request->execute($input_parameters);
                                
        return $this->_pdoRef->lastInsertId();        
    }
    
    
}
