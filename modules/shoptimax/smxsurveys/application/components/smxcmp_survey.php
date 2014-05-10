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

class smxcmp_survey extends smxcmp_survey_parent
{
    /**
     * Marking object as component
     * @var bool
     */
    protected $_blIsComponent = true;
    
    /**
     * Sets blIsComponent = true
     * Executes parent::init().
     */
    public function init()
    {   
        parent::init();
    }

    /**
     * Executes parent::render()
     *
     * @return object
     */
    public function render()
    {   $myConfig = $this->getConfig();
        $mySession = $this->getSession();

        $soxId = $myConfig->getParameter( "snid");
        if(!isset($soxId))
        {
            // select newest survey!
            $sQ = "select oxid from smxsurveys where (active = '1' or (curdate() > startdate and curdate() < enddate)) order by createdate desc";
            $soxId = oxDb::getDb()->getOne($sQ);
        }
        if( $soxId != "-1" && isset( $soxId))
        {
            // load object
            $oSurvey = oxNew( "smxsurveys" );
            $oSurvey->load($soxId);
            if($oSurvey && $oSurvey->isActive())
            {
                // check session and cookie for vote
                $alreadyVoted = $mySession->getVar("surveydone_" . $soxId);
                if($oSurvey->smxsurveys__setcookie->value == "1")
                {
                    $cookieName = str_replace(".", "_", "surveydone_" . $soxId);
                    if(!isset($alreadyVoted) && isset($_COOKIE[$cookieName]))
                    {
                        $alreadyVoted = $_COOKIE[$cookieName];
                        $mySession->setVar("surveydone_" . $soxId, true);
                    }
                }
                $this->_oParent->_aViewData["alreadyvoted"] = $alreadyVoted;

                $this->_oParent->_aViewData["jscheck"] = $oSurvey->smxsurveys__jscheck->value;

                // check user groups
                $oUser = $this->getUser();
                $assignedToGroup = false;
                if( $oUser)
                {
                    $aGroups = $oUser->getUserGroups();
                    if( isset( $aGroups) && count($aGroups) )
                    {
                        foreach($aGroups as $groupId => $oGroup)
                        {
                            if($oSurvey->InGroup($groupId))
                            {
                                $assignedToGroup = true;
                            }
                        }
                    }
                }

                if($oSurvey->numGroups() == 0
                    || ($oSurvey->numGroups() > 0 && $assignedToGroup)
                )
                {
                    // show results?
                    if($alreadyVoted || $myConfig->getParameter("showresults") == true)
                    {
                        if($oSurvey->smxsurveys__showresult->value == "1")
                        {
                            $sSmxsurveysAnswersViewName = getViewName('smxsurveys_answers');
                            $sSel = "select * from $sSmxsurveysAnswersViewName where surveyid = '".$soxId."'";
                            $logList = oxNew( "oxlist", "smxsurveysanswers" );
                            $logList->selectString( $sSel );
                            if($logList->count())
                            {
                                $this->_oParent->_aViewData["surveyanswers"] = $logList->getArray();
                            }

                            $this->_oParent->_aViewData["showresults"] = true;
                        }
                    }
                    // write to view
                    $this->_oParent->_aViewData["snid"] = $soxId;
                    $this->_oParent->_aViewData["survey"] = $oSurvey;
                }
            }
        }

        return parent::render();
    }

    /**
     * Saves all survey answers to database and sets session var and cookie
     * @global <type> $myConfig
     * @global <type> $mySession
     */
    public function saveSurvey()
    {   $myConfig = $this->getConfig();
        $mySession = $this->getSession();

        $soxId = $myConfig->getParameter("snid");
        $smxanswers = $myConfig->getParameter("smxanswers");
        $smxanswersfreetext = $myConfig->getParameter("smxanswersfreetext");
        $alreadyVoted = $mySession->getVar("surveydone_" . $soxId);

        if( !$alreadyVoted && $soxId != "-1" && isset( $soxId) && is_array($smxanswers))
        {
            foreach($smxanswers as $questionId => $answerId)
            {
                $answerIds = array();
                $smxsurveysquestion = oxNew("smxsurveysquestion");
                $smxsurveysquestion->load($questionId);

                // checkboxes?
                if($smxsurveysquestion->smxsurveys_questions__questiontype->value == "1" && is_array($answerId))
                {
                    foreach($answerId as $checkboxId => $checkboxValue)
                    {
                        $answerIds[] = $checkboxId;
                    }
                }
                else // radio buttons
                {
                    $answerIds[] = $answerId;
                }

                // save all answers
                foreach($answerIds as $answerId)
                {
                    $smxsurveysanswer = oxNew("smxsurveysanswers");
                    $smxsurveysanswer->smxsurveys_answers__surveyid = new oxField($soxId, oxField::T_RAW);
                    $smxsurveysanswer->smxsurveys_answers__answerid = new oxField($answerId, oxField::T_RAW);
                    $smxsurveysanswer->smxsurveys_answers__questionid = new oxField($questionId, oxField::T_RAW);
                    if(isset($smxanswersfreetext[$answerId]))
                        $smxsurveysanswer->smxsurveys_answers__freetext = new oxField($smxanswersfreetext[$answerId], oxField::T_RAW);
                    $smxsurveysanswer->save();

                    // load the answer and update counter
                    $smxposssurveysanswer = oxNew("smxsurveysanswer2question");
                    $smxposssurveysanswer->load($answerId);
                    $answerCount = $smxposssurveysanswer->smxsurveys_answers2questions__answercount->value;
                    $smxposssurveysanswer->smxsurveys_answers2questions__answercount = new oxField($answerCount + 1, oxField::T_RAW);
                    $smxposssurveysanswer->save();
                }
            }

            $oSurvey = oxNew( "smxsurveys");
            $oSurvey->load($soxId);
            // set as voted:
            $this->_oParent->_aViewData["surveysaved"] = true;
            $mySession->setVar("surveydone_" . $soxId, true);
            if($oSurvey->smxsurveys__setcookie->value == "1")
            {
                // set cookie, too!
                $cookieName = str_replace(".", "_", "surveydone_" . $soxId);
                setcookie($cookieName, true, time()+60*60*24*120); // expires in 120 days
            }
            // show results?
            if($oSurvey->smxsurveys__showresult->value == "1")
                $this->_oParent->_aViewData["showresults"] = true;

            // save user?
            if($myConfig->getParameter("useremail") != "")
            {
                $oSurveyParticipant = oxNew( "smxsurveysparticipant");
                $oSurveyParticipant->smxsurveys_participants__surveyid = new oxField($soxId, oxField::T_RAW);
                $oSurveyParticipant->smxsurveys_participants__username = new oxField($myConfig->getParameter("useremail"), oxField::T_RAW);
                $oSurveyParticipant->save();
            }
        }
    }

}
?>
