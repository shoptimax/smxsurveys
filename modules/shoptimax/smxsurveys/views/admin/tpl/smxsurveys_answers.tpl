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
function add_answer(number)
{
    var ktable = document.getElementById('smxanswerstable');
    var numanswers = document.getElementById('number_smxanswers');
    if (!ktable || !numanswers) return false;
    var zaehler = numanswers.value;

    for (var i=1; i <= number; i++)
    {
        zaehler++;
        var new_tr1 = document.createElement('tr');
        var tr1_td1 = document.createElement('td');
        tr1_td1.innerHTML = '[{ oxmultilang ident="SMXSURVEYS_ANSWER" }] ' + zaehler;
        tr1_td1.setAttribute('class', 'edittext');
        tr1_td1.setAttribute('className', 'edittext');
        var tr1_td2 = document.createElement('td');
        tr1_td2.setAttribute('class', 'edittext');
        tr1_td2.setAttribute('className', 'edittext');
        var myInput1 = document.createElement("input");
        myInput1.setAttribute("type","text");
        myInput1.setAttribute('name','smxanswers[]');
        myInput1.setAttribute("value","");
        myInput1.setAttribute("class","edittext");
        myInput1.setAttribute("className","edittext");
        myInput1.setAttribute("style","width:250px;");
        myInput1.style.width="250px";
        tr1_td2.appendChild(myInput1);

        var tr1_td3 = document.createElement('td');
        tr1_td3.setAttribute('class', 'edittext');
        tr1_td3.setAttribute('className', 'edittext');
        var myCb = document.createElement("input");
        myCb.setAttribute("type","checkbox");
        var zaehler2 = parseInt(zaehler)-1;
        myCb.setAttribute('name','smxanswersfreetext[' + zaehler2 + ']');
        myCb.setAttribute("value","1");
        myCb.setAttribute("class","edittext");
        myCb.setAttribute("className","edittext");
        tr1_td3.appendChild(myCb);
        tr1_td3.appendChild(document.createTextNode(' Freitext?'));

        new_tr1.appendChild(tr1_td1);
        new_tr1.appendChild(tr1_td2);
        new_tr1.appendChild(tr1_td3);
        ktable.appendChild(new_tr1);
    }
    numanswers.value = zaehler;
    return false;
}

function remove_answer(num)
{
    var ktable = document.getElementById('smxanswerstable');
    var numanswers = document.getElementById('number_smxanswers');
    if (!ktable || !numanswers) return false;
    var zaehler = numanswers.value;

    var myTR = document.getElementById("tr1_"+num);
    if(typeof myTR != "undefined")
    {
        myTR.parentNode.removeChild(myTR);
        zaehler--;
    }
    numanswers.value = zaehler;
    return false;
}
function moveElem(elem,direction)
{
    clickedRowIndex=elem.parentNode.parentNode.rowIndex;
    if(clickedRowIndex =="0" && direction=="up")
    {
        return false;
    }
    maxrindex= (elem.parentNode.parentNode.parentNode.getElementsByTagName("tr").length)-1;
    if(clickedRowIndex ==maxrindex && direction=="down")
    {
        return false;
    }

    parentTable=elem.parentNode.parentNode.parentNode;
    clickedrow=parentTable.getElementsByTagName("tr")[clickedRowIndex];

    if(direction=="up")
    {
        adjacentRowIndex= clickedRowIndex -1;
    }

    if(direction=="down")
    {
        adjacentRowIndex= clickedRowIndex +1;
    }

    adjacentrow= parentTable.getElementsByTagName("tr")[adjacentRowIndex];
    clickedrow_clone=clickedrow.cloneNode(true);
    adjacentrow_clone=adjacentrow.cloneNode(true);
    adjacentrow=parentTable.replaceChild(clickedrow_clone,adjacentrow);
    clickedrow=parentTable.replaceChild(adjacentrow_clone,clickedrow);
}
//-->
</script>

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="smxsurveys_answers">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

<form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
[{ $oViewConf->getHiddenSid() }]
<input type="hidden" name="cl" value="smxsurveys_answers">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="voxid" value="[{ $oxid }]">
<input type="hidden" name="editval[smxsurveys__surveyid]" value="[{ $oxid }]">

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <td valign="top" class="edittext">

        <table cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td valign="top" class="edittext" align="left" style="width:100%;height:99%;padding-top:20px;padding-left:20px;padding-right:30px;padding-bottom:30px;">
            <table style="width:100%;height:99%;" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td valign="top">
                    <table id="smxquestionstable1">
                        [{if $smxquestions }]
                            <tr>
                                <td class="edittext" valign="top">
                                    <select class="edittext" name="smxquestion" onchange="this.form.submit()" style="margin-top:4px;">
                                    <option value="">[{ oxmultilang ident="SMXSURVEYS_ANSWERSELECT" }]</option>
                                    [{foreach from=$smxquestions item=question name="allquestions"}]
                                        <option style="width:250px;" value="[{$question->smxsurveys_questions__oxid->value}]" [{if $smxquestion == $question->smxsurveys_questions__oxid->value}] selected="selected"[{/if}]>[{$question->smxsurveys_questions__text->value}]</option>
                                    [{/foreach}]
                                    </select>
                                </td>
                                <td valign="top">
                                    [{if $smxquestion }]
                                    <table id="smxanswerstable1">
                                        <tbody id="smxanswerstable">
                                        [{assign var="acount" value="1"}]
                                        [{if !$smxanswers }]
                                        <tr>
                                            <td class="edittext">
                                                [{ oxmultilang ident="SMXSURVEYS_ANSWER" }] 1:
                                            </td>
                                            <td class="edittext">
                                                <input type="text" class="edittext" style="width:250px;" name="smxanswers[]"  maxlength="255" />
                                            </td>
                                            <td class="edittext">
                                                <input type="checkbox" class="edittext" name="smxanswersfreetext[]" value="1"> [{ oxmultilang ident="SMXSURVEYS_ANSWERFREETEXT" }]</input>
                                            </td>
                                        </tr>
                                        [{else}]
                                            [{foreach from=$smxanswers item=answer name="allanswers"}]
                                                [{assign var="acount" value=$smarty.foreach.allanswers.iteration}]
                                                <tr id="tr1_[{$acount}]">
                                                    <td class="edittext">
                                                        [{ oxmultilang ident="SMXSURVEYS_ANSWER" }] [{$acount}]:
                                                    </td>
                                                    <td class="edittext">
                                                        <input type="text" class="edittext" style="width:250px;" name="smxanswers[[{$answer->smxsurveys_answers2questions__oxid->value}]]"  maxlength="255" value="[{$answer->smxsurveys_answers2questions__text->value}]" />
                                                    </td>
                                                    <td class="edittext">
                                                        <input type="checkbox" class="edittext" name="smxanswersfreetext[[{$answer->smxsurveys_answers2questions__oxid->value}]]" value="1"[{if $answer->smxsurveys_answers2questions__is_freetext->value == "1"}] checked="checked"[{/if}]> [{ oxmultilang ident="SMXSURVEYS_ANSWERFREETEXT" }]</input>
                                                    </td>
                                                    <td>
                                                        <div class="edittext"><a href="#" onclick="return remove_answer('[{$acount}]');">[-]</a> [{ oxmultilang ident="SMXSURVEYS_REMOVEANSWER" }]</div>
                                                    </td>
                                                    <td class="edittext">
                                                        <a class="edittext" href="#" onclick="moveElem(this,'down')"><img src="[{$oViewConf->getModuleUrl('smxsurveys', 'views/admin/img/smxsurveys_desc.gif') }]" border="0" alt="down" title="down"></a>
                                                        <a class="edittext" href="#" onclick="moveElem(this,'up')"><img src="[{$oViewConf->getModuleUrl('smxsurveys', 'views/admin/img/smxsurveys_asc.gif') }]" border="0" alt="up" title="up"></a>
                                                    </td>
                                                </tr>
                                            [{/foreach}]
                                        [{/if}]
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="number_smxanswers" id="number_smxanswers" value="[{$acount}]" />
                                    <div class="edittext"><a href="#" onclick="return add_answer('1');">[+]</a> [{ oxmultilang ident="SMXSURVEYS_MOREANSWERS" }]</div>
                                    [{/if}]
                                </td>
                            </tr>
                        [{/if}]
                        
                    </table>
              </td></tr>
            </table>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="edittext" style="padding-left:20px;">
          [{include file="language_edit.tpl"}]
          <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'" [{ $readonly }]>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
[{include file="bottomnaviitem.tpl" navigation=smxsurveys}]
[{include file="bottomitem.tpl"}]