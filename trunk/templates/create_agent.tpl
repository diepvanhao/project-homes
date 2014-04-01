{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; ">Create Agent</div>
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
<form action="create_agent.php" method="post">
    <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
        <tr>
            <td class='form1'>Agent Name: <span class="required">*</span></td>
            <td class='form2'><input type='text' class='text' name='agent_name' id='agent_name' value="{$agent_name}"  style="height:26px; width: 351px;"><div id="agent_name_error"class="error"></div></td>
        </tr>
        <tr>
            <td class='form1'>Email: </td>
            <td class='form2'><input type='text' class='text' name='agent_email' id='agent_email' value="{$agent_email}"  style="height:26px; width: 351px;"><div id="agent_email_error"class="error"></div></td>
        </tr>
        <tr>
            <td class='form1'>Address: <span class="required">*</span></td>
            <td class='form2'><input type='text' class='text' name='agent_address' id='agent_address' value="{$agent_address}" style="height:26px; width: 351px;"><div id="agent_address_error"class="error"></div></td>
        </tr>
        <tr>
            <td class='form1'>Phone Number: <span class="required">*</span></td>
            <td class='form2'><input type='text' class='text' name='agent_phone' id='agent_phone' value="{$agent_phone}" maxlength='70' style="height:26px; width: 351px;"><div id="agent_phone_error"class="error"></div></td>
        </tr>
        <tr>
            <td class='form1'>Fax: </td>
            <td class='form2'><input type='text' class='text' name='agent_fax' id='agent_fax' value="{$agent_fax}" maxlength='70' style="height:26px; width: 351px;"><div id="agent_fax_error"class="error"></div></td>
        </tr>
        <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-signup' value='Create' id="submit" name="submit"/>&nbsp;                     
                    </div>
                </td>
            </tr>
    </table>
</form>
{/nocache}

{include file='footer.tpl'}