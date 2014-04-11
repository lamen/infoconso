<?php

/**
* Proxy.class.php
* Petite classe pour realiser des Get ou Post via CURL
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
class Proxy {

    public function __construct() {

        //instance unique sur la classe DebugLogs
        $this->log=DebugLogs::getInstance();
        $this->log->add("-- DEBUT ".get_class($this)." ----------------------------------------------------",PROXY_LOG);
    }
    public function __destruct() {

        $this->log->add("-- FIN ".get_class($this)." ----------------------------------------------------",PROXY_LOG);
    }
    /**
    * Execute un POST sur une URL.
    * $url : url sur laquelle on souhaite poster
    * $header : header de la requete
    * $postfields : donnees a poster            
    */
    public function post($url,$header,$postfields) {

        $ch=curl_init();
        $this->log->add("--  url = ".$url,PROXY_LOG);
        $this->log->add("--  header = ".print_r($header,true),PROXY_LOG);
        $this->log->add("--  postfields = ".$postfields,PROXY_LOG);
        //Paremetres de CURL, voir http://www.php.net/manual/fr/function.curl-setopt.php
        if(CURL_PROXY)
        {
            curl_setopt($ch,CURLOPT_PROXY,CURL_PROXY_HOST.':'.CURL_PROXY_IP);
            //if need !
        }
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_VERBOSE,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_FAILONERROR,false);
        curl_setopt($ch,CURLOPT_HEADER,true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postfields);
        //Tentative de POST
        $result=curl_exec($ch);
        curl_close($ch);
        //Log la reponse
        $this->log->add("--  post answer answer= ".$result,PROXY_LOG);
        //Retourne la reponse
        return $result;
    }
}
//class Proxy
?>