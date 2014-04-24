{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; ">Add House To Broker</div>
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
   <form action="add_house_broker.php" method="post">
    <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
        <tr>
            <td class='form1'>Name: </td>
            <td class='form2'><input type='text'  value="{$broker_company_name}"  style="height:26px; width: 351px;" disabled><div id="broker_company_name_error"class="error"></div></td>
        </tr>
        <tr>
            <td class='form1'>Address: </td>
            <td class='form2'><input type='text' value="{$broker_company_address}"  style="height:26px; width: 351px;"disabled><div id="broker_company_address_error"class="error"></div></td>
        </tr>
        <tr>
            <td class='form1'>Phone Number: </td>
            <td class='form2'><input type='text'  value="{$broker_company_phone}" style="height:26px; width: 351px;"disabled><div id="broker_company_phone_error"class="error"></div></td>
        </tr>
        <tr>
            <td class='form1'>Email: </td>
            <td class='form2'><input type='text'  value="{$broker_company_email}"  style="height:26px; width: 351px;"disabled><div id="broker_company_email_error"class="error"></div></td>
        </tr>
        <tr>
            <td class='form1'>Fax: </td>
            <td class='form2'><input type='text'  value="{$broker_company_fax}"  style="height:26px; width: 351px;"disabled><div id="broker_company_fax_error"class="error"></div></td>
        </tr>
        <tr>
            <td class='form1'>Person In Charge: </td>
            <td class='form2'><input type='text'  value="{$broker_company_undertake}"  style="height:26px; width: 351px;"disabled><div id="broker_company_undertake_error"class="error"></div></td>
        </tr>
        <tr>
                <td class='form1'>Add House: </td>
                <td class='form2'>
                    <select id="house_id" name="house_id" style="height:26px; width: 351px;">
                        {foreach from=$houses item=house}
                            <option value="{$house.id}">{$house.house_name}</option>        
                        {/foreach}
                    </select>
                </td>
            </tr>
        <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-search' value='Add' id="submit" name="submit"/>&nbsp;  &nbsp; 
                        <input type='button' class='btn-search' value='Back' id="back" name="back" onclick="back();"/>&nbsp;  
                        <input type='hidden'  value='{$broker_id}' id="broker_id" name="broker_id"/>                                       
                        <input type='hidden'  name='broker_company_name' id='broker_company_name' value="{$broker_company_name}"/>
                        <input type='hidden'  name='broker_company_address' id='broker_company_address' value="{$broker_company_address}"/>
                        <input type='hidden'  name='broker_company_phone' id='broker_company_phone' value="{$broker_company_phone}"/>
                        <input type='hidden'  name='broker_company_email' id='broker_company_email' value="{$broker_company_email}"/>
                        <input type='hidden'  name='broker_company_fax' id='broker_company_fax' value="{$broker_company_fax}"/>
                        <input type='hidden'  name='broker_company_undertake' id='broker_company_undertake' value="{$broker_company_undertake}"/>
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
                window.location.href = "manage_broker.php";
            });
        });
    </script>
{/literal}
{include file='footer.tpl'}