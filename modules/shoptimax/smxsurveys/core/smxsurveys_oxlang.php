<?php

/**
 * This software is the property of shoptimax GmbH and is protected.
 * @copyright (c) shoptimax GmbH | 2014 
 * @author smxsm
 * @package oxid-ee-514-shoptifind
 * @version ##@@VERSION@@##
 * 
 * Encoding: UTF-8
 * Date: 09.05.2014
 * 
 * Description of smxsurveys_oxlang
 */
class smxsurveys_oxlang extends smxsurveys_oxlang_parent {
    
    public function getMultiLangTables()
    {
        return array_merge(array('smxsurveys', 'smxsurveys_questions','smxsurveys_answers2questions'), parent::getMultiLangTables());
    }    
}

?>
