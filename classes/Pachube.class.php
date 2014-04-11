<?php
/**
* Pachube.class.php
* Fonctions de base pour dialoger avec le systeme Pachube
* 
* Auteur   : christophe Courche christophe.courche@gmail.com
* Version  : 1.0 30/05/2011 
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
* 1.0 30/05/2011 : Creation 
* 
*/
class Pachube {

    private $header='',$pachubeUrl='';
    /**
    * Constructeur de la classe
    */
    public function __construct() {

        $this->pachubeUrl="http://".PACHUBE_HOST.PACHUBE_URL;
        $this->header=array("POST ".PACHUBE_URL." HTTP/1.0","Host: ".PACHUBE_HOST."","User-Agent: TSS","X-PachubeApiKey: ".PACHUBE_KEY."","Accept: application/json","Connection: close");
        //instance unique sur la classe DebugLogs
        $this->log=DebugLogs::getInstance();
        $this->log->add("-- DEBUT ".get_class($this)." ----------------------------------------------------",PACHUBE_LOG);
        $this->log->add("--  PachubeUrl = ".$this->pachubeUrl,PACHUBE_LOG);
    }
    function __destruct() {

        $this->log->add("-- FIN ".get_class($this)." ----------------------------------------------------",PACHUBE_LOG);
    }
    /**
    * Envoie une donnee a Pachube
    * 1 "feed" = 1 ou plusieurs Datastream
    * 1 datastream -> stockage des données pour une valeur ex Papp        
    */
    public function publishingEvent($feedId,$datastreamId,$value) {

        $proxy=new Proxy;
        //Prepare les parametres a poster
        //Execute la requete (Json)
        $putUrl=$this->pachubeUrl.$feedId."?_method=put";
        $eemlData='{
          "version":"1.0.0",
          "datastreams":[
              {"id":"'.$datastreamId.'", "current_value":"'.$value.'"}
          ]}';          
        $result=$proxy->post($putUrl,$this->header,$eemlData);
        
        //Pachube return "200 OK" if publish is ok
        if(strstr($result,"200 OK")) 
            return true;
        else 
            return false;
    }
}
?>