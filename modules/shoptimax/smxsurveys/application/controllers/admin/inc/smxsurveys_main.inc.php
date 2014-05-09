<?php
/**
 * This Software is the property of Shoptimax GmbH and is protected
 * by copyright law - it is NOT Freeware.
 * @copy (C) Shoptimax 2009
 */

$aColumns = array( 'container1' => array(    // field , table,  visible, multilanguage, ident
                                        array( 'oxtitle',  'oxgroups', 1, 0, 0 ),
                                        array( 'oxid',     'oxgroups', 0, 0, 0 ),
                                        array( 'oxid',     'oxgroups', 0, 0, 1 ),
                                        ),
                     'container2' => array(
                                        array( 'oxtitle',  'oxgroups', 1, 0, 0 ),
                                        array( 'oxid',     'oxgroups', 0, 0, 0 ),
                                        array( 'oxid',     'oxobject2group', 0, 0, 1 ),
                                        )
                    );
/**
 * Class manages news user groups rights
 */
class ajaxComponent extends ajaxListComponent
{
    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        // active AJAX component
        $sGroupTable = getViewName('oxgroups');

        $sDiscountId      = oxConfig::getParameter( 'oxid' );
        $sSynchDiscountId = oxConfig::getParameter( 'synchoxid' );

        // category selected or not ?
        if ( !$sDiscountId) {
            $sQAdd  = " from $sGroupTable where 1 ";
        } else {
            $sQAdd  = " from oxobject2group left join $sGroupTable on oxobject2group.oxgroupsid=$sGroupTable.oxid ";
            $sQAdd .= " where oxobject2group.oxobjectid = '$sDiscountId' ";
        }

        if ( $sSynchDiscountId && $sSynchDiscountId != $sDiscountId) {
            $sQAdd .= ' and '.$sGroupTable.'.oxid not in ( select '.$sGroupTable.'.oxid from oxobject2group left join '.$sGroupTable.' on oxobject2group.oxgroupsid='.$sGroupTable.'.oxid ';
            $sQAdd .= " where oxobject2group.oxobjectid = '$sSynchDiscountId' ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes some user group from viewing some news.
     *
     * @return null
     */
    public function removegroupfromnews()
    {
        $aRemoveGroups = $this->_getActionIds( 'oxobject2group.oxid' );
        if ( oxConfig::getParameter( 'all' ) ) {

            $sQ = $this->_addFilter( "delete oxobject2group.* ".$this->_getQuery() );
            oxDb::getDb()->Execute( $sQ );

        } elseif ( $aRemoveGroups && is_array( $aRemoveGroups ) ) {
            $sQ = "delete from oxobject2group where oxobject2group.oxid in ('" . implode( "', '", $aRemoveGroups ) . "') ";
            oxDb::getDb()->Execute( $sQ );
        }
    }

    /**
     * Adds user group for viewing some news.
     *
     * @return null
     */
    public function addgrouptonews()
    {
        $aAddGroups = $this->_getActionIds( 'oxgroups.oxid' );
        $soxId      = oxConfig::getParameter( 'synchoxid' );

        if ( oxConfig::getParameter( 'all' ) ) {
            $sGroupTable = getViewName('oxgroups');
            $aAddGroups = $this->_getAll( $this->_addFilter( "select $sGroupTable.oxid ".$this->_getQuery() ) );
        }
        if ( $soxId && $soxId != "-1" && is_array( $aAddGroups ) ) {
            foreach ($aAddGroups as $sAddgroup) {
                $oNewGroup = oxNew( "oxobject2group" );
                $oNewGroup->oxobject2group__oxobjectid = new oxField($soxId);
                $oNewGroup->oxobject2group__oxgroupsid = new oxField($sAddgroup);
                $oNewGroup->save();
            }
        }
    }
}
