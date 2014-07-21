{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; ">店舗情報編集</div>
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
    {if $notify ne ""}
        {$notify}
    {/if}
    <form action="edit_agent.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>店舗名: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='agent_name' id='agent_name' value="{$agent_name}"  style="height:26px; width: 351px;"><div id="agent_name_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Eメール: <span class="required">*</span></td>
                <td class='form2'><input type='email' class='text' name='agent_email' id='agent_email' value="{$agent_email}"  style="height:26px; width: 351px;"><div id="agent_email_error"class="error"></div></td>
            </tr>

            <tr>
                <td class='form1'>都道府県:  <span class="required">*</span></td>
                <td class='form2'><select id="city_id" name="city_id" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$cities item=city}
                            <option value="{$city.id}" {if $city.id eq $city_id}selected="selected"{/if}>{$city.city_name}</option>        
                        {/foreach}
                    </select><div id="error_city_id" class="error"></div>
                </td>
            </tr>      
            <tr>
                <td class='form1'>市区町村:  <span class="required">*</span></td>
                <td class='form2'><select id="district_id" name="district_id" style="height:26px; width: 351px;">                       

                    </select><div id="error_district_id" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>大字・通称:  <span class="required">*</span></td>
                <td class='form2'><select id="street_id" name="street_id" style="height:26px; width: 351px;">

                    </select><div id="error_street_id" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>字・丁目:  <span class="required">*</span></td>
                <td class='form2'><select id="ward_id" name="ward_id" style="height:26px; width: 351px;">

                    </select><div id="error_ward_id" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>番地: </td>
                <td class='form2'><input type='text' class='text' name='agent_address' id='agent_address' value="{$agent_address}" style="height:26px; width: 351px;"><div id="agent_address_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>電話番号: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='agent_phone' id='agent_phone' value="{$agent_phone}" maxlength='70' style="height:26px; width: 351px;"><div id="agent_phone_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>ファックス: </td>
                <td class='form2'><input type='text' class='text' name='agent_fax' id='agent_fax' value="{$agent_fax}" maxlength='70' style="height:26px; width: 351px;"><div id="agent_fax_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-search' value='変更' id="submit" name="submit"/>&nbsp;  &nbsp; 
                        <input type='button' class='btn-search' value='戻る' id="back" name="back" onclick="back();"/>&nbsp;  
                        <input type='hidden'  value='{$agent_id}' id="agent_id" name="agent_id"/>                   
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