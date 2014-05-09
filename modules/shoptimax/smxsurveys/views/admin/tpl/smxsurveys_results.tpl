[{include file="headitem.tpl" title="[OXID Umfragen]"}] 

[{if $readonly}]
	[{assign var="readonly" value="readonly disabled"}]
[{else}]
	[{assign var="readonly" value=""}]
[{/if}]

<script type="text/javascript">
<!--
[{ if $updatelist == 1}]
    UpdateList('[{ $oxid }]');
[{ /if}]

function UpdateList( sID)
{
    var oSearch = parent.list.document.getElementById("search");
    oSearch.oxid.value=sID;
    oSearch.submit();
}

//-->
</script>

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="smxsurveys_results">
</form>
<form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
[{ $oViewConf->getHiddenSid() }]
<input type="hidden" name="cl" value="smxsurveys_results">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="voxid" value="[{ $oxid }]">
<input type="hidden" name="editval[smxsurveys__oxid]" value="[{ $oxid }]">

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <td valign="top" class="edittext">

        <table cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td valign="top" class="edittext" align="left" style="width:100%;height:99%;padding-top:20px;padding-left:20px;padding-right:30px;padding-bottom:30px;">
            <table style="width:100%;height:99%;" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td valign="top">
                [{foreach from=$surveyquestions item=question name="allquestions"}]
                    [{assign var="qcount" value=$smarty.foreach.allquestions.iteration}]
                    <tr id="tr1_[{$qcount}]">
                        <td valign="top" class="edittext">
                            <strong>[{$question->smxsurveys_questions__text->value}]</strong>
                            <br />
                            [{foreach from=$question->getPossibleAnswers() item=answer name="allanswers" }]
                                [{$answer->smxsurveys_answers2questions__text->value}]
                                ([{ oxmultilang ident="SMXSURVEYS_VOTES" }]: [{ $answer->smxsurveys_answers2questions__answercount->value }] [{ oxmultilang ident="SMXSURVEYS_OF" }] [{$question->getNumAnswers()}])
                                [{if $question->getNumAnswers() > 0}]
                                    [{math equation="round((x / y) * 100, 2)" x=$answer->smxsurveys_answers2questions__answercount->value y=$question->getNumAnswers() assign="percentage"}]
                                    <img src="[{$oViewConf->getModuleUrl('smxsurveys', 'views/basic/img/smxsurveys_percent.gif') }]" width="[{$percentage}]" height="5" alt="[{$percentage}]%" title="[{$percentage}]%"> ([{$percentage}]%)
                                [{/if}]
                                <br />
                                [{if $answer->smxsurveys_answers2questions__is_freetext->value == "1"}]
                                <textarea class="edittext" rows="5" cols="50">[{foreach from=$answer->getFreetextAnswers() item=freetext name="allfreetexts" }]- [{$freetext->smxsurveys_answers__freetext->value|cat:"\n"}][{/foreach}]
                                </textarea>
                                [{/if}]
                            [{/foreach}]
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;<br /></td>
                    </tr>
                [{/foreach}]
                    
              </td></tr>
            </table>
          </td>
          <td class="edittext" width="40%" align="left" valign="top" style="width:100%;height:99%;padding-top:20px;padding-left:20px;padding-right:30px;padding-bottom:30px;">
              [{if $survey->smxsurveys__saveuser->value == "1" }]
                  <strong>[{ oxmultilang ident="SMXSURVEYS_PARTICIPANTS" }]</strong>
                  <br />
                  <textarea rows="10" cols="40">[{foreach from=$surveyparticipants item=participant name="allparticipants"}][{$participant->smxsurveys_participants__username->value|cat:"\n"}][{/foreach}]</textarea>
              [{/if}]
          </td>
        </tr>
      </table>
</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]