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
class SmxsurveysQuestion extends oxI18n
{
    /**
     * User group object (default null).
     * @var object
     */
    protected $_oGroups  = null;

    //var $sCoreTbl = 'smxsurveys';
    protected $_sClassName = 'smxsurveysquestion';
    
    //var $sCoreTbl = 'smxsurveys_questions';

    /**
     * Class constructor, initiates parent constructor (parent::oxI18n()).
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('smxsurveys_questions');
    }

    /**
     * Assigns object data.
     *
     * @param string $dbRecord database record to be assigned
     *
     * @return null
     */
    public function assign( $dbRecord )
    {

        parent::assign( $dbRecord );
    }

    /**
     * Inserts object details to DB, returns true on success.
     * @return bool
     */
    protected function _insert()
    {
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
     *
     * @param string $sOxid Object ID (default null)
     *
     * @return bool
     */
    public function delete( $sOxid = null )
    {
        if ( !$sOxid ) {
            $sOxid = $this->getId();
        }
        if ( !$sOxid ) {
            return false;
        }

        if ( $blDelete = parent::delete( $sOxid ) ) {
            $sQ = "delete from oxobject2group where oxobject2group.oxobjectid = '$sOxid' ";
            oxDb::getDb()->execute( $sQ );
        }
        return $blDelete;
    }

    /**
     * Get all assigned answers to question
     * @global <type> $myConfig
     * @return array
     */
    public function getPossibleAnswers()
    {
        $myConfig = $this->getConfig();
        
        $sSmxsurveysQuestionsViewName = getViewName('smxsurveys_answers2questions');
        $sSel = "select * from $sSmxsurveysQuestionsViewName where questionid = '".$this->sOXID."' order by sort asc";
        $logList = oxNew( "oxlist" );
        $logList->init( "smxsurveysanswer2question" );
        $logList->selectString( $sSel );
        if($logList->count())
        {
            return $logList->getArray();
        }
        return null;
    }

    function getNumAnswers()
    {
        $myConfig = $this->getConfig();

        $sSmxsurveysAnswersViewName = getViewName('smxsurveys_answers');
        $sSel = "select count(*) from $sSmxsurveysAnswersViewName where questionid = '".$this->sOXID."'";
        return oxDb::getDB()->getOne($sSel);
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
            case 'title':
            case 'oxlongdesc':
                $iDataType = oxField::T_RAW;
                break;
        }
        return parent::_setFieldData($sFieldName, $sValue, $iDataType);
    }
}
?>