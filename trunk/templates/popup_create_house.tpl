<div style="background-color: #F1F5FE; width: 100%;height:25px; text-align: center;font-size: 1.8em;line-height: 55px; margin-bottom: 2%;">Apartment Registry</div>
{literal}
    <script type="text/javascript">
        birthday('popup_house_build_time');
        $('#popup_owner').click(function() {
            if ($('#popup_owner').is(':checked'))
                var owner = 1;
            else
                var owner = 0;
            if (owner == 1) {
                $('#popup_owner_info').css('display', 'block');
            } else {
                $('#popup_owner_info').css('display', 'none');
            }
        });
        //city
        $('#popup_city_id').change(function(e) {
            var city_id = $('#popup_city_id').val();
            var district_id = $('#popup_district_id').val();

            if (city_id == "") {
                $('#popup_district_id').empty();
                $('#popup_street_id').empty();
                $('#popup_ward_id').empty();
            } else {
                $.post("include/function_ajax.php", {city_id: city_id, district_id: district_id, action: 'create_house', task: 'getDistrictList'},
                function(result) {
                    if (result) {
                        $('#popup_district_id').empty();
                        $('#popup_district_id').html(result);
                        $('#popup_district_id').change();
                    } else {
                        $('#popup_district_id').empty();
                        $('#popup_street_id').empty();
                        $('#popup_ward_id').empty();
                    }
                });
            }
        });
        //district
        $('#popup_district_id').change(function(e) {
            var district_id = $('#popup_district_id').val();
            var street_id = $('#popup_street_id').val();

            if (district_id == "") {
                $('#popup_street_id').empty();
                $('#popup_ward_id').empty();
            } else {
                $.post("include/function_ajax.php", {district_id: district_id, street_id: street_id, action: 'create_house', task: 'getStreetList'},
                function(result) {
                    if (result) {
                        $('#popup_street_id').empty();
                        $('#popup_street_id').html(result);
                        $('#popup_street_id').change();
                    } else {
                        $('#popup_street_id').empty();
                        $('#popup_ward_id').empty();
                    }
                });
            }
        });
        //street
        $('#popup_street_id').change(function(e) {
            var street_id = $('#popup_street_id').val();
            var ward_id = $('#popup_ward_id').val();

            if (street_id == "") {
                $('#popup_ward_id').empty();
            } else {
                $.post("include/function_ajax.php", {street_id: street_id, ward_id: ward_id, action: 'create_house', task: 'getWardList'},
                function(result) {
                    if (result) {
                        $('#popup_ward_id').empty();
                        $('#popup_ward_id').html(result);
                    } else {
                        $('#popup_ward_id').empty();
                    }
                });
            }
        });
/*
        //owner information
        //city
        $('#popup_city_id_owner').change(function(e) {
            var city_id = $('#popup_city_id_owner').val();
            var district_id = $('#popup_district_id').val();
            if (city_id == "") {
                $('#popup_district_id_owner').empty();
                $('#popup_street_id_owner').empty();
                $('#popup_ward_id_owner').empty();
            } else {
                $.post("include/function_ajax.php", {city_id: city_id, district_id: district_id, action: 'create_house', task: 'getDistrictList'},
                function(result) {
                    if (result) {
                        $('#popup_district_id_owner').empty();
                        $('#popup_district_id_owner').html(result);
                        $('#popup_district_id_owner').change();
                    } else {
                        $('#popup_district_id_owner').empty();
                        $('#popup_street_id_owner').empty();
                        $('#popup_ward_id_owner').empty();
                    }
                });
            }
        });
        //district
        $('#popup_district_id_owner').change(function(e) {
            var district_id = $('#popup_district_id_owner').val();
            var street_id = $('#popup_street_id').val();

            if (district_id == "") {
                $('#popup_street_id_owner').empty();
                $('#popup_ward_id_owner').empty();
            } else {
                $.post("include/function_ajax.php", {district_id: district_id, street_id: street_id, action: 'create_house', task: 'getStreetList'},
                function(result) {
                    if (result) {
                        $('#popup_street_id_owner').empty();
                        $('#popup_street_id_owner').html(result);
                        $('#popup_street_id_owner').change();
                    } else {
                        $('#popup_street_id_owner').empty();
                        $('#popup_ward_id_owner').empty();
                    }
                });
            }
        });
        //street
        $('#popup_street_id_owner').change(function(e) {
            var street_id = $('#popup_street_id_owner').val();
            var ward_id = $('#popup_ward_id').val();
            if (street_id == "") {
                $('#popup_ward_id_owner').empty();
            } else {
                $.post("include/function_ajax.php", {street_id: street_id, ward_id: ward_id, action: 'create_house', task: 'getWardList'},
                function(result) {
                    if (result) {
                        $('#popup_ward_id_owner').empty();
                        $('#popup_ward_id_owner').html(result);
                    } else {
                        $('#popup_ward_id_owner').empty();
                    }
                });
            }
        }); */
         $('#popup_submit').click(function() {
            if (!$('#popup_house_name').val()||
                    !$('#popup_city_id').val()||
                    !$('#popup_district_id').val()||
                    !$('#popup_street_id').val()||
                    !$('#popup_ward_id').val()
            ) {
                $('#popup_error').html('Please complete the required fields');
                $('#popup_error').show();
            } else {
                $('#popup_error').hide();
                $.post("popup_create_house.php", 
                        {
                            house_name: $('#popup_house_name').val(),
                            city_id: $('#popup_city_id').val(), 
                            district_id: $('#popup_district_id').val(),
                            street_id: $('#popup_street_id').val(),
                            ward_id: $('#popup_ward_id').val(),
                            house_address: $('#popup_house_address').val(),
                            house_area: $('#popup_house_area').val(),
                            house_build_time: $('#popup_house_build_time').val(),
                            house_type: $('#popup_house_type').val(),
                            house_structure: $('#popup_house_structure').val(),
                            house_description: $('#popup_house_description').val(),
                            submit: 1
                        },
                        function(data) {
                            var result = JSON.parse(data);
                            if(result.status == 1){
                                $("#house_id").append(new Option(result.data.name, result.data.id,true,true));
                                $('#house_id').change();
                                $('#error_house').html("");
                                popup.close();
                            }else{
                                var tmp = '';
                                if(result.data.length){
                                    for(var i in result.data){
                                        tmp = "<div class='error'>"+ result.data[i] +"</div>";
                                    }
                                    $('#popup_error').html(tmp);
                                }else{
                                    $('#popup_error').html('Something wrong. Please close popup and try again!');
                                }
                                $('#popup_error').show();
                            }
                        }
                );
            }
        });
    </script>
{/literal}
{nocache}
    <div class="error" id="popup_error" style="display: none;">Please complete the required fields</div>
    <form action="popup_create_house.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>Name: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='house_name' id='popup_house_name'  style="height:26px; width: 351px;"></td>
            </tr>

            <tr>
                <td class='form1'>City:  <span class="required">*</span></td>
                <td class='form2'>
                    <select id="popup_city_id" name="city_id" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$cities item=city}
                            <option value="{$city.id}" {if $city.id eq $city_id}selected="selected"{/if}>{$city.city_name}</option>        
                        {/foreach}
                    </select>
                </td>
            </tr>      
            <tr>
                <td class='form1'>District:  <span class="required">*</span></td>
                <td class='form2'>
                    <select id="popup_district_id" name="popup_district_id" style="height:26px; width: 351px;"></select>
                </td>
            </tr>
            <tr>
                <td class='form1'>Street:  <span class="required">*</span></td>
                <td class='form2'>
                    <select id="popup_street_id" name="street_id" style="height:26px; width: 351px;"></select>
                </td>
            </tr>
            <tr>
                <td class='form1'>Ward:  <span class="required">*</span></td>
                <td class='form2'>
                    <select id="popup_ward_id" name="ward_id" style="height:26px; width: 351px;"></select>
                </td>
            </tr>
            <tr>
                <td class='form1'>Address number: </td>
                <td class='form2'>
                    <input type='text' class='text' name='house_address' id='popup_house_address' style="height:26px; width: 351px;">
                </td>
            </tr>  
            <tr>
                <td class='form1'>Area: </td>
                <td class='form2'>
                    <input type='text' class='text' name='house_area' id='popup_house_area' style="height:26px; width: 351px;">
                </td>
            </tr>

            <tr>
                <td class='form1'>Build time: </td>
                <td class='form2'>
                    <input type='text' class='text' name='house_build_time' id='popup_house_build_time' style="height:26px; width: 351px;">
                </td>
            </tr>

            <tr>
                <td class='form1'>Type: </td>
                <td class='form2'>
                    <select id="popup_house_type" name="house_type" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$houseTypes item=houseType}
                            <option value="{$houseType.id}" >{$houseType.type_name}</option>        
                        {/foreach}
                    </select>
                </td>
            </tr>

            <tr>
                <td class='form1'>Structure: </td>
                <td class='form2'>
                    <select id="popup_house_structure" name="house_structure" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$houseStructures item=houseStructure}
                            <option value="{$houseStructure.id}">{$houseStructure.structure_name}</option>        
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>Description: </td>
                <td class='form2'>
                    <textarea name='house_description' id='popup_house_description'   style="height:70px; width: 351px;"></textarea>
                </td>
            </tr>                      
            
            
        </table>
<!--
        <div style="margin-bottom: 20px;">
            <input type="checkbox" value="" id="popup_owner" name="owner" /> <label for="owner">オーナーの情報を入力しますか？</label>
        </div>
        <div id="popup_owner_info" style="display: none; ">
            <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                <tr><td colspan="2" style="background-color: white;"><label class="title">オーナー情報</label></td></tr>
                <tr>
                    <td class='form1'>名称: <span class="required">*</span></td>
                    <td class='form2'>
                        <input type='text' class='text' name='popup_house_owner_name' id='popup_house_owner_name' style="height:26px; width: 351px;">
                    </td>
                </tr>

                <tr>
                    <td class='form1'>都道府県:  </td>
                    <td class='form2'><select id="popup_city_id_owner" name="city_id_owner" style="height:26px; width: 351px;">
                            <option value=""></option>
                            {foreach from=$cities item=city}
                                <option value="{$city.id}" {if $city.id eq $city_id_owner}selected="selected"{/if}>{$city.city_name}</option>        
                            {/foreach}
                        </select>
                    </td>
                </tr>      
                <tr>
                    <td class='form1'>市区町村:  </td>
                    <td class='form2'>
                        <select id="popup_district_id_owner" name="district_id_owner" style="height:26px; width: 351px;"></select>
                    </td>
                </tr>
                <tr>
                    <td class='form1'>大字・通称:  </td>
                    <td class='form2'>
                        <select id="popup_street_id_owner" name="street_id_owner" style="height:26px; width: 351px;"></select>
                    </td>
                </tr>
                <tr>
                    <td class='form1'>字・丁目:  </td>
                    <td class='form2'>
                        <select id="popup_ward_id_owner" name="ward_id_owner" style="height:26px; width: 351px;"></select>
                    </td>
                </tr>
                <tr>
                    <td class='form1'>番地: </td>
                    <td class='form2'><input type='text' class='text' name='house_owner_address' id='popup_house_owner_address' style="height:26px; width: 351px;"></td>
                </tr>
                <tr>
                    <td class='form1'>Ｅメール: </td>
                    <td class='form2'><input type='email' class='text' name='house_owner_email' id='popup_house_owner_email' style="height:26px; width: 351px;"></td>
                </tr>
                <tr>
                    <td class='form1'>電話番号: </td>
                    <td class='form2'><input type='text' class='text' name='house_owner_phone' id='popup_house_owner_phone'  style="height:26px; width: 351px;"></td>
                </tr>
                <tr>
                    <td class='form1'>ファックス: </td>
                    <td class='form2'><input type='text' class='text' name='house_owner_fax' id='popup_house_owner_fax' style="height:26px; width: 351px;"></td>
                </tr>            
            </table>
        </div>
        -->
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">           
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
            <center style="margin-right: 8%;">
                <input type='button' class='btn-signup' value='Submit' id="popup_submit" name="submit"/>&nbsp;    
            </center>
            </td>
            </tr>
        </table>
    </form>
{/nocache}