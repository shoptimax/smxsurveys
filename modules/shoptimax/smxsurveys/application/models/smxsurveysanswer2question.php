<?php
/**
 * This Software is the property of Shoptimax GmbH and is protected
 * by copyright law - it is NOT Freeware.
 * @copyright (C) Shoptimax 2009
 */

/**
 * Survey manager.
 * @package core
 */
class smxsurveysanswer2question extends oxI18n
{
    /**
     * User group object (default null).
     * @var object
     */
    protected $_oGroups  = null;

    //var $sCoreTbl = 'smxsurveys';
    protected $_sClassName = 'smxsurveysanswer2question';
    
    //var $sCoreTbl = 'smxsurveys_answers2questions';

    /**
     * Class constructor, initiates parent constructor (parent::oxI18n()).
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('smxsurveys_answers2questions');
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
     * get all freetext answers
     * @global <type> $myConfig
     * @return array
     */
    public function getFreetextAnswers()
    {
        $myConfig =  $this->getConfig();
        $sSmxsurveysAnswersViewName = getViewName('smxsurveys_answers');
        if($this->smxsurveys_answers2questions__is_freetext->value == "1")
        {
            $sSel = "select * from $sSmxsurveysAnswersViewName where answerid = '".$this->sOXID."'";
            $logList = oxNew( "oxlist", "smxsurveysanswers" );
            $logList->selectString( $sSel );
            if($logList->count())
            {
                return $logList->getArray();
            }
        }
        return null;
    }

}
?>