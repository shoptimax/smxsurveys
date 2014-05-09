<?php
/**
 * This Software is the property of Shoptimax GmbH and is protected
 * by copyright law - it is NOT Freeware.
 * @copy (C) Shoptimax 2009
 */
/**
 * @package admin
 */
class Smxsurveys_Answers extends oxAdminView
{
    /**
     * Executes parent method parent::render(), creates oxnews object and
     * passes news text to smarty. Returns name of template file "news_text.tpl".
     * @return string
     */
    function render()
    {   $myConfig = $this->getConfig();
        $mySession = $this->getSession();

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

            // selected
            $selectedQuestion = $myConfig->getParameter("smxquestion");
            $this->_aViewData["smxquestion"] = $selectedQuestion;

            // get assigned questions
            $qList = $oSurveys->getAllQuestions();
            if(count($qList))
            {
                // load correct language!
                foreach($qList as $lOxid => $lObject)
                {
                    $question = oxNew("smxsurveysquestion" );
                    $question->loadInLang( $this->_iEditLang, $lOxid );
                    $oOtherLang = $question->getAvailableInLangs();
                    if (!isset($oOtherLang[$this->_iEditLang]))
                    {
                        // echo "language entry doesn't exist! using: ".key($oOtherLang);
                        $question->loadInLang($this->_iEditLang, $questionId );
                    }
                    $objList[$lOxid] = $question;

                    if($selectedQuestion == $lOxid)
                    {
                        $answers = $question->getPossibleAnswers();

                        if(count($answers))
                        {
                            // load correct language!
                            foreach($answers as $lOxid => $lObject)
                            {
                                $answer = oxNew("smxsurveysanswer2question");
                                $answer->loadInLang($this->_iEditLang, $lOxid);
                                
                                $oOtherLang = $answer->getAvailableInLangs();
                                if (!isset($oOtherLang[$this->_iEditLang]))
                                {
                                    // echo "language entry doesn't exist! using: ".key($oOtherLang);
                                    $answer->loadInLang($this->_iEditLang, $lOxid );
                                }
                                $answerList[$lOxid] = $answer;
                                //DumpVar($question->getPossibleAnswers());
                            }

                            //DumpVar($answers);
                            $this->_aViewData["smxanswers"] = $answerList;
                        }
                    }
                }

                $this->_aViewData["smxquestions"] = $objList;
            }
        }

        return "smxsurveys_answers.tpl";
    }

    function saveinnlang()
    {
        // apply new language
        $sNewLanguage = oxConfig::getParameter( "new_lang");
        $this->save($sNewLanguage);
    }
    /**
     * Saves answers
     */
    function save($sNewLanguage = "")
    {   $myConfig = $this->getConfig();
        $mySession = $this->getSession();

        if($sNewLanguage == "")
            $sNewLanguage = $this->_iEditLang;

        $soxId = $myConfig->getParameter( "oxid");
        $question = $myConfig->getParameter("smxquestion");
        $allAnswers = $myConfig->getParameter("smxanswers");
        $smxanswersfreetext = $myConfig->getParameter("smxanswersfreetext");
        $count = 0;
        $countTotal = 0;
        $allAnswersIds = array();
        if(is_array($allAnswers))
        {
            foreach($allAnswers as $answerId => $answerText)
            {
                $questionText = trim($answerText);
                if($answerText == "")
                    continue;
                $answer = oxNew("smxsurveysanswer2question");
                if($answerId != "")
                {
                    $answer->loadInLang($this->_iEditLang, $answerId);
                    $answer->smxsurveys_answers2questions__is_freetext = new oxField($smxanswersfreetext[$answerId], oxField::T_RAW);
                }
                else
                {
                    $answer->smxsurveys_answers2questions__is_freetext = new oxField($smxanswersfreetext[$count], oxField::T_RAW);
                    $count++;
                }
                $answer->smxsurveys_answers2questions__sort = new oxField($countTotal, oxField::T_RAW);
                $answer->smxsurveys_answers2questions__text = new oxField($answerText, oxField::T_RAW);
                $answer->smxsurveys_answers2questions__questionid = new oxField($question, oxField::T_RAW);
                $answer->smxsurveys_answers2questions__surveyid = new oxField($soxId, oxField::T_RAW);
                $answer->save();
                $allAnswersIds[] = $answer->smxsurveys_answers2questions__oxid->value;
                $countTotal++;
            }
        }
        
        // delete all answers not assigned anymore (deleted via Javascript / DOM)
        if(true/*is_array($allAnswersIds) && count($allAnswersIds)*/)
        {
            $allIds = "'" . implode("','", $allAnswersIds) . "'";
            $sSurveysAnswersViewName = getViewName('smxsurveys_answers2questions');
            $delSql = "delete from $sSurveysAnswersViewName where surveyid='$soxId' and questionid='$question' and oxid not in ($allIds)";
            oxDb::getDB()->execute($delSql);
        }
    }
    
}
?>
