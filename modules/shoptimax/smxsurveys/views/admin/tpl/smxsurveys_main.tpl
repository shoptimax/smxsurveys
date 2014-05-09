[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="smxsurveys_main">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>


<form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
[{ $oViewConf->getHiddenSid() }]
<input type="hidden" name="cl" value="smxsurveys_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="voxid" value="[{ $oxid }]">
<input type="hidden" name="editval[smxsurveys__oxid]" value="[{ $oxid }]">

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <td valign="top" class="edittext">

        <table cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="edittext" width="90">
            [{ oxmultilang ident="GENERAL_ACTIVE" }]
            </td>
            <td class="edittext">
            <input class="edittext" type="checkbox" name="editval[smxsurveys__active]" value='1' [{if $edit->smxsurveys__active->value == 1}]checked[{/if}] [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_ACTIVFROMTILL" }]
            </td>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_FROM" }]<input type="text" class="editinput" size="30" name="editval[smxsurveys__startdate]" value="[{$edit->smxsurveys__startdate|oxformdate}]" [{include file="help.tpl" helpid=article_vonbis}] [{ $readonly }]><br>
            [{ oxmultilang ident="GENERAL_TILL" }] <input type="text" class="editinput" size="30" name="editval[smxsurveys__enddate]" value="[{$edit->smxsurveys__enddate|oxformdate}]" [{include file="help.tpl" helpid=article_vonbis}] [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_DATE" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="25" maxlength="[{$edit->smxsurveys__createdate->fldmax_length}]" name="editval[smxsurveys__createdate]" value="[{$edit->smxsurveys__createdate|oxformdate }]" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_TITLE" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="34" maxlength="[{$edit->smxsurveys__title->fldmax_length}]" name="editval[smxsurveys__title]" value="[{$edit->smxsurveys__title->value }]" [{ $readonly }]>
            </td>
        </tr>
        <tr>
          <td class="edittext">
          [{ oxmultilang ident="SMXSURVEYS_LINK" }]
          </td>
          <td class="edittext">
          <input type="text" size="34" class="edittext" style="font-size: 7pt;" value="[{ $link }]">
          <a href="[{ $directlink }]" target="_blank"><img src="[{$oViewConf->getModuleUrl('smxsurveys', 'views/admin/img/smxsurveys_asc.gif') }]" border="0" alt=""/></a>
          </td>
        </tr>
        <tr>
          <td class="edittext">
          [{ oxmultilang ident="SMXSURVEYS_SHOWRESULTS" }]
          </td>
          <td class="edittext">
            <input type="checkbox" class="editinput" name="editval[smxsurveys__showresult]" value="1"[{if $edit->smxsurveys__showresult->value == "1"}] checked="checked"[{/if}] [{ $readonly }]>
          </td>
        </tr>
        <tr>
          <td class="edittext">
          [{ oxmultilang ident="SMXSURVEYS_SAVEUSER" }]
          </td>
          <td class="edittext">
            <input type="checkbox" class="editinput" name="editval[smxsurveys__saveuser]" value="1"[{if $edit->smxsurveys__saveuser->value == "1"}] checked="checked"[{/if}] [{ $readonly }]>
          </td>
        </tr>
        <tr>
          <td class="edittext">
          [{ oxmultilang ident="SMXSURVEYS_SETCOOKIE" }]
          </td>
          <td class="edittext">
            <input type="checkbox" class="editinput" name="editval[smxsurveys__setcookie]" value="1"[{if $edit->smxsurveys__setcookie->value == "1"}] checked="checked"[{/if}] [{ $readonly }]>
          </td>
        </tr>
        <tr>
          <td class="edittext">
          [{ oxmultilang ident="SMXSURVEYS_JSCHECK" }]
          </td>
          <td class="edittext">
            <input type="checkbox" class="editinput" name="editval[smxsurveys__jscheck]" value="1"[{if $edit->smxsurveys__jscheck->value == "1"}] checked="checked"[{/if}] [{ $readonly }]>
          </td>
        </tr>

        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
                 [{include file="language_edit.tpl"}]
            </td>
        </tr>

        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'" [{ $readonly }]>
            </td>
        </tr>
        </table>
    </td>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext vr" align="left" width="50%">
    [{ if $oxid != "-1"}]

       <input [{ $readonly }] type="button" value="[{ oxmultilang ident="GENERAL_ASSIGNGROUPS" }]" class="edittext" onclick="JavaScript:showDialog('&cl=smxsurveys_main&aoc=1&oxid=[{ $oxid }]');">

    [{ /if}]
    </td>
    </tr>
</table>


</form>


[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
