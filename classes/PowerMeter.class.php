<?php

/**
* PowerMeter.class.php
* Fonctions de base pour dialoger avec Google PowerMeter
* 
* Auteur   : christophe Courche christophe.courche@gmail.com
* Version  : 1.0 31/05/2011
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
* 1.0 31/05/2011 : Creation 
* 
*/
class PowerMeter {

    /**
    * Constructeur de la classe
    */
    public function __construct() {

        //instance unique sur la classe DebugLogs
        $this->log=DebugLogs::getInstance();
        $this->log->add("-- DEBUT ".get_class($this)." ----------------------------------------------------",POWERMETER_LOG);
        $this->log->add("--  senseUrl = ".$this->senseUrl,SENSE_LOG);
    }
    function __destruct() {

        $this->log->add("-- FIN ".get_class($this)." ----------------------------------------------------",POWERMETER_LOG);
    }
    /**
    * Envoie une donnee a Sen.se, identifiee par son Id
    */
    public function send($value) {

        $host=POWERMETER_HOST;
        $meter=POWERMETER_METER;
        $authsubtoken=POWERMETER_AUTHSUBTOKEN;
        $user=POWERMETER_USER;
        $variable=POWERMETER_VARIABLE;
        $posturl="";
        $url="";
        $quantity="";
        $proxy=new Proxy;
        $quantity=$value;
        $posturl="/powermeter/feeds/user/$user/$user/variable/$meter.$variable/instMeasurement";
        $header=array("POST $posturl HTTP/1.0","Host: www.google.com","User-Agent: powermeter","Content-Type: application/atom+xml","Expect: ","Authorization: AuthSub token=\"$authsubtoken\"","Connection: close","");
        $url="https://".$host.$posturl;
        $kPowerBodyTemplate='<?xml version="1.0" encoding="UTF-8"?><entry xmlns="http://www.w3.org/2005/Atom" xmlns:meter="http://schemas.google.com/meter/2008"><meter:occurTime meter:uncertainty="1.0">'.date('Y-m-d\TH:i:s.000\Z').'</meter:occurTime><meter:quantity meter:uncertainty="0.001" meter:unit="kW h">'.$quantity.'</meter:quantity></entry>';
        //Execute la requete
        $result=$proxy->post($url,$header,$kPowerBodyTemplate);
        //PowerMeter return "201 Created" if publish is ok
        if(strstr($result,"201 Created")) 
            return true;
        else 
            return false;
    }
}
?>