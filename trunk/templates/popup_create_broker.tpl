<div id="content">
    <h1>Broker Company Registry</h1>
    {literal}
    <script type="text/javascript">
             //city
            $('#popup_city_id').change(function(e) {
                var popup_city_id = $('#popup_city_id').val();
                var popup_district_id = $('#popup_district_id').val();
                
                if (popup_city_id == "") {
                    $('#popup_district_id').empty();
                    $('#popup_street_id').empty();
                    $('#popup_ward_id').empty();
                } else {
                    $.post("include/function_ajax.php", {city_id: popup_city_id, district_id: popup_district_id, action: 'create_house', task: 'getDistrictList'},
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
                var popup_district_id = $('#popup_district_id').val();
                var popup_street_id = $('#popup_street_id').val();

                if (popup_district_id == "") {
                    $('#popup_street_id').empty();
                    $('#popup_ward_id').empty();
                } else {
                    $.post("include/function_ajax.php", {district_id: popup_district_id, street_id: popup_street_id, action: 'create_house', task: 'getStreetList'},
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
                var popup_street_id = $('#popup_street_id').val();
                var popup_ward_id = $('#popup_ward_id').val();

                if (popup_street_id == "") {
                    $('#popup_ward_id').empty();
                } else {
                    $.post("include/function_ajax.php", {street_id: popup_street_id, ward_id: popup_ward_id, action: 'create_house', task: 'getWardList'},
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
            $('#popup_submit').click(function() {
                if (!$('#popup_broker_company_name').val()||
                        !$('#popup_city_id').val()||
                        !$('#popup_district_id').val()||
                        !$('#popup_street_id').val()||
                        !$('#popup_ward_id').val()||
                        !$('#popup_broker_company_phone').val()
                ) {
                    $('#popup_error').html('Please complete the required fields');
                    $('#popup_error').show();
                } else {
                    $('#popup_error').hide();
                    $.post(
                            "popup_create_broker.php", 
                            {
                                broker_company_name: $('#popup_broker_company_name').val(),
                                city_id: $('#popup_city_id').val(), 
                                district_id: $('#popup_district_id').val(),
                                street_id: $('#popup_street_id').val(),
                                ward_id: $('#popup_ward_id').val(),
                                broker_company_address: $('#popup_broker_company_address').val(),
                                broker_company_phone: $('#popup_broker_company_phone').val(),
                                broker_company_email: $('#popup_broker_company_email').val(),
                                broker_company_fax: $('#popup_broker_company_fax').val(),
                                broker_company_undertake: $('#broker_company_undertake').val(),
                                submit: 1
                            },
                            function(data) {
                                var result = JSON.parse(data);
                                if(result.status == 1){
                                    
                                    $("#broker_id").append(new Option(result.data.name, result.data.id,true,true));
//                                    $('#broker_id').val(result.data.id);
                                    $('#error_broker').html("");
                                    $('#error_house').html("");
                                    if ($('#broker_id').val()) {
                                        $('#yoke_muscle').click();
                                    }
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
    <form action="popup_create_broker_company.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>Name: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='broker_company_name' id='popup_broker_company_name' style="height:26px; width: 351px;"></td>
            </tr>

            <tr>
                <td class='form1'>City:  <span class="required">*</span></td>
                <td class='form2'><select id="popup_city_id" name="city_id" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$cities item=city}
                            <option value="{$city.id}" >{$city.city_name}</option>        
                        {/foreach}
                    </select>
                </td>
            </tr>      
            <tr>
                <td class='form1'>District:  <span class="required">*</span></td>
                <td class='form2'><select id="popup_district_id" name="district_id" style="height:26px; width: 351px;">                       

                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>Street:  <span class="required">*</span></td>
                <td class='form2'><select id="popup_street_id" name="street_id" style="height:26px; width: 351px;">

                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>Ward:  <span class="required">*</span></td>
                <td class='form2'><select id="popup_ward_id" name="ward_id" style="height:26px; width: 351px;">

                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>Address number: </td>
                <td class='form2'><input type='text' class='text' name='broker_company_address' id='popup_broker_company_address' style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>Phone number: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='broker_company_phone' id='popup_broker_company_phone' style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>Email: <span class="required"></span></td>
                <td class='form2'><input type='email' class='text' name='broker_company_email' id='popup_broker_company_email'  style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>Fax: </td>
                <td class='form2'><input type='text' class='text' name='broker_company_fax' id='popup_broker_company_fax' style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>Responsible: </td>
                <td class='form2'><input type='text' class='text' name='broker_company_undertake' id='popup_broker_company_undertake' style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='button' class='btn-signup' value='Submit' id="popup_submit" name="submit"/>&nbsp;  
                    </div>
                </td>
            </tr>
        </table>
    </form>
    {/nocache}
</div>