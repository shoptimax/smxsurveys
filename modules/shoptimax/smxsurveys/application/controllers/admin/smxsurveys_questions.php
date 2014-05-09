<?php
/**
 * This Software is the property of Shoptimax GmbH and is protected
 * by copyright law - it is NOT Freeware.
 * @copy (C) Shoptimax 2009
 */

/**
 * @package admin
 */
class Smxsurveys_Questions extends oxAdminView
{
    /**
     * @return string
     */
    public function render()
    {   $myConfig = $this->getConfig();

        parent::render();

        $soxId = $myConfig->getParameter( "oxid");
        // check if we right now saved a new entry
        $sSavedID = $myConfig->getParameter( "saved_oxid");
        if( ($soxId == "-1" || !isset( $soxId)) && isset( $sSavedID) )
        {   $soxId = $sSavedID;
            oxSession::deleteVar( "saved_oxid");
            $this->_aViewData["oxid"] =  $soxId;
            // for reloading upper frame
            $this->_aViewData["updatelist"] =  "1";
        }
        if( $soxId != "-1" && isset( $soxId))
        {   
            // load object
            $oSurveys = oxNew( "smxsurveys" );
            $oSurveys->loadInLang( $this->_iEditLang, $soxId );
            $oOtherLang = $oSurveys->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oSurveys->loadInLang( key($oOtherLang), $soxId );
            }
            $this->_aViewData["edit"] =  $oSurveys;

            //Disable editing for derived items
            if ($oSurveys->isDerived())
                $this->_aViewData['readonly'] = true;

            // remove already created languages
            $this->_aViewData["posslang"] =  array_diff ( oxLang::getInstance()->getLanguageNames(), $oOtherLang);

            foreach ( $oOtherLang as $id => $language) {
                $oLang= new oxStdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }

            // get assigned questions
            $qList = $oSurveys->getAllQuestions();
            if(count($qList))
            {
                // load correct language!
                foreach($qList as $lOxid => $lObject)
                {
                    $question = oxNew("smxsurveysquestion");
                    $question->loadInLang( $this->_iEditLang, $lOxid );
                    $oOtherLang = $question->getAvailableInLangs();
                    if (!isset($oOtherLang[$this->_iEditLang]))
                    {
                        $question->loadInLang( key($oOtherLang), $lOxid );
                    }
                    $objList[$lOxid] = $question;
                    //DumpVar($question);
                }

                $this->_aViewData["smxquestions"] = $objList;
            }
        }

        return "smxsurveys_questions.tpl";
    }

    public function saveinnlang()
    {   
        // apply new language
        $sNewLanguage = oxConfig::getParameter( "new_lang");
        $this->save($sNewLanguage);
    }
    /**
     * Saves questions
     */
    public function save($sNewLanguage = "")
    {   $myConfig = $this->getConfig();

        if($sNewLanguage == "") {
            $sNewLanguage = $this->_iEditLang;
        }
            
        $soxId = $myConfig->getParameter( "oxid");
        
        // load object
        $oSurveys = oxNew( "smxsurveys" );
        $oSurveys->loadInLang( $sNewLanguage, $soxId );
        
        $allQuestions = $myConfig->getParameter("smxquestions");
        $smxquestionstype = $myConfig->getParameter("smxquestionstype");
        $count = 0;
        $countTotal = 0;
        $allQuestionsIds = array();
        if(is_array($allQuestions))
        {
            foreach($allQuestions as $questionId => $questionText)
            {
                $questionText = trim($questionText);
                if($questionText == "") {
                    continue;
                }
                $question = oxNew("smxsurveysquestion");
                if($questionId != "" && $questionId != "0")
                {
                    $question->loadInLang($this->_iEditLang, $questionId);
                    $question->smxsurveys_questions__questiontype = new oxField($smxquestionstype[$questionId], oxField::T_RAW);
                }
                else
                {
                    $question->smxsurveys_questions__questiontype = new oxField($smxquestionstype[$count], oxField::T_RAW);
                    $count++;
                }
                $question->smxsurveys_questions__sort = new oxField($countTotal, oxField::T_RAW);
                $question->smxsurveys_questions__text = new oxField($questionText, oxField::T_RAW);
                $question->smxsurveys_questions__surveyid = new oxField($soxId, oxField::T_RAW);
                $question->smxsurveys_questions__active = new oxField("1", oxField::T_RAW);
                $question->setLanguage( $sNewLanguage);
                $question->save();
                $allQuestionsIds[] = $question->smxsurveys_questions__oxid->value;
                $countTotal++;
            }
        }
        // delete all questions not assigned anymore (deleted via Javascript / DOM)
        if(true)
        {
            $sSurveysQuestionsViewName = getViewName('smxsurveys_questions');
            $allIds = "'" . implode("','", $allQuestionsIds) . "'";
            $delSql = "delete from $sSurveysQuestionsViewName where surveyid='$soxId' and oxid not in ($allIds)";
            oxDb::getDB()->execute($delSql);

            // delete assigned answers, too:
            $sSurveysAnswersViewName = getViewName('smxsurveys_answers2questions');
            $delSql = "delete from $sSurveysAnswersViewName where surveyid='$soxId' and questionid not in ($allIds)";
            oxDb::getDB()->execute($delSql);
        }
        $this->_aViewData["updatelist"] = "1";

        // set oxid if inserted
        if ( $soxId == "-1") {
            oxSession::setVar( "saved_oxid", $question->smxsurveys_questions__oxid->value);
        }

        // set oxid if inserted
        $this->setEditObjectId( $oSurveys->getId() );
    }

}
?>
