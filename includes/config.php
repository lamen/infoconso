<?php
/*
* config.php
* 
* Configuration de "TeleinfoStick System" (tss)
* 
* @author Christophe Courché <christophe@suiviconso.fr>
* @version 1.0
*/
/******************************************/
/*        Section : global                */
/******************************************/
define('PHP_ERROR_REPORTING_LEVEL','E_ALL');
define('PHP_MAX_EXEC_TIME',120);
/** Si Windows : true, linux : false **/
define('PHP_ON_WINDOWS',false);
/******************************************/
/*        Section : CSV                   */
/******************************************/
define('CSV_LOG',true);
//define('CSV_PATH','F:\Dev_Php\tss\trunk\csv'); //ex Windows
define('CSV_PATH','/var/www/tss/csv'); //ex Linux
/******************************************/
/*        Section : base de données       */
/******************************************/
/** Log de la classe Mysql ? **/
define('MYSQL_LOG',true);
/** Le nom de la base de données de teleinfo2net. */
define('DB_NAME','tssBdd');
/** Utilisateur de la base de données MySQL. */
define('DB_USER','tss');
/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD','tss0555');
/** Adresse de l'hébergement MySQL. */
define('DB_HOST','localhost');
/** Table de stockage des données Teleinfo */
define('TBTELEINFO','tbTeleinfo');
/** Table de stockage des T° 1-Wire */
define('TBTEMP1WIRE','tbTemp1Wire');
/******************************************/
/*        Section : TeleinfoStick                */
/******************************************/
/** Log de la classe TeleinfoStick ? **/
define('TS_LOG',true);
/** Simulation (test) : true / carte de collecte : false  **/
define('TS_SIMU',true);
/** Port serie assigne a TeleinfoStick **/
define('TS_PORT','/dev/ttyUSB0');
/** Correspondance Id Sonde 1-Wire / Bdd **/
$sonde1WireIdSqlRow=array();
//T° garage
$sonde1WireIdSqlRow['SAC']='T1';
//T° Ch1 Eva
$sonde1WireIdSqlRow['S0D']='T2';
//T° Ch2 Salle de Jeux
$sonde1WireIdSqlRow['S1A']='T3';
//T° Ch3 Rdc
$sonde1WireIdSqlRow['S85']='T4';
//T° Cuisine
$sonde1WireIdSqlRow['SBB']='T5';
/******************************************/
/*        Section : Curl                  */
/******************************************/
/** Log de la classe Curl ? **/
define('PROXY_LOG',true);
/** Il y a t'il un proxy http a traverser ? **/
define('CURL_PROXY',false);
/** Ip du proxy http a traverser **/
define('CURL_PROXY_HOST','10.193.118.30');
/** Port du proxy http a traverser **/
define('CURL_PROXY_IP','3128');
/******************************************/
/*        Section : PHP config            */
/******************************************/
error_reporting(PHP_ERROR_REPORTING_LEVEL);
set_time_limit(PHP_MAX_EXEC_TIME);
?>

