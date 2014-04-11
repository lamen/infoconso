<?php
/**
* ts2Csv.php
* Collecte et sauve la trame Teleinfo issue d'un TeleinfoStick vers un fichier
* CSV journalier.
* Programme a appeler regulierement via Cron par ex.
* 
* Auteur   : christophe Courche christophe.courche@gmail.com
* Version  : 1.0 08/06/2011
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
* 1.0 08/06/2011 : Creation 
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
require_once('./classes/TeleinfoStick.class.php');
require_once('./classes/Csv.class.php');
/**
*----------- MAIN ------------
*/
$log=DebugLogs::getInstance();
$log->add("-- START ".$_SERVER["SCRIPT_NAME"]." -- fromIp: ".$_SERVER["REMOTE_ADDR"]." -- ".date("Y-m-d H:i:s")." --",true);

//Conteneur de la trame Teleinfo.
$teleinfoFeed=array();
//Des objets utiles.
$ts=new Teleinfostick;
$csv=new Csv;

//Recupere une trame teleinfo
$teleinfoFeed=$ts->getTeleinfoFeed();
if(!$teleinfoFeed)
{
    //Pas de Teleinfo, pas la peine de continuer
    echo ERROR_TELEINFO;
    exit();
}

//Extraction des donnees 1-Wire, si elles existent.
//Remarque : necessite l'extension TS 1-WIRE !
$tempFeed=$ts->get1WireFeed($teleinfoFeed);

//Extraction de l'etat des entree numeriques, si elles existent.
//Remarque : necessite l'extension TS 1-WIRE !
$inputState=$ts->getInputState($teleinfoFeed);


//******************* OPERATIONS COMPLEMENTAIRES *****************
//------------ Stockage dans fichier CSV -------------------------
$path=CSV_PATH;
$retour=$csv->save($teleinfoFeed,$path);

//Tout les traitements sont termines et OK
echo "200 OK";
?>