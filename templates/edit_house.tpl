{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin-bottom: 2%;">物件情報編集</div>
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
        <div><label class="title">登録完了致しました</label></div>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>名称: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='house_name' id='house_name' value="{$house_name}"  style="height:26px; width: 351px;"><div id="house_name_error"class="error"></div></td>
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
                <td class='form1'>字・丁目: </td>
                <td class='form2'><select id="ward_id" name="ward_id" style="height:26px; width: 351px;">

                    </select><div id="error_ward_id" class="error"></div>
                </td>
            </tr>  
            <tr>
                <td class='form1'>番地:</td>
                <td class='form2'><input type='text' class='text' name='house_address' id='house_address' value="{$house_address}"  style="height:26px; width: 351px;"><div id="house_address_error"class="error"></div></td>
            </tr> 
            <tr>
                <td class='form1'>エリア: </td>
                <td class='form2'><input type='text' class='text' name='house_area' id='house_area' value="{$house_area}" style="height:26px; width: 351px;"><div id="house_area_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>築年月: </td>
                <td class='form2'><input type='text' class='text' name='house_build_time' id='house_build_time' value="{$house_build_time}"  style="height:26px; width: 351px;"><div id="house_build_time_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>物件種別: </td>
                <td class='form2'>
                    <select id="house_type" name="house_type" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$houseTypes item=houseType}
                            <option value="{$houseType.id}" {if $houseType.id eq $house_type}selected="selected"{/if}>{$houseType.type_name}</option>        
                        {/foreach}
                    </select><div id="error_house_type" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>建物構造: </td>
                <td class='form2'>
                    <select id="house_structure" name="house_structure" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$houseStructures item=houseStructure}
                            <option value="{$houseStructure.id}" {if $houseStructure.id eq $house_structure}selected="selected"{/if}>{$houseStructure.structure_name}</option>        
                        {/foreach}
                    </select><div id="error_house_structure" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>備考: </td>
                <td class='form2'><textarea name='house_description' id='house_description'   style="height:126px; width: 351px;">{$house_description}</textarea><div id="house_description_error"class="error"></div></td>
            </tr>                      
            {*<tr>
            <td class='form1'>Status: </td>
            <td class='form2'><input type='text' class='text' name='house_status' id='house_status' value="{$house_status}"  style="height:26px; width: 351px;"><div id="house_status_error"class="error"></div></td>
            </tr>*}
        </table>
        <div style="margin-bottom: 20px;"><input type="checkbox" value="" id="owner" name="owner" {if $owner eq 1}checked="checked"{/if}/> <label for="owner">オーナーの情報を入力しますか？</label></div>
        <div id="owner_info" {if $owner eq 1}style="display: block;"{else}style="display: none;" {/if}>
            <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                <tr><td colspan="2" style="background-color: white;"><label class="title">オーナー情報</label></td></tr>
                <tr>
                    <td class='form1'>名称: <span class="required">*</span></td>
                    <td class='form2'><input type='text' class='text' name='house_owner_name' id='house_owner_name' value="{$house_owner_name}"  style="height:26px; width: 351px;"><div id="house_owner_name_error"class="error"></div></td>
                </tr>
                
                <tr>
                    <td class='form1'>都道府県:  </td>
                    <td class='form2'><select id="city_id_owner" name="city_id_owner" style="height:26px; width: 351px;">
                            <option value=""></option>
                            {foreach from=$cities item=city}
                                <option value="{$city.id}" {if $city.id eq $city_id_owner}selected="selected"{/if}>{$city.city_name}</option>        
                            {/foreach}
                        </select><div id="error_city_id_owner" class="error"></div>
                    </td>
                </tr>      
                <tr>
                    <td class='form1'>市区町村:  </td>
                    <td class='form2'><select id="district_id_owner" name="district_id_owner" style="height:26px; width: 351px;">                       

                        </select><div id="error_district_id_owner" class="error"></div>
                    </td>
                </tr>
                <tr>
                    <td class='form1'>大字・通称:  </td>
                    <td class='form2'><select id="street_id_owner" name="street_id_owner" style="height:26px; width: 351px;">

                        </select><div id="error_street_id_owner" class="error"></div>
                    </td>
                </tr>
                <tr>
                    <td class='form1'>字・丁目:  </td>
                    <td class='form2'><select id="ward_id_owner" name="ward_id_owner" style="height:26px; width: 351px;">

                        </select><div id="error_ward_id_owner" class="error"></div>
                    </td>
                </tr>
                <tr>
                    <td class='form1'>番地: </td>
                    <td class='form2'><input type='text' class='text' name='house_owner_address' id='house_owner_address' value="{$house_owner_address}"  style="height:26px; width: 351px;"><div id="house_owner_address_error"class="error"></div></td>
                </tr>
                <tr>
                    <td class='form1'>Ｅメール: </td>
                    <td class='form2'><input type='email' class='text' name='house_owner_email' id='house_owner_email' value="{$house_owner_email}" style="height:26px; width: 351px;"><div id="house_owner_email_error"class="error"></div></td>
                </tr>
                <tr>
                    <td class='form1'>電話番号: </td>
                    <td class='form2'><input type='text' class='text' name='house_owner_phone' id='house_owner_phone' value="{$house_owner_phone}"  style="height:26px; width: 351px;"><div id="house_owner_phone_error"class="error"></div></td>
                </tr>
                <tr>
                    <td class='form1'>ファックス: </td>
                    <td class='form2'><input type='text' class='text' name='house_owner_fax' id='house_owner_fax' value="{$house_owner_fax}"  style="height:26px; width: 351px;"><div id="house_owner_fax_error"class="error"></div></td>
                </tr>            
            </table>
        </div>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">           
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-search' value='変更' id="submit" name="submit"/>&nbsp;  &nbsp; 
                        <input type='button' class='btn-search' value='戻る' id="back" name="back" onclick="back();"/>&nbsp;  
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
            $('#back').click(function() {
                window.location.href = "manage_house.php";
            });
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

                                                //owner information
                                                //city
                                                $('#city_id_owner').change(function(e) {
                                                    var city_id = $('#city_id_owner').val();
                                                    var district_id ={/literal}{if $district_id_owner ne ""}{$district_id_owner}{else}0{/if}{';'}{literal}
                                                                if (city_id == "") {
                                                                    $('#district_id_owner').empty();
                                                                    $('#street_id_owner').empty();
                                                                    $('#ward_id_owner').empty();
                                                                } else {
                                                                    $.post("include/function_ajax.php", {city_id: city_id, district_id: district_id, action: 'create_house', task: 'getDistrictList'},
                                                                    function(result) {
                                                                        if (result) {
                                                                            $('#district_id_owner').empty();
                                                                            $('#district_id_owner').html(result);
                                                                            $('#district_id_owner').change();
                                                                        } else {
                                                                            $('#district_id_owner').empty();
                                                                            $('#street_id_owner').empty();
                                                                            $('#ward_id_owner').empty();
                                                                        }
                                                                    });
                                                                }
                                                            });
                                                            //district
                                                            $('#district_id_owner').change(function(e) {
                                                                var district_id = $('#district_id_owner').val();
                                                                var street_id ={/literal}{if $street_id_owner ne ""}{$street_id_owner}{else}0{/if}{';'}{literal}

                                                                            if (district_id == "") {
                                                                                $('#street_id_owner').empty();
                                                                                $('#ward_id_owner').empty();
                                                                            } else {
                                                                                $.post("include/function_ajax.php", {district_id: district_id, street_id: street_id, action: 'create_house', task: 'getStreetList'},
                                                                                function(result) {
                                                                                    if (result) {
                                                                                        $('#street_id_owner').empty();
                                                                                        $('#street_id_owner').html(result);
                                                                                        $('#street_id_owner').change();
                                                                                    } else {
                                                                                        $('#street_id_owner').empty();
                                                                                        $('#ward_id_owner').empty();
                                                                                    }
                                                                                });
                                                                            }
                                                                        });
                                                                        //street
                                                                        $('#street_id_owner').change(function(e) {
                                                                            var street_id = $('#street_id_owner').val();
                                                                            var ward_id ={/literal}{if $ward_id_owner ne ""}{$ward_id_owner}{else}0{/if}{';'}{literal}

                                                                                        if (street_id == "") {
                                                                                            $('#ward_id_owner').empty();
                                                                                        } else {
                                                                                            $.post("include/function_ajax.php", {street_id: street_id, ward_id: ward_id, action: 'create_house', task: 'getWardList'},
                                                                                            function(result) {
                                                                                                if (result) {
                                                                                                    $('#ward_id_owner').empty();
                                                                                                    $('#ward_id_owner').html(result);
                                                                                                } else {
                                                                                                    $('#ward_id_owner').empty();
                                                                                                }
                                                                                            });
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
                $('#city_id_owner').change();
            });
        </script>
    {/literal}
{/if}
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