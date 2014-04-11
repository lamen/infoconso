<?php

/**
* Teleinfostick.class.php
* Fonctions pour exploiter la trame Teleinfo via TeleinfoStick
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
* 1.1 11/10/2011 : Test disponibilite port serie.
* 1.0 26/05/2011 : Creation 
* 
*/
class Teleinfostick {
    /**
    * Constructeur de la classe
    */
    public function __construct() {
        //instance unique sur la classe DebugLogs
        $this->log=DebugLogs::getInstance();
        $this->log->add("-- DEBUT ".get_class($this)." ----------------------------------------------------",TS_LOG);
    }
    public function __destruct() {
        $this->log->add("-- FIN ".get_class($this)." ----------------------------------------------------",TS_LOG);
    }
    /**
    * Initialisation d'une trame Teleinfo (pour tests sans TeleinfoStick) 
    * Retourne la trame Teleinfo dand un tableau "cle/valeur"       
    */
    public function getTeleinfoFeed() {
        $char="";
        $contents="";
        $teleinfoArray=array();
        $trame=array();
        //Simulation d'une trame Teleinfo ?
        if(TS_SIMU)
        {
            $teleinfoArray["ADCO"]='020828356291';
            //Adresse du compteur
            $teleinfoArray["OPTARIF"]='HC..';
            //Option tarifaire choisie
            $teleinfoArray["ISOUSC"]='30';
            //Intensité souscrite
            $teleinfoArray["HCHC"]='006649307';
            //Heures Creuses
            $teleinfoArray["HCHP"]='009456933';
            //Heures Pleines
            $teleinfoArray["PTEC"]='HP..';
            //Période Tarifaire en cours
            $teleinfoArray["IINST"]='000';
            //Intensité Instantanée
            $teleinfoArray["IMAX"]='036';
            //Intensité maximale appelée
            $teleinfoArray["PAPP"]='00100';
            //Puissance apparente
            $teleinfoArray["HHPHC"]='A';
            //Horaire Heures Pleines Heures Creuses
            $teleinfoArray["MOTDETAT"]='000000';
            //Mot d'état du compteur
            //Option 1-wire (TS 1-WIRE)
            $teleinfoArray["SAC"]='21.4';
            //Temperature sonde 1-wire
            $teleinfoArray["SAB"]='12.3';
            //Temperature sonde 1-wire
        }
        else 
        // Lecture des infos en provenance d'un TeleinfoStick
        {
            // Ouvrir le port en lecture
            $handle=fopen(TS_PORT,"r");
            if ($handle)
            {
              while(fread($handle,1)!=chr(2));
              do
              {
                  $char=fread($handle,1);
                  if($char!=chr(2)) 
                      $contents.=$char;
              }while($char!=chr(2));
              // Fermer le port
              fclose($handle);
              // Supprimer les caracteres debut/fin de trame
              $trame=substr($contents,1,-1);
              // Separer les messages
              $trame=explode(chr(10).chr(10),$trame);
              // Creation d'un tableau cle/valeur
              foreach($trame as $key=>$message)
              {
                  // Separer l'etiquette, la valeur et le checksum
                  $message=explode(chr(32),$message,3);
                  list($etiquette,$valeur,$checksum)=$message;
                  $teleinfoArray[$etiquette]=$valeur;
              }
            }
            else
            {
              $this->log->add("-- Erreur acces TeleinfoStick sur ".TS_PORT,TS_LOG);
            }
        }
        $this->log->add("-- TeleinfoFeed =  ".print_r($teleinfoArray,true),TS_LOG);
        return $teleinfoArray;
    }
    /**
    * Extraction des temperatures 1-WIRE de la trame teleinfo officielle
    * si des donnees 1-WIRE sont presentes.    
    * Retourne les donnes de temperatures dans un tableau "cle/valeur".
    * Les donnes 1-Wire sont supprimees dans la trame Teleinfo source.              
    */
    public function get1WireFeed(&$teleinfoFeed) {
        $ret=false;
        $tempFeed=array();
        foreach($teleinfoFeed as $key=>$value)
        {
            //Si l'etiquette commance par un "S" et que sa longeur est 3 caracteres
            //il s'agit d'une temperature.
            if(($key[0]=="S")&&(strlen($key)==3))
            {
                //Ajoute la temperature au tableau de stockage
                $tempFeed[$key]=$value;
                //Supprime l'etiquette a la trame teleinfo
                unset($teleinfoFeed[$key]);
                $ret=true;
            }
        }
        if($ret) 
            //Retourne le tabelau
            return $tempFeed;
        else 
            //ou pas ;-)
            return false;
    }
    /**
     * Extraction de l'etat des entrees numerique de la trame teleinfo officielle
     * si ces donnees sont presentes.    
     * Retourne l'etat de chaque entree numerique dans un tableau "cle/valeur".
     * L'etat des entrees numeriques sont supprimees dans la trame Teleinfo
     * source.              
     */
    public function getInputState(&$teleinfoFeed) {
        $ret=false;
        $inputState=array();
        foreach($teleinfoFeed as $key=>$value)
        {
            //Gestion Entree numerique (temporaire)
            if(($key[0]=="E")&&(strlen($key)==3))
            {
                //Supprime l'etiquette a la trame teleinfo
                unset($inputState[$key]);
                $ret=true;
            }
        }
        if($ret) 
            //Retourne le tabelau
            return $inputState;
        else 
            //ou pas ;-)
            return false;
    }
    /**
    * Retourne l'ADCO contenu dans la trame Teleinfo             
    */
    public function getAdco($teleinfoFeed) {
        $adco=$teleinfoFeed["ADCO"];
        $this->log->add("-- Adco =  ".$adco,TS_LOG);
        //Retourne l'ADCO si tout va bien, sinon retourne false.
        if(strlen($adco)!=12) 
            $adco=false;
        return $adco;
    }
    /**
    * Prépare la requete SQL pour inserer les donnees Teleinfo dans 
    * la base.              
    */
    public function getTeleinfoMySqlReq($teleinfoSource,$timestamp) {
        $req="INSERT INTO ".TBTELEINFO." SET ";
        //Ajout de la date au format MySql
        $req=$req."DATE = '".$timestamp."',";
        //Ajoute les valeurs teleinfo
        $nbKeys=0;
        foreach($teleinfoSource as $key=>$values)
        {
            //Gestion des valeurs "vides" ou non
            if($values=="")
            {
                $req=$req.$key."=NULL,";
            } else
            {
                $req=$req.$key."='".$values."',";
            }
            $ret=true;
        }
        $req=rtrim($req,",");
        //La requete est prete.
        $this->log->add("-- TeleinfoMySqlReq =  ".$req,TS_LOG);
        if($ret) 
            return $req;
        else 
            return false;
    }
    /**
    * Prépare la requete SQL pour inserer les temperatures 1-Wire dans 
    * la base.              
    */
    public function get1WireMySqlReq(&$sondeTemp,$mysqldate,$adco) {
        global $sonde1WireIdSqlRow;
        $req="INSERT INTO ".TBTEMP1WIRE." SET ";
        //Ajout de la date au format MySql
        $req=$req."DATE = '".$mysqldate."',";
        //Ajout de l'ACDO
        $req=$req."ADCO = '".$adco."',";
        //Ajoute les valeurs teleinfo
        $nbKeys=0;
        foreach($sondeTemp as $key=>$values)
        {
            //Ajoute la sonde a la requete que si sa description existe
            if(isset($sonde1WireIdSqlRow[$key]))
            {
                $req=$req.$sonde1WireIdSqlRow[$key]."='".$sondeTemp[$key]."',";
                $ret=true;
            }
        }
        $req=rtrim($req,",");
        //La requete est prete.
        $this->log->add("-- 1WireMySqlReq =  ".$req,TS_LOG);
        if($ret) 
            return $req;
        else 
            return false;
    }
}
// class Teleinfostick
?>

