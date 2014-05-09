<?php
/**
 * This Software is the property of Shoptimax GmbH and is protected
 * by copyright law - it is NOT Freeware.
 * @copy (C) Shoptimax 2009
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