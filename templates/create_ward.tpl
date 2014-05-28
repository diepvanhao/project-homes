{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; ">Create Ward</div>
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    <form action="create_ward.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class="form1">Select Street:  <span class="required">*</span></td>
                <td class="form2">
                    <select id="street_id" name="street_id" style="height:26px; width: 300px;">
                        <option value=""></option>
                        {foreach from=$streets item=street}
                            <option value="{$street.id}" {if $street.id eq $street_id}selected="selected"{/if}>{$street.street_name}</option>        
                        {/foreach}
                    </select>
                    <div id="error_street" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>Ward Name: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='ward_name' id='ward_name' value="{$ward_name}"  style="height:26px; width: 300px;"><div id="ward_name_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-signup' style="width: 50%;" value='Create' id="submit" name="submit"/>&nbsp;                     
                    </div>
                </td>
            </tr>
        </table>
    </form>
{/nocache}

{include file='footer.tpl'}