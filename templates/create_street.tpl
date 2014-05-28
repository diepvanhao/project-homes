{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; ">Create Street</div>
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    <form action="create_street.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class="form1">Select District:  <span class="required">*</span></td>
                <td class="form2">
                    <select id="district_id" name="district_id" style="height:26px; width: 300px;">
                        <option value=""></option>
                        {foreach from=$districts item=district}
                            <option value="{$district.id}" {if $district.id eq $district_id}selected="selected"{/if}>{$district.district_name}</option>        
                        {/foreach}
                    </select>
                    <div id="error_street" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>Street Name: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='street_name' id='street_name' value="{$street_name}"  style="height:26px; width: 300px;"><div id="street_name_error"class="error"></div></td>
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