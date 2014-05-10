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
 * @package admin
 */
class Smxsurveys_Results extends oxAdminView
{
    /**
     * @return string
     */
    public function render()
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
