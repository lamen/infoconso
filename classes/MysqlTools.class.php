<?php
/**
* MysqlTools.class.php
* Quelques outils pour se connecter a une base MySql, executer des
* requetes, ... 
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
* Gestion des erreurs avec les exceptions
*/
class Erreur extends Exception {

    public function __construct($Msg) {
        parent::__construct($Msg);
        //instance unique sur la classe DebugLogs
        //$this->log=DebugLogs::getInstance();
    }
    public function LogErreur() {
        //$this->log->add("-- Erreur Mysql : ".$this->getMessage()." Ligne : ".$this->getLine(),MYSQL_LOG);
        return false;
    }
}
class Mysql {
    private $Serveur='',$Bdd='',$Identifiant='',$Mdp='',$Lien='',$Debogue=true;
    /**
    * Constructeur de la classe
    * Connexion aux serveur de base de donnee et selection de la base
    *
    * $Serveur     = L'hote (ordinateur sur lequel Mysql est installe)
    * $Bdd         = Le nom de la base de données
    * $Identifiant = Le nom d'utilisateur
    * $Mdp         = Le mot de passe
    */
    public function __construct($Serveur='localhost',$Bdd='base',$Identifiant='root',$Mdp='') {

        $this->Serveur=$Serveur;
        $this->Bdd=$Bdd;
        $this->Identifiant=$Identifiant;
        $this->Mdp=$Mdp;
        
        //instance unique sur la classe DebugLogs
        $this->log=DebugLogs::getInstance();        
        $this->log->add("-- DEBUT ".get_class($this)." ----------------------------------------------------",MYSQL_LOG);
        
        $this->Lien=mysql_connect($this->Serveur,$this->Identifiant,$this->Mdp);
        if(!$this->Lien&&$this->Debogue) 
            throw new Erreur('Erreur de connexion au serveur MySql : '.$this->Serveur,MYSQL_LOG);
        $Base=mysql_select_db($this->Bdd,$this->Lien);
        if(!$Base&&$this->Debogue) 
            throw new Erreur('Erreur de connexion à la base de donnees : '.$this->Bdd,MYSQL_LOG);
        $this->Lien=mysql_connect($this->Serveur,$this->Identifiant,$this->Mdp);
        $this->log->add("-- Connexion serveur : ".$this->Serveur." / Base : ".$this->Bdd." OK !",MYSQL_LOG);
        
    }
    function __destruct() {
        
        $this->log->add("-- Deconnexion serveur : ".$this->Serveur." / Base : ".$this->Bdd." OK !",MYSQL_LOG);
        $this->log->add("-- FIN ".get_class($this)." ----------------------------------------------------",MYSQL_LOG);
        mysql_close($this->Lien);
        
    }
    
    
    /**
    * Execute une requête SQL et récupère le résultat dans un tableau pré formaté
    * type cle/valeur
    *
    * $requete = Requête SQL a executer
    */
    
    public function tabResSQL($requete) {
        $this->log->add("-- TabResSQL : requete -> ".$requete,MYSQL_LOG);
        $ressource=mysql_query($requete,$this->Lien);
        if ((!$ressource) and ($this->Debogue)) 
            throw new Erreur('Erreur de requête SQL!!!');
        $tabResultat=mysql_fetch_array($ressource);
        mysql_free_result($ressource);
        return $tabResultat;
    }
    
    /**
    * Retourne le dernier identifiant genere par un champ de type AUTO_INCREMENT
    *
    */
    
    public function DernierId() {
        return mysql_insert_id($this->Lien);
    }
    
    /**
    * Execute une requete SQL, renvoie un handler sur la ressource
    *
    * $Requete = Requete SQL
    */
    
    public function executeSQL($Requete) {
        $this->log->add("-- Mysql ExecuteSql : ".$Requete,MYSQL_LOG);
        $Ressource=mysql_query($Requete,$this->Lien);
        if ((!$Ressource) and ($this->Debogue)) 
            throw new Erreur('ExecuteSQL : '.$Requete);
        $this->log->add("-- Mysql ExecuteSql Ok !",MYSQL_LOG);
        return $Ressource;
    }
    
}
?>