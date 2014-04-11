<?php

/**
* Csv.class.php
* Fonctions de base pour sauver la trame teleinfo dans un fichier CSV
* 
* Auteur   : christophe Courche christophe.courche@gmail.com
* Version  : 1.0 03/06/2011 
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
* 1.0 06/06/2011 : Creation 
* 
*/
class Csv {
    /**
    * Constructeur de la classe
    */
    public function __construct() {
        //instance unique sur la classe Csv
        $this->log=DebugLogs::getInstance();
        $this->log->add("-- DEBUT ".get_class($this)." ----------------------------------------------------",CSV_LOG);
    }
    function __destruct() {
        $this->log->add("-- FIN ".get_class($this)." ----------------------------------------------------",CSV_LOG);
    }
    /**
    * Creation de l'entete du fichier CSV
    *       
    */
    public function head($teleinfoFeed) {
        //Extraction de chaque etiquette
        foreach($teleinfoFeed as $key=>$value)
        {
            $head=$head.$key.",";
        }
        //Retire le dernier ";"
        $head=substr($head,0,-1);
        //retourne l'entete
        return $head;
    }
    /**
    * Ecrit dans un fichier des donnees
    * $file : chemin + nom du fichier
    * $data : donnees a ecrire (chaine caracteres)                
    */
    public function fileSave($file,$data) {
        //Creation du fichier
        $fp=fopen($file,"a+");
        if(!$fp)
        {
            //impossible de creer ou ouvrir le fichier.
            return false;
        }
        //Ecriture de l'entête
        fputs($fp,$data);
        //ferme le fichier
        fclose($fp);
        return true;
    }
    /**
    * Sauve les donnees Teleinfo dans un fichier CSV
    * $teleinfoFeed : donnees Teleinfo
    * $file : chemin + nom du fichier        
    */
    public function save($teleinfoFeed,$file) {
        //Construit le path + nom du fichier JourMoisAnnee.csv
        $timestamp=date('dmY');
        if(PHP_ON_WINDOWS) 
            $csvFile=$file.'\\'.$timestamp.'.csv';
        else 
            $csvFile=$file.'/'.$timestamp.'.csv';
        //Verifie si un fichier existe CSV existe
        if(!file_exists($csvFile))
        {
            //Le fichier n'existe pas, creation du fichier
            //et ecriture de l'entete.
            $this->log->add("-- CSV : Creation $csvFile + entete",CSV_LOG);
            $head="DATE,";
            $head=$head.$this->head($teleinfoFeed);
            //Ajoute un CR LF
            $head=$head."\n";
            //Creation du fichier + ecriture entete
            $ret=$this->fileSave($csvFile,$head);
            if(!$ret)
            {
                //L'ecriture c'est mal passee.
                return false;
            }
        }
        //Ajoute les donnees au fichier existant
        $this->log->add("-- CSV : Ajout des donnees au fichier CSV existant",CSV_LOG);
        $buffer=date('d/m/Y H:i:00').",";
        //format A.Dauguet 07/04/2011 20:19:21
        //Extraction de chaque etiquette
        foreach($teleinfoFeed as $key=>$value)
        {
            $buffer=$buffer.$value.",";
        }
        //Retire le dernier ";"
        $buffer=substr($buffer,0,-1);
        //Ajoute un CR LF
        $buffer=$buffer."\n";
        //Ecrit les donnees dans le fichier
        $ret=$this->fileSave($csvFile,$buffer);
        if(!$ret)
        {
            //L'ecriture c'est mal passee.
            return false;
        }
        return true;
    }
}
?>