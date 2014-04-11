<?php

/**
* Sense.class.php
* Fonctions de base pour dialoger avec le systeme Sen.se
* 
* Auteur   : christophe Courche christophe.courche@gmail.com
* Version  : 1.0 26/05/2011 
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
* 1.0 26/05/2011 : Creation 
* 
*/
class Sense {

    private $header='',$senseUrl='',$paramToPostInJSON='';
    /**
    * Constructeur de la classe
    */
    public function __construct() {

        $this->senseUrl="http://".SENSE_HOST.SENSE_URL;
        $this->header=array("POST SENSE_URL HTTP/1.0","Host: SENSE_HOST","User-Agent: TSS","Content-Type: application/json","Connection: close");
        //instance unique sur la classe DebugLogs
        $this->log=DebugLogs::getInstance();
        $this->log->add("-- DEBUT ".get_class($this)." ----------------------------------------------------",SENSE_LOG);
        $this->log->add("--  senseUrl = ".$this->senseUrl,SENSE_LOG);
    }
    function __destruct() {

        $this->log->add("-- FIN ".get_class($this)." ----------------------------------------------------",SENSE_LOG);
    }
    /**
    * Envoie une donnee a Sen.se, identifiee par son Id
    */
    public function publishingEvent($feedId,$value) {

        $proxy=new Proxy;
        //Prepare les parametres a poster
        $this->paramsToPostInJSON='{"feed_id": '.$feedId.',"value": '.$value.'}';
        //Execute la requete
        $result=$proxy->post($this->senseUrl,$this->header,$this->paramsToPostInJSON);
        //Sen.se return 200 OK if publish is ok
        if(strstr($result,"HTTP/1.1 200 OK")) 
            return true;
        else 
            return false;
    }
}
?>