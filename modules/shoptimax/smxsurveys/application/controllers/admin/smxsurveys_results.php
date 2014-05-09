<?php
/**
 * This Software is the property of Shoptimax GmbH and is protected
 * by copyright law - it is NOT Freeware.
 * @copyright (C) Shoptimax 2009
 */

/**
 * @package admin
 */
class Smxsurveys_Results extends oxAdminView
{
    /**
     * @return string
     */
    function render()
    {   $myConfig = $this->getConfig();
        $mySession = $this->getSession();

        parent::render();

        $soxId = $myConfig->getParameter( "oxid");

        if( $soxId != "-1" && isset( $soxId))
        {   // load object
            $oSurvey = oxNew( "smxsurveys");
            $oSurvey->loadInLang( $this->_iEditLang, $soxId);

            if($oSurvey)
            {
                $sSmxsurveysAnswersViewName = getViewName('smxsurveys_answers');
                $sSel = "select * from $sSmxsurveysAnswersViewName where surveyid = '".$soxId."'";
                $logList = oxNew( "oxlist" );
                $logList->init( "smxsurveysanswers" );
                //$logList->oLstoTpl->init( array( $sSmxsurveysAnswersViewName ) );
                $logList->selectString( $sSel );
                if($logList->count())
                {
                    $this->_aViewData["surveyanswers"] = $logList->getArray();
                }
                $surveyQuestions = $oSurvey->getAllQuestions();
                $this->_aViewData["survey"] = $oSurvey;
                $this->_aViewData["surveyquestions"] = $surveyQuestions;

                $sSmxsurveysParticipantsViewName = getViewName('smxsurveys_participants');
                $sSel = "select * from $sSmxsurveysParticipantsViewName where surveyid = '".$soxId."'";
                $logList = &oxNew( "oxlist" );
                $logList->init( "smxsurveysparticipant" );
                //$logList->oLstoTpl->init( array( $sSmxsurveysParticipantsViewName ) );
                $logList->selectString( $sSel );
                if($logList->count())
                {
                    $this->_aViewData["surveyparticipants"] = $logList->getArray();
                }

            }
        }

        return "smxsurveys_results.tpl";
    }

}
?>
