<?php
/**
 * This Software is the property of Shoptimax GmbH and is protected
 * by copyright law - it is NOT Freeware.
 * @copy (C) Shoptimax 2009
 */

/**
 * Survey manager.
 * @package core
 */
class Smxsurveys extends oxI18n
{
    /**
     * User group object (default null).
     * @var object
     */
    protected $_oGroups  = null;
    
    protected $_sClassName = 'smxsurveys';

    /**
     * Class constructor, initiates parent constructor (parent::oxI18n()).
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('smxsurveys');
    }

    /**
     * Assigns object data.
     * @param string $dbRecord
     */
    public function assign( $dbRecord)
    {	$myConfig = $this->getConfig();

        parent::assign( $dbRecord );

    }

    /**
     * Checks if this object is in group, returns true on success.
     *
     * @param string $sGroupID user group ID
     *
     * @return bool
     */
    public function inGroup( $sGroupID )
    {
        $blResult = false;
        $aGroups  = $this->getGroups();
        foreach ( $aGroups as $oObject ) {
            if ( $oObject->_sOXID == $sGroupID ) {
                $blResult = true;
                break;
            }
        }
        return $blResult;
    }

    /**
     * Returns list of user groups assigned to current news object
     *
     * @return oxlist
     */
    public function getGroups()
    {
        if ( $this->_oGroups == null && $sOxid = $this->getId() ) {
            // usergroups
            $this->_oGroups = oxNew( 'oxlist', 'oxgroups' );
            $sSelect  = "select oxgroups.* from oxgroups, oxobject2group ";
            $sSelect .= "where oxobject2group.oxobjectid='$sOxid' ";
            $sSelect .= "and oxobject2group.oxgroupsid=oxgroups.oxid ";
            $this->_oGroups->selectString( $sSelect );
        }

        return $this->_oGroups;
    }

    /**
     * Gets all assigned groups
     * @return array
     */
    public function numGroups()
    {    if( !$this->_oGroups)
            $this->_oGroups = $this->getGroups();

         return $this->_oGroups->count();
    }

    /**
     * Inserts object details to DB, returns true on success.
     * @return bool
     */
    protected function _insert()
    {
        // setting insert time
        $this->smxsurveys__createdate = new oxField( date( 'Y-m-d' ) );
        if ( !$this->smxsurveys__createdate || oxUtilsDate::getInstance()->isEmptyDate( $this->smxsurveys__createdate->value ) ) {
            // if date field is empty, assigning current date
            $this->smxsurveys__createdate = new oxField( date( 'Y-m-d' ) );
        } else {
            $this->smxsurveys__createdate = new oxField( oxUtilsDate::getInstance()->formatDBDate( $this->smxsurveys__createdate->value, true ) );
        }

        return parent::_insert();
    }

    /**
     * Updates object information in DB.
     */
    protected function _update()
    {
        parent::_update();
    }

    /**
     * Deletes object information from DB, returns true on success.
     * @param string $sOXID Object ID (default null)
     * @return bool
     */
    public function delete( $sOXID = null)
    {	$myConfig = $this->getConfig();

        if( !$sOXID)
            $sOXID = $this->getId();
        if( !$sOXID)
            return false;

        $oDB = oxDb::getDb();

        if ( $blDelete = parent::delete( $sOXID ) ) {
            $sDelete = "delete from oxobject2group where oxobject2group.oxobjectid = '$sOXID'";
            $rs = $oDB->Execute( $sDelete);

            $sDelete = "delete from smxsurveys_answers where surveyid = '$sOXID'";
            $rs = $oDB->Execute( $sDelete);

            $sDelete = "delete from smxsurveys_answers2questions where surveyid = '$sOXID'";
            $rs = $oDB->Execute( $sDelete);

            $sDelete = "delete from smxsurveys_questions where surveyid = '$sOXID'";
            $rs = $oDB->Execute( $sDelete);

            $sDelete = "delete from smxsurveys_participants where surveyid = '$sOXID'";
            $rs = $oDB->Execute( $sDelete);
        }
        return $blDelete;
    }

    public function isActive()
    {
        $curdate = date("Y-m-d H:m:s");
        return ($this->smxsurveys__active->value == '1' || ($curdate > $this->smxsurveys__startdate->value and $curdate < $this->smxsurveys__enddate->value));
    }

    public function getAllQuestions()
    {
        $myConfig = $this->getConfig();
        
        $sSmxsurveysQuestionsViewName = getViewName('smxsurveys_questions');
        $sSel = "select * from $sSmxsurveysQuestionsViewName where surveyid = '".$this->getId()."' order by sort asc";
        $logList = oxNew( "oxlist" );
        $logList->init( "smxsurveysquestion" );
        $logList->selectString( $sSel );
        if($logList->count())
        {
            return $logList->getArray();
        }
        return null;
    }
    
    /**
     * Sets data field value
     *
     * @param string $sFieldName index OR name (eg. 'oxarticles__oxtitle') of a data field to set
     * @param string $sValue     value of data field
     * @param int    $iDataType  field type
     *
     * @return null
     */
    protected function _setFieldData( $sFieldName, $sValue, $iDataType = oxField::T_TEXT)
    {
        switch (strtolower($sFieldName)) {
            case 'oxshortdesc':
            case 'oxlongdesc':
                $iDataType = oxField::T_RAW;
                break;
        }
        return parent::_setFieldData($sFieldName, $sValue, $iDataType);
    }
}
?>