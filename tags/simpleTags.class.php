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
    private $_id            = NULL;
    private $_name          = NULL;
    private $_enabled       = false;
    private $_parents       = array();
    private $_children      = array();
        
    function __construct( $name, $enabled = NULL, array $parents = NULL ) {
        $this->_name = $name;
        $this->_enabled = $enabled;
        $this->_parents = $parents;
    }
    
    function getId(){
        return $this->_id;
    }
    function getName(){
        return $this->_name;
    }
    function isEnabled(){
        return boolval( $this->_enabled );
    }
    
    function setName( $name ){
        $this->_name = $name;
        return $this;
    }
    function enable(){
        $this->_enabled = true;
        return $this;
    }
    function setId( $id ){
        $this->_id = $id;
        return $this;
    }
    
    function addParent( $parentId ){
        $this->_parents[] = $parentId;
        return $this;
    }
    function addChild( $childId ){
        $this->_children[] = $childId;
        return $this;
    }
    
     function addParents( $parentsIds ){
        array_push( $this->_parents, $parentsIds );
        return $this;
    }
    function addChildren( $childrenIds ){
        array_push( $this->_children, $childrenIds );
        return $this;
    }
    
    function getChildren(){
        return $this->_children;
    }
    function getParents(){
        return $this->_parents;
    }
    
    
    function __set($name, $value) {
        throw new Exception('Can\'t set a property!');
    }
    
    function __get($name) {
        throw new Exception('Can\'t get a property!');
    }
        
    function __call($name, $arguments) {
        throw new Exception('Can\'t call a function!');
    }
    
}



class simpleTagsModel {
    
    static private $instance = null;

    static function getInstance( PDO $pdoInstance ){
        return (self::$instance) ? self::$instance : new simpleTagsModel( $pdoInstance );
    }
    /**
     * Создает необходимые таблицы
     * @param PDO $pdoRef - ссылка на объект PDO
     */
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
     
    
    private $_pdoRef = null;
                
    function __construct( PDO $pdoRef ) {
        $this->_pdoRef = $pdoRef;        
    }
    
    
    function addTag( $tagName,$enabled = true ) {
        $input_parameters = array(
            'tagName' => strip_tags( $tagName ),
            'tagEnabled' => boolval( $enabled )    
        );
        
        $sql = "INSERT INTO `simpleTags`(`tagId`, `name`, `enabled`) "
                . "VALUES ('',:tagName,:tagEnabled)";
       try{ 
            /*Вставить исключения*/
            $request = $this->_pdoRef->prepare($sql);
            $request->execute($input_parameters);
            
            return $this->_pdoRef->lastInsertId();        
       }  catch (Exception $e){
           
       }                         
        
    } // end addTag()
    
    function rmTag ($tagId){
        $sql = "DELETE FROM `simpleTags` WHERE `tagId` = :tagId";
        $request = $this->_pdoRef->prepare($sql);
        $request->bindParam( 'tagId', $tagId, PDO::PARAM_INT,10);
        return $request->execute();
    }
    function rmTags( $tagsIds ){
        $sql = "DELETE FROM `simpleTags` WHERE `tagId` IN (:tagsIds)";
        $request = $this->_pdoRef->prepare($sql);
        $request->bindParam( 'tagId', explode( ',', $tagsIds ), PDO::PARAM_STR);
        return $request->execute();
    }
    
    function setChild(){}
    function getChildren(){}
    function setParent(){}
    function getParents(){}
       
    
    
    
    
}
