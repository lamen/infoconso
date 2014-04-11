<?php

/**
* Debuglogs.class.php
* Petite classe pour utiliser log4php. 
* 
* Auteur   : christophe Courche christophe.courche@gmail.com
* Version  : 1.0 10/09/2009 
* 
* License  : Creative Common CC BY-NC 2.0
* http://creativecommons.org/licenses/by-nc/2.0/fr/ 
* Vous etes libres de partager, reproduire, distribuer, communiquer et
* modifier l'oeuvre selon les conditions suivantes :
*    - Vous devez attribuer l'oeuvre de la maniere indiquee par l'auteur
*     de l'oeuvre ou le titulaire des droits (mais pas d'une maniere qui
*     suggererait qu'ils vous soutiennent ou approuvent votre utilisation
*     de l'oeuvre).
*    - Vous n'avez pas le droit d'utiliser cette oeuvre a des fins
*     commerciales.   
*/
/**
* Changelog
* 
* 1.0 10/09/2009 : Creation 
* 
*/
class DebugLogs {

    private static $Myinstance;
    //Constructeur
    private function __construct() {
    }
    /**
    * getInstance
    * Retourne une instance unique sur la classe DebugLogs (pattern Sigleton)       
    */
    static function getInstance() {

        //Construction d'un Singleton, l'objet DebugLogs sera toujours unique.
        if(empty(self::$Myinstance))
        {
            self::$Myinstance=new DebugLogs;
        }
        return self::$Myinstance;
    }
    /**
    * add
    * Ajoute un message au logs.   
    * $message : le message a ecrire dans les logs (obligatoire)  
    * $on_off : permet d'activer ou non le log (true par defaut)             
    */
    function add($message,$on_off=true) {

        if($on_off)
        {
            //le logger est de type "DebugLogs"
            //voir log4php
            $logger=Logger::getLogger("DebugLogs");
            //Ecrit le message sur les differents appender pre-configures.
            //voir log4php
            $logger->info(" ".$message);
        }
    }
}
//class DebugLogs
?>

