{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin-bottom: 2%;">Create Client</div>
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    {if $notify ne ""}
        {$notify}
    {/if}
    <form method="post">
        <div><label class="title">Client Infomation</label></div>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>Name: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='client_name' id='client_name' value="{$data.client_name}"  style="height:26px; width: 351px;"><div id="client_name_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Birthday:</td>
                <td class='form2'><input type='text' name='client_birthday' id='client_birthday'  value="{$data.client_birthday}"  style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>Address: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='client_address' id='client_address' value="{$data.client_address}" style="height:26px; width: 351px;"><div id="client_address_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Phone: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='client_phone' id='client_phone' value="{$data.client_phone}" style="height:26px; width: 351px;"><div id="client_phone_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Income: </td>
                <td class='form2'><input type='text' class='text' name='client_income' id='client_income' value="{$data.client_income}"  style="height:26px; width: 351px;"><div id="client_income_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Occupation: </td>
                <td class='form2'><input type='text' class='text' name='client_occupation' id='client_occupation' value="{$data.client_occupation}"  style="height:26px; width: 351px;"><div id="client_occupation_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Company: </td>
                <td class='form2'><input type='text' class='text' name='client_company' id='client_company' value="{$data.client_company}"  style="height:26px; width: 351px;"><div id="client_company_error"class="error"></div></td>
            </tr>

            <tr>
                <td class='form1'>Fax: </td>
                <td class='form2'><input type='text' class='text' name='client_fax' id='client_fax' value="{$data.client_fax}"  style="height:26px; width: 351px;"><div id="client_fax_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Gender: </td>
                <td class='form2'>
                    <select id="gender"name="client_gender" style="height:26px; width: 351px;">
                        <option value="male"{if $data.client_gender eq "male"}selected{/if}>Male</option>
                        <option value="female"{if $data.client_gender eq "female"}selected{/if}>Female</option>
                        <option value="other"{if $data.client_gender eq "other"}selected{/if}>Other</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>Email: </td>
                <td class='form2'><input type='email' class='text' name='client_email' id='client_email' value="{$data.client_email}"  style="height:26px; width: 351px;"><div id="client_email_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Reason Change: </td>
                <td class='form2'><input type='text' class='text' name='client_reason_change' id='client_reason_change' value="{$data.client_reason_change}"  style="height:26px; width: 351px;"><div id="client_reason_change_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Time Change: </td>
                <td class='form2'><input type='text' class='text' name='client_time_change' id='client_time_change' value="{$data.client_time_change}"  style="height:26px; width: 351px;"><div id="client_time_change_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Resident Name: </td>
                <td class='form2'><input type='text' class='text' name='client_resident_name' id='client_resident_name' value="{$data.client_resident_name}"  style="height:26px; width: 351px;"><div id="client_resident_name_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Resident Phone: </td>
                <td class='form2'><input type='text' class='text' name='client_resident_phone' id='client_resident_phone' value="{$data.client_resident_phone}" style="height:26px; width: 351px;"><div id="client_resident_phone_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Rent: </td>
                <td class='form2'><input type='text' class='text' name='client_rent' id='house_owner_phone' value="{$data.client_rent}"  style="height:26px; width: 351px;"><div id="client_rent_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Room Type: </td>
                <td class='form2'><input type='text' class='text' name='client_room_type' id='client_room_type' value="{$data.client_room_type}"  style="height:26px; width: 351px;"><div id="client_room_type_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-search' value='Change' id="submit" name="submit"/>&nbsp;         
                        <a  href="manage_client.php"><button class='btn-search' type="button">Back</button></a> 
                    </div>
                </td>
            </tr>
        </table>
    </form>
{/nocache}
{literal}
    <script type="text/javascript">
        $(document).ready(function() {
            birthday('client_birthday');
            birthday('client_time_change');
        });
    </script>
{/literal}
{include file='footer.tpl'}