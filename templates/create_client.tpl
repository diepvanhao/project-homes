{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin-bottom: 2%;">Create Client</div>
{literal}
    <script type="text/javascript">
        $(document).ready(function() {
             //city
            $('#city_id').change(function(e) {
                var city_id = $('#city_id').val();
                var district_id ={/literal}{if $district_id ne ""}{$district_id}{else}0{/if}{';'}{literal}
                
                if (city_id == "") {
                    $('#district_id').empty();
                    $('#street_id').empty();
                    $('#ward_id').empty();
                } else {
                    $.post("include/function_ajax.php", {city_id: city_id, district_id: district_id, action: 'create_house', task: 'getDistrictList'},
                    function(result) {
                        if (result) {
                            $('#district_id').empty();
                            $('#district_id').html(result);
                            $('#district_id').change();
                        } else {
                            $('#district_id').empty();
                            $('#street_id').empty();
                            $('#ward_id').empty();
                        }
                    });
                }
            });
            //district
            $('#district_id').change(function(e) {
                var district_id = $('#district_id').val();
                var street_id ={/literal}{if $street_id ne ""}{$street_id}{else}0{/if}{';'}{literal}

                if (district_id == "") {
                    $('#street_id').empty();
                    $('#ward_id').empty();
                } else {
                    $.post("include/function_ajax.php", {district_id: district_id, street_id: street_id, action: 'create_house', task: 'getStreetList'},
                    function(result) {
                        if (result) {
                            $('#street_id').empty();
                            $('#street_id').html(result);
                            $('#street_id').change();
                        } else {
                            $('#street_id').empty();
                            $('#ward_id').empty();
                        }
                    });
                }
            });
            //street
            $('#street_id').change(function(e) {
                var street_id = $('#street_id').val();
                var ward_id ={/literal}{if $ward_id ne ""}{$ward_id}{else}0{/if}{';'}{literal}

                if (street_id == "") {
                    $('#ward_id').empty();
                } else {
                    $.post("include/function_ajax.php", {street_id: street_id, ward_id: ward_id, action: 'create_house', task: 'getWardList'},
                    function(result) {
                        if (result) {
                            $('#ward_id').empty();
                            $('#ward_id').html(result);
                        } else {
                            $('#ward_id').empty();
                        }
                    });
                }
            });
        });
    </script>
{/literal}
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    <form action="create_client.php" method="post">
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
                <td class='form1'>City:  <span class="required">*</span></td>
                <td class='form2'><select id="city_id" name="city_id" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$cities item=city}
                            <option value="{$city.id}" {if $city.id eq $city_id}selected="selected"{/if}>{$city.city_name}</option>        
                        {/foreach}
                    </select><div id="error_city_id" class="error"></div>
                </td>
            </tr>      
            <tr>
                <td class='form1'>District:  <span class="required">*</span></td>
                <td class='form2'><select id="district_id" name="district_id" style="height:26px; width: 351px;">                       

                    </select><div id="error_district_id" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>Street:  <span class="required">*</span></td>
                <td class='form2'><select id="street_id" name="street_id" style="height:26px; width: 351px;">

                    </select><div id="error_street_id" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>Ward:  <span class="required">*</span></td>
                <td class='form2'><select id="ward_id" name="ward_id" style="height:26px; width: 351px;">

                    </select><div id="error_ward_id" class="error"></div>
                </td>
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
                <td class='form1'>Room Type:  </td>
                <td class='form2'><select id="client_room_type" name="client_room_type" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$roomTypes item=roomType}
                            <option value="{$roomType.id}" {if $roomType.id eq $data.client_room_type}selected="selected"{/if}>{$roomType.room_name}</option>        
                        {/foreach}
                    </select><div id="client_room_type_error"class="error"></div></div>
                </td>
            </tr>
            {*<tr>
                <td class='form1'>Room Type: </td>
                <td class='form2'><input type='text' class='text' name='client_room_type' id='client_room_type' value="{$data.client_room_type}"  style="height:26px; width: 351px;"><div id="client_room_type_error"class="error"></div></td>
            </tr>*}
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
{literal}
    <script type="text/javascript">
        $(document).ready(function() {
            birthday('client_birthday');
            birthday('client_time_change');
        });
    </script>
{/literal}
{if $city_id ne ""}
    {literal}
        <script type="text/javascript">
            $(document).ready(function() {
                $('#city_id').change();
            });
        </script>
    {/literal}
{/if}
{include file='footer.tpl'}