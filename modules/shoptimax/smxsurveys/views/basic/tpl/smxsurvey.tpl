[{assign var="template_title" value="SMXSURVEYS_SURVEY"|oxmultilangassign }]
[{capture append="oxidBlock_pageHead"}]
    <link rel="stylesheet" type="text/css" href="[{$oViewConf->getModuleUrl('smxsurveys', 'views/basic/src/tinybox/style.css')}]" />
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('smxsurveys', 'views/basic/src/tinybox/packed.js')}]"></script>
[{/capture}]

[{capture append="oxidBlock_content"}]

<script language="JavaScript">
[{if $jscheck}]
function radiovalue(radiobutton)
{
	var value= new String()
	for (i=0;i<radiobutton.length;i++)
	{
		if (radiobutton[i].checked)
		{
			value=radiobutton[i].value;
		}
	}
	return value;
}

function getCheckboxSiblings(cbName)
{
    var cbArray = new Array();
    var count = 0;
    var allCBs = document.getElementsByTagName("input");
    for(var i=0; i<allCBs.length; i++)
    {
        if(allCBs[i].name.indexOf(cbName) !== -1)
        {
            cbArray[count++] = allCBs[i];
        }
    }
    return cbArray;
}

function checkboxCheck(cbName)
{
    var a = getCheckboxSiblings(cbName);
    if(typeof a !== "undefined")
    {
        var p=0;
        for(i=0;i<a.length;i++){
            if(a[i].checked){
                return true;
            }
        }
    }
    return false;
}

function submitForm(formname, checkme)
{
    if(!checkme)
        return true;

    var msg = "";
    for(var i=0; i<document.forms[formname].elements.length; i++)
    {
        if((document.forms[formname].elements[i].type.indexOf("radio")!==-1) && (document.forms[formname].elements[i].name.indexOf("smxanswers[") !== -1))
        {
            var actId = document.forms[formname].elements[i].id;
            if(radiovalue(eval("document.forms[formname]."+actId)).length < 1 && msg.indexOf(actId) === -1)
            {
                msg += actId;
                msg += "\n";
            }
        }
        else if((document.forms[formname].elements[i].type.indexOf("checkbox")!==-1) && (document.forms[formname].elements[i].name.indexOf("smxanswers[") !== -1))
        {
            var cbArrayName = document.forms[formname].elements[i].name.substring(0, document.forms[formname].elements[i].name.lastIndexOf("["));
            if(!checkboxCheck(cbArrayName))
            {
                msg += document.forms[formname].elements[i].id;
                msg += "\n";
            }
        }
    }
    if(msg.length > 0)
    {
        alert('[{ oxmultilang ident="SMXSURVEYS_PLEASEANSWER"}]');
        return false;
    }
    else
    {
        return true;
    }
}
[{/if}]
</script>

[{if $survey}]
<strong id="test_smxSurveyHeader" class="boxhead">[{ $template_title }] - [{$survey->smxsurveys__title->value}]</strong>
<div class="main">
    [{if $alreadyvoted}]
        <strong>[{ oxmultilang ident="SMXSURVEYS_ALREADYVOTED" }]</strong>
    [{else}]
        [{if $surveysaved}]
            <strong>[{ oxmultilang ident="SMXSURVEYS_THANKSFORVOTING" }]</strong>
        [{else}]
            [{if !$showresults && $survey}]
            <form name="surveyform" method="post" action="[{$oViewConf->getSelfActionLink()}]" [{if $jscheck}]onsubmit="return submitForm(this.name, true);"[{/if}]>
            [{ $oViewConf->getHiddenSid() }]
            [{ $oViewConf->getNavFormParams() }]
            <input type="hidden" name="fnc" value="saveSurvey" />
            <input type="hidden" name="snid" value="[{$snid}]" />
            <input type="hidden" name="cl" value="content">
            <input type="hidden" name="tpl" value="smxsurvey.tpl">
            <table>
            [{foreach from=$survey->getAllQuestions() item=question name="allquestions"}]
                [{assign var="qcount" value=$smarty.foreach.allquestions.iteration}]
                <tr id="tr1_[{$qcount}]">
                    <td valign="top">
                        <strong>[{$question->smxsurveys_questions__text->value}]</strong>
                        <br />
                        [{foreach from=$question->getPossibleAnswers() item=answer name="allanswers" }]
                            [{if $question->smxsurveys_questions__questiontype->value == "0"}]
                                <input type='radio' name="smxanswers[[{$question->smxsurveys_questions__oxid->value}]]" id="smxanswers_[{$question->smxsurveys_questions__oxid->value|replace:".":""}]" value="[{$answer->smxsurveys_answers2questions__oxid->value}]" style="margin:0;padding:0;width:15px;border:0;" />
                            [{elseif $question->smxsurveys_questions__questiontype->value == "1"}]
                                <input type='checkbox' name="smxanswers[[{$question->smxsurveys_questions__oxid->value}]][[{$answer->smxsurveys_answers2questions__oxid->value}]]" id="smxanswers_[{$question->smxsurveys_questions__oxid->value|replace:".":""}]" value="1" style="margin:0;padding:0;width:15px;border:0;" />
                            [{/if}]
                            [{$answer->smxsurveys_answers2questions__text->value}]
                            [{if $answer->smxsurveys_answers2questions__is_freetext->value == "1"}]
                                <input type="text" value="" maxlength="255" size="60" name="smxanswersfreetext[[{$answer->smxsurveys_answers2questions__oxid->value}]]" />
                            [{/if}]
                            <br />
                        [{/foreach}]
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;<br /></td>
                </tr>
            [{/foreach}]
            [{if $survey->smxsurveys__saveuser->value == "1" }]
            <tr>
                <td><br />
                    <strong>[{ oxmultilang ident="SMXSURVEYS_YOUREMAIL" }]</strong>
                    <br />
                    <input type="text" name="useremail" size="40" maxlength="255" value="[{$oxcmp_user->oxuser__oxusername->value}]" />
                </td>
            </tr>
            [{/if}]
            <tr>
                <td><br />
                    <input type="submit" value="[{ oxmultilang ident="SMXSURVEYS_DOVOTE" }]" />
                    [{if $survey->smxsurveys__showresult->value == "1" }]
                        <input type="button" value="[{ oxmultilang ident="SMXSURVEYS_SHOWRESULT" }]" onclick="location.href='[{$oViewConf->getSelfLink()}]cl=content&tpl=smxsurvey.tpl&snid=[{$snid}]&showresults=1';" />
                    [{/if}]
                </td>
            </tr>
            </table>
            </form>
            [{/if}]
        [{/if}]
    [{/if}]

[{if $showresults && $survey}]
    <table style="width:100%;height:99%;" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>
                <br/>
                <strong>[{ oxmultilang ident="SMXSURVEYS_RESULT" }]</strong>
                <br/>
                <br/>
            </td>
        </tr>
        <tr>
        <td valign="top">
        [{foreach from=$survey->getAllQuestions() item=question name="allquestions"}]
            [{assign var="qcount" value=$smarty.foreach.allquestions.iteration}]
            <tr id="tr1_[{$qcount}]">
                <td valign="top" class="edittext">
                    <strong>[{$question->smxsurveys_questions__text->value}]</strong>
                    <br />
                    <table>
                    [{foreach from=$question->getPossibleAnswers() item=answer name="allanswers" }]
                    <tr>
                        <td valign="top" class="edittext">[{$answer->smxsurveys_answers2questions__text->value}]</td>
                        <td valign="top" class="edittext">[{if $question->getNumAnswers() > 0}]
                            [{ math equation="round((x / y) * 100, 2)" x=$answer->smxsurveys_answers2questions__answercount->value y=$question->getNumAnswers() assign="percentage" }]
                            [{ math equation="round((x / y) * 100)" x=$answer->smxsurveys_answers2questions__answercount->value y=$question->getNumAnswers() assign="percentagepic" }]
                            <img src="[{$oViewConf->getModuleUrl("smxsurveys", "views/basic/img/smxsurveys_percent.gif")}]" width="[{$percentagepic}]" height="5" alt="[{$percentage}]%" title="[{$percentage}]%"> ([{$percentage}]%)
                        [{/if}]
                        <br />
                        [{if $answer->smxsurveys_answers2questions__is_freetext->value == "1"}]
                            
                            </td>
                            </tr>
                            <tr>
                            <td valign="top" class="edittext" colspan="2">
                                <div id="detaillink[{$smarty.foreach.allanswers.iteration}]_[{$qcount}]" style="text-decoration:underline;cursor:pointer;">&gt;&gt;[{ oxmultilang ident="SMXSURVEYS_SHOWDETAILS" }]</div>
                                <script type="text/javascript">
                                    var content[{$smarty.foreach.allanswers.iteration}]_[{$qcount}] = "<b>[{$question->smxsurveys_questions__text->value}] - [{$answer->smxsurveys_answers2questions__text->value}]</b><br/><ul>[{foreach from=$answer->getFreetextAnswers() item=freetext name="allfreetexts" }]<li>[{$freetext->smxsurveys_answers__freetext->value}]</li>[{/foreach}]</ul><br/><p style='text-align:right;'><a href='javascript:TINY.box.hide()'>x</a></p>";
                                    T$('detaillink[{$smarty.foreach.allanswers.iteration}]_[{$qcount}]').onclick = function(){TINY.box.show(content[{$smarty.foreach.allanswers.iteration}]_[{$qcount}],0,0,0,1)}
                                </script>
                        [{/if}]
                        </td>
                    </tr>
                    [{/foreach}]

                    </table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;<br /></td>
            </tr>
        [{/foreach}]
    </table>
[{/if}]
</div>
[{/if}]
    
    [{insert name="oxid_tracker" title=$template_title }]
[{/capture}]

[{include file="layout/page.tpl" sidebar="Left"}]