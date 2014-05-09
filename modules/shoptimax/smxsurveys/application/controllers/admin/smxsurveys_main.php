<?php
/**
 * This Software is the property of Shoptimax GmbH and is protected
 * by copyright law - it is NOT Freeware.
 * @copyright (C) Shoptimax 2009
 */


/**
 * Admin main survey manager.
 * Performs collection and updatind (on user submit) main item information.
 * @package admin
 */
class Smxsurveys_main extends oxAdminDetails
{
    /**
     * Executes parent method parent::render(), creates oxlist object and
     * collects user groups information, passes data to Smarty engine,
     * returns name of template file "news_main.tpl".
     * @return string
     */
    public function render()
    {   $myConfig = $this->getConfig();

        parent::render();

        $soxId = $myConfig->getParameter( "oxid");
        // check if we right now saved a new entry
        $sSavedID = $myConfig->getParameter( "saved_oxid");
        if( ($soxId == "-1" || !isset( $soxId)) && isset( $sSavedID) )
        {   $soxId = $sSavedID;
            oxSession::deleteVar( "saved_oxid");
            $this->_aViewData["oxid"] =  $soxId;
            // for reloading upper frame
            $this->_aViewData["updatelist"] =  "1";
        }

        if( $soxId != "-1" && isset( $soxId))
        {   // load object
            $oSurveys = oxNew( "smxsurveys" );
            $oSurveys->loadInLang( $this->_iEditLang, $soxId );
            $this->_aViewData["link"] =  "[{\$oViewConf->getSelfLink()}]&cl=content&tpl=smxsurvey.tpl&snid=".$soxId;
            $this->_aViewData["directlink"] =  $myConfig->getConfigParam('sShopURL') . "index.php?cl=content&tpl=smxsurvey.tpl&snid=".$soxId;
            
            $oOtherLang = $oSurveys->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                //echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oSurveys->loadInLang( key($oOtherLang), $soxId );
            }
            $this->_aViewData["edit"] =  $oSurveys;

            //Disable editing for derived items
            if ($oSurveys->isDerived())
                $this->_aViewData['readonly'] = true;

            // remove already created languages
            $this->_aViewData["posslang"] =  array_diff ( oxLang::getInstance()->getLanguageNames(), $oOtherLang);

            foreach ( $oOtherLang as $id => $language) {
                $oLang= new oxStdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }
        }
        if ( oxConfig::getParameter("aoc") ) {

            $aColumns = array();
            include_once 'inc/'.strtolower(__CLASS__).'.inc.php';
            $this->_aViewData['oxajax'] = $aColumns;

            return "popups/news_main.tpl";
        }

        return "smxsurveys_main.tpl";
    }

    /**
     * Saves news parameters changes.
     */
    public function save()
    {   $myConfig = $this->getConfig();

        $this->resetContentCache();

        $soxId      = $myConfig->getParameter( "oxid");
        $aParams    = $myConfig->getParameter( "editval");

        // checkbox handling
        if( !isset( $aParams['smxsurveys__active']))
            $aParams['smxsurveys__active'] = 0;

        if (!$aParams['smxsurveys__showresult'])
            $aParams['smxsurveys__showresult'] = "0";

        if (!$aParams['smxsurveys__saveuser'])
            $aParams['smxsurveys__saveuser'] = "0";

        if (!$aParams['smxsurveys__setcookie'])
            $aParams['smxsurveys__setcookie'] = "0";
            
        if (!$aParams['smxsurveys__jscheck'])
            $aParams['smxsurveys__jscheck'] = "0";

        $aParams['smxsurveys__oxshopid'] = $myConfig->getShopId();

        $oSurveys = oxNew( "smxsurveys" );
        if( $soxId != "-1")
            $oSurveys->loadInLang( $this->_iEditLang, $soxId );
        else
            $aParams['smxsurveys__oxid'] = null;
            
        //Disable editing for derived items
        if ($oSurveys->isDerived())
            return;

        $oSurveys->setLanguage(0);
        $oSurveys->assign( $aParams);
        $oSurveys->setLanguage($this->_iEditLang);
        $oSurveys->save();
        $this->_aViewData["updatelist"] = "1";
        // set oxid if inserted
        if ( $soxId == "-1")
            oxSession::setVar( "saved_oxid", $oSurveys->smxsurveys__oxid->value);
    }


    /**
     * Saves news parameters in different language.
     *
     * @return null
     */
    public function saveinnlang()
    {
        $soxId      = oxConfig::getParameter( "oxid");
        $aParams    = oxConfig::getParameter( "editval");
        // checkbox handling
        if ( !isset( $aParams['smxsurveys__active']))
            $aParams['smxsurveys__active'] = 0;

            $this->resetContentCache();

        $oSurveys = oxNew( "smxsurveys" );

        if ( $soxId != "-1")
            $oSurveys->loadInLang( $this->_iEditLang, $soxId );
        else
            $aParams['smxsurveys__oxid'] = null;

            //Disable editing for derived items
            if ($oSurveys->isDerived())
                return;

        //$aParams = $oNews->ConvertNameArray2Idx( $aParams);
        $oSurveys->setLanguage(0);
        $oSurveys->assign( $aParams);

        // apply new language
        $sNewLanguage = oxConfig::getParameter( "new_lang");
        $oSurveys->setLanguage( $sNewLanguage);
        $oSurveys->save();
        $this->_aViewData["updatelist"] = "1";

        // set oxid if inserted
        if ( $soxId == "-1")
            oxSession::setVar( "saved_oxid", $oSurveys->smxsurveys__oxid->value);
    }
}
?>
