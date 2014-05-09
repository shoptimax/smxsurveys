<?php
/**
 * This Software is the property of Shoptimax GmbH and is protected
 * by copyright law - it is NOT Freeware.
 * @copy (C) Shoptimax 2009
 */

/**
 * News list manager.
 * Creates news objects, fetches its data.
 * @package core
 */
class Smxsurveyslist extends oxList
{
    /**
     * List Object class name
     *
     * @var string
     */
    protected $_sObjectsInListName = 'smxsurveys';
    /**
     * Ref. to user object
     */
    protected $_oUser = null;

    /**
     * Loads surveys stored in DB, filtered by user groups, returns array, filled with
     * objects, that keeps news data.
     * @param integer $iLimit Limit of records to fetch from DB(default 0)
     * @return array
     */
    public function loadSurveys( $iLimit = 0)
    {	$myConfig = $this->getConfig();
    	$mySession = $this->getSession();
    
        if ( $iLimit ) {
            $this->setSqlLimit( 0, $iLimit );
        }

    	$sSmxsurveysViewName = getViewName('smxsurveys');
        $oBaseObject   = $this->getBaseObject();
        $sSelectFields = $oBaseObject->getSelectFields();
    	
        if ( $oUser = $this->getUser() ) {
            // performance - only join if user is logged in
            $sSelect  = "select $sSelectFields from $sSmxsurveysViewName ";
            $sSelect .= "left join oxobject2group on oxobject2group.oxobjectid=$sSmxsurveysViewName.oxid where ";
            $sSelect .= "oxobject2group.oxgroupsid in ( select oxgroupsid from oxobject2group where oxobjectid='".$oUser->getId()."' ) or ";
            $sSelect .= "( oxobject2group.oxgroupsid is null ) ";
        } else {
            $sSelect  = "select $sSelectFields, oxobject2group.oxgroupsid from $sSmxsurveysViewName ";
            $sSelect .= "left join oxobject2group on oxobject2group.oxobjectid=$sSmxsurveysViewName.oxid where oxobject2group.oxgroupsid is null ";
        }

        $sSelect .= " and ".$oBaseObject->getSqlActiveSnippet();
        $sSelect .= " group by $sSmxsurveysViewName.oxid order by $sSmxsurveysViewName.oxdate desc ";

        $this->selectString( $sSelect );
    }   
    /**
     * News list user setter
     *
     * @param oxuser $oUser user object
     *
     * @return null
     */
    public function setUser( $oUser )
    {
        $this->_oUser = $oUser;
    }

    /**
     * News list user getter
     *
     * @return oxuser
     */
    public function getUser()
    {
        if ( $this->_oUser == null ) {
            $this->_oUser = parent::getUser();
        }

        return $this->_oUser;
    }
}
?>