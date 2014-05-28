{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; ">Create District</div>
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    <form action="create_district.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class="form1">Select City:  <span class="required">*</span></td>
                <td class="form2">
                    <select id="city_id" name="city_id" style="height:26px; width: 300px;">
                        <option value=""></option>
                        {foreach from=$cities item=city}
                            <option value="{$city.id}" {if $city.id eq $city_id}selected="selected"{/if}>{$city.city_name}</option>        
                        {/foreach}
                    </select>
                    <div id="error_city" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>District Name: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='district_name' id='district_name' value="{$district_name}"  style="height:26px; width: 300px;"><div id="district_name_error"class="error"></div></td>
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