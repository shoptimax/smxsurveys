<?php
/**
 * This Software is the property of Shoptimax GmbH and is protected
 * by copyright law - it is NOT Freeware.
 * @copy (C) Shoptimax 2009
 */

/**
 * Admin survey list manager.
 * Performs collection and managing (such as filtering or deleting) function. 
 * @package admin
 */
class Smxsurveys_list extends oxAdminList
{
    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'smxsurveys_list.tpl';

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'smxsurveys';

    /**
     * Type of list.
     *
     * @var string
     */
    protected $_sListType = 'smxsurveyslist';

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSortField = "createdate";

    /**
     * Returns sorting fields array
     *
     * @return array
     */
    public function getListSorting()
    {
        $aSorting = parent::getListSorting();
        if ( isset( $aSorting["smxsurveys"][$this->_sDefSortField] )) {
            $this->_blDesc = true;
        }
        return $aSorting;
    }
}
?>