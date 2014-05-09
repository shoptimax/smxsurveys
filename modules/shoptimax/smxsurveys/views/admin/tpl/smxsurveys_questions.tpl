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

function add_question(number)
{
    var ktable = document.getElementById('smxquestionstable');
    var numquestions = document.getElementById('number_smxquestions');
    if (!ktable || !numquestions) return false;
    var zaehler = numquestions.value;
    for (var i=1; i <= number; i++)
    {
        zaehler++;
        var new_tr1 = document.createElement('tr');
        new_tr1.setAttribute('id', 'tr1_'+zaehler);
        var tr1_td1 = document.createElement('td');
        tr1_td1.innerHTML = '[{ oxmultilang ident="SMXSURVEYS_QUESTION" }] ' + zaehler + ':';
        tr1_td1.setAttribute('class', 'edittext');
        tr1_td1.setAttribute('className', 'edittext');
        var tr1_td2 = document.createElement('td');
        tr1_td2.setAttribute('class', 'edittext');
        tr1_td2.setAttribute('className', 'edittext');
        var myInput1 = document.createElement("input");
        myInput1.setAttribute("type","text");
        myInput1.setAttribute('name','smxquestions[]');
        myInput1.setAttribute("value","");
        myInput1.setAttribute("class","edittext");
        myInput1.setAttribute("className","edittext");
        myInput1.setAttribute("style","width:250px;");
        myInput1.style.width="250px";
        tr1_td2.appendChild(myInput1);

        var tr1_td3 = document.createElement('td');
        tr1_td3.setAttribute('class', 'edittext');
        tr1_td3.setAttribute('className', 'edittext');
        var mySelect1 = document.createElement("select");
        mySelect1.setAttribute('name','smxquestionstype[]');
        mySelect1.setAttribute('class', 'edittext');
        mySelect1.setAttribute('className', 'edittext');
        var myOption1 = document.createElement("option");
        myOption1.setAttribute('value','0');
        myOption1.innerHTML = '[{ oxmultilang ident="SMXSURVEYS_RADIOBUTTONS" }]';
        mySelect1.appendChild(myOption1);
        var myOption2 = document.createElement("option");
        myOption2.setAttribute('value','1');
        myOption2.innerHTML = '[{ oxmultilang ident="SMXSURVEYS_CHECKBOXES" }]';
        mySelect1.appendChild(myOption2);
        tr1_td3.appendChild(mySelect1);

        new_tr1.appendChild(tr1_td1);
        new_tr1.appendChild(tr1_td2);
        new_tr1.appendChild(tr1_td3);
        ktable.appendChild(new_tr1);
    }
    numquestions.value = zaehler;
    return false;
}

function remove_question(num)
{
    var ktable = document.getElementById('smxquestionstable');
    var numquestions = document.getElementById('number_smxquestions');
    if (!ktable || !numquestions) return false;
    var zaehler = numquestions.value;

    var myTR = document.getElementById("tr1_"+num);
    if(typeof myTR != "undefined")
    {
        myTR.parentNode.removeChild(myTR);
        zaehler--;
    }
    numquestions.value = zaehler;
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
    <input type="hidden" name="cl" value="smxsurveys_questions">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

<form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
[{ $oViewConf->getHiddenSid() }]
<input type="hidden" name="cl" value="smxsurveys_questions">
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
                    <table id="smxquestionstable1" class="edittext">
                        <tbody id="smxquestionstable" class="edittext">
                        [{assign var="qcount" value="1"}]
                        [{if !$smxquestions }]
                        <tr>
                            <td class="edittext">
                                [{ oxmultilang ident="SMXSURVEYS_QUESTION" }] 1:
                            </td>
                            <td class="edittext">
                                <input type="text" class="edittext" style="width:250px;" name="smxquestions[0]"  maxlength="255" />
                            </td>
                            <td>
                                <select class="edittext" name="smxquestionstype[[{$question->smxsurveys_questions__oxid->value}]]">
                                    <option value="0"[{if $question->smxsurveys_questions__questiontype->value == "0"}] selected="selected"[{/if}]>[{ oxmultilang ident="SMXSURVEYS_RADIOBUTTONS" }]</option>
                                    <option value="1"[{if $question->smxsurveys_questions__questiontype->value == "1"}] selected="selected"[{/if}]>[{ oxmultilang ident="SMXSURVEYS_CHECKBOXES" }]</option>
                                </select>
                            </td>
                        </tr>
                        [{else}]
                            [{foreach from=$smxquestions item=question name="allquestions"}]
                                [{assign var="qcount" value=$smarty.foreach.allquestions.iteration}]
                                <tr id="tr1_[{$qcount}]">
                                    <td class="edittext">
                                        [{ oxmultilang ident="SMXSURVEYS_QUESTION" }] [{$qcount}]:
                                    </td>
                                    <td class="edittext">
                                        <input type="text" class="edittext" style="width:250px;" name="smxquestions[[{$question->smxsurveys_questions__oxid->value}]]"  maxlength="255" value="[{$question->smxsurveys_questions__text->value}]" />
                                    </td>
                                    <td>
                                        <select class="edittext" name="smxquestionstype[[{$question->smxsurveys_questions__oxid->value}]]">
                                            <option value="0"[{if $question->smxsurveys_questions__questiontype->value == "0"}] selected="selected"[{/if}]>[{ oxmultilang ident="SMXSURVEYS_RADIOBUTTONS" }]</option>
                                            <option value="1"[{if $question->smxsurveys_questions__questiontype->value == "1"}] selected="selected"[{/if}]>[{ oxmultilang ident="SMXSURVEYS_CHECKBOXES" }]</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="edittext"><a href="#" onclick="return remove_question('[{$qcount}]');">[-]</a> [{ oxmultilang ident="SMXSURVEYS_REMOVEQUESTION" }]</div>
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
                    <input type="hidden" name="number_smxquestions" id="number_smxquestions" value="[{$qcount}]" />
                    <div class="edittext"><a href="#" onclick="return add_question('1');">[+]</a> [{ oxmultilang ident="SMXSURVEYS_ADDQUESTION" }]</div>
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
[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]