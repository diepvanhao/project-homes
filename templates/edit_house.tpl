{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin-bottom: 2%;">Edit House</div>
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    {if $notify ne ""}
        {$notify}
    {/if}
    <form action="edit_house.php" method="post">
        <div><label class="title">House Infomation</label></div>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>Name: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='house_name' id='house_name' value="{$house_name}"  style="height:26px; width: 351px;"><div id="house_name_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Address:  <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='house_address' id='house_address' value="{$house_address}"  style="height:26px; width: 351px;"><div id="house_address_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Size: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='house_size' id='house_size' value="{$house_size}" style="height:26px; width: 351px;"><div id="house_size_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Area: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='house_area' id='house_area' value="{$house_area}" style="height:26px; width: 351px;"><div id="house_area_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Price:  <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='house_original_price' id='house_original_price' value="{$house_original_price}"  style="height:26px; width: 351px;"><div id="house_original_price_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Discount: </td>
                <td class='form2'><input type='text' class='text' name='house_discount' id='house_discount' value="{$house_discount}"  style="height:26px; width: 351px;"><div id="house_discount_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Build Time: </td>
                <td class='form2'><input type='text' class='text' name='house_build_time' id='house_build_time' value="{$house_build_time}"  style="height:26px; width: 351px;"><div id="house_build_time_error"class="error"></div></td>
            </tr>

            <tr>
                <td class='form1'>House Type: </td>
                <td class='form2'><input type='text' class='text' name='house_type' id='house_type' value="{$house_type}"  style="height:26px; width: 351px;"><div id="house_type_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Room Type: </td>
                <td class='form2'><input type='text' class='text' name='house_room_type' id='house_room_type' value="{$house_room_type}"  style="height:26px; width: 351px;"><div id="house_room_type_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Structure: </td>
                <td class='form2'><input type='text' class='text' name='house_structure' id='house_structure' value="{$house_structure}"  style="height:26px; width: 351px;"><div id="house_structure_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Description: </td>
                <td class='form2'><input type='text' class='text' name='house_description' id='house_description' value="{$house_description}"  style="height:26px; width: 351px;"><div id="house_description_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Administrative Expense: </td>
                <td class='form2'><input type='text' class='text' name='house_administrative_expense' id='house_administrative_expense' value="{$house_administrative_expense}"  style="height:26px; width: 351px;"><div id="house_house_administrative_expense_error"class="error"></div></td>
            </tr>            
            {*<tr>
            <td class='form1'>Status: </td>
            <td class='form2'><input type='text' class='text' name='house_status' id='house_status' value="{$house_status}"  style="height:26px; width: 351px;"><div id="house_status_error"class="error"></div></td>
            </tr>*}
        </table>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <div><label class="title">Owner Infomation</label></div>
            <tr>
                <td class='form1'>Name: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='house_owner_name' id='house_owner_name' value="{$house_owner_name}"  style="height:26px; width: 351px;"><div id="house_owner_name_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Address: </td>
                <td class='form2'><input type='text' class='text' name='house_owner_address' id='house_owner_address' value="{$house_owner_address}"  style="height:26px; width: 351px;"><div id="house_owner_address_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Email: </td>
                <td class='form2'><input type='text' class='text' name='house_owner_email' id='house_owner_email' value="{$house_owner_email}" style="height:26px; width: 351px;"><div id="house_owner_email_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Phone Number: </td>
                <td class='form2'><input type='text' class='text' name='house_owner_phone' id='house_owner_phone' value="{$house_owner_phone}"  style="height:26px; width: 351px;"><div id="house_owner_phone_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Fax: </td>
                <td class='form2'><input type='text' class='text' name='house_owner_fax' id='house_owner_fax' value="{$house_owner_fax}"  style="height:26px; width: 351px;"><div id="house_owner_fax_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-search' value='Change' id="submit" name="submit"/>&nbsp;  &nbsp; 
                        <input type='button' class='btn-search' value='Back' id="back" name="back" onclick="back();"/>&nbsp;  
                        <input type='hidden'  value='{$house_id}' id="house_id" name="house_id"/>  
                        <input type='hidden'  value='{$owner_id}' id="owner_id" name="owner_id"/>  
                    </div>
                </td>
            </tr>
        </table>
    </form>
{/nocache}
{literal}
    <script type="text/javascript">
        $(document).ready(function() {
            birthday('house_build_time');
            
            $('#back').click(function(){
            window.location.href="manage_house.php";
        });
        });
    </script>
{/literal}
{include file='footer.tpl'}