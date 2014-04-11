-- 
-- Structure de la table `tbTemp1Wire`
-- 

CREATE TABLE `tbTemp1Wire` (
  `id` int(11) NOT NULL auto_increment,
  `DATE` datetime default NULL,
  `ADCO` varchar(12) collate latin1_german2_ci default NULL,
  `T1` varchar(7) collate latin1_german2_ci default NULL,
  `T2` varchar(7) collate latin1_german2_ci default NULL,
  `T3` varchar(7) collate latin1_german2_ci default NULL,
  `T4` varchar(7) collate latin1_german2_ci default NULL,
  `T5` varchar(7) collate latin1_german2_ci default NULL,
  `T6` varchar(7) collate latin1_german2_ci default NULL,
  `T7` varchar(7) collate latin1_german2_ci default NULL,
  `T8` varchar(7) collate latin1_german2_ci default NULL,
  `T9` varchar(7) collate latin1_german2_ci default NULL,
  `T10` varchar(7) collate latin1_german2_ci default NULL,
  `T11` varchar(7) collate latin1_german2_ci default NULL,
  `T12` varchar(7) collate latin1_german2_ci default NULL,
  `T13` varchar(7) collate latin1_german2_ci default NULL,
  `T14` varchar(7) collate latin1_german2_ci default NULL,
  `T15` varchar(7) collate latin1_german2_ci default NULL,
    PRIMARY KEY  (`id`),
  KEY `SEARCH_INDEX` (`ADCO`,`DATE`)
) ENGINE=MyISAM;