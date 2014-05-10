<?php
/**
 *    This file is part of smxsurveys.
 *
 *    smxsurveys is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    smxsurveys is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this package.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.shoptimax.de
 * @package   smxsurveys
 * @copyright (C) shoptimax GmbH 2009-2014
 * @version 0.5.0
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
