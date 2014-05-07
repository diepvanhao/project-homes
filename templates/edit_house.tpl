{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin-bottom: 2%;">Create House</div>
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    <form action="create_house.php" method="post">
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
                <td class='form1'>Structure: </td>
                <td class='form2'><input type='text' class='text' name='house_structure' id='house_structure' value="{$house_structure}"  style="height:26px; width: 351px;"><div id="house_structure_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Description: </td>
                <td class='form2'><textarea name='house_description' id='house_description'   style="height:126px; width: 351px;">{$house_description}</textarea><div id="house_description_error"class="error"></div></td>
            </tr>                      
            {*<tr>
            <td class='form1'>Status: </td>
            <td class='form2'><input type='text' class='text' name='house_status' id='house_status' value="{$house_status}"  style="height:26px; width: 351px;"><div id="house_status_error"class="error"></div></td>
            </tr>*}
        </table>
        <div style="margin-bottom: 20px;"><input type="checkbox" value="" id="owner" name="owner" {if $owner eq 1}checked="checked"{/if}/> <label for="owner">Do you input owner information?</label></div>
        <div id="owner_info" {if $owner eq 1}style="display: block;"{else}style="display: none;" {/if}>
            <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                <tr><td colspan="2" style="background-color: white;"><label class="title">Owner Infomation</label></td></tr>
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
                    <td class='form2'><input type='email' class='text' name='house_owner_email' id='house_owner_email' value="{$house_owner_email}" style="height:26px; width: 351px;"><div id="house_owner_email_error"class="error"></div></td>
                </tr>
                <tr>
                    <td class='form1'>Phone Number: </td>
                    <td class='form2'><input type='text' class='text' name='house_owner_phone' id='house_owner_phone' value="{$house_owner_phone}"  style="height:26px; width: 351px;"><div id="house_owner_phone_error"class="error"></div></td>
                </tr>
                <tr>
                    <td class='form1'>Fax: </td>
                    <td class='form2'><input type='text' class='text' name='house_owner_fax' id='house_owner_fax' value="{$house_owner_fax}"  style="height:26px; width: 351px;"><div id="house_owner_fax_error"class="error"></div></td>
                </tr>            
            </table>
        </div>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">           
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-left: 30px;">
                        <input type='submit' class='btn-signup' value='Create' id="submit" name="submit"/>&nbsp;                     
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
            $('#owner').click(function() {
                if ($('#owner').is(':checked'))
                    var owner = 1;
                else
                    var owner = 0;
                if (owner == 1) {
                    $('#owner_info').css('display', 'block');
                } else {
                    $('#owner_info').css('display', 'none');
                }
            });
        });
    </script>
{/literal}
{if $owner eq 1}
    {literal}
        <script type="text/javascript">
            $(document).ready(function() {
                $('#owner').attr('checked', 'checked');
            });
        </script>
    {/literal}
{/if}
{include file='footer.tpl'}