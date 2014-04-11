<?php

/**
* tsBdd.php
* Collecte et sauve la trame Teleinfo issue d'un TeleinfoStick dans une base de donnees.
* Programme a appeler regulierement via Cron par ex.
* 
* Auteur   : christophe Courche christophe.courche@gmail.com
* Version  : 1.0 26/05/2011 
* License  : Creative Common CC BY-NC 2.0
* http://creativecommons.org/licenses/by-nc/2.0/fr/ 
* Vous etes libres de 
*    - partager, reproduire, distribuer, communiquer et modifier l'oeuvre
* Selon les conditions suivantes :
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
* 1.0 26/09/2011 : Creation 
* 
*/
/**
*   Config log4php 
*/
define('LOG4PHP_DIR','./log4php');
define('LOG4PHP_CONFIGURATION',LOG4PHP_DIR.'/resources/tss.properties');
require_once LOG4PHP_DIR.'/Logger.php';
/**
*   Includes 
*/
require_once('./includes/errors.php');
require_once('./includes/config.php');
/**
*   Classes 
*/
require_once('./classes/DebugLogs.class.php');
require_once('./classes/MysqlTools.class.php');
require_once('./classes/TeleinfoStick.class.php');
require_once('./classes/Proxy.class.php');
/**
*----------- MAIN ------------
*/
//Conteneur de la trame Teleinfo.
$teleinfoFeed=array();
//Des objets utiles.
$ts=new Teleinfostick;
//Date d'insertion identique pour toutes les requetes.
$timestamp=date('Y-m-d H:i:s');
//On commence par la connexion a la base de donnees.
try
{
    $Mysql=new Mysql($Serveur=DB_HOST,$Bdd=DB_NAME,$Identifiant=DB_USER,$Mdp=DB_PASSWORD);
}
catch(Erreur$e)
{
    //Oupsss, probleme avec la Bdd
    echo $e->LogErreur();
    return(ERROR_BDD);
}
//Recupere une trame teleinfo
$teleinfoFeed=$ts->getTeleinfoFeed();
if(!$teleinfoFeed)
{
    //Pas de Teleinfo, pas la peine de continuer
    echo ERROR_TELEINFO;
    exit();
}
//Extraction de ADCO
$adco=$ts->getAdco($teleinfoFeed);
if(!$adco)
{
    //Adco non valide, pas la peine de continuer
    echo ERROR_ADCO;
    exit();
}

//Extraction + stockage Mysql des donnees 1-Wire, si elles existent.
//Remarque : necessite l'extension TS 1-WIRE !
$tempFeed=$ts->get1WireFeed($teleinfoFeed);
if($tempFeed)
{
    //Creation requete Sql pour insersion des donnees 1-Wire une Bdd
    $temp1WireMySqlReq=$ts->get1WireMySqlReq($tempFeed,$timestamp,$adco);
    try
    {
        $ret=$Mysql->ExecuteSQL($temp1WireMySqlReq);
    }
    catch(Erreur$e)
    {
        echo $e->LogErreur();
        return(ERROR_SQL);
    }
}

//Stockage Mysql des donnees Teleinfo "natives"
$teleinfoMySqlReq=$ts->getTeleinfoMySqlReq($teleinfoFeed,$timestamp);
try
{
    $ret=$Mysql->ExecuteSQL($teleinfoMySqlReq);
}
catch(Erreur$e)
{
    echo $e->LogErreur();
    return(ERROR_SQL);
}

//Tout les traitements sont termines et OK
echo "200 OK"
?>