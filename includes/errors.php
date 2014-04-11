<?php

/*
* config.php
* 
* Codes d'erreurs "TeleinfoStick System" (tss)
* 
* @author Christophe Courché <christophe@suiviconso.fr>
* @version 1.0
*/
/******************************************/
/*        Erreurs : base de données       */
/******************************************/
//Erreur accès à la base de données
define("ERROR_BDD","-10");
//Erreur requete Sql
define("ERROR_SQL","-11");
/******************************************/
/*        Erreurs : Teleinfo              */
/******************************************/
//Erreur recuperation trame Teleinfo
define("ERROR_TELEINFO","-20");
//Le champ ADCO est manquant ou vide
define("ERROR_ADCO","-21");
/******************************************/
/*        Erreurs : Sen.se                */
/******************************************/
//Erreur Sen.se
define("ERROR_SENSE_PUBLISHEVENT","-30");
/******************************************/
/*        Erreurs : Pachube                */
/******************************************/
//Erreur Pachube
define("ERROR_PACHUBE_PUBLISHEVENT","-40");
/******************************************/
/*        Erreurs : CSV       */
/******************************************/
//Erreur creation fichier
define("ERROR_CSV_OPENFILE","-50");
?>

