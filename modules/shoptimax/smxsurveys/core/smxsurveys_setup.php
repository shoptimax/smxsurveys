<?php

/*
 * This software is the property of shoptimax GmbH and is protected.
 * @copyright (c) shoptimax GmbH | 2014
 * @author smx_sm
 * @package oxid-ee-505-shoptifind
 * @version ##@@VERSION@@##
 * @date 07.05.2014
 */

/**
 * Description of smxsurveys_setup
 *
 * @author Stefan
 */
class smxsurveys_setup  extends oxSuperCfg {

    /**
     * Setup routine
     */
    public static function onActivate() {
        $shopId = oxRegistry::getConfig()->getShopId();
        $bIsEE = oxRegistry::getConfig()->getEdition() === "EE";
        $oxShopIdStr = "`OXSHOPID` int(11) NOT NULL default '1'";
        if (!$bIsEE) {
            $oxShopIdStr = "`OXSHOPID` varchar(32) collate latin1_general_ci NOT NULL";
        }
        $db = oxDb::getDb();
        try {
            $sQ = "CREATE TABLE IF NOT EXISTS `smxsurveys` (
                `OXID` varchar(32) collate latin1_general_ci NOT NULL,
                $oxShopIdStr,
                `OXSHOPINCL` bigint(20) unsigned NOT NULL default '0',
                `OXSHOPEXCL` bigint(20) unsigned NOT NULL default '0',
                `ACTIVE` enum('0','1') NOT NULL default '0',
                `CREATEDATE` datetime NOT NULL default '0000-00-00 00:00:00',
                `STARTDATE` datetime NOT NULL default '0000-00-00 00:00:00',
                `ENDDATE` datetime NOT NULL default '0000-00-00 00:00:00',
                `TITLE` varchar(255) NOT NULL,
                `TITLE_1` varchar(255) NOT NULL,
                `SETCOOKIE` enum('0','1') NOT NULL default '0',
                `SHOWRESULT` enum('0','1') NOT NULL default '1',
                `SAVEUSER` enum('0','1') NOT NULL default '0',
                `JSCHECK` enum('0','1') NOT NULL,
                PRIMARY KEY  (`OXID`)
              ) ENGINE=MyISAM ;";
            $db->Execute($sQ);

            $sQ = "CREATE TABLE IF NOT EXISTS `smxsurveys_answers` (
                `OXID` varchar(32) collate latin1_general_ci NOT NULL,
                `ANSWERID` varchar(32) collate latin1_general_ci NOT NULL,
                `QUESTIONID` varchar(32) collate latin1_general_ci NOT NULL,
                `SURVEYID` varchar(32) collate latin1_general_ci NOT NULL,
                `FREETEXT` text NOT NULL,
                `ANSWERDATE` datetime NOT NULL default '0000-00-00 00:00:00',
                PRIMARY KEY  (`OXID`)
              ) ENGINE=MyISAM ;";
            $db->Execute($sQ);

            $sQ = "CREATE TABLE IF NOT EXISTS `smxsurveys_answers2questions` (
                `OXID` varchar(32) collate latin1_general_ci NOT NULL,
                `QUESTIONID` varchar(32) collate latin1_general_ci NOT NULL,
                `SURVEYID` varchar(32) collate latin1_general_ci NOT NULL,
                `TEXT` varchar(255) NOT NULL,
                `TEXT_1` varchar(255) NOT NULL,
                `IS_FREETEXT` enum('0','1') NOT NULL default '0',
                `ANSWERCOUNT` int(10) NOT NULL default '0',
                `SORT` int(10) NOT NULL default '0',
                PRIMARY KEY  (`OXID`)
              ) ENGINE=MyISAM ;";
            $db->Execute($sQ);
            
            $sQ = "CREATE TABLE IF NOT EXISTS `smxsurveys_participants` (
                `OXID` varchar(32) collate latin1_general_ci NOT NULL,
                `SURVEYID` varchar(32) collate latin1_general_ci NOT NULL,
                `USERNAME` varchar(255) NOT NULL,
                PRIMARY KEY  (`OXID`)
              ) ENGINE=MyISAM ;";
            $db->Execute($sQ);
            
            $sQ = "CREATE TABLE IF NOT EXISTS `smxsurveys_questions` (
                `OXID` varchar(32) collate latin1_general_ci NOT NULL,
                `SURVEYID` varchar(32) collate latin1_general_ci NOT NULL,
                `QUESTIONTYPE` enum('0','1','2') NOT NULL,
                `TEXT` varchar(255) NOT NULL,
                `TEXT_1` varchar(255) NOT NULL,
                `ACTIVE` enum('0','1') NOT NULL default '1',
                `SORT` int(4) NOT NULL,
                PRIMARY KEY  (`OXID`)
              ) ENGINE=MyISAM ;";
            $db->Execute($sQ);

           // Recreate views
            $dbmetadatahandler = oxRegistry::get('oxDbMetaDataHandler');
            $dbmetadatahandler->updateViews(array('smxsurveys', 'smxsurveys_questions','smxsurveys_answers2questions'));
            
            // delete tmp
            $tmpdir = oxRegistry::getConfig()->getConfigParam('sCompileDir');
            $d = opendir($tmpdir);
            while (($filename = readdir($d) ) !== false) {
                $filepath = $tmpdir . $filename;
                if (is_file($filepath)) {
                    unlink($filepath);
                }
            }
            
        } catch (Exception $ex) {
            error_log("Error activating module: " . $ex->getMessage());
        }
    }
            
}
