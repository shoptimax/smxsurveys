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
class smxsurveysanswers extends oxI18n
{
    /**
     * User group object (default null).
     * @var object
     */
    protected $_oGroups  = null;

    protected $_sClassName = 'smxsurveysanswers';
    
    //var $sCoreTbl = 'smxsurveys_answers';

    /**
     * Class constructor, initiates parent constructor (parent::oxI18n()).
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('smxsurveys_answers');
    }

    /**
     * Inserts object details to DB, returns true on success.
     * @return bool
     */
    function insert()
    {
        // set insert date
        $this->smxsurveys_answers__answerdate = new oxField( date( 'Y-m-d H:m:s' ) );
        return parent::insert();
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
        
        // convert date's to international format
        $this->smxsurveys_answers__answerdate->setValue( oxUtilsDate::getInstance()->formatDBDate( $this->smxsurveys_answers__answerdate->value));

    }

    /**
     * Inserts object details to DB, returns true on success.
     * @return bool
     */
    protected function _insert()
    {
        // set date
        $this->smxsurveys_answers__answerdate = new oxField( date( 'Y-m-d H:m:s' ) );
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

}
?>