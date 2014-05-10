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

 /**
  * Include parent class
  **/

class Smxsurveys_Mall extends Admin_Mall
{
    /**
     * DB table having oxshopincl and oxshopexcl fields we are going to deal with
     */
    var $_sMallTable = "smxsurveys";
    
    
    
    /**
     * Set $blAllowSubshopCopy to true if you want to allow the record to be copied to subshops
     *
     * @var bool
     */
    var $_blAllowSubshopCopy = false;

}
?>