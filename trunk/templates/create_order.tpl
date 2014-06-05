{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin-bottom: 2%;">Create Order</div>
{literal}
    <script type="text/javascript">
        $(document).ready(function() {
            var txt = $("input#client_phone");
            var func = function(e) {
                if (e.keyCode === 32) {
                    txt.val(txt.val().replace(/\s/g, ''));
                }
            }
            txt.keyup(func).blur(func);

            birthday('client_birthday');
            birthday('client_time_change');
            timepicker('log_time_call');
            timepicker('log_time_arrive_company');
            timepicker('log_time_mail');
            birthday('log_date_appointment_from');
            birthday('log_date_appointment_to');
            birthday('log_payment_date_appointment_from');
            birthday('log_payment_date_appointment_to');
            birthday('aspirations_build_time');
            birthday('contract_signature_day');
            birthday('contract_handover_day');
            birthday('contract_period_from');
            birthday('contract_period_to');
            birthday('contract_application_date');
            $('#search').keyup(function(e) {
                var search = $('#search').val();
                $('#error_house').html("");
                //    showloadgif();
                $.post("include/function_ajax.php", {search: search, action: 'create_order', task: 'getHouseSearch'},
                function(result) {
                    if (result) {
                        $('#house_id').empty();
                        $('#house_id').html(result);
                        $('#step').click();
                        //   hideloadgif();
                    } else {
                        $('#house_id').empty();
                        $('#room_id').empty();
                        $('#house_description').html("");
                        $('#error_house').html("No any house for your keyword");
                        //     hideloadgif();
                    }
                });
            });
            $('#step').click(function() {
                var house_id = $('#house_id').val();
                $('#submit').attr('disabled', false);
                $("#submit").css('color', '#fff');
                $.post('include/function_ajax.php', {house_id: house_id, action: 'create_order', task: 'getContentHouse'},
                function(result) {
                    var json = $.parseJSON(result);
                    $('#house_description').html(json.house_description);
                    get_room(house_id, 0);
                });
            });
            $('#house_id').change(function() {
                $('#submit').attr('disabled', false);
                $("#submit").css('color', '#fff');
                var house_id = $('#house_id').val();
                $.post('include/function_ajax.php', {house_id: house_id, action: 'create_order', task: 'getContentHouse'},
                function(result) {
                    var json = $.parseJSON(result);
                    $('#house_description').html(json.house_description);
                    get_room(house_id, 0);
                });
            });
            $('#room_id').change(function() {
                var room_id = $('#room_id').val();
                var broker_id = $('#broker_id').val();
                var house_id = $('#house_id').val();
                $('#error_room').html("");
                $('#order_rent_cost').val("");
                $.post('include/function_ajax.php', {house_id: house_id, room_id: room_id, broker_id: broker_id, action: 'create_order', task: 'checkRoom'},
                function(result) {
                    var json = $.parseJSON(result);
                    if (json.status == 1) {
                        $('#error_room').html("This room had person rent. Please choose other room");
                        $('#submit').attr('disabled', true);
                        $("#submit").css('color', 'grey');
                    } else if (json.status == 2) {
                        $('#error_room').html("This room is contructing. Please choose other room");
                        $('#submit').attr('disabled', true);
                        $("#submit").css('color', 'grey');
                    } else {
                        if (json.flag == 'false') {
                            $('#error_room').html("This room isn't belong to broker company that you selected.");
                            $('#submit').attr('disabled', true);
                            $("#submit").css('color', 'grey');
                        } else {
                            $('#order_rent_cost').val(json.room_rent);
                            $('#submit').attr('disabled', false);
                            $("#submit").css('color', '#fff');
                        }
                    }
                });
            });
            $('#contract_cost').keyup(function(e) {
                var contract_plus_money = parseFloat($('#contract_plus_money').val());
                var contract_key_money = parseFloat($('#contract_key_money').val());
                var contract_cost = parseFloat($('#contract_cost').val());

                $('#contract_total').val((contract_plus_money > 0 ? contract_plus_money : 0) + (contract_key_money > 0 ? contract_key_money : 0) + (contract_cost > 0 ? contract_cost : 0));
            });
            $('#contract_plus_money').keyup(function(e) {
                var contract_plus_money = parseFloat($('#contract_plus_money').val());
                var contract_key_money = parseFloat($('#contract_key_money').val());
                var contract_cost = parseFloat($('#contract_cost').val());
                $('#contract_total').val((contract_plus_money > 0 ? contract_plus_money : 0) + (contract_key_money > 0 ? contract_key_money : 0) + (contract_cost > 0 ? contract_cost : 0));
            });
            $('#contract_key_money').keyup(function(e) {
                var contract_plus_money = parseFloat($('#contract_plus_money').val());
                var contract_key_money = parseFloat($('#contract_key_money').val());
                var contract_cost = parseFloat($('#contract_cost').val());
                $('#contract_total').val((contract_plus_money > 0 ? contract_plus_money : 0) + (contract_key_money > 0 ? contract_key_money : 0) + (contract_cost > 0 ? contract_cost : 0));
            });
            $('#back').click(function() {
                var broker_id = $('#broker_id').val();
                var staff_id = $('#staff_id').val();
                var house_id = $('#house_id').val();
                var room_id = $('#room_id').val();
                var order_name = $('#order_name').val();
                var order_rent_cost = $('#order_rent_cost').val();
                var order_comment = $('#order_comment').val();

                window.location.href = "create_order.php?step=2&broker_id=" + broker_id + '&house_id=' + house_id + "&room_id=" + room_id + "&staff_id=" + staff_id + "&order_name=" + order_name + "&order_rent_cost=" + order_rent_cost + "&order_comment=" + order_comment;
            });
            $('#client_info ul li').click(function() {
                $('#client_info ul li').each(function() {
                    if ($(this).attr('class') == 'select_menu') {
                        $(this).removeClass('select_menu');
                        $(this).addClass('noselect_menu');
                    }
                });
                $(this).removeClass('noselect_menu');
                $(this).addClass('select_menu');
                //active tag
                var id = $(this).attr('title');
                //
                $('#client_detail').find('div').each(function() {
                    if ($(this).attr('class') == 'active') {
                        $(this).removeClass('active');
                        $(this).addClass('inactive');
                    }
                });
                $('#client_detail').find('div').each(function() {
                    if ($(this).attr('id') == id) {
                        $(this).removeClass('inactive');
                        $(this).addClass('active');
                    }
                });

            });
            /*$('#client_detail').find('#client_id').each(function() {
             $(this).val(4);
             });*/
            $('#client_detail').find('#save').click(function(e) {
                var cus_id = $('#cus_id').val();
                if (cus_id == "" && getDivClass('basic') == 0) {
                    alert('Please supply basic information first !!!');
                    $('#client_info ul li').first().click();
                    e.preventDefault();
                }
                //validate
                $('#client_detail').find('div').each(function() {
                    if ($(this).attr('class') == 'active' && $(this).attr('id') == 'basic') {
                        var name = $('#client_name').val();
                        var phone = $('#client_phone').val();
                        if (name == "" || phone == "") {
                            $('#error_validate').html(' Note: fill Name and Phone number fields !!!');
                            e.preventDefault();

                        }
                    } else if ($(this).attr('class') == 'active' && $(this).attr('id') == 'detail') {
                        /*save information client detail*/
                        var gender = $('#gender').val();
                        var client_address = $('#client_address').val();
                        var city_id = $('#city_id').val();
                        var district_id = $('#district_id').val();
                        var street_id = $('#street_id').val();
                        var ward_id = $('#ward_id').val();
                        var client_occupation = $('#client_occupation').val();
                        var client_company = $('#client_company').val();
                        var client_income = $('#client_income').val();
                        var client_room_type = $('#client_room_type').val();
                        var client_rent = $('#client_rent').val();
                        var client_reason_change = $('#client_reason_change').val();
                        var client_time_change = $('#client_time_change').val();
                        var client_resident_name = $('#client_resident_name').val();
                        var client_resident_phone = $('#client_resident_phone').val();
                        var client_id = $('#client_id').val();
                        var order_id = $('#order_id').val();




                        if (city_id == "" || city_id == null) {
                            $('#error_city_id').html('City is required');
                            return false;
                        } else if (district_id == "" || district_id == null) {
                            $('#error_district_id').html('District is required');
                            return false;
                        } else if (street_id == "" || street_id == null) {
                            $('#error_street_id').html('Street is required');
                            return false;
                        } else if (ward_id == "" || ward_id == null) {
                            $('#error_ward_id').html('Ward is required');
                            return false;
                        }
                        //clear notify error
                        $('#error_city_id').html('');
                        $('#error_district_id').html('');
                        $('#error_street_id').html('');
                        $('#error_ward_id').html('');

                        $.post("include/function_ajax.php", {gender: gender, client_address: client_address, city_id: city_id, district_id: district_id, street_id: street_id, ward_id: ward_id, client_occupation: client_occupation,
                            client_company: client_company, client_income: client_income, client_room_type: client_room_type, client_rent: client_rent,
                            client_reason_change: client_reason_change, client_time_change: client_time_change, client_resident_name: client_resident_name,
                            client_resident_phone: client_resident_phone, client_id: client_id, order_id: order_id, action: 'customer', task: 'detail'},
                        function(result) {
                            if (result == 'success') {
                                alert('Saved');
                            } else if (result == 'fail') {
                                alert("Save fail");
                            }
                        });
                    } else if ($(this).attr('class') == 'active' && $(this).attr('id') == 'history') {

                        var log_status_appointment = $('input[name="log_status_appointment"]:checked').val();
                        var log_payment_appointment_status = $('input[name="log_payment_appointment_status"]:checked').val();
                        var log_payment_appointment_report = $('input[name="log_payment_appointment_report"]:checked').val();

                        var log_time_call = $('#log_time_call').val();
                        var log_time_arrive_company = $('#log_time_arrive_company').val();
                        var log_time_mail = $('#log_time_mail').val();
                        var log_comment = $('#log_comment').val();
                        var log_date_appointment_from = $('#log_date_appointment_from').val();
                        var log_date_appointment_to = $('#log_date_appointment_to').val();
                        var log_payment_date_appointment_from = $('#log_payment_date_appointment_from').val();
                        var log_payment_date_appointment_to = $('#log_payment_date_appointment_to').val();
                        var log_revisit = $('#log_revisit').val();
                        var source_id = $('#source_id').val();

                        if ($('#log_tel').is(':checked'))
                            var log_tel = 1;
                        else
                            var log_tel = 0;

                        if ($('#log_tel_status').is(':checked'))
                            var log_tel_status = 1;
                        else
                            var log_tel_status = 0;

                        if ($('#log_mail').is(':checked'))
                            var log_mail = 1;
                        else
                            var log_mail = 0;

                        if ($('#log_mail_status').is(':checked'))
                            var log_mail_status = 1;
                        else
                            var log_mail_status = 0;

                        if ($('#log_contact_head_office').is(':checked'))
                            var log_contact_head_office = 1;
                        else
                            var log_contact_head_office = 0;

                        if ($('#log_shop_sign').is(':checked'))
                            var log_shop_sign = 1;
                        else
                            var log_shop_sign = 0;

                        if ($('#log_local_sign').is(':checked'))
                            var log_local_sign = 1;
                        else
                            var log_local_sign = 0;

                        if ($('#log_introduction').is(':checked'))
                            var log_introduction = 1;
                        else
                            var log_introduction = 0;

                        if ($('#log_flyer').is(':checked'))
                            var log_flyer = 1;
                        else
                            var log_flyer = 0;

                        if ($('#log_line').is(':checked'))
                            var log_line = 1;
                        else
                            var log_line = 0;

                        var client_id = $('#client_id').val();
                        var order_id = $('#order_id').val();

                        $.post("include/function_ajax.php", {log_time_call: log_time_call, log_time_arrive_company: log_time_arrive_company, log_time_mail: log_time_mail,
                            log_tel: log_tel, log_tel_status: log_tel_status, log_mail: log_mail, log_comment: log_comment, log_date_appointment_from: log_date_appointment_from,
                            log_date_appointment_to: log_date_appointment_to, log_payment_date_appointment_from: log_payment_date_appointment_from, log_payment_date_appointment_to: log_payment_date_appointment_to,
                            log_payment_appointment_status: log_payment_appointment_status, log_payment_appointment_report: log_payment_appointment_report,
                            log_mail_status: log_mail_status, log_contact_head_office: log_contact_head_office, log_shop_sign: log_shop_sign, log_local_sign: log_local_sign,
                            log_introduction: log_introduction, log_flyer: log_flyer, log_line: log_line, log_revisit: log_revisit, source_id: source_id,
                            log_status_appointment: log_status_appointment, client_id: client_id, order_id: order_id, action: 'customer', task: 'history'},
                        function(result) {
                            var json = $.parseJSON(result);
                            if (json.id != "")
                                alert('Saved');
                            else if (json.id == "")
                                alert("Updated");
                        });

                    } else if ($(this).attr('class') == 'active' && $(this).attr('id') == 'aspirations') {
                        var aspirations_type_house = $('#aspirations_type_house').val();
                        var aspirations_type_room = $('#aspirations_type_room').val();
                        var aspirations_build_time = $('#aspirations_build_time').val();
                        var aspirations_area = $('#aspirations_area').val();
                        var aspirations_size = $('#aspirations_size').val();
                        var aspirations_rent_cost = $('#aspirations_rent_cost').val();
                        var aspirations_comment = $('#aspirations_comment').val();
                        var client_id = $('#client_id').val();
                        var order_id = $('#order_id').val();
                        $.post("include/function_ajax.php", {aspirations_type_house: aspirations_type_house, aspirations_type_room: aspirations_type_room, aspirations_build_time: aspirations_build_time,
                            aspirations_area: aspirations_area, aspirations_size: aspirations_size, aspirations_rent_cost: aspirations_rent_cost, aspirations_comment: aspirations_comment,
                            client_id: client_id, order_id: order_id, action: 'customer', task: 'aspirations'},
                        function(result) {
                            var json = $.parseJSON(result);
                            if (json.id != "")
                                alert('Saved');
                            else if (json.id == "")
                                alert("Updated");
                        });

                    } else if ($(this).attr('class') == 'active' && $(this).attr('id') == 'introduce') {
                        var house_id = $('#house_id').val();
                        var house_description = $('#house_description').val();
                        var client_id = $('#client_id').val();
                        var order_id = $('#order_id').val();
                        var room_id = $('#room_id').val();
                        $('#error_house').html('');
                        $('#error_room_introduce').html('');
                        if (house_id == "")
                            $('#error_house').html('Please choose house.');
                        else if (room_id == "") {
                            $('#error_room_introduce').html('Please choose room.');
                        } else {
                            $.post("include/function_ajax.php", {house_id: house_id, room_id: room_id, introduce_house_content: house_description,
                                client_id: client_id, order_id: order_id, action: 'customer', task: 'introduce'},
                            function(result) {
                                var json = $.parseJSON(result);
                                if (json.id != "")
                                    alert('Saved');
                                else if (json.id == "")
                                    $('#error_house').html('This house is introduced. Please choose other house to introduce !!!');
                            });
                        }
                    } else if ($(this).attr('class') == 'active' && $(this).attr('id') == 'contract') {
                        var contract_name = $('#contract_name').val();
                        var contract_cost = $('#contract_cost').val();
                        var contract_key_money = $('#contract_key_money').val();
                        var contract_condition = $('#contract_condition').val();
                        var contract_valuation = $('#contract_valuation').val();
                        var contract_signature_day = $('#contract_signature_day').val();

                        var contract_handover_day = $('#contract_handover_day').val();
                        var contract_period_from = $('#contract_period_from').val();
                        var contract_period_to = $('#contract_period_to').val();
                        var contract_deposit_1 = $('#contract_deposit_1').val();
                        var contract_deposit_2 = $('#contract_deposit_2').val();
                        var contract_application_date = $('#contract_application_date').val();

                        if ($('#contract_cancel').is(':checked'))
                            var contract_cancel = 1;
                        else
                            var contract_cancel = 0;

                        if ($('#contract_application').is(':checked'))
                            var contract_application = 1;
                        else
                            var contract_application = 0;

                        var contract_total = $('#contract_total').val();

                        var client_id = $('#client_id').val();
                        var order_id = $('#order_id').val();
                        var label = new Array();
                        var plus_money = new Array();
                        $("input[name^='contract_lable_money']").each(function() {
                            label.push($(this).val());
                        });

                        $("input[name^='contract_plus_money']").each(function() {
                            plus_money.push($(this).val());
                        });

                        $.post("include/function_ajax.php", {contract_name: contract_name, contract_cost: contract_cost, contract_key_money: contract_key_money,
                            contract_condition: contract_condition, contract_valuation: contract_valuation, contract_signature_day: contract_signature_day, contract_handover_day: contract_handover_day,
                            contract_period_from: contract_period_from, contract_period_to: contract_period_to, contract_deposit_1: contract_deposit_1, contract_deposit_2: contract_deposit_2,
                            contract_cancel: contract_cancel, contract_total: contract_total, contract_application: contract_application, contract_application_date: contract_application_date, label: label, plus_money: plus_money,
                            client_id: client_id, order_id: order_id, action: 'customer', task: 'contract'},
                        function(result) {
                            var json = $.parseJSON(result);
                            if (json.id != "")
                                alert('Saved');
                            else if (json.id == "")
                                alert("Updated");
                        });
                    }
                });
            });
            $('#done').click(function() {
                showloadgif();
                window.location.href = "create_order.php";
            });
        });
        function getDivClass(title) {
            var flag = false;
            $('#client_detail').find('div').each(function() {
                if ($(this).attr('class') == 'active' && $(this).attr('id') == title) {
                    flag = true;
                }
            });
            if (flag)
                return 1;
            else
                return 0;
        }
        function selectCustomer(id) {
            $.post("include/function_ajax.php", {id: id, action: 'customer', task: 'selectCustomer'},
            function(result) {
                if (result != "") {

                    /*reset form*/
                    $('#cus_id').val('');
                    $('#log_time_call').val('');
                    $('#log_time_arrive_company').val('');
                    $('#log_time_mail').val('');
                    $('#log_comment').val('');
                    $('#log_date_appointment_from').val('');
                    $('#log_date_appointment_to').val('');
                    $('#log_payment_date_appointment_from').val('');
                    $('#log_payment_date_appointment_to').val('');
                    $('input[name="log_status_appointment"]').attr('checked', "");
                    $('input[name="log_payment_appointment_status"]').attr('checked', "");
                    $('input[name="log_payment_appointment_report"]').attr('checked', "");
                    $('#log_tel').attr('checked', "");
                    $('#log_tel_status').attr('checked', "");
                    $('#log_mail').attr('checked', "");
                    $('#log_mail_status').attr('checked', "");
                    $('#log_contact_head_office').attr('checked', "");
                    $('#log_shop_sign').attr('checked', "");
                    $('#log_local_sign').attr('checked', "");
                    $('#log_introduction').attr('checked', "");
                    $('#log_flyer').attr('checked', "");
                    $('#log_line').attr('checked', "");
                    $('#log_revisit').val('');
                    $('#source_id').each(function(e) {
                        $('option').removeAttr('selected');
                    });
                    $('#aspirations_type_house').val('');
                    $('#aspirations_type_room').val('');
                    $('#aspirations_build_time').val('');
                    $('#aspirations_area').val('');
                    $('#aspirations_size').val('');
                    $('#aspirations_rent_cost').val('');
                    $('#aspirations_comment').val('');
                    $('#contract_name').val('');
                    $('#contract_cost').val('');
                    $('#contract_plus_money').val('');
                    $('#contract_key_money').val('');
                    $('#contract_condition').val('');
                    $('#contract_valuation').val('');
                    $('#contract_signature_day').val('');
                    $('#contract_handover_day').val('');
                    $('#contract_period_from').val('');
                    $('#contract_period_to').val('');
                    $('#contract_deposit_1').val('');
                    $('#contract_deposit_2').val('');
                    $('#contract_cancel').val('');
                    $('#contract_total').val('');
                    var json = $.parseJSON(result);
                    $('#client_name').val(json.client_name);
                    $('#client_birthday').val(json.client_birthday);
                    $('#client_email').val(json.client_email);
                    $('#client_phone').val(json.client_phone);
                    $('#gender').val(json.gender);
                    $('#client_address').val(json.client_address);

                    $('#city_cus').val(json.city_id);
                    $('#district_cus').val(json.district_id);
                    $('#street_cus').val(json.street_id);
                    $('#ward_cus').val(json.ward_id);
                    $('#city_id').find('option').each(function() {
                        if ($(this).val() == json.city_id) {
                            $(this).attr('selected', 'selected');
                        }
                    });

                    $('#client_occupation').val(json.client_occupation);
                    $('#client_company').val(json.client_company);
                    $('#client_income').val(json.client_income);
                    $('#client_room_type').val(json.client_room_type);
                    $('#client_rent').val(json.client_rent);
                    $('#client_reason_change').val(json.client_reason_change);
                    $('#client_time_change').val(json.client_time_change);
                    $('#client_resident_name').val(json.client_resident_name);
                    $('#client_resident_phone').val(json.client_resident_phone);
                    //get information order, history, aspirations and contract
                    // if(selectClient())
                    $('#city_cus').change();
                }
            });
        }

        function get_room(house_id, room_id) {
            $('#error_room').html("");
            $.post("include/function_ajax.php", {house_id: house_id, room_id: room_id, action: 'create_order', task: 'getRoomContent'},
            function(result) {
                if (result) {
                    $('#room_id').empty();
                    $('#room_id').html(result);
                } else {
                    $('#room_id').empty();
                    $('#house_description').html("");
                    if (house_id)
                        $('#error_room').html("This house haven't been room yet");
                }
            });
        }
    </script>
{/literal}
{if $error|@count gt 0}
    {foreach from=$error item=val}
        <div class="error">{$val}</div>
    {/foreach}
{/if}
<!--
{*step1   *}
{if $step eq 1}
    <form action="create_order.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">      
            <tr>
                <td class="form1">
                    Select Sources:
                </td>
                <td class="form2">
                    <select id="broker_id" name="broker_id" style="height:26px; width: 251px;">
                        <option value=""></option>
    {foreach from=$brokers key=k item=val}
        <option value="{$val.id}"{if $val.id eq $broker_id}selected{/if} >{$val.broker_company_name}</option>                  
    {/foreach}                                                   
</select>
</td>
</tr>
<tr>
<td class='form1'>&nbsp;</td>
<td class='form2'>
<div style="margin-top:10px">
    <input type='submit' class='btn-signup' value='Next' id="submit" name="submit" style="width: 100px;"onclick="showloadgif()"/>&nbsp;  
    <input type="hidden" id="step" name="step" value="2"/>                        
</div>
</td>
</tr>
</table>
</form>
{/if}
-->
{*step2*}                       
{if $step eq 2}    

    <form action="create_order.php" method="post">
        <div class="title"><label >Input house information</label></div>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">      
            <tr>
                <td class="form1">Filter Broker</td>
                <td class="form2"><input type="text" id="filter_broker" name="filter_broker" value="" placeholder="Enter broker name to filter for selection broker" style="height:26px; width: 300px;"/>
                </td>
            </tr>
            <tr>       
                <td class='form1'>Select Broker Company: </td>
                <td class='form2'>
                    <select id="broker_id" name="broker_id" style="height:26px; width: 300px;">
                        <option value=""></option>
                        {foreach from=$brokers item=broker}
                            <option value="{$broker.id}" {if $broker.id eq $broker_id}selected="selected"{/if}>{$broker.broker_company_name}</option>        
                        {/foreach}
                    </select>
                    <div id="error_broker" class="error"></div>
                </td>
            </tr> 
            <tr> 
                {assign var=broker_link value='If not broker company that you want. You can add new broker company by link <a href="./create_broker_company.php">Create Broker</a>'}
                <td colspan="2" nowrap><div>{$broker_link|wordwrap:70:"<br />\n"}</div></td>
            </tr>            
            <tr>
                <td class="form1">
                    Assign
                </td>

                <td class='form2'>
                    <select id="staff_id" name="staff_id" style="height:26px; width: 300px;">
                        <option value=""></option>
                        {foreach from=$users item=user}
                            <option value="{$user.id}"{if $user.id eq $staff_id}selected="selected"{/if}>{$user.user_fname} {$user.user_lname}</option>        
                        {/foreach}
                    </select><div id="error_staff" class="error"></div>

                </td>
            </tr>
            <tr>
                <td class="form1">Filter House</td>
                <td class="form2"><input type="text" id="search" name="search" value="" placeholder="Enter house name to filter for selection house" style="height:26px; width: 300px;"/>
                </td>
            </tr>
            <tr>            
                <td class='form1'>Select House: </td>
                <td class='form2'>
                    <select id="house_id" name="house_id" style="height:26px; width: 300px;">
                        <option value=""></option>
                        {foreach from=$houses item=house}
                            <option value="{$house.id}"{if $house.id eq $house_id}selected="selected"{/if}>{$house.house_name}</option>        
                        {/foreach}
                    </select><div id="error_house" class="error"></div>
                </td>
            </tr>
            <tr>            
                <td class='form1'>Description House: </td>
                <td class='form2'><textarea style="width: 340px;height: 129px;" disabled="1" id="house_description"></textarea></td>
            </tr>
            <tr>            
                <td colspan="2"><div>If not house that you want. You can add new house by link <a href="./create_house.php">Create House</a></div></td>
            </tr>

            <tr>            
                <td class='form1'>Select Room: </td>
                <td class='form2'><select id="room_id" name="room_id" style="height:26px; width: 300px;">
                        <option value=""></option>

                    </select><div id="error_room" class="error"></div>
                </td>
            </tr>
            <!--order part-->
            <tr>            
                <td class='form1'>Order name: </td>
                <td class='form2'><input type='text' id="order_name" name="order_name" value="{$order_name}"style="height: 26px; width: 300px;"/><div id="error_order_name" class="error"></div></td>
            </tr>
            <tr>            
                <td class='form1'>Price: </td>
                <td class='form2'><input type='text' id="order_rent_cost" name="order_rent_cost" value="{$order_rent_cost}"style="height: 26px; width: 300px;"/></td>
            </tr>
            <tr>            
                <td class='form1'>Comment: </td>
                <td class='form2'><input type='text' id="order_comment" name="order_comment" value="{$order_comment}"style="height: 26px; width: 300px;"/></td>
            </tr>

            <!--end order-->
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-signup' value='Next' id="submit" name="submit" style="width: 100px;"/>&nbsp;                          
                        <input type="hidden" id="step" name="step" value="verify"/>     
                        <input type="hidden" id="yoke_muscle" name="yoke_muscle"/>
                        <input type="hidden" id="room_bk" name="room_bk" value="{$room_id}"/>
                    </div>
                </td>
            </tr>
        </table>
    </form>
    {literal}
        <script type="text/javascript">
            $(document).ready(function() {
                $('#filter_broker').keyup(function(e) {
                    var filter = $('#filter_broker').val();
                    $('#error_broker').html("");
                    //showloadgif();
                    $.post("include/function_ajax.php", {filter: filter, action: 'create_order', task: 'getBrokerFilter'},
                    function(result) {
                        if (result) {
                            //  hideloadgif();
                            $('#broker_id').empty();
                            $('#broker_id').html(result);
                            $('table').find('tr').css('display', '');
                            $('#yoke_muscle').click();
                        } else {
                            // hideloadgif();
                            $('#broker_id').empty();
                            $('#broker_id').empty();
                            //$('#house_description').html("");
                            $('table').find('tr').css('display', 'none');
                            $('table').find('tr:first-child').css('display', '');
                            $('table').find('tr:nth-child(2)').css('display', '');
                            $('table').find('tr:nth-child(3)').css('display', '');
                            $('table').find('tr:last-child').css('display', '');
                            $('#error_broker').html("No any broker company for your keyword");
                        }
                    });
                });
                $('#yoke_muscle').click(function() {
                    var broker_id = $('#broker_id').val();
                    //$('#submit').attr('disabled', false);
                    // $("#submit").css('color', '#fff');
                    $('#room_id').html('');
                    $('#search').val('');
                    $('error_house').html('');
                    $('error_room').html('');
                    $.post('include/function_ajax.php', {broker_id: broker_id, action: 'create_order', task: 'getHouseList'},
                    function(result) {
                        if (result) {
                            $('#house_id').empty();
                            $('#house_id').html(result);
                        } else {
                            $('#house_id').empty();
                            $('#error_house').html('No any house for your keyword');
                        }

                    });
                });


                $('#submit').click(function(e) {
                    $('#error_broker').html("");
                    $('#error_staff').html("");
                    $('#error_house').html("");
                    $('#error_order_name').html("");
                    $('#error_room').html("");
                    var staff_id = $('#staff_id').val();
                    var broker_id = $('#broker_id').val();
                    var house_id = $('#house_id').val();
                    var order_name = $('#order_name').val();
                    var room_id = $('#room_id').val();
                    if (broker_id == "" || broker_id == null) {
                        $('#error_broker').html('Please choose broker company.');
                        e.preventDefault();
                        return false;
                    } else if (staff_id == "" || staff_id == null) {
                        $('#error_staff').html('Please choose assign.');
                        e.preventDefault();
                        return false;
                    } else if (house_id == "" || house_id == null) {
                        $('#error_house').html('Please choose house.');
                        e.preventDefault();
                        return false;
                    } else if (room_id == "" || room_id == null) {
                        $('#error_room').html('Please choose room.');
                        e.preventDefault();
                        return false;
                    } else if (order_name == "" || order_name == null) {
                        $('#error_order_name').html('Order name is required.');
                        e.preventDefault();
                        return false;
                    } else {
                        showloadgif();
                        $('#submit').submit();
                    }

                });
                $('#broker_id').change(function() {
                    //active form
                    $('#error_broker').html("");
                    $('#error_house').html("");
                    var broker_id = $('#broker_id').val();
                    if (broker_id) {
                        $('table').find('tr').css('display', '');
                        $('#yoke_muscle').click();
                    } else {
                        $('table').find('tr').css('display', 'none');
                        $('table').find('tr:first-child').css('display', '');
                        $('table').find('tr:nth-child(2)').css('display', '');
                        $('table').find('tr:nth-child(3)').css('display', '');
                        $('table').find('tr:last-child').css('display', '');
                    }
                });
                var broker_id = $('#broker_id').val();
                if (broker_id) {
                    $('table').find('tr').css('display', '');
                } else {
                    $('table').find('tr').css('display', 'none');
                    $('table').find('tr:first-child').css('display', '');
                    $('table').find('tr:nth-child(2)').css('display', '');
                    $('table').find('tr:nth-child(3)').css('display', '');
                    $('table').find('tr:last-child').css('display', '');
                }
                var house_id = $('#house_id').val();

                // var room_id ={/literal}{if $room_id ne ""}{$room_id}{else}0{/if}{';'}{literal}
                var room_id = $('#room_bk').val();

                $.post('include/function_ajax.php', {house_id: house_id, action: 'create_order', task: 'getContentHouse'},
                function(result) {
                    var json = $.parseJSON(result);
                    $('#house_description').html(json.house_description);
                    get_room(house_id, room_id);
                });
            });
        </script>
    {/literal}
{/if}

{*step verify*}
{if $step eq "verify"}
    <form action="create_order.php" method="post">
        <div class="title"><label ><h2>Verify information</h2></label></div>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>Source:</td>
                <td class='form2'>{$brokers.broker_company_name}</td>
            </tr>
            <tr>
                <td class='form1' nowrap>Staff assigned:</td>
                <td class='form2'> {$staffs.user_fname} {$staffs.user_lname}</td>
            </tr>
            <tr>
                <td class='form1' nowrap>House name:</td>
                <td class='form2'>{$houses.house_name}</td>
            </tr>
            <tr>
                <td class='form1' nowrap>Room Number:</td>
                <td class='form2'>{$room_id}</td>
            </tr>
            <tr>
                <td class='form1'>Desctiption:</td>
                <td class='form2'>{$houses.house_description}</td>
            </tr>
            <tr>
                <td class='form1'>Order name:</td>
                <td class='form2'>{$order_name}</td>
            </tr>
            <tr>
                <td class='form1'>Price:</td>
                <td class='form2'>{$order_rent_cost}</td>
            </tr>
            <tr>
                <td class='form1'>Comment:</td>
                <td class='form2'>{$order_comment}</td>
            </tr>
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type="submit" class='btn-signup' value="Registry" id="registry" name="registry" style="width: 100px;"/>&nbsp; 
                        <input type="button" class='btn-signup' value="Later" id="later" name="later"style="width: 100px;margin: 0px 10px 0px 10px;"/>
                        <input type="button" class='btn-signup' value="Cancel" id="cancel" name="cancel"style="width: 100px;"/>
                        <input type='button' class='btn-signup' value='Back' id="back" name="back" style="width: 100px;float: right;margin-right: 1%;"/>&nbsp; 
                        <input type="hidden" id="create_id" name="create_id" value="{$staffs.id}"/>
                        <input type="hidden" id="staff_id" name="staff_id" value="{$staffs.id}"/>
                        <input type="hidden" id="house_id" name="house_id" value="{$houses.id}"/>
                        <input type="hidden" id="room_id" name="room_id" value="{$room_id}"/> 
                        <input type="hidden" id="broker_id" name="broker_id" value="{$brokers.id}"/>
                        <input type="hidden" id="order_name" name="order_name" value="{$order_name}"/>
                        <input type="hidden" id="order_rent_cost" name="order_rent_cost" value="{$order_rent_cost}"/>    
                        <input type="hidden" id="order_comment" name="order_comment" value="{$order_comment}"/>
                        <input type="hidden" id="step" name="step" value="registry"/>
                    </div>
                </td>
            </tr>
        </table>
    </form>
    {literal}
        <script type="text/javascript">
            $(document).ready(function() {
                $('#sidebar_container').css('display', 'none');
                $('#later').click(function() {
                    window.location.href = "create_order.php";
                });
            });</script>
        {/literal}
    {/if}

{if $step eq "registry"}
    {if $errorHouseExist ne ""}

        <div class="error">Don't refesh browser if not neccessary !!!</div>

    {/if}
    <form action="create_order.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="60%">
            <tr>
                <td>Filter customer</td>
                <td><input type="text" id="filter" name="filter"value="{$filter}" style="height: 26px; width: 300px;" placeholder="Type name of customer"/>
                    <span>
                        <input type='submit' class='btn-search' value='Submit' id="search" name="submit"/>&nbsp;                     
                    </span>
                </td>
            <input type="hidden" id="step" name="step" value="registry"/><div style="float: right;"><input type="button" value="Done" id="done" name="done"class='btn-search'/></div>
            </tr>
        </table>
    </form>

    <div style="margin-bottom:10px;">
        <center>
            Page:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="create_order.php?step='registry'&filter={$filter}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
    <div id="customer">
        <ul>
            <li class="even">No</li>
            <li class="even">Name</li>
            <li class="even">Birthday</li>
            <li class="even">Address</li>
            <li class="even">Phone Number</li>
        </ul>

        {foreach from=$customers key=k item=item}
            <ul>
                <li {if $item@iteration is div by 2}class="odd"{/if} onclick="selectCustomer({$item.id})">{$k+1}</li>
                <li {if $item@iteration is div by 2}class="odd"{/if} onclick="selectCustomer({$item.id})">{$item.client_name}</li>
                <li {if $item@iteration is div by 2}class="odd"{/if} onclick="selectCustomer({$item.id})">{$item.client_birthday}</li>
                <li {if $item@iteration is div by 2}class="odd"{/if} onclick="selectCustomer({$item.id})">{$item.client_address|truncate:30}</li>
                <li {if $item@iteration is div by 2}class="odd"{/if} onclick="selectCustomer({$item.id})">{$item.client_phone}</li>
            </ul>
        {/foreach}

    </div>
    <div style="background-color: #F1F5FE; width: 100%;height:25px; text-align: center;font-size: 1.8em;line-height: 25px; margin-top: 50px; ">Customer Information</div>
    <div id="client_info">
        <ul>
            <li class="select_menu" title="basic">Basic Info</li>
            <li class="noselect_menu" title="detail">Detail</li>
            <li class="noselect_menu" title="history">History</li>
            <li class="noselect_menu" title="aspirations">Aspirations</li>
            <li class="noselect_menu" title="introduce">Introduce</li>
            <li class="noselect_menu" title="contract">Contract</li>
        </ul>
    </div>
    <div id="client_detail">

        <div id="basic"class="active">
            <form action="create_order.php" method="post">        
                <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                    <tr>
                        <td class='form1'>Name:</td>
                        <td class='form2'><input type="text" id="client_name" name="client_name" value="{$client_name}"style="height: 26px; width: 300px;"/></td>
                        <td class='form1' nowrap>Birthday:</td>
                        <td class='form2'> <input type='text' id="client_birthday" name="client_birthday" value="{$client_birthday}"style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Email:</td>
                        <td class='form2'><input type="text" id="client_email" name="client_email" value="{$client_email}" style="height: 26px; width: 300px;"/></td>
                        <td class='form1'>Phone number:</td>
                        <td class='form2'> <input type='text' id="client_phone" name="client_phone" value="{$client_phone}" style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>&nbsp;</td>
                        <td class='form2'></td>
                        <td class='form1' nowrap>Fax:</td>
                        <td class='form2'> <input type='text' id="client_fax" name="client_fax" value="{$client_fax}" style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>&nbsp;</td>
                        <td class='form2' colspan="3">
                            <div id="error_validate" class="error"></div>
                            <div style="margin-top:10px;text-align: center;">
                                <input type="submit" class='btn-signup' value="Save" id="save" name="save" style="width: 100px;"/>&nbsp; 
                                <input type="hidden" id="task" name="task" value="basic"/>
                                <input type="hidden" id="step" name="step" value="registry"/> 
                                <input type="hidden" id="client_id" name="client_id" value="{$client_id}"/>
                                <input type="hidden" id="order_id" name="order_id" value="{$order_id}"/>
                            </div>                        
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="detail"class="inactive">
            <form action="create_order.php" method="post">        
                <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                    <tr>
                        <td class='form1'>Gender: </td>
                        <td class='form2'>
                            <select id="gender"name="gender" style="height:26px; width: 300px;">
                                <option value="male" {if $gender eq "male"}selected{/if}>Male</option>
                                <option value="female"{if $gender eq "female"}selected{/if}>Female</option>
                                <option value="other" {if $gender eq "other"}selected{/if}>Other</option>
                            </select>
                        </td>
                        <td class='form1' nowrap>Numer Address: <span class="required">*</span></td>
                        <td class='form2'> <input type='text' id="client_address" name="client_address" value="{$client_address}" style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>City:  <span class="required">*</span></td>
                        <td class='form2'><select id="city_id" name="city_id" style="height:26px; width: 300px;">
                                <option value=""></option>
                                {foreach from=$cities item=city}
                                    <option value="{$city.id}" {if $city.id eq $city_id}selected="selected"{/if}>{$city.city_name}</option>        
                                {/foreach}
                            </select><div id="error_city_id" class="error"></div>
                        </td>
                        <td class='form1'>District:  <span class="required">*</span></td>
                        <td class='form2'><select id="district_id" name="district_id" style="height:26px; width: 300px;">                       

                            </select><div id="error_district_id" class="error"></div>
                        </td>
                    </tr>      

                    <tr>
                        <td class='form1'>Street:  <span class="required">*</span></td>
                        <td class='form2'><select id="street_id" name="street_id" style="height:26px; width: 300px;">

                            </select><div id="error_street_id" class="error"></div>
                        </td>
                        <td class='form1'>Ward:  <span class="required">*</span></td>
                        <td class='form2'><select id="ward_id" name="ward_id" style="height:26px; width: 300px;">

                            </select><div id="error_ward_id" class="error"></div>
                        </td>
                    </tr>

                    <tr>
                        <td class='form1'>Occupation:</td>
                        <td class='form2'><input type="text" id="client_occupation" name="client_occupation" value="{$client_occupation}" style="height: 26px; width: 300px;"/></td>
                        <td class='form1' nowrap>Company:</td>
                        <td class='form2'> <input type='text' id="client_company" name="client_company"  value="{$client_company}" style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Income:</td>
                        <td class='form2'><input type="text" id="client_income" name="client_income" value="{$client_income}" style="height: 26px; width: 300px;"/></td>
                        <td class='form1' nowrap>Room type:</td>
                        <td class='form2'> <input type='text' id="client_room_type" name="client_room_type" value="{$client_room_type}"style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Rent current :</td>
                        <td class='form2'><input type="text" id="client_rent" name="client_rent" value="{$client_rent}"style="height: 26px; width: 300px;"/></td>
                        <td class='form1' nowrap>Reason change:</td>
                        <td class='form2'> <input type='text' id="client_reason_change" name="client_reason_change" value="{$client_reason_change}"style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Date change :</td>
                        <td class='form2'><input type="text" id="client_time_change" name="client_time_change" value="{$client_time_change}"style="height: 26px; width: 300px;"/></td>
                        <td class='form1' nowrap>Client resident name</td>
                        <td class='form2'><input type="text" id="client_resident_name" name="client_resident_name" value="{$client_resident_name}"style="height: 26px; width: 300px;"/> </td>
                    </tr>
                    <tr>
                        <td class='form1'>Client resident phone :</td>
                        <td class='form2'><input type="text" id="client_resident_phone" name="client_resident_phone" value="{$client_resident_phone}"style="height: 26px; width: 300px;"/></td>
                        <td class='form1' nowrap></td>
                        <td class='form2'> </td>
                    </tr>
                    <tr>
                        <td class='form1'>&nbsp;</td>
                        <td class='form2' colspan="3">
                            <div id="error_validate" class="error"></div>
                            <div style="margin-top:10px;text-align: center;">
                                <input type="button" class='btn-signup' value="Save" id="save" name="save" style="width: 100px;"/>&nbsp; 
                                <input type="hidden" id="task" name="task" value="detail"/>
                                <input type="hidden" id="step" name="step" value="registry"/> 
                                <input type="hidden" id="city_cus" name="city_cus" value=""/> 
                                <input type="hidden" id="district_cus" name="district_cus" value=""/> 
                                <input type="hidden" id="street_cus" name="street_cus" value=""/> 
                                <input type="hidden" id="ward_cus" name="ward_cus" value=""/> 
                                <input type="hidden" id="client_id" name="client_id" value="{$client_id}"/>
                                <input type="hidden" id="order_id" name="order_id" value="{$order_id}"/>
                            </div>                        
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="history"class="inactive">
            <form action="create_order.php" method="post">        
                <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                    <tr>
                        <td class='form1'>Time call: </td>
                        <td class='form2'>
                            <input type='text' id="log_time_call" name="log_time_call" value="{$log_time_call}"style="height: 26px; width: 215px;"/>
                        </td>
                        <td class='form1' nowrap>Time arrive:</td>
                        <td class='form2'> <input type='text' id="log_time_arrive_company" name="log_time_arrive_company" value="{$log_time_arrive_company}"style="height: 26px; width: 215px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Time send email: </td>
                        <td class='form2'>
                            <input type='text' id="log_time_mail" name="log_time_mail" value="{$log_time_mail}"style="height: 26px; width: 215px;"/>
                        </td>
                        <td class='form1' nowrap>Comment:</td>
                        <td class='form2'> <input type='text' id="log_comment" name="log_comment" value="{$log_comment}"style="height: 26px; width: 215px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'nowrap>Date appointment from:</td>
                        <td class='form2'><input type="text" id="log_date_appointment_from" name="log_date_appointment_from" value="{$log_date_appointment_from}"style="height: 26px; width: 215px;"/></td>
                        <td class='form1' nowrap>Appointment status:</td>
                        <td class='form2'>
                            <input type='radio' id="log_status_appointment_yes" name="log_status_appointment" value="1" {if $log_status_appointment eq '1'}checked="checked" {/if}/><label for="log_status_appointment_yes">Yes</label> &nbsp; &nbsp; 
                            <input type='radio' id="log_status_appointment_no" name="log_status_appointment" value="0" {if $log_status_appointment eq '0'}checked="checked" {/if}/><label for="log_status_appointment_no">No</label>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>Date appointment to: </td>
                        <td class='form2'>
                            <input type='text' id="log_date_appointment_to" name="log_date_appointment_to" value="{$log_date_appointment_to}"style="height: 26px; width: 215px;"/>
                        </td>
                        <td class='form1' nowrap></td>
                        <td class='form2'></td>
                    </tr>
                    <tr>
                        <td class='form1' nowrap>Payment date appointment from:</td>
                        <td class='form2'> <input type='text' id="log_payment_date_appointment_from" name="log_payment_date_appointment_from" value="{$log_payment_date_appointment_from}"style="height: 26px; width: 215px;"/></td>
                        <td class='form1' nowrap>Payment appointment status:</td>
                        <td class='form2'>
                            <input type='radio' id="log_payment_appointment_status_yes" name="log_payment_appointment_status" value="1" {if $log_payment_appointment_status eq '1'}checked="checked" {/if}/><label for="log_payment_appointment_status_yes">Yes</label> &nbsp; &nbsp; 
                            <input type='radio' id="log_payment_appointment_status_no" name="log_payment_appointment_status" value="0" {if $log_payment_appointment_status eq '0'}checked="checked" {/if}/><label for="log_payment_appointment_status_no">No</label>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>Payment date appointment to: </td>
                        <td class='form2'>
                            <input type='text' id="log_payment_date_appointment_to" name="log_payment_date_appointment_to" value="{$log_payment_date_appointment_to}"style="height: 26px; width: 215px;"/>
                        </td>
                        <td class='form1' nowrap>Payment appointment report:</td>
                        <td class='form2'>
                            <input type='radio' id="log_payment_appointment_report_yes" name="log_payment_appointment_report" value="1" {if $log_payment_appointment_report eq '1'}checked="checked" {/if}/><label for="log_payment_appointment_report_yes">Yes</label> &nbsp; &nbsp; 
                            <input type='radio' id="log_payment_appointment_report_no" name="log_payment_appointment_report" value="0" {if $log_payment_appointment_report eq '0'}checked="checked" {/if}/><label for="log_payment_appointment_report_no">No</label>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>Select Source:</td>
                        <td class='form2'><select id="source_id" name="source_id" style="height:26px; width: 215px;">
                                <option value=""></option>
                                {foreach from=$sources item=source}
                                    <option value="{$source.id}" {if $source_id eq $source.id} selected="selected"{/if}>{$source.source_name}</option>        
                                {/foreach}
                            </select>
                        </td>
                        <td class='form1' nowrap><span id="error_source"></span></td>
                        <td class='form2'></td>
                    </tr>
                    <tr>
                        <td class='form1'>Contact by tel:</td>
                        <td class='form2'><input type="checkbox" id="log_tel" name="log_tel" {if $log_tel eq '1'}checked="checked" {/if} style="height: 26px; width: 15px;"/></td>
                        <td class='form1' nowrap>Tel status:</td>
                        <td class='form2'> <input type='checkbox' id="log_tel_status" name="log_tel_status" {if $log_tel_status eq '1'}checked="checked" {/if}style="height: 26px; width: 15px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Contact by mail:</td>
                        <td class='form2'><input type="checkbox" id="log_mail" name="log_mail" {if $log_mail eq '1'}checked="checked" {/if}style="height: 26px; width: 15px;"/></td>
                        <td class='form1' nowrap>Mail status:</td>
                        <td class='form2'> <input type='checkbox' id="log_mail_status" name="log_mail_status"{if $log_mail_status eq '1'}checked="checked" {/if} style="height: 26px; width: 15px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Contact by head office :</td>
                        <td class='form2'><input type="checkbox" id="log_contact_head_office" name="log_contact_head_office" {if $log_contact_head_office eq '1'}checked="checked" {/if}style="height: 26px; width: 15px;"/></td>
                        <td class='form1' nowrap>Shop sign:</td>
                        <td class='form2'> <input type="checkbox" id="log_shop_sign" name="log_shop_sign"{if $log_shop_sign eq '1'}checked="checked" {/if} style="height: 26px; width: 15px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Local sign:</td>
                        <td class='form2'><input type="checkbox" id="log_local_sign" name="log_local_sign"{if $log_local_sign eq '1'}checked="checked" {/if} style="height: 26px; width: 15px;"/></td>
                        <td class='form1' nowrap>Introduction:</td>
                        <td class='form2'> <input type='checkbox' id="log_introduction" name="log_introduction" {if $log_introduction eq '1'}checked="checked" {/if}style="height: 26px; width: 15px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Flyer:</td>
                        <td class='form2'><input type="checkbox" id="log_flyer" name="log_flyer"{if $log_flyer eq '1'}checked="checked" {/if} style="height: 26px; width: 15px;"/></td>
                        <td class='form1' nowrap>Line:</td>
                        <td class='form2'> <input type='checkbox' id="log_line" name="log_line"{if $log_line eq '1'}checked="checked" {/if} style="height: 26px; width: 15px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Revisit: </td>
                        <td class='form2'>
                            <input type='text' id="log_revisit" name="log_revisit" value="{$log_revisit}"style="height: 26px; width: 215px;"/>
                        </td>
                        <td class='form1' nowrap><span id="error_revisit"></span></td>
                        <td class='form2'></td>
                    </tr>
                    <tr>
                        <td class='form1'>&nbsp;</td>
                        <td class='form2' colspan="3">
                            <div id="error_validate" class="error"></div>
                            <div style="margin-top:10px;text-align: center;">
                                <input type="button" class='btn-signup' value="Save" id="save" name="save" style="width: 100px;"/>&nbsp; 
                                <input type="hidden" id="task" name="task" value="history"/>
                                <input type="hidden" id="step" name="step" value="registry"/> 
                                <input type="hidden" id="client_id" name="client_id" value="{$client_id}"/>
                                <input type="hidden" id="order_id" name="order_id" value="{$order_id}"/>
                            </div>                        
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="aspirations" class="inactive">
            <form action="create_order.php" method="post">        
                <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                    <tr>
                        <td class='form1'>House type: </td>
                        <td class='form2'>
                            <input type='text' id="aspirations_type_house" name="aspirations_type_house" value="{$aspirations_type_house}" style="height: 26px; width: 300px;"/>
                        </td>
                        <td class='form1' nowrap>Room type:</td>
                        <td class='form2'> <input type='text' id="aspirations_type_room" name="aspirations_type_room" value="{$aspirations_type_room}"style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Build time:</td>
                        <td class='form2'><input type="text" id="aspirations_build_time" name="aspirations_build_time" value="{$aspirations_build_time}"style="height: 26px; width: 300px;"/></td>
                        <td class='form1' nowrap>Area:</td>
                        <td class='form2'> <input type='text' id="aspirations_area" name="aspirations_area" value="{$aspirations_area}"style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Size:</td>
                        <td class='form2'><input type="text" id="aspirations_size" name="aspirations_size"value="{$aspirations_size}" style="height: 26px; width: 300px;"/></td>
                        <td class='form1' nowrap>Price:</td>
                        <td class='form2'> <input type='text' id="aspirations_rent_cost" name="aspirations_rent_cost"value="{$aspirations_rent_cost}" style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Comment:</td>
                        <td class='form2'><input type="text" id="aspirations_comment" name="aspirations_comment" value="{$aspirations_comment}"style="height: 26px; width: 300px;"/></td>
                        <td class='form1' nowrap></td>
                        <td class='form2'></td>
                    </tr>                
                    <tr>
                        <td class='form1'>&nbsp;</td>
                        <td class='form2' colspan="3">
                            <div style="margin-top:10px;text-align: center;">
                                <div id="error_validate" class="error"></div>
                                <input type="button" class='btn-signup' value="Save" id="save" name="save" style="width: 100px;"/>&nbsp; 
                                <input type="hidden" id="task" name="task" value="aspirations"/>
                                <input type="hidden" id="step" name="step" value="registry"/> 
                                <input type="hidden" id="client_id" name="client_id" value="{$client_id}"/>
                                <input type="hidden" id="order_id" name="order_id" value="{$order_id}"/>
                            </div>                        
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="introduce" class="inactive">
            <form action="create_order.php" method="post">            
                <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">      

                    <tr>
                        <td class="form1">Filter House</td>
                        <td class="form2"><input type="text" id="search" name="search" value="" placeholder="Enter house name to filter for selection house" style="height:26px; width: 300px;"/>
                        </td>
                    </tr>
                    <tr>            
                        <td class='form1'>Select House: </td>
                        <td class='form2'>
                            <select id="house_id" name="house_id" style="height:26px; width: 300px;">
                                <option value=""></option>
                                {foreach from=$houses item=house}
                                    <option value="{$house.id}" {if $house_id eq $house.id} selected="selected"{/if}>{$house.house_name}</option>        
                                {/foreach}
                            </select><div id="error_house" class="error"></div>
                        </td>
                    </tr>
                    <tr>            
                        <td class='form1'>Description House: </td>
                        <td class='form2'><textarea style="width: 340px;height: 129px;" disabled="1" id="house_description" >{$introduce_house_content}</textarea></td>
                    </tr>
                    <tr>            
                        <td colspan="2"><div>If not house that you want. You can add new house by link <a href="./create_house.php">Create House</a></div></td>
                    </tr>
                    <tr>            
                        <td class='form1'>Select Room: </td>
                        <td class='form2'><select id="room_id" name="room_id" style="height:26px; width: 300px;">
                                <option value=""></option>

                            </select><div id="error_room_introduce" class="error"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>&nbsp;</td>
                        <td class='form2'>
                            <div id="error_validate" class="error"></div>
                            <div style="margin-top:10px">
                                <input type="button" class='btn-signup' value="Save" id="save" name="save" style="width: 100px;"/>&nbsp;  
                                <input type="hidden" id="task" name="task" value="introduce"/>
                                <input type="hidden" id="step" name="step" value="registry"/>  
                                <input type="hidden" id="client_id" name="client_id" value="{$client_id}"/>
                                <input type="hidden" id="order_id" name="order_id" value="{$order_id}"/>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="contract" class="inactive">
            <form action="create_order.php" method="post">        
                <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%" id="contract_plus">
                    <tr>
                        <td class='form1'>Name: </td>
                        <td class='form2'>
                            <input type='text' id="contract_name" name="contract_name" value="{$contract_name}"style="height: 26px; width: 300px;"/>
                        </td>
                        <td class='form1' nowrap>Cost:</td>
                        <td class='form2'> <input type='text' id="contract_cost" name="contract_cost" value="{$contract_cost}"style="height: 26px; width: 300px;"/></td>
                    </tr>                    

                    <tr>                    
                        <td class='form1'>Key fee:</td>
                        <td class='form2'><input type="text" id="contract_key_money" name="contract_key_money" value="{$contract_key_money}"style="height: 26px; width: 300px;"/>
                            <select id="contract_key_money_unit" style="width: 2%;height:3%;position: absolute;">
                                <option value="円">円</option>
                                <option value="月">月</option>
                            </select>
                        </td>
                        <td class='form1'></td>
                        <td class='form2'></td>                                           
                    </tr>
                    <tr>                    
                        <td class='form1' nowrap>Condition:</td>
                        <td class='form2'><textarea style="width: 300px;height: 129px;"  id="contract_condition"name="contract_condition">{$contract_condition}</textarea></td>
                        <td class='form1' nowrap>Valuation:</td>
                        <td class='form2'><textarea style="width: 300px;height: 129px;"  id="contract_valuation"name="contract_valuation">{$contract_valuation}</textarea></td>
                    </tr>
                    <tr>
                        <td class='form1'>Signature day:</td>
                        <td class='form2'><input type="text" id="contract_signature_day" name="contract_signature_day" value="{$contract_signature_day}"style="height: 26px; width: 300px;"/></td>
                        <td class='form1' nowrap>Handover day:</td>
                        <td class='form2'><input type="text" id="contract_handover_day" name="contract_handover_day"value="{$contract_handover_day}" style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Period from:</td>
                        <td class='form2'><input type="text" id="contract_period_from" name="contract_period_from"value="{$contract_period_from}" style="height: 26px; width: 300px;"/></td>
                        <td class='form1' nowrap>Period to:</td>
                        <td class='form2'><input type="text" id="contract_period_to" name="contract_period_to" value="{$contract_period_to}"style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Deposit 1:</td>
                        <td class='form2'><input type="text" id="contract_deposit_1" name="contract_deposit_1" value="{$contract_deposit_1}"style="height: 26px; width: 300px;"/></td>
                        <td class='form1' nowrap>Deposit 2:</td>
                        <td class='form2'><input type="text" id="contract_deposit_2" name="contract_deposit_2"value="{$contract_deposit_2}" style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Total:</td>
                        <td class='form2'><input type="text" id="contract_total" name="contract_total" disabled="1" value="{$contract_total}"style="height: 26px; width: 300px;"/></td>
                        <td class='form1' nowrap>Cancel:</td>
                        <td class='form2'><input type="checkbox" id="contract_cancel" name="contract_cancel" {if $contract_cancel eq '1'}checked="checked"{/if}/></td>
                    </tr>
                    <tr>
                        <td class='form1'>Application:</td>
                        <td class='form2'><input type="checkbox" id="contract_application" name="contract_application" {if $contract_application eq '1'}checked="checked"{/if}/></td>
                        <td class='form1' nowrap>Application Date:</td>
                        <td class='form2'><input type="text" id="contract_application_date" name="contract_application_date" value="{$contract_application_date}"style="height: 26px; width: 300px;"/></td>
                    </tr>
                    <tr>                    
                        <td class='form1'></td>
                        <td class='form2'>
                            <input type="button" id="add" class='btn-signup' name="add" value="Add plus fee" style="width: 140px;"/> 
                        </td>       
                        <td class='form1'></td>
                        <td class='form2'></td>   
                    </tr>
                    {foreach from=$plus_money key=k item=money}
                        <tr>
                            <td class='form1'>{$k} :</td>
                            <td class='form2'>
                                <input type='hidden' name='contract_lable_money[]' value="{$k}"/>
                                <input type='text' id='contract_plus_money' name='contract_plus_money[]' value="{$money}" style='height: 26px; width: 300px;'/>
                                <input type='button' id='remove' name='remove' class='btn-remove' value='remove' onClick='removePlus(this)' />
                            </td> 
                            <td class='form1'></td>
                            <td class='form2'></td> 
                        </tr>
                    {/foreach}
                    <tr>
                        <td class='form1'>&nbsp;</td>
                        <td class='form2' colspan="3">
                            <div id="error_validate" class="error"></div>
                            <div style="margin-top:10px;text-align: center;">
                                <input type="button" class='btn-signup' value="Save" id="save" name="save" style="width: 100px;"/>&nbsp; 
                                <input type="hidden" id="task" name="task" value="contract"/>
                                <input type="hidden" id="step" name="step" value="registry"/> 
                                <input type="hidden" id="client_id" name="client_id" value="{$client_id}"/>
                                <input type="hidden" id="order_id" name="order_id" value="{$order_id}"/>
                            </div>                        
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <input type="hidden" id="cus_id" name="cus_id" value="{$client_id}"/>
    </div>

    {literal}
        <style type="text/css">

            .active{
                float: left;
                width: 100%;
                display: block;
            }
            .inactive{
                float: left;
                width: 100%;
                display: none;
            }
            .content{
                width:100%;
                margin: 0;
                padding: 0;
            }

            #customer ul{
                width: 100%;
                border: 1px solid white;

            }

            #customer ul li:nth-child(1){
                float: left;
                background: transparent;
                padding: 0px;
                width: 10%;
                cursor: pointer;
            }
            #customer ul li:nth-child(2){
                float: left;
                background: transparent;
                padding: 0px;
                width: 20%;
                cursor: pointer;
            }
            #customer ul li:nth-child(3){
                float: left;
                background: transparent;
                padding: 0px;
                width: 20%;
                cursor: pointer;
            }
            #customer ul li:nth-child(4){
                float: left;
                background: transparent;
                padding: 0px;
                width: 35%;
                cursor: pointer;
            }
            #customer ul li:nth-child(5){
                float: left;
                background: transparent;
                padding: 0px;
                width: 15%;
                cursor: pointer;
            }
            #customer ul li.odd{
                background: silver;
            }
            #customer ul li.even{
                background:#D9D5CF; 
                border-bottom: 1px solid #617AAC;
            }
            #customer ul li:first-child{
                margin-left: 0;
            }
            #customer ul li:last-child{
                float: right;
            }
            #customer{
                width: 100%;
            }
            /////////////////////////client info//////////////////////////
            #client_info{
                width: 100%;
            }

            /*#client_info ul li{
                float: left;
                background: beige;
                padding: 0px;
                width: 16.65%;
                cursor: pointer;
                text-align: center;
            }*/
            #client_info ul li.select_menu{
                background: url(include/images/bg-btn-forget.gif) repeat-x;
                float: left;
                //background: beige;
                padding: 0px;
                width: 16.65%;
                cursor: pointer;
                text-align: center;
            }
            #client_info ul li.noselect_menu{
                float: left;
                background: beige;
                padding: 0px;
                width: 16.65%;
                cursor: pointer;
                text-align: center;
            }
            #client_info ul li:hover{

                background: url(include/images/bg-btn-forget.gif) repeat-x;

            }

        </style>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#sidebar_container').css('display', 'none');
                //var fieldCount = 1;
                $('#add').click(function() {
                    var label = prompt('which  plus do you want to add ?', '');
                    if (label != null && label != "" && label != 0) {
                        // fieldCount++;
                        $('#contract table tr:nth-last-child(2)').after("<tr><td class='form1'>" + label + " :</td><td class='form2'><input type='hidden' name='contract_lable_money[]' value='" + label + "'/><input type='text' id='contract_plus_money' name='contract_plus_money[]' value=''style='height: 26px; width: 300px;'/><input type='button' id='remove' name='remove' class='btn-remove' value='remove' onClick='removePlus(this)' /></td> <td class='form1'></td><td class='form2'></td> </tr>");
                    }
                });
                //Address
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

                                                    //clone 
                                                    //city
                                                    $('#city_cus').change(function(e) {
                                                        var city_id = $('#city_cus').val();
                                                        var district_id = $('#district_cus').val();

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
                                                                    $('#district_cus').change();
                                                                } else {
                                                                    $('#district_id').empty();
                                                                    $('#street_id').empty();
                                                                    $('#ward_id').empty();
                                                                }
                                                            });
                                                        }
                                                    });
                                                    //district
                                                    $('#district_cus').change(function(e) {
                                                        var district_id = $('#district_id').val();
                                                        var street_id = $('#street_cus').val();

                                                        if (district_id == "") {
                                                            $('#street_id').empty();
                                                            $('#ward_id').empty();
                                                        } else {
                                                            $.post("include/function_ajax.php", {district_id: district_id, street_id: street_id, action: 'create_house', task: 'getStreetList'},
                                                            function(result) {
                                                                if (result) {
                                                                    $('#street_id').empty();
                                                                    $('#street_id').html(result);
                                                                    $('#street_cus').change();
                                                                } else {
                                                                    $('#street_id').empty();
                                                                    $('#ward_id').empty();
                                                                }
                                                            });
                                                        }
                                                    });
                                                    //street
                                                    $('#street_cus').change(function(e) {
                                                        var street_id = $('#street_id').val();
                                                        var ward_id = $('#ward_cus').val();

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
                                                function removePlus(childElem) {
                                                    var row = $(childElem).closest("tr"); // find <tr> parent
                                                    row.remove();
                                                }
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
{/if}
<div id="loadgif">Loading...</div>


{include file='footer.tpl'}