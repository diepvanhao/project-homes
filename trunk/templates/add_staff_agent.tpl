{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; ">店舗にスタッフを負担します</div>
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
        {literal}
            <style type="text/css">
                #submit{
                    visibility: hidden;
                }
            </style>
            {/literal}
    {/if}
    {if $notify ne ""}
        {$notify}
    {/if}
    <form action="add_staff_agent.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>店舗名:</td>
                <td class='form2'><input type='text'  value="{$agent_name}"  style="height:26px; width: 351px;"disabled></td>
            </tr>
            <tr>
                <td class='form1'>Eメール: </td>
                <td class='form2'><input type='text' value="{$agent_email}"  style="height:26px; width: 351px;" disabled></td>
            </tr>
            <tr>
                <td class='form1'>番地: </td>
                <td class='form2'><input type='text'  value="{$agent_address}" style="height:26px; width: 351px;"disabled></td>
            </tr>
            <tr>
                <td class='form1'>電話番号: </td>
                <td class='form2'><input type='text'  value="{$agent_phone}" maxlength='70' style="height:26px; width: 351px;"disabled></td>
            </tr>
            <tr>
                <td class='form1'>ファックス: </td>
                <td class='form2'><input type='text'  value="{$agent_fax}" maxlength='70' style="height:26px; width: 351px;"disabled></td>
            </tr>
            <tr>
                <td class='form1'>担当: </td>
                <td class='form2'>
                    <select id="staff_id" name="staff_id" style="height:26px; width: 351px;">
                        {foreach from=$users item=user}
                            <option value="{$user.id}">{$user.user_fname} {$user.user_lname}</option>        
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-search' value='担当' id="submit" name="submit"/>&nbsp;  &nbsp; 
                        <input type='button' class='btn-search' value='戻る' id="back" name="back" onclick="back();"/>&nbsp;  
                        <input type='hidden'  value='{$agent_id}' id="agent_id" name="agent_id"/>               
                        <input type='hidden'  name='agent_name' id='agent_name' value="{$agent_name}"/>
                        <input type='hidden'  name='agent_email' id='agent_email' value="{$agent_email}"/>
                        <input type='hidden'  name='agent_address' id='agent_address' value="{$agent_address}"/>
                        <input type='hidden'  name='agent_phone' id='agent_phone' value="{$agent_phone}"/>
                        <input type='hidden'  name='agent_fax' id='agent_fax' value="{$agent_fax}"/>
                    </div>
                </td>
            </tr>
        </table>
    </form>
{/nocache}
{literal}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#back').click(function() {
                window.location.href = "manage_agent.php";
            });
        });
    </script>
{/literal}
{include file='footer.tpl'}