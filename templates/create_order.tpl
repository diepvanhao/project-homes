{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin-bottom: 2%;">オーダー登録</div>
{literal}
    <script type="text/javascript">
        var checkPrice = function(el) {
            if (isNaN(el.val())) {
                if (isNaN(parseFloat(el.val()))) {
                    el.val('');
                } else {
                    el.val(parseFloat(el.val()));
                }
            }
        }
        var sendmail = 0;
        $(document).ready(function() {
            var txt = $("input#client_phone");
            var func = function(e) {
                if (e.keyCode === 32) {
                    txt.val(txt.val().replace(/\s/g, ''));
                }
            }
            txt.keyup(func).blur(func);

            birthday('log_revisit');
            birthday('client_birthday');
            birthday('client_time_change');
            timepicker('log_time_call');
            timepicker('log_time_arrive_company');
            birthday('log_time_call_date');
            birthday('log_time_arrive_company_date');
            timepicker('log_time_mail');
            birthday('log_time_mail_date');
            timepicker('log_date_appointment_from');
            timepicker('log_date_appointment_to');
            birthday('log_date_appointment_from_date');
            birthday('log_date_appointment_to_date');
            birthday('contract_payment_date_from');
            birthday('contract_payment_date_to');
            birthday('aspirations_build_time');
            timepicker('contract_signature_day');
            timepicker('contract_handover_day');
            birthday('contract_period_from');
            birthday('contract_period_to');
            birthday('contract_signature_day_date');
            birthday('contract_handover_day_date');
            birthday('contract_period_from_date');
            birthday('contract_application_date');
            //birthday('contract_period_to_date');
            //birthday('contract_application_date');
            $('#search').keyup(function(e) {
                var search = $('#search').val();
                $('#error_house').css("color", '#ddd');
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
                        $('#error_house').html("物件名が見つかりませんでした。");
                        $('#error_house').css("color", '');
                        //     hideloadgif();
                    }
                });
            });
            $("#agent_id").change(function(e) {
                var agent_id = $('#agent_id').val();
                $.post("include/function_ajax.php", {agent_id: agent_id, action: 'create_order', task: 'getPartner'},
                function(result) {
                    if (result) {
                        $('#partner_id').empty();
                        $('#partner_id').html(result);
                    } else {
                        $('#partner_id').empty();
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
                $('#error_room').css("color", '#ddd');
                $('#order_rent_cost').val("");
                $.post('include/function_ajax.php', {house_id: house_id, room_id: room_id, broker_id: broker_id, action: 'create_order', task: 'checkRoom'},
                function(result) {
                    var json = $.parseJSON(result);
                    if (json.status == 1) {
                        /* $('#error_room').html("入居中です。別の部屋を選択してください。");
                         $('#submit').attr('disabled', true);
                         $("#submit").css('color', 'grey');*/
                        $('#order_rent_cost').val(json.room_rent);
                    } else if (json.status == 2) {
                        /*$('#error_room').html("未完成です。別の部屋を選択してください。");
                         $('#submit').attr('disabled', true);
                         $("#submit").css('color', 'grey');*/
                        $('#order_rent_cost').val(json.room_rent);
                    } else {
                        if (json.flag == 'false') {
                            $('#error_room').html("この部屋は、選択した管理会社の管理ではありません。");
                            $('#submit').attr('disabled', true);
                            $("#submit").css('color', 'grey');
                            $('#error_room').css("color", '');

                        } else {
                            $('#order_rent_cost').val(json.room_rent);
                            $('#submit').attr('disabled', false);
                            $("#submit").css('color', '#fff');
                        }
                    }
                });
            });
            $('#contract_broker_fee').keyup(function(e) {
                checkPrice($('#contract_broker_fee'));
                var contract_key_money = parseFloat($('#contract_key_money').val());
                var contract_cost = parseFloat($('#contract_cost').val());
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val());
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val());
                var contract_ads_fee_unit = $('#contract_ads_fee_unit').val();
                if (contract_ads_fee_unit == 'ヵ月') {
                    contract_ads_fee = contract_ads_fee * contract_cost;
                }

                var label = new Array();
                var plus_money = new Array();
                var plus_money_unit = new Array();
                var total_plus = 0;
                $("input[name^='contract_label_money']").each(function() {
                    label.push($(this).val());
                });

                $("input[name^='contract_plus_money']").each(function() {
                    plus_money.push($(this).val());
                });

                $('#contract table tr td').find('#contract_plus_money_unit').each(function(e) {
                    plus_money_unit.push($(this).val());
                });

                for (var i = 0; i < plus_money_unit.length; i++) {
                    if (plus_money_unit[i] == 'ヵ月')
                        plus_money[i] = parseFloat(plus_money[i] * contract_cost);
                    total_plus += parseFloat(plus_money[i]);
                }

                $('#contract_total').val((contract_key_money > 0 ? contract_key_money : 0) + (contract_cost > 0 ? contract_cost : 0) + (contract_broker_fee > 0 ? contract_broker_fee : 0));
            });
            $('#contract_ads_fee').keyup(function(e) {
                checkPrice($('#contract_ads_fee'));
                var contract_key_money = parseFloat($('#contract_key_money').val());
                var contract_cost = parseFloat($('#contract_cost').val());
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val());
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val());
                var contract_ads_fee_unit = $('#contract_ads_fee_unit').val();
                if (contract_ads_fee_unit == 'ヵ月') {
                    contract_ads_fee = contract_ads_fee * contract_cost;
                }

                var label = new Array();
                var plus_money = new Array();
                var plus_money_unit = new Array();
                var total_plus = 0;
                $("input[name^='contract_label_money']").each(function() {
                    label.push($(this).val());
                });

                $("input[name^='contract_plus_money']").each(function() {
                    plus_money.push($(this).val());
                });

                $('#contract table tr td').find('#contract_plus_money_unit').each(function(e) {
                    plus_money_unit.push($(this).val());
                });

                for (var i = 0; i < plus_money_unit.length; i++) {
                    if (plus_money_unit[i] == 'ヵ月')
                        plus_money[i] = parseFloat(plus_money[i] * contract_cost);
                    total_plus += parseFloat(plus_money[i]);
                }

                $('#contract_total').val((contract_key_money > 0 ? contract_key_money : 0) + (contract_cost > 0 ? contract_cost : 0) + (contract_broker_fee > 0 ? contract_broker_fee : 0));
            });
            $('#contract_broker_fee_unit').change(function(e) {
                var contract_key_money = parseFloat($('#contract_key_money').val());
                var contract_cost = parseFloat($('#contract_cost').val());
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val());
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val());
                var contract_ads_fee_unit = $('#contract_ads_fee_unit').val();
                if (contract_ads_fee_unit == 'ヵ月') {
                    contract_ads_fee = contract_ads_fee * contract_cost;
                }

                var label = new Array();
                var plus_money = new Array();
                var plus_money_unit = new Array();
                var total_plus = 0;
                $("input[name^='contract_label_money']").each(function() {
                    label.push($(this).val());
                });

                $("input[name^='contract_plus_money']").each(function() {
                    plus_money.push($(this).val());
                });

                $('#contract table tr td').find('#contract_plus_money_unit').each(function(e) {
                    plus_money_unit.push($(this).val());
                });

                for (var i = 0; i < plus_money_unit.length; i++) {
                    if (plus_money_unit[i] == 'ヵ月')
                        plus_money[i] = parseFloat(plus_money[i] * contract_cost);
                    total_plus += parseFloat(plus_money[i]);
                }

                $('#contract_total').val((contract_key_money > 0 ? contract_key_money : 0) + (contract_cost > 0 ? contract_cost : 0) + (contract_broker_fee > 0 ? contract_broker_fee : 0));
            });
            $('#contract_ads_fee_unit').change(function(e) {
                var contract_key_money = parseFloat($('#contract_key_money').val());
                var contract_cost = parseFloat($('#contract_cost').val());
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val());
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val());
                var contract_ads_fee_unit = $('#contract_ads_fee_unit').val();
                if (contract_ads_fee_unit == 'ヵ月') {
                    contract_ads_fee = contract_ads_fee * contract_cost;
                }

                var label = new Array();
                var plus_money = new Array();
                var plus_money_unit = new Array();
                var total_plus = 0;
                $("input[name^='contract_label_money']").each(function() {
                    label.push($(this).val());
                });

                $("input[name^='contract_plus_money']").each(function() {
                    plus_money.push($(this).val());
                });

                $('#contract table tr td').find('#contract_plus_money_unit').each(function(e) {
                    plus_money_unit.push($(this).val());
                });

                for (var i = 0; i < plus_money_unit.length; i++) {
                    if (plus_money_unit[i] == 'ヵ月')
                        plus_money[i] = parseFloat(plus_money[i] * contract_cost);
                    total_plus += parseFloat(plus_money[i]);
                }
                $('#contract_total').val((contract_key_money > 0 ? contract_key_money : 0) + (contract_cost > 0 ? contract_cost : 0) + (contract_broker_fee > 0 ? contract_broker_fee : 0));
            });
            $('#contract_key_money_unit').change(function(e) {
                var contract_key_money = parseFloat($('#contract_key_money').val());
                var contract_cost = parseFloat($('#contract_cost').val());
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val());
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val());
                var contract_ads_fee_unit = $('#contract_ads_fee_unit').val();
                if (contract_ads_fee_unit == 'ヵ月') {
                    contract_ads_fee = contract_ads_fee * contract_cost;
                }

                var label = new Array();
                var plus_money = new Array();
                var plus_money_unit = new Array();
                var total_plus = 0;
                $("input[name^='contract_label_money']").each(function() {
                    label.push($(this).val());
                });

                $("input[name^='contract_plus_money']").each(function() {
                    plus_money.push($(this).val());
                });

                $('#contract table tr td').find('#contract_plus_money_unit').each(function(e) {
                    plus_money_unit.push($(this).val());
                });

                for (var i = 0; i < plus_money_unit.length; i++) {
                    if (plus_money_unit[i] == 'ヵ月')
                        plus_money[i] = parseFloat(plus_money[i] * contract_cost);
                    total_plus += parseFloat(plus_money[i]);
                }

                $('#contract_total').val((contract_key_money > 0 ? contract_key_money : 0) + (contract_cost > 0 ? contract_cost : 0) + (contract_broker_fee > 0 ? contract_broker_fee : 0));
            });
            $('#contract_cost').keyup(function(e) {
                checkPrice($('#contract_cost'));
                var contract_key_money = parseFloat($('#contract_key_money').val());
                var contract_cost = parseFloat($('#contract_cost').val());
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val());
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val());
                var contract_ads_fee_unit = $('#contract_ads_fee_unit').val();
                if (contract_ads_fee_unit == 'ヵ月') {
                    contract_ads_fee = contract_ads_fee * contract_cost;
                }

                var label = new Array();
                var plus_money = new Array();
                var plus_money_unit = new Array();
                var total_plus = 0;
                $("input[name^='contract_label_money']").each(function() {
                    label.push($(this).val());
                });

                $("input[name^='contract_plus_money']").each(function() {
                    plus_money.push($(this).val());
                });

                $('#contract table tr td').find('#contract_plus_money_unit').each(function(e) {
                    plus_money_unit.push($(this).val());
                });

                for (var i = 0; i < plus_money_unit.length; i++) {
                    if (plus_money_unit[i] == 'ヵ月')
                        plus_money[i] = parseFloat(plus_money[i] * contract_cost);
                    total_plus += parseFloat(plus_money[i]);
                }

                $('#contract_total').val((contract_key_money > 0 ? contract_key_money : 0) + (contract_cost > 0 ? contract_cost : 0) + (contract_broker_fee > 0 ? contract_broker_fee : 0));
            });
            $('#contract_plus_money').keyup(function(e) {
                var contract_key_money = parseFloat($('#contract_key_money').val());
                var contract_cost = parseFloat($('#contract_cost').val());
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val());
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val());
                var contract_ads_fee_unit = $('#contract_ads_fee_unit').val();
                if (contract_ads_fee_unit == 'ヵ月') {
                    contract_ads_fee = contract_ads_fee * contract_cost;
                }

                var label = new Array();
                var plus_money = new Array();
                var plus_money_unit = new Array();
                var total_plus = 0;
                $("input[name^='contract_label_money']").each(function() {
                    label.push($(this).val());
                });

                $("input[name^='contract_plus_money']").each(function() {
                    plus_money.push($(this).val());
                });

                $('#contract table tr td').find('#contract_plus_money_unit').each(function(e) {
                    plus_money_unit.push($(this).val());
                });

                for (var i = 0; i < plus_money_unit.length; i++) {
                    if (plus_money_unit[i] == 'ヵ月')
                        plus_money[i] = parseFloat(plus_money[i] * contract_cost);
                    total_plus += parseFloat(plus_money[i]);
                }

                $('#contract_total').val((contract_key_money > 0 ? contract_key_money : 0) + (contract_cost > 0 ? contract_cost : 0) + (contract_broker_fee > 0 ? contract_broker_fee : 0));
            });
            $('#calculator').click(function(e) {

                $("input[name^='contract_plus_money[]']").keyup(function(e) {
                    var contract_key_money = parseFloat($('#contract_key_money').val());
                    var contract_cost = parseFloat($('#contract_cost').val());
                    var contract_key_money_unit = $('#contract_key_money_unit').val();
                    if (contract_key_money_unit == 'ヵ月') {
                        contract_key_money = contract_key_money * contract_cost;
                    }
                    var contract_broker_fee = parseFloat($('#contract_broker_fee').val());
                    var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                    if (contract_broker_fee_unit == 'ヵ月') {
                        contract_broker_fee = contract_broker_fee * contract_cost;
                    }
                    var contract_ads_fee = parseFloat($('#contract_ads_fee').val());
                    var contract_ads_fee_unit = $('#contract_ads_fee_unit').val();
                    if (contract_ads_fee_unit == 'ヵ月') {
                        contract_ads_fee = contract_ads_fee * contract_cost;
                    }

                    var label = new Array();
                    var plus_money = new Array();
                    var plus_money_unit = new Array();
                    var total_plus = 0;
                    $("input[name^='contract_label_money']").each(function() {
                        label.push($(this).val());
                    });

                    $("input[name^='contract_plus_money']").each(function() {
                        plus_money.push($(this).val());
                    });

                    $('#contract table tr td').find('#contract_plus_money_unit').each(function(e) {
                        plus_money_unit.push($(this).val());
                    });

                    for (var i = 0; i < plus_money_unit.length; i++) {
                        if (plus_money_unit[i] == 'ヵ月')
                            plus_money[i] = parseFloat(plus_money[i] * contract_cost);
                        total_plus += parseFloat(plus_money[i]);
                    }

                    $('#contract_total').val((contract_key_money > 0 ? contract_key_money : 0) + (contract_cost > 0 ? contract_cost : 0) + (contract_broker_fee > 0 ? contract_broker_fee : 0));
                });
            });
            $('#contract_key_money').keyup(function(e) {
                checkPrice($('#contract_key_money'));
                var contract_key_money = parseFloat($('#contract_key_money').val());
                var contract_cost = parseFloat($('#contract_cost').val());
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val());
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val());
                var contract_ads_fee_unit = $('#contract_ads_fee_unit').val();
                if (contract_ads_fee_unit == 'ヵ月') {
                    contract_ads_fee = contract_ads_fee * contract_cost;
                }

                var label = new Array();
                var plus_money = new Array();
                var plus_money_unit = new Array();
                var total_plus = 0;
                $("input[name^='contract_label_money']").each(function() {
                    label.push($(this).val());
                });

                $("input[name^='contract_plus_money']").each(function() {
                    plus_money.push($(this).val());
                });

                $('#contract table tr td').find('#contract_plus_money_unit').each(function(e) {
                    plus_money_unit.push($(this).val());
                });

                for (var i = 0; i < plus_money_unit.length; i++) {
                    if (plus_money_unit[i] == 'ヵ月')
                        plus_money[i] = parseFloat(plus_money[i] * contract_cost);
                    total_plus += parseFloat(plus_money[i]);
                }

                $('#contract_total').val((contract_key_money > 0 ? contract_key_money : 0) + (contract_cost > 0 ? contract_cost : 0) + (contract_broker_fee > 0 ? contract_broker_fee : 0));
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
                var client_name = $('#client_name').val();
                var client_email = $('#client_email').val();
                var client_phone = $('#client_phone').val();
                if ((client_name == "" || client_phone == "") && (client_name == "" || client_email == "")) {
                    alert(' 注意：　名称と電話番号をご入力ください。 !!!');
                    $('#error_validate').html(' 注意：　名称と電話番号をご入力ください。 !!!');
                    $('#client_info ul li').first().click();
                    e.preventDefault();

                } else {
                    $('#transaction').submit();
                }
            });
            $('#client_detail').find('#save1').click(function(e) {
                var cus_id = $('#cus_id').val();
                if (cus_id == "" && getDivClass('basic') == 0) {
                    alert('基本情報の入力をお願いいたします。 !!!');
                    $('#client_info ul li').first().click();
                    e.preventDefault();
                }
                //validate
                $('#client_detail').find('div').each(function() {
                    if ($(this).attr('class') == 'active' && $(this).attr('id') == 'basic') {
                        $('#error_validate').html('');
                        var client_name = $('#client_name').val();
                        var client_phone = $('#client_phone').val();
                        var client_read_way = $('#client_read_way').val();
                        var client_birthday = $('#client_birthday').val();
                        var client_email = $('#client_email').val();
                        var client_fax = $('#client_fax').val();
                        var client_id = $('#client_id').val();
                        var order_id = $('#order_id').val();
                        var cus_id = $('#cus_id').val();
                        if (client_name == "" || client_phone == "") {
                            $('#error_validate').html(' 注意：　名称と電話番号をご入力ください。 !!!');
                            e.preventDefault();

                        } else if (cus_id) {
                            $.post("include/function_ajax.php", {client_name: client_name, client_phone: client_phone, client_read_way: client_read_way, client_birthday: client_birthday, client_email: client_email, client_fax: client_fax,
                                client_id: client_id, order_id: order_id, action: 'customer', task: 'basic'},
                            function(result) {
                                if (result == 'success') {
                                    alert('保存済');
                                } else if (result == 'fail') {
                                    alert("保存に失敗しました。");
                                }
                            });
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
                        var client_room_type_number = $('#client_room_type_number').val();

                        var client_rent = $('#client_rent').val();
                        var client_reason_change = $('#client_reason_change').val();
                        var client_time_change = $('#client_time_change').val();
                        var client_resident_name = $('#client_resident_name').val();
                        var client_resident_phone = $('#client_resident_phone').val();
                        var client_id = $('#client_id').val();
                        var order_id = $('#order_id').val();

                        if (city_id == "" || city_id == null) {
                            $('#error_city_id').html('都道府県は必須です。');
                            return false;
                        } else if (district_id == "" || district_id == null) {
                            $('#error_district_id').html('市区町村は必須です。');
                            return false;
                        } else if (street_id == "" || street_id == null) {
                            $('#error_street_id').html('大字・通称は必須です。');
                            return false;
                        } else if (ward_id == "" || ward_id == null) {
                            $('#error_ward_id').html('字・丁目は必須です。');
                            return false;
                        }
                        //clear notify error
                        $('#error_city_id').html('');
                        $('#error_district_id').html('');
                        $('#error_street_id').html('');
                        $('#error_ward_id').html('');

                        $.post("include/function_ajax.php", {gender: gender, client_address: client_address, city_id: city_id, district_id: district_id, street_id: street_id, ward_id: ward_id, client_occupation: client_occupation,
                            client_company: client_company, client_income: client_income, client_room_type: client_room_type, client_room_type_number: client_room_type_number, client_rent: client_rent,
                            client_reason_change: client_reason_change, client_time_change: client_time_change, client_resident_name: client_resident_name,
                            client_resident_phone: client_resident_phone, client_id: client_id, order_id: order_id, action: 'customer', task: 'detail'},
                        function(result) {
                            if (result == 'success') {
                                alert('保存済');
                            } else if (result == 'fail') {
                                alert("保存に失敗しました。");
                            }
                        });
                    } else if ($(this).attr('class') == 'active' && $(this).attr('id') == 'history') {

                        var log_status_appointment = $('input[name="log_status_appointment"]:checked').val();
                        var log_time_call = $('#log_time_call').val();
                        var log_time_arrive_company = $('#log_time_arrive_company').val();
                        var log_time_mail = $('#log_time_mail').val();

                        var log_time_call_date = $('#log_time_call_date').val();
                        var log_time_arrive_company_date = $('#log_time_arrive_company_date').val();
                        var log_time_mail_date = $('#log_time_mail_date').val();

                        var log_comment = $('#log_comment').val();
                        var log_date_appointment_from = $('#log_date_appointment_from').val();
                        var log_date_appointment_to = $('#log_date_appointment_to').val();
                        var log_date_appointment_from_date = $('#log_date_appointment_from_date').val();
                        var log_date_appointment_to_date = $('#log_date_appointment_to_date').val();

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
                            log_time_call_date: log_time_call_date, log_time_arrive_company_date: log_time_arrive_company_date, log_time_mail_date: log_time_mail_date,
                            log_tel: log_tel, log_tel_status: log_tel_status, log_mail: log_mail, log_comment: log_comment, log_date_appointment_from: log_date_appointment_from,
                            log_date_appointment_to: log_date_appointment_to, log_date_appointment_from_date: log_date_appointment_from_date, log_date_appointment_to_date: log_date_appointment_to_date,
                            log_mail_status: log_mail_status, log_contact_head_office: log_contact_head_office, log_shop_sign: log_shop_sign, log_local_sign: log_local_sign,
                            log_introduction: log_introduction, log_flyer: log_flyer, log_line: log_line, log_revisit: log_revisit, source_id: source_id,
                            log_status_appointment: log_status_appointment, client_id: client_id, order_id: order_id, action: 'customer', task: 'history_create'},
                        function(result) {
                            var json = $.parseJSON(result);
                            if (json.id != "")
                                alert('保存');
                            else if (json.id == "")
                                alert("更新が完了しました。");
                        });

                    } else if ($(this).attr('class') == 'active' && $(this).attr('id') == 'aspirations') {
                        var aspirations_type_house = $('#aspirations_type_house').val();
                        var aspirations_type_room = $('#aspirations_type_room').val();
                        var aspirations_type_room_number = $('#aspirations_type_room_number').val();
                        var aspirations_build_time = $('#aspirations_build_time').val();
                        var aspirations_area = $('#aspirations_area').val();
                        var aspirations_size = $('#aspirations_size').val();
                        var aspirations_rent_cost = $('#aspirations_rent_cost').val();
                        var aspirations_comment = $('#aspirations_comment').val();
                        var client_id = $('#client_id').val();
                        var order_id = $('#order_id').val();
                        $.post("include/function_ajax.php", {aspirations_type_house: aspirations_type_house, aspirations_type_room: aspirations_type_room, aspirations_type_room_number: aspirations_type_room_number, aspirations_build_time: aspirations_build_time,
                            aspirations_area: aspirations_area, aspirations_size: aspirations_size, aspirations_rent_cost: aspirations_rent_cost, aspirations_comment: aspirations_comment,
                            client_id: client_id, order_id: order_id, action: 'customer', task: 'aspirations'},
                        function(result) {
                            var json = $.parseJSON(result);
                            if (json.id != "")
                                alert('保存');
                            else if (json.id == "")
                                alert("更新が完了しました。");
                        });

                    } else if ($(this).attr('class') == 'active' && $(this).attr('id') == 'introduce') {
                        var house_id = $('#introduce_house_id').val();
                        var house_description = $('#introduce_house_content').val();
                        var client_id = $('#client_id').val();
                        var order_id = $('#order_id').val();
                        var room_id = $('#introduce_room_id').val();
                        $('#error_introduce_house_id').html('');
                        $('#error_introduce_room_id').html('');
                        if (house_id == "")
                            $('#error_introduce_house_id').html('物件を選択ください。.');
                        else if (room_id == "") {
                            $('#error_introduce_room_id').html('お部屋を選択ください。.');
                        } else {
                            $.post("include/function_ajax.php", {house_id: house_id, room_id: room_id, introduce_house_content: house_description,
                                client_id: client_id, order_id: order_id, action: 'customer', task: 'introduce'},
                            function(result) {
                                var json = $.parseJSON(result);
                                if (json.id != "")
                                    alert('保存');
                                else if (json.id == "")
                                    $('#error_introduce_house_id').html('紹介しました。別の部屋および物件を選択してください。 !!!');
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

                        var contract_signature_day_date = $('#contract_signature_day_date').val();

                        var contract_handover_day_date = $('#contract_handover_day_date').val();
                        var contract_period_from_date = $('#contract_period_from_date').val();
                        var contract_period_to_date = $('#contract_period_to_date').val();

                        var contract_deposit_1 = $('#contract_deposit_1').val();
                        var contract_deposit_2 = $('#contract_deposit_2').val();
                        var contract_application_date = $('#contract_application_date').val();
                        var contract_payment_status = $('input[name="contract_payment_status"]:checked').val();
                        var contract_payment_report = $('input[name="contract_payment_report"]:checked').val();
                        var contract_payment_date_from = $('#contract_payment_date_from').val();
                        var contract_payment_date_to = $('#contract_payment_date_to').val();
                        var partner_id = $('#partner_id').val();
                        var partner_percent = $('#partner_percent').val();
                        var money_payment = $('#money_payment').val();
                        /*
                         var plus_money_unit = new Array();
                         $("input[name^='contract_plus_money']").each(function() {
                         plus_money_unit.push($(this).val());
                         });
                         alert(plus_money_unit);
                         $('#contract table tr td').find('#contract_plus_money_unit').each(function(e) {
                         alert($(this).val());
                         });
                         //alert($('#contract_plus_money_unit').val());
                         return false;*/
                        if ($('#contract_cancel').is(':checked'))
                            var contract_cancel = 1;
                        else
                            var contract_cancel = 0;

                        if ($('#contract_application').is(':checked'))
                            var contract_application = 1;
                        else
                            var contract_application = 0;

                        if ($('#contract_transaction_finish').is(':checked'))
                            var contract_transaction_finish = 1;
                        else
                            var contract_transaction_finish = 0;

                        if ($('#contract_ambition').is(':checked'))
                            var contract_ambition = 1;
                        else
                            var contract_ambition = 0;

                        if ($('#room_rented').is(':checked'))
                            var room_rented = 1;
                        else
                            var room_rented = 0;

                        var contract_total = $('#contract_total').val();

                        var client_id = $('#client_id').val();
                        var order_id = $('#order_id').val();
                        var contract_deposit1_money_unit = $('#contract_deposit1_money_unit').val();
                        var contract_key_money_unit = $('#contract_key_money_unit').val();
                        var contract_deposit2_money_unit = $('#contract_deposit2_money_unit').val();
                        var contract_broker_fee = $('#contract_broker_fee').val();
                        var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                        var contract_ads_fee = $('#contract_ads_fee').val();
                        var contract_ads_fee_unit = $('#contract_ads_fee_unit').val();

                        var label = new Array();

                        var plus_money = new Array();
                        var plus_money_unit = new Array();
                        $("input[name^='contract_label_money']").each(function() {
                            label.push($(this).val());
                        });

                        $("input[name^='contract_plus_money']").each(function() {
                            plus_money.push($(this).val());
                        });

                        // $("input[name^='contract_plus_money_unit']").each(function() {
                        //      plus_money_unit.push($(this).val());
                        // });
                        $('#contract table tr td').find('#contract_plus_money_unit').each(function(e) {
                            plus_money_unit.push($(this).val());
                        });

                        //validate
                        if (partner_id.length > 0) {
                            if (partner_percent == "") {
                                $('#error_partner_id').html('この人は何パーセントやりましたか。 ?');
                                return false;
                            }
                        }
                        $('#error_partner_id').html("");
                        $.post("include/function_ajax.php", {contract_name: contract_name, contract_cost: contract_cost, contract_key_money: contract_key_money,
                            contract_condition: contract_condition, contract_valuation: contract_valuation,
                            contract_signature_day: contract_signature_day, contract_handover_day: contract_handover_day, contract_period_from: contract_period_from, contract_period_to: contract_period_to,
                            contract_signature_day_date: contract_signature_day_date, contract_handover_day_date: contract_handover_day_date, contract_period_from_date: contract_period_from_date, contract_period_to_date: contract_period_to_date,
                            contract_deposit_1: contract_deposit_1, contract_deposit_2: contract_deposit_2,
                            contract_cancel: contract_cancel, contract_total: contract_total, contract_application: contract_application, contract_application_date: contract_application_date, label: label, plus_money: plus_money,
                            plus_money_unit: plus_money_unit, contract_key_money_unit: contract_key_money_unit, contract_deposit1_money_unit: contract_deposit1_money_unit, contract_deposit2_money_unit: contract_deposit2_money_unit,
                            contract_broker_fee: contract_broker_fee, contract_broker_fee_unit: contract_broker_fee_unit, contract_ads_fee: contract_ads_fee, contract_ads_fee_unit: contract_ads_fee_unit,
                            contract_transaction_finish: contract_transaction_finish, contract_payment_date_from: contract_payment_date_from, contract_payment_date_to: contract_payment_date_to,
                            contract_payment_status: contract_payment_status, contract_payment_report: contract_payment_report, partner_id: partner_id, partner_percent: partner_percent,
                            contract_ambition: contract_ambition, money_payment: money_payment, room_rented: room_rented,
                            client_id: client_id, order_id: order_id, action: 'customer', task: 'contract'},
                        function(result) {
                            var json = $.parseJSON(result);
                            if (json.id != "")
                                alert('保存済');
                            else if (json.id == "")
                                alert("更新が完了しました。");

                            if (sendmail && ($('#contract_application').attr('checked') || $('#contract_transaction_finish').attr('checked'))) {
                                $.post("include/mail_ajax.php", {application: $('#contract_application').attr('checked'), transaction: $('#contract_transaction_finish').attr('checked'), order_id: $('#order_id').val()},
                                function(result) {
                                    //                            alert(result);
                                }
                                );
                            }
                        });
                    }
                });
            });
            $('#done').click(function() {
                showloadgif();
                window.location.href = "create_order.php";
            });
            $('#contract_deposit_1').keyup(function() {
                checkPrice($('#contract_deposit_1'));
            });
            $('#contract_deposit_2').keyup(function() {
                checkPrice($('#contract_deposit_2'));
            });
            $('#client_income').keyup(function() {
                checkPrice($('#client_income'));
            });
            $('#client_rent').keyup(function() {
                checkPrice($('#client_rent'));
            });
        });
        function CalculatorPlus() {
            var contract_key_money = parseFloat($('#contract_key_money').val());
            var contract_cost = parseFloat($('#contract_cost').val());
            var contract_key_money_unit = $('#contract_key_money_unit').val();
            if (contract_key_money_unit == 'ヵ月') {
                contract_key_money = contract_key_money * contract_cost;
            }
            var contract_broker_fee = parseFloat($('#contract_broker_fee').val());
            var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
            if (contract_broker_fee_unit == 'ヵ月') {
                contract_broker_fee = contract_broker_fee * contract_cost;
            }
            var contract_ads_fee = parseFloat($('#contract_ads_fee').val());
            var contract_ads_fee_unit = $('#contract_ads_fee_unit').val();
            if (contract_ads_fee_unit == 'ヵ月') {
                contract_ads_fee = contract_ads_fee * contract_cost;
            }

            var label = new Array();
            var plus_money = new Array();
            var plus_money_unit = new Array();
            var total_plus = 0;
            $("input[name^='contract_label_money']").each(function() {
                label.push($(this).val());
            });

            $("input[name^='contract_plus_money']").each(function() {
                plus_money.push($(this).val());
            });

            $('#contract table tr td').find('#contract_plus_money_unit').each(function(e) {
                plus_money_unit.push($(this).val());
            });

            for (var i = 0; i < plus_money_unit.length; i++) {
                if (plus_money_unit[i] == 'ヵ月')
                    plus_money[i] = parseFloat(plus_money[i] * contract_cost);
                total_plus += parseFloat(plus_money[i]);
            }

            $('#contract_total').val((contract_key_money > 0 ? contract_key_money : 0) + (contract_cost > 0 ? contract_cost : 0) + (contract_broker_fee > 0 ? contract_broker_fee : 0));
        }
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
                    $('#client_name').val('');
                    $('#client_read_way').val('');
                    $('#client_birthday').val('');
                    $('#client_email').val('');
                    $('#client_phone').val('');
                    $('#gender').val('');
                    $('#client_address').val('');

                    $('#city_cus').val('');
                    $('#district_cus').val('');
                    $('#street_cus').val('');
                    $('#ward_cus').val('');
                    $('#city_id').each(function() {
                        $('option').removeAttr('selected');
                    });
                    $('#district').each(function() {
                        $('option').removeAttr('selected');
                    });
                    $('#street').each(function() {
                        $('option').removeAttr('selected');
                    });
                    $('#ward').each(function() {
                        $('option').removeAttr('selected');
                    });

                    $('#client_occupation').val("");
                    $('#client_company').val("");
                    $('#client_income').val("");
                    //$('#client_room_type').val(json.client_room_type);
                    $('#client_room_type').each(function() {
                        $('option').removeAttr('selected');
                    });
                    $('#client_rent').val("");
                    $('#client_reason_change').val("");
                    $('#client_time_change').val("");
                    $('#client_resident_name').val("");
                    $('#client_resident_phone').val("");
                    $('#client_room_type_number').val("");
                    /* $('#log_time_call').val('');
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
                     $('#contract_total').val('');*/

                    var json = $.parseJSON(result);
                    $('#client_name').val(json.client_name);
                    $('#client_read_way').val(json.client_read_way);
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
                    //$('#client_room_type').val(json.client_room_type);
                    $('#client_room_type').find('option').each(function() {
                        if ($(this).val() == json.client_room_type) {
                            $(this).attr('selected', 'selected');
                        }
                    });
                    $('#client_rent').val(json.client_rent);
                    $('#client_reason_change').val(json.client_reason_change);
                    $('#client_time_change').val(json.client_time_change);
                    $('#client_resident_name').val(json.client_resident_name);
                    $('#client_resident_phone').val(json.client_resident_phone);
                    $('#client_room_type_number').val(json.client_room_type_number);

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
                        $('#error_room').html("この物件は部屋番号が存在していません。");
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
        <div class="title"><label ></label></div>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">      
            <tr>
                <td class="form1">管理会社検索</td>
                <td class="form2"><input type="text" id="filter_broker" name="filter_broker" value="" placeholder="管理会社名を入力する。" style="height:26px; width: 215px;"/>
                </td>
            </tr>
            <tr>       
                <td class='form1'>管理会社選択: </td>
                <td class='form2'>
                    <select id="broker_id" name="broker_id" style="height:26px; width: 215px;">
                        <option value=""></option>
                        {foreach from=$brokers item=broker}
                            <option value="{$broker.id}" {if $broker.id eq $broker_id}selected="selected"{/if}>{$broker.broker_company_name}</option>        
                        {/foreach}
                    </select>
                    <div id="error_broker" class="error"></div>
                </td>
            </tr> 
            <tr> 
                {assign var=broker_link value='次のリンクで、新しい管理会社の情報を追加することができます。 <a href="./create_broker_company.php">管理会社登録</a>'}
                <td colspan="2" nowrap><div>次のリンクで、新しい管理会社の情報を追加することができます。<a href="javascript:createBroker();">{*<a href="./create_broker_company.php">*}管理会社登録</a></div></td>
            </tr>            
            <tr>
                <td class="form1">
                    担当
                </td>

                <td class='form2'>
                    <select id="staff_id" name="staff_id" style="height:26px; width: 215px;">
                        <option value=""></option>
                        {foreach from=$users item=user}
                            {if $user.id eq $loged_id}
                                <option value="{$user.id}"{if $user.id eq $staff_id}selected="selected"{/if}> {$user.user_lname} {$user.user_fname}</option>        
                            {/if}
                        {/foreach}
                    </select><div id="error_staff" class="error"></div>

                </td>
            </tr>
            <tr>
                <td class="form1">物件検索</td>
                <td class="form2"><input type="text" id="search" name="search" value="" placeholder="物件名を入力する。" style="height:26px; width: 215px;"/>
                </td>
            </tr>
            <tr>            
                <td class='form1'>物件選択: </td>
                <td class='form2'>
                    <select id="house_id" name="house_id" style="height:26px; width: 215px;">
                        <option value=""></option>
                        {foreach from=$houses item=house}
                            <option value="{$house.id}"{if $house.id eq $house_id}selected="selected"{/if}>{$house.house_name}</option>        
                        {/foreach}
                    </select><div id="error_house" class="error"></div>
                </td>
            </tr>
            <tr>            
                <td class='form1'>物件備考: </td>
                <td class='form2'><textarea style="width: 340px;height: 129px;" disabled="1" id="house_description">{$house_description}</textarea></td>
            </tr>
            <tr>            
                <td colspan="2"><div>次のリンクで、新しい物件情報を追加することができます。<a href="javascript:createHouse();">{*<a href="./create_house.php">*}物件登録</a></div></td>
            </tr>

            <tr>            
                <td class='form1'>部屋選択: </td>
                <td class='form2'><select id="room_id" name="room_id" style="height:26px; width: 215px;">
                        <option value=""></option>

                    </select><div id="error_room" class="error"></div>
                </td>
            </tr>
            <tr>            
                <td colspan="2"><div>次のリンクで、新しい物件情報を追加することができます。 <a href="javascript:createRoom();"> <!--href="./create_room.php?return_url=create_order.php" --> 部屋情報</a></div></td>
            </tr>
            <!--order part-->
            <tr>            
                <td class='form1'>オーダーID: </td>
                <td class='form2'><input type='text' id="order_name" name="order_name" value="{$order_name}"style="height: 26px; width: 215px;"/><div id="error_order_name" class="error"></div></td>
            </tr>
            <tr>            
                <td class='form1'>賃料: </td>
                <td class='form2'><input type='text' id="order_rent_cost" name="order_rent_cost" value="{$order_rent_cost}"style="height: 26px; width: 215px;"/></td>
            </tr>
            <tr>            
                <td class='form1'>備考: </td>
                <td class='form2'><input type='text' id="order_comment" name="order_comment" value="{$order_comment}"style="height: 26px; width: 215px;"/></td>
            </tr>

            <!--end order-->
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-signup' value='次' id="submit" name="submit" style="width: 100px;"/>&nbsp;                          
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
            function createRoom() {
                var params = '';
                if ($('#broker_id').val()) {
                    params += '&broker_id=' + $('#broker_id').val();
                }
                if ($('#staff_id').val()) {
                    params += '&staff_id=' + $('#staff_id').val();
                }
                if ($('#house_id').val()) {
                    params += '&house_id=' + $('#house_id').val();
                }
                if ($('#house_description').val()) {
                    params += '&house_description=' + $('#house_description').val();
                }
                window.location = "create_room.php?return_url=create_order.php" + params;
            }
            function createBroker() {
                var params = '';
                if ($('#broker_id').val()) {
                    params += '&broker_id=' + $('#broker_id').val();
                }
                if ($('#staff_id').val()) {
                    params += '&staff_id=' + $('#staff_id').val();
                }
                window.location = "create_broker_company.php?return_url=create_order.php" + params;
            }
            function createHouse(){
                var params = '';
                if ($('#broker_id').val()) {
                    params += '&broker_id=' + $('#broker_id').val();
                }
                if ($('#staff_id').val()) {
                    params += '&staff_id=' + $('#staff_id').val();
                }                                
                window.location = "create_house.php?return_url=create_order.php" + params;
            }
            $(document).ready(function() {
                $('#filter_broker').keyup(function(e) {
                    var filter = $('#filter_broker').val();
                    $('#error_broker').css("color", "#ddd");
                    //showloadgif();
                    $.post("include/function_ajax.php", {filter: filter, action: 'create_order', task: 'getBrokerFilter'},
                    function(result) {
                        if (result) {
                            //  hideloadgif();
                            $('#broker_id').empty();
                            $('#broker_id').html(result);
                            //$('table').find('tr').css('display', '');
                            $('#yoke_muscle').click();
                        } else {
                            // hideloadgif();
                            $('#broker_id').empty();
                            $('#broker_id').empty();
                            //$('#house_description').html("");
                            /*  $('table').find('tr').css('display', 'none');
                             $('table').find('tr:first-child').css('display', '');
                             $('table').find('tr:nth-child(2)').css('display', '');
                             $('table').find('tr:nth-child(3)').css('display', '');
                             $('table').find('tr:last-child').css('display', '');*/
                            $('#error_broker').html("仲介会社名が見つかりませんでした。");
                            $('#error_broker').css("color", "");
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
                            $('#error_house').html('物件名が見つかりませんでした。');
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
                        $('#error_broker').html('仲介会社を選択ください。');
                        e.preventDefault();
                        return false;
                    } else if (staff_id == "" || staff_id == null) {
                        $('#error_staff').html('担当者を選択ください。.');
                        e.preventDefault();
                        return false;
                    } else if (house_id == "" || house_id == null) {
                        $('#error_house').html('物件を選択ください。.');
                        e.preventDefault();
                        return false;
                    } else if (room_id == "" || room_id == null) {
                        $('#error_room').html('お部屋を選択ください。.');
                        e.preventDefault();
                        return false;
                    } else if (order_name == "" || order_name == null) {
                        $('#error_order_name').html('オーダー名は必須です。.');
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
                        // $('table').find('tr').css('display', '');
                        $('#yoke_muscle').click();
                    } else {
                        /* $('table').find('tr').css('display', 'none');
                         $('table').find('tr:first-child').css('display', '');
                         $('table').find('tr:nth-child(2)').css('display', '');
                         $('table').find('tr:nth-child(3)').css('display', '');
                         $('table').find('tr:last-child').css('display', '');*/
                    }
                });
                var broker_id = $('#broker_id').val();
                if (broker_id) {
                    // $('table').find('tr').css('display', '');
                } else {
                    /*$('table').find('tr').css('display', 'none');
                     $('table').find('tr:first-child').css('display', '');
                     $('table').find('tr:nth-child(2)').css('display', '');
                     $('table').find('tr:nth-child(3)').css('display', '');
                     $('table').find('tr:last-child').css('display', '');*/
                }
                var house_id = $('#house_id').val();

                // var room_id ={/literal}{if $room_id ne ""}{$room_id}{else}0{/if}{';'}{literal}
                var room_id = $('#room_bk').val();
                if (house_id) {
                    $.post('include/function_ajax.php', {house_id: house_id, action: 'create_order', task: 'getContentHouse'},
                    function(result) {
                        var json = $.parseJSON(result);
                        $('#house_description').html(json.house_description);
                        get_room(house_id, room_id);
                    });
                }
            });
        </script>
    {/literal}
    {if $brokerClick eq '1'}
        {literal}
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#broker_id').change();
                });
            </script>
        {/literal}
    {/if}
    {if $houseClick eq '1'}
        {literal}
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#house_id').change();
                });
            </script>
        {/literal}
    {/if}
{/if}

{*step verify*}
{if $step eq "verify"}
    <form action="create_order.php" method="post">
        <div class="title"><label ><h2>情報確認</h2></label></div>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>媒体:</td>
                <td class='form2'>{$brokers.broker_company_name}</td>
            </tr>
            <tr>
                <td class='form1' nowrap>担当者:</td>
                <td class='form2'>{$staffs.user_lname} {$staffs.user_fname} </td>
            </tr>
            <tr>
                <td class='form1' nowrap>物件名:</td>
                <td class='form2'>{$houses.house_name}</td>
            </tr>
            <tr>
                <td class='form1' nowrap>号室:</td>
                <td class='form2'>{$room_id}</td>
            </tr>
            <tr>
                <td class='form1'>備考:</td>
                <td class='form2'>{$houses.house_description}</td>
            </tr>
            <tr>
                <td class='form1'>オーダーＩＤ:</td>
                <td class='form2'>{$order_name}</td>
            </tr>
            <tr>
                <td class='form1'>賃料:</td>
                <td class='form2'>{$order_rent_cost}</td>
            </tr>
            <tr>
                <td class='form1'>備考:</td>
                <td class='form2'>{$order_comment}</td>
            </tr>
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type="submit" class='btn-signup' value="登録" id="registry" name="registry" style="width: 100px;"/>&nbsp; 
                        <input type="button" class='btn-signup' value="後で" id="later" name="later"style="width: 100px;margin: 0px 10px 0px 10px;"/>
                        <input type="button" class='btn-signup' value="キャンセル" id="cancel" name="cancel"style="width: 130px;"/>
                        <input type='button' class='btn-signup' value='戻る' id="back" name="back" style="width: 100px;float: right;margin-right: 1%;"/>&nbsp; 
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
                $('#cancel').click(function() {
                    window.location.href = "create_order.php";
                });
                $('#later').click(function() {
                    //save order 
                    var room_id = $('#room_id').val();

                    var order_name = $('#order_name').val();

                    var order_rent_cost = $('#order_rent_cost').val();

                    var order_comment = $('#order_comment').val();

                    var create_id = $('#create_id').val();

                    var house_id = $('#house_id').val();

                    var broker_id = $('#broker_id').val();
                    $.post("include/function_ajax.php", {room_id: room_id, order_name: order_name, order_rent_cost: order_rent_cost,
                        order_comment: order_comment, create_id: create_id, house_id: house_id, broker_id: broker_id,
                        action: 'create_order', task: 'Later'},
                    function(result) {
                        var json = $.parseJSON(result);
                        if (json.id) {
                            alert('保存が完了しました。!!!');
                            window.location.href = "create_order.php";
                        } else if (json.error) {
                            alert('オーダーが存在しています。再度部屋番号を確認してください。');
                        }
                    });
                });
            });
        </script>
    {/literal}
{/if}

{if $step eq "registry"}
    {if $errorHouseExist ne ""}

        <div class="error"></div>

    {/if}
    <form action="create_order.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="60%">
            <tr>
                <td>顧客検索</td>
                <td><input type="text" id="filter" name="filter"value="{$filter}" style="height: 26px; width: 215px;" placeholder="顧客名を入力"/>
                    <span>
                        <input type='submit' class='btn-search' value='送信' id="search" name="submit"/>&nbsp;                     
                    </span>
                </td>
            <input type="hidden" id="step" name="step" value="registry"/><div style="float: right;"><input type="button" value="完了" id="done" name="done"class='btn-search'/></div>
            </tr>
        </table>
    </form>

    <div style="margin-bottom:10px;">
        <center>
            ページ:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="create_order.php?step='registry'&filter={$filter}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
    <div id="customer">
        <ul>
            <li class="even">番号</li>
            <li class="even">名称</li>
            <li class="even">生年月日</li>
            <li class="even">住所</li>
            <li class="even">電話番号</li>
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
    <div style="background-color: #F1F5FE; width: 100%;height:25px; text-align: center;font-size: 1.8em;line-height: 25px; margin-top: 50px; ">お客様情報</div>
    <div id="client_info">
        <ul>
            <li class="select_menu" title="basic">基本情報</li>
            <li class="noselect_menu" title="detail">詳細</li>
            <li class="noselect_menu" title="history">履歴</li>
            <li class="noselect_menu" title="aspirations">希望</li>
            <li class="noselect_menu" title="introduce">紹介物件</li>
            <li class="noselect_menu" title="contract">申込・契約</li>
        </ul>
    </div>
    <div id="client_detail">      
        <form action="create_order.php" method="post" id="transaction">   
            <div id="basic"class="active">                 
                <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                    <tr>
                        <td class='form1'>名称:</td>
                        <td class='form2'><input type="text" id="client_name" name="client_name" value="{$client_name}"style="height: 26px; width: 215px;"/></td>
                        <td class='form1' nowrap>生年月日:</td>
                        <td class='form2'> <input type='text' id="client_birthday" name="client_birthday" value="{$client_birthday}"style="height: 26px; width: 215px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>フリガナ:</td>
                        <td class='form2'><input type="text" id="client_read_way" name="client_read_way" value="{$client_read_way}"style="height: 26px; width: 215px;"/></td>
                        <td class='form1'>Ｅメール:</td>
                        <td class='form2'><input type="text" id="client_email" name="client_email" value="{$client_email}" style="height: 26px; width: 215px;"/></td>
                    </tr>
                    <tr>                        
                        <td class='form1'>電話番号:</td>
                        <td class='form2'> <input type='text' id="client_phone" name="client_phone" value="{$client_phone}" style="height: 26px; width: 215px;"/></td>
                        <td class='form1' nowrap>ファックス:</td>
                        <td class='form2'> <input type='text' id="client_fax" name="client_fax" value="{$client_fax}" style="height: 26px; width: 215px;"/></td>
                    </tr>

                    <tr>
                        <td class='form1'>&nbsp;</td>
                        <td class='form2' colspan="3">
                            <div id="error_validate" class="error"></div>
                            <div style="margin-top:10px;text-align: center;">
                                {*<input type="submit" class='btn-signup' value="保存" id="save" name="save" style="width: 100px;"/>*}&nbsp; 
                                {*<input type="hidden" id="task" name="task" value="basic"/>*}
                                <input type="hidden" id="step" name="step" value="registry"/> 
                                <input type="hidden" id="client_id" name="client_id" value="{$client_id}"/>
                                <input type="hidden" id="order_id" name="order_id" value="{$order_id}"/>
                            </div>                        
                        </td>
                    </tr>
                </table>
                {*</form>*}
            </div>
            <div id="detail"class="inactive">
                {* <form action="create_order.php" method="post"> *}       
                <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                    <tr>
                        <td class='form1'>性別: </td>
                        <td class='form2'>
                            <select id="gender"name="gender" style="height:26px; width: 215px;">
                                <option value="male" {if $gender eq "male"}selected{/if}>男性</option>
                                <option value="female"{if $gender eq "female"}selected{/if}>女性</option>
                                <option value="other" {if $gender eq "other"}selected{/if}>その他</option>
                            </select>
                        </td>
                        <td class='form1' nowrap>番地:</td>
                        <td class='form2'> <input type='text' id="client_address" name="client_address" value="{$client_address}" style="height: 26px; width: 215px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>都道府県:  <span class="required">*</span></td>
                        <td class='form2'><select id="city_id" name="city_id" style="height:26px; width: 215px;">
                                <option value=""></option>
                                {foreach from=$cities item=city}
                                    <option value="{$city.id}" {if $city.id eq $city_id}selected="selected"{/if}>{$city.city_name}</option>        
                                {/foreach}
                            </select><div id="error_city_id" class="error"></div>
                        </td>
                        <td class='form1'>市区町村:  <span class="required">*</span></td>
                        <td class='form2'><select id="district_id" name="district_id" style="height:26px; width: 215px;">                       

                            </select><div id="error_district_id" class="error"></div>
                        </td>
                    </tr>      

                    <tr>
                        <td class='form1'>大字・通称:  <span class="required">*</span></td>
                        <td class='form2'><select id="street_id" name="street_id" style="height:26px; width: 215px;">

                            </select><div id="error_street_id" class="error"></div>
                        </td>
                        <td class='form1'>字・丁目:  <span class="required">*</span></td>
                        <td class='form2'><select id="ward_id" name="ward_id" style="height:26px; width: 215px;">

                            </select><div id="error_ward_id" class="error"></div>
                        </td>
                    </tr>

                    <tr>
                        <td class='form1'>職業 :</td>
                        <td class='form2'><input type="text" id="client_occupation" name="client_occupation" value="{$client_occupation}" style="height: 26px; width: 215px;"/></td>
                        <td class='form1' nowrap>会社名:</td>
                        <td class='form2'> <input type='text' id="client_company" name="client_company"  value="{$client_company}" style="height: 26px; width: 215px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>年収:</td>
                        <td class='form2'>
                            <input type="text" id="client_income" name="client_income" value="{$client_income}" style="height: 26px; width: 215px;"/>
                            <label style="padding: 1.7% 4.7% 1.7% 4.7%;background-color: white;">円</label>
                        </td>
                        <td class='form1' nowrap>間取り:</td>
                        <td class='form2'> 
                            <input type='text' class='text' name='client_room_type_number' id='client_room_type_number' value="{$client_room_type_number}" style="height:26px; width: 90px;"/>
                            <select id="client_room_type" name="client_room_type" style="position: absolute;margin-left: 0.5%;height:28px; width: 115px;">
                                <option value=""></option>
                                {foreach from=$roomTypes item=roomType}
                                    <option value="{$roomType.id}" {if $roomType.id eq $client_room_type}selected="selected"{/if}>{$roomType.room_name}</option>        
                                {/foreach}
                            </select><div id="error_client_room_type" class="error"></div>
                           <!-- <input type='text' id="client_room_type" name="client_room_type" value="{$client_room_type}"style="height: 26px; width: 215px;"/>-->
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>現在の賃料:</td>
                        <td class='form2'>
                            <input type="text" id="client_rent" name="client_rent" value="{$client_rent}"style="height: 26px; width: 215px;"/>
                            <label style="padding: 1.7% 4.7% 1.7% 4.7%;background-color: white;">円</label>
                        </td>
                        <td class='form1' nowrap>引越理由:</td>
                        <td class='form2'> <input type='text' id="client_reason_change" name="client_reason_change" value="{$client_reason_change}"style="height: 26px; width: 215px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>引越予定日:</td>
                        <td class='form2'><input type="text" id="client_time_change" name="client_time_change" value="{$client_time_change}"style="height: 26px; width: 215px;"/></td>
                        <td class='form1' nowrap>入居者の名前</td>
                        <td class='form2'><input type="text" id="client_resident_name" name="client_resident_name" value="{$client_resident_name}"style="height: 26px; width: 215px;"/> </td>
                    </tr>
                    <tr>
                        <td class='form1'>入居者の電話番号:</td>
                        <td class='form2'><input type="text" id="client_resident_phone" name="client_resident_phone" value="{$client_resident_phone}"style="height: 26px; width: 215px;"/></td>
                        <td class='form1' nowrap></td>
                        <td class='form2'> </td>
                    </tr>
                    <tr>
                        <td class='form1'>&nbsp;</td>
                        <td class='form2' colspan="3">
                            <div id="error_validate" class="error"></div>
                            <div style="margin-top:10px;text-align: center;">
                                {*<input type="button" class='btn-signup' value="保存" id="save" name="save" style="width: 100px;"/>&nbsp; *}
                                {*<input type="hidden" id="task" name="task" value="detail"/>*}
                                {*<input type="hidden" id="step" name="step" value="registry"/> *}
                                <input type="hidden" id="city_cus" name="city_cus" value=""/> 
                                <input type="hidden" id="district_cus" name="district_cus" value=""/> 
                                <input type="hidden" id="street_cus" name="street_cus" value=""/> 
                                <input type="hidden" id="ward_cus" name="ward_cus" value=""/> 
                                {* <input type="hidden" id="client_id" name="client_id" value="{$client_id}"/>
                                <input type="hidden" id="order_id" name="order_id" value="{$order_id}"/>*}
                            </div>                        
                        </td>
                    </tr>
                </table>
                {*</form>*}
            </div>
            <div id="history"class="inactive">
                {* <form action="create_order.php" method="post">  *}      
                <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                    <tr>
                        <td colspan="2" style="text-align: right;">連絡タイプを選択する?</td>
                        <td colspan="2">
                            <input type="radio" checked="checked" id="log_time_call_type" name="choose_contact_type"/><label for="log_time_call_type">ＴＥＬ</label>
                            <input type="radio" id="log_time_mail_type" name="choose_contact_type"/><label for="log_time_mail_type">Eメール</label>
                            <input type="radio" id="log_time_arrive_company_type" name="choose_contact_type"/><label for="log_time_arrive_company_type">来店</label>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>通話時刻: </td>
                        <td class='form2'>
                            <input type='text' id="log_time_call_date" name="log_time_call_date" value="{$log_time_call_date}"style="height: 26px; width: 115px;"/>
                            <input type='text' id="log_time_call" name="log_time_call" value="{$log_time_call}"style="height: 26px; width: 95px;"/>
                        </td>
                        <td class='form1' nowrap></td>
                        <td class='form2'>

                        </td>
                    </tr>
                    <tr>
                        <td class='form1' nowrap>来店時刻:</td>
                        <td class='form2'>
                            <input type='text' id="log_time_arrive_company_date" name="log_time_arrive_company_date" value="{$log_time_arrive_company_date}"style="height: 26px; width: 115px;"/>
                            <input type='text' id="log_time_arrive_company" name="log_time_arrive_company" value="{$log_time_arrive_company}"style="height: 26px; width: 95px;"/>
                        </td>
                        <td class='form1'>{*本社反響:*}</td>
                        <td class='form2'>{*<input type="checkbox" value="1" id="log_contact_head_office" name="log_contact_head_office" {if $log_contact_head_office eq '1'}checked="checked" {/if}style="height: 26px; width: 15px;"/>*}</td>                        

                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>メール送信時刻: </td>
                        <td class='form2'>
                            <input type='text' id="log_time_mail_date" name="log_time_mail_date" value="{$log_time_mail_date}"style="height: 26px; width: 115px;"/>
                            <input type='text' id="log_time_mail" name="log_time_mail" value="{$log_time_mail}"style="height: 26px; width: 95px;"/>
                        </td>
                        <td class='form1' nowrap></td>
                        <td class='form2'></td>
                    </tr>
                    <tr>
                        <td class='form1'nowrap>予約日付　（～まで）:</td>
                        <td class='form2'>
                            <input type="text" id="log_date_appointment_from_date" name="log_date_appointment_from_date" value="{$log_date_appointment_from_date}"style="height: 26px; width: 115px;"/>
                            <input type="text" id="log_date_appointment_from" name="log_date_appointment_from" value="{$log_date_appointment_from}"style="height: 26px; width: 95px;"/>
                        </td>
                        <td class='form1' nowrap>ご来店:</td>
                        <td class='form2'>
                            <input type='radio' id="log_status_appointment_yes" name="log_status_appointment" value="1" {if $log_status_appointment eq '1'}checked="checked" {/if}/><label for="log_status_appointment_yes">はい。</label> &nbsp; &nbsp; 
                            <input type='radio' id="log_status_appointment_no" name="log_status_appointment" value="0" {if $log_status_appointment eq '0'}checked="checked" {/if}/><label for="log_status_appointment_no">いいえ。</label>
                        </td>
                    </tr>
                    <tr>
                        <!--<td class='form1'>予約日付　（～から）: </td>
                        <td class='form2'>
                            <input type='text' id="log_date_appointment_to_date" name="log_date_appointment_to_date" value="{$log_date_appointment_to_date}"style="height: 26px; width: 115px;"/>
                            <input type='text' id="log_date_appointment_to" name="log_date_appointment_to" value="{$log_date_appointment_to}"style="height: 26px; width: 95px;"/>
                        </td>-->

                        <td class='form1'>媒体を選択してください。:</td>
                        <td class='form2'><select id="source_id" name="source_id" style="height:26px; width: 215px;">
                                <option value=""></option>
                                {foreach from=$sources item=source}
                                    <option value="{$source.id}" {if $source_id eq $source.id} selected="selected"{/if}>{$source.source_name}</option>        
                                {/foreach}
                            </select>
                        </td>
                        <td class='form1'></td>
                        <td class='form2'></td>
                    </tr>

                    <tr>
                        <td class='form1'>TEL:</td>
                        <td class='form2'><input type="checkbox" value="1" id="log_tel" name="log_tel" {if $log_tel eq '1'}checked="checked" {/if} style="height: 26px; width: 15px;"/></td>
                        <td class='form1' nowrap>{*現況:*}</td>
                        <td class='form2'> {*<input type='checkbox' value="1" id="log_tel_status" name="log_tel_status" {if $log_tel_status eq '1'}checked="checked" {/if}style="height: 26px; width: 15px;"/>*}</td>
                    </tr>
                    <tr>
                        <td class='form1'>MAIL:</td>
                        <td class='form2'><input type="checkbox" value="1" id="log_mail" name="log_mail" {if $log_mail eq '1'}checked="checked" {/if}style="height: 26px; width: 15px;"/></td>
                        <td class='form1' nowrap>{*現況:*}</td>
                        <td class='form2'> {*<input type='checkbox' value="1" id="log_mail_status" name="log_mail_status"{if $log_mail_status eq '1'}checked="checked" {/if} style="height: 26px; width: 15px;"/>*}</td>
                    </tr>

                    <tr>
                        <td class='form1' nowrap>店頭看板:</td>
                        <td class='form2'> <input type="checkbox" value="1" id="log_shop_sign" name="log_shop_sign"{if $log_shop_sign eq '1'}checked="checked" {/if} style="height: 26px; width: 15px;"/></td>
                        <td class='form1' nowrap></td>
                        <td class='form2'></td>
                    </tr>
                    <tr>
                        <td class='form1'>現地看板:</td>
                        <td class='form2'><input type="checkbox" value="1" id="log_local_sign" name="log_local_sign"{if $log_local_sign eq '1'}checked="checked" {/if} style="height: 26px; width: 15px;"/></td>
                        <td class='form1' nowrap>紹介:</td>
                        <td class='form2'> <input type='checkbox' value="1" id="log_introduction" name="log_introduction" {if $log_introduction eq '1'}checked="checked" {/if}style="height: 26px; width: 15px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>チラシ:</td>
                        <td class='form2'><input type="checkbox" value="1" id="log_flyer" name="log_flyer"{if $log_flyer eq '1'}checked="checked" {/if} style="height: 26px; width: 15px;"/></td>
                        <td class='form1' nowrap>ＬＩＮＥ:</td>
                        <td class='form2'> <input type='checkbox' value="1" id="log_line" name="log_line"{if $log_line eq '1'}checked="checked" {/if} style="height: 26px; width: 15px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>再来店: </td>
                        <td class='form2'>
                            <input type='text' id="log_revisit" name="log_revisit" value="{$log_revisit}"style="height: 26px; width: 215px;"/>
                        </td>
                        <td class='form1' nowrap>備考:</td>
                        <td class='form2'> <input type='text' id="log_comment" name="log_comment" value="{$log_comment}"style="height: 26px; width: 215px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>&nbsp;</td>
                        <td class='form2' colspan="3">
                            <div id="error_validate" class="error"></div>
                            <div style="margin-top:10px;text-align: center;">
                                {*<input type="button" class='btn-signup' value="保存" id="save" name="save" style="width: 100px;"/>&nbsp; 
                                <input type="hidden" id="task" name="task" value="history"/>
                                <input type="hidden" id="step" name="step" value="registry"/> 
                                <input type="hidden" id="client_id" name="client_id" value="{$client_id}"/>
                                <input type="hidden" id="order_id" name="order_id" value="{$order_id}"/>*}
                            </div>                        
                        </td>
                    </tr>
                </table>
                {* </form>*}
            </div>
            <div id="aspirations" class="inactive">
                {*<form action="create_order.php" method="post">    *}    
                <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                    <tr>
                        <td class='form1'>物件種別: </td>
                        <td class='form2'>
                            <select id="aspirations_type_house" name="aspirations_type_house" style="height:26px; width: 215px;">
                                <option value=""></option>
                                {foreach from=$houseTypes item=houseType}
                                    <option value="{$houseType.id}" {if $houseType.id eq $aspirations_type_house}selected="selected"{/if}>{$houseType.type_name}</option>        
                                {/foreach}
                            </select><div id="error_aspirations_type_house" class="error"></div>

                        </td>
                        <td class='form1' nowrap>間取り:</td>
                        <td class='form2'>                            
                            <input type='text' class='text' name='aspirations_type_room_number' id='aspirations_type_room_number' value="{$aspirations_type_room_number}" style="height:26px; width: 90px;"/>
                            <select id="aspirations_type_room" name="aspirations_type_room" style="position: absolute;margin-left: 0.5%;height:28px; width: 115px;">
                                <option value=""></option>
                                {foreach from=$roomTypes item=roomType}
                                    <option value="{$roomType.id}" {if $roomType.id eq $aspirations_type_room}selected="selected"{/if}>{$roomType.room_name}</option>        
                                {/foreach}
                            </select><div id="error_aspirations_type_room" class="error"></div>
                    </tr>
                    <tr>
                        <td class='form1'>築年月:</td>
                        <td class='form2'><input type="text" id="aspirations_build_time" name="aspirations_build_time" value="{$aspirations_build_time}"style="height: 26px; width: 215px;"/></td>
                        <td class='form1' nowrap>アリアー:</td>
                        <td class='form2'> <input type='text' id="aspirations_area" name="aspirations_area" value="{$aspirations_area}"style="height: 26px; width: 215px;"/></td>
                    </tr>
                    <tr>
                        <td class='form1'>面積:</td>
                        <td class='form2'><input type="text" id="aspirations_size" name="aspirations_size"value="{$aspirations_size}" style="height: 26px; width: 215px;"/></td>
                        <td class='form1' nowrap>賃料:</td>
                        <td class='form2'> 
                            <input type='text' id="aspirations_rent_cost" name="aspirations_rent_cost"value="{$aspirations_rent_cost}" style="height: 26px; width: 215px;"/>
                            <label style="padding: 2% 4.5% 1% 4.5%;background-color: white;">円</label>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>備考:</td>
                        <td class='form2'><input type="text" id="aspirations_comment" name="aspirations_comment" value="{$aspirations_comment}"style="height: 26px; width: 215px;"/></td>
                        <td class='form1' nowrap></td>
                        <td class='form2'></td>
                    </tr>                
                    <tr>
                        <td class='form1'>&nbsp;</td>
                        <td class='form2' colspan="3">
                            <div style="margin-top:10px;text-align: center;">
                                <div id="error_validate" class="error"></div>
                                {*<input type="button" class='btn-signup' value="保存" id="save" name="save" style="width: 100px;"/>&nbsp; 
                                <input type="hidden" id="task" name="task" value="aspirations"/>
                                <input type="hidden" id="step" name="step" value="registry"/> 
                                <input type="hidden" id="client_id" name="client_id" value="{$client_id}"/>
                                <input type="hidden" id="order_id" name="order_id" value="{$order_id}"/>*}
                            </div>                        
                        </td>
                    </tr>
                </table>
                {*</form>*}
            </div>
            <div id="introduce" class="inactive">
                {*<form action="create_order.php" method="post"> *}           
                <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">      

                    <tr>
                        <td class="form1">物件検索</td>
                        <td class="form2">
                            <input type="text" id="search_house" name="search_house" value="" placeholder="物件名を入力する。" style="height:26px; width: 215px;"/>                            
                        </td>
                    </tr>
                    <tr>            
                        <td class='form1'>物件選択: </td>
                        <td class='form2'>
                            <select id="introduce_house_id" name="introduce_house_id" style="height:26px; width: 215px;">
                                <option value=""></option>
                                {foreach from=$houses item=house}
                                    <option value="{$house.id}" {if $introduce_house_id eq $house.id} selected="selected"{/if}>{$house.house_name}</option>        
                                {/foreach}
                            </select><div id="error_introduce_house_id" class="error"></div>
                        </td>
                    </tr>
                    <tr>            
                        <td class='form1'>物件備考: </td>
                        <td class='form2'><textarea style="width: 215px;height: 129px;" disabled="1" id="introduce_house_content" name="introduce_house_content" >{$introduce_house_content}</textarea></td>
                    </tr>
                    <tr>            
                        <td colspan="2"><div>次のリンクで、新しい物件情報を追加することができます。 <a href="./create_house.php">物件登録</a></div></td>
                    </tr>
                    <tr>            
                        <td class='form1'>部屋選択: </td>
                        <td class='form2'><select id="introduce_room_id" name="introduce_room_id" style="height:26px; width: 215px;">
                                <option value=""></option>

                            </select><div id="error_introduce_room_id" class="error"></div>
                        </td>
                    </tr>      
                    <tr>
                        <td class='form1'>&nbsp;</td>
                        <td class='form2'>
                            <div id="error_validate" class="error"></div>
                            <div style="margin-top:10px">
                                {*<input type="button" class='btn-signup' value="保存" id="save" name="save" style="width: 100px;"/>&nbsp;  
                                <input type="hidden" id="task" name="task" value="introduce"/>*}
                                <input type="hidden" id="introduce_house" name="introduce_house" />
                                {*<input type="hidden" id="step" name="step" value="registry"/>  
                                <input type="hidden" id="client_id" name="client_id" value="{$client_id}"/>
                                <input type="hidden" id="order_id" name="order_id" value="{$order_id}"/>*}
                            </div>
                        </td>
                    </tr>
                </table>
                {* </form>*}
            </div>
            <div id="contract" class="inactive">
                {*<form action="create_order.php" method="post">  *}      
                <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%" id="contract_plus">
                    <tr>                       
                        <td class='form1' nowrap>賃料:</td>
                        <td class='form2'> <input type='text' id="contract_cost" name="contract_cost" value="{$contract_cost}"style="height: 26px; width: 215px;"/>
                            <label style="padding: 2% 5.5% 1% 5.5%;background-color: white;">円</label>
                        </td>
                        <td class='form1'>礼金:</td>
                        <td class='form2'><input type="text" id="contract_key_money" name="contract_key_money" value="{$contract_key_money}"style="height: 26px; width: 215px;"/>
                            <select id="contract_key_money_unit" style="width: 15%;padding: 1% 0px 1% 0%;">
                                <option value="円">円</option>
                                <option value="ヵ月">ヵ月</option>
                            </select>
                        </td>
                    </tr>                                        
                    <tr>                    
                        <td class='form1'>仲介手数料:</td>
                        <td class='form2'><input type="text" id="contract_broker_fee" name="contract_broker_fee" value="{$contract_broker_fee}"style="height: 26px; width: 215px;"/>
                            <select id="contract_broker_fee_unit" style="width: 15%;padding: 1% 0px 1% 0%;">
                                <option value="円">円</option>
                                <option value="ヵ月">ヵ月</option>
                            </select>
                        </td>
                        <td class='form1'>広告費:</td>
                        <td class='form2'><input type="text" id="contract_ads_fee" name="contract_ads_fee" value="{$contract_ads_fee}"style="height: 26px; width: 215px;"/>
                            <select id="contract_ads_fee_unit" style="width: 15%;padding: 1% 0px 1% 0%;">
                                <option value="円">円</option>
                                <option value="ヵ月">ヵ月</option>
                            </select>
                        </td>                                           
                    </tr>
                    <tr>
                        <td class='form1'>敷金・保証金（預かり）:</td>
                        <td class='form2'><input type="text" id="contract_deposit_1" name="contract_deposit_1" value="{$contract_deposit_1}"style="height: 26px; width: 215px;"/>
                            <select id="contract_deposit1_money_unit" style="width: 15%;padding: 1% 0px 1% 0%;">
                                <option value="円">円</option>
                                <option value="ヵ月">ヵ月</option>
                            </select></td>
                        <td class='form1' nowrap>敷金・保証金（償却）:</td>
                        <td class='form2'><input type="text" id="contract_deposit_2" name="contract_deposit_2"value="{$contract_deposit_2}" style="height: 26px; width: 215px;"/>
                            <select id="contract_deposit2_money_unit" style="width: 15%;padding: 1% 0px 1% 0%;">
                                <option value="円">円</option>
                                <option value="ヵ月">ヵ月</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>管理費:</td>
                        <td class='form2'>
                            <input type="text"  name='room_administrative_expense' id='room_administrative_expense' value="{$room_administrative_expense}" style="height: 26px; width: 215px;">
                            <label style="padding: 2% 5.5% 1% 5.5%;background-color: white;">円</label>
                        </td> 
                        <td class='form1'>申込金:</td>
                        <td class='form2'>
                            <input type="text"  name='money_payment' id='money_payment' value="{$money_payment}" style="height: 26px; width: 215px;">
                            <label style="padding: 2% 5.5% 1% 5.5%;background-color: white;">円</label>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>合計:</td>
                        <td class='form2'><input type="text" id="contract_total" name="contract_total"  value="{$contract_total}"style="height: 26px; width: 215px;"/>
                            <label style="padding: 2% 5.5% 1% 5.5%;background-color: white;">円</label>
                        </td>

                        <td class='form1'>他決:</td>
                        <td class='form2'><input type="checkbox" value="1" id="room_rented" name="room_rented" {if $room_rented eq '1'}checked="checked"{/if}/></td>
                    </tr>
                    <tr>
                        <td class='form1' nowrap>契約金入金予定日:</td>
                        <td class='form2'> <input type='text' id="contract_payment_date_from" name="contract_payment_date_from" value="{$contract_payment_date_from}"style="height: 26px; width: 215px;"/></td>
                        <td class='form1' nowrap>入金状況:</td>
                        <td class='form2'>
                            <input type='radio' id="contract_payment_status_yes" name="contract_payment_status" value="1" {if $contract_payment_status eq '1'}checked="checked" {/if}/><label for="contract_payment_status_yes">はい。</label> &nbsp; &nbsp; 
                            <input type='radio' id="contract_payment_status_no" name="contract_payment_status" value="0" {if $contract_payment_status eq '0'}checked="checked" {/if}/><label for="contract_payment_status_no">いいえ。</label>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>広告費入金予定日: </td>
                        <td class='form2'>
                            <input type='text' id="contract_payment_date_to" name="contract_payment_date_to" value="{$contract_payment_date_to}"style="height: 26px; width: 215px;"/>
                        </td>
                        <td class='form1' nowrap>入金状況:</td>
                        <td class='form2'>
                            <input type='radio' id="contract_payment_report_yes" name="contract_payment_report" value="1" {if $contract_payment_report eq '1'}checked="checked" {/if}/><label for="contract_payment_report_yes">はい。</label> &nbsp; &nbsp; 
                            <input type='radio' id="contract_payment_report_no" name="contract_payment_report" value="0" {if $contract_payment_report eq '0'}checked="checked" {/if}/><label for="contract_payment_report_no">いいえ。</label>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>契約日:</td>
                        <td class='form2'>
                            <input type="text" id="contract_signature_day_date" name="contract_signature_day_date" value="{$contract_signature_day_date}"style="height: 26px; width: 115px;"/>
                            <input type="text" id="contract_signature_day" name="contract_signature_day" value="{$contract_signature_day}"style="height: 26px; width: 95px;"/>
                        </td>
                        <td class='form1' nowrap>鍵渡日:</td>
                        <td class='form2'>
                            <input type="text" id="contract_handover_day_date" name="contract_handover_day_date"value="{$contract_handover_day_date}" style="height: 26px; width: 115px;"/>
                            <input type="text" id="contract_handover_day" name="contract_handover_day"value="{$contract_handover_day}" style="height: 26px; width: 95px;"/>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>契約始期:</td>
                        <td class='form2'>
                            {*<input type="text" id="contract_period_from_date" name="contract_period_from_date"value="{$contract_period_from_date}" style="height: 26px; width: 115px;"/>*}
                            <input type="text" id="contract_period_from" name="contract_period_from"value="{$contract_period_from}" style="height: 26px; width: 215px;"/>
                        </td>
                        <td class='form1' nowrap>契約終期:</td>
                        <td class='form2'>
                            {*<input type="text" id="contract_period_to_date" name="contract_period_to_date" value="{$contract_period_to_date}"style="height: 26px; width: 115px;"/>*}
                            <input type="text" id="contract_period_to" name="contract_period_to" value="{$contract_period_to}"style="height: 26px; width: 215px;"/>
                        </td>
                    </tr>                    

                    <tr>
                        <td class='form1'>申込金:</td>
                        <td class='form2'>{*<input type="checkbox" value="1" onClick="javascript:sendMail(this);" id="contract_application" name="contract_application" {if $contract_application eq '1'}checked="checked"{/if}/>*}
                            <input type='radio' id="contract_application_yes" onClick="javascript:sendMail(this);" name="contract_application" value="1" {if $contract_application eq '1'}checked="checked" {/if}/><label for="contract_application_yes">有</label> &nbsp; &nbsp; 
                            <input type='radio' id="contract_application_no" name="contract_application" value="0" {if $contract_application eq '0'}checked="checked" {/if}/><label for="contract_application_no">無</label>
                        </td>
                        <td class='form1' nowrap>申込日:</td>
                        <td class='form2'><input type="text" id="contract_application_date" name="contract_application_date" value="{$contract_application_date}"style="height: 26px; width: 215px;"/></td>
                    </tr>

                    <tr>
                        <td class='form1'>店舗:</td>
                        <td class='form2'>
                            <select id="agent_id"name="agent_id"style="height: 26px; width: 215px;">
                                <option value=""></option>
                                {foreach from=$agents item=agent}
                                    <option value="{$agent.id}">{$agent.agent_name}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td class='form1' nowrap>按分先:</td>
                        <td class='form2'>
                            <select id="partner_id"name="partner_id" style="height:26px; width: 180px;">
                                <option value=""></option>
                                {foreach from=$partners item=partner}
                                    <option value="{$partner.id}"{if $partner.id eq $partner_id}selected{/if}>{$partner.user_lname} {$partner.user_fname} </option>
                                {/foreach}
                            </select>
                            <input type="number" id="partner_percent"name="partner_percent" value="{$partner_percent}" style="height: 23px; width: 50px;position: absolute;margin-left: 0.5%"/><label style="float: right;margin: 2% 9% 0 0;">%</label>
                            <div id="error_partner_id" class="error"></div>
                        </td>
                    </tr>
                    <tr>                    
                        <td class='form1' nowrap>条件:</td>
                        <td class='form2'><textarea style="width: 215px;height: 129px;"  id="contract_condition"name="contract_condition">{$contract_condition}</textarea></td>
                        <td class='form1' nowrap>評価額:</td>
                        <td class='form2'><textarea style="width: 215px;height: 129px;"  id="contract_valuation"name="contract_valuation">{$contract_valuation}</textarea></td>
                    </tr>
                    <tr>
                        <td class='form1'>売上計上:</td>
                        <td class='form2'><input type="checkbox" value="1" onClick="javascript:sendMail(this);" id="contract_transaction_finish" name="contract_transaction_finish" {if $contract_transaction_finish eq '1'}checked="checked"{/if}/></td>
                        <td class='form1' nowrap>キャンセル:</td>
                        <td class='form2'><input type="checkbox" value="1" id="contract_cancel" name="contract_cancel" {if $contract_cancel eq '1'}checked="checked"{/if}/></td>
                    </tr>
                    <tr>                    
                        <td class='form1'></td>
                        <td class='form2'>
                            <input type="button" id="add" class='btn-signup' name="add" value="その他、付帯" style="width: 140px;"/> 
                        </td>       
                        <td class='form1' nowrap>自社物件</td>
                        <td class='form2'><input type="checkbox" value="1" id="contract_ambition" name="contract_ambition" {if $contract_ambition eq '1'}checked="checked"{/if}/></td>
                    </tr>
                    {foreach from=$plus_money key=k item=money}
                        <tr>
                            <td class='form1'>{$k} :</td>
                            <td class='form2'>
                                <input type='hidden' name='contract_label_money[]' value="{$k}"/>
                                <input type='text' id='contract_plus_money' name='contract_plus_money[]' value="{$money}" style='height: 26px; width: 210px;'/>
                                <select id='contract_plus_money_unit'name='contract_plus_money_unit[]' style='width: 14%;padding: 1% 0px 1% 0%;'onchange='CalculatorPlus();'><option value='円'>円</option><option value='ヵ月'>ヵ月</option></select>
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
                                {*<input type="button" class='btn-signup' value="保存" id="save" name="save" style="width: 100px;"/>&nbsp; 
                                <input type="hidden" id="task" name="task" value="contract"/>
                                <input type="hidden" id="step" name="step" value="registry"/>                                 
                                <input type="hidden" id="client_id" name="client_id" value="{$client_id}"/>
                                <input type="hidden" id="order_id" name="order_id" value="{$order_id}"/>*}
                                <input type="hidden" id="calculator" name="calculator"/>
                                <input type="button" class='btn-signup' value="エクスポート" onclick="javascript:openImport();" style="width: 150px;"/> 
                            </div>                        
                        </td>
                    </tr>
                </table>
                <div id="export_form" style="display:none;">
                    <table cellpadding="0" cellspacing="0" style="margin-left: 0px;" width="100%">      
                        <tbody>
                            <tr>
                                <td class="form1">
                                    選択
                                </td>
                                <td class="form2">
                                    <select name="export_option">
                                        <option value="10">オーダー</option>
                                        <option value="1">申込報告書</option>
                                        <option value="2">精算書家主用</option>
                                        <option value="3">精算書入居者用</option>
                                        <option value="4">入居者対応補助</option>
                                        <option value="5">請求書</option>
                                        <option value="6">業務委託料</option>
                                        <option value="7">広告料</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="form1">&nbsp;</td>
                                <td class="form2">
                                    <div style="margin-top:10px">
                                        <input type="submit" class='btn-signup' value="エクスポート" id="export" name="export" style="width: 150px;"/> 
                                        <input type="button" class='btn-signup' value="戻る" onclick="javascript:closeImport();" style="width: 100px;"/> 
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {* </form>*}
            </div>
            <div style="text-align: right;padding-top: 1%;">
                <input type="submit" class='btn-signup' value="保存" id="save" name="save" style="width: 100px;background: #617AAC;"/>
            </div>
        </form>
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
            function openImport() {
                $('#export_form').show();
                // $('#contract_plus').hide();
            }
            function closeImport() {
                $('#export_form').hide();
                // $('#contract_plus').show();
            }
            function sendMail(el) {
                if (el.checked && confirm("Do you want to send mail?")) {
                    sendmail = 1;
                }
            }
            $(document).ready(function() {
                $('#sidebar_container').css('display', 'none');
                var fieldCount = 1;
                $('#add').click(function() {
                    var label = prompt('その他費用を追加する ?', '');
                    if (label != null && label != "" && label != 0) {
                        // fieldCount++;
                        $('#contract #contract_plus tr:nth-last-child(2)').after("<tr><td class='form1'>" + label + " :</td><td class='form2'><input type='hidden' name='contract_label_money[]' value='" + label + "'/><input type='text' id='contract_plus_money' name='contract_plus_money[]' value=''style='height: 26px; width: 210px;'onkeyup='CalculatorPlus();'/><select id='contract_plus_money_unit'name='contract_plus_money_unit[]' style='width: 14%;padding: 1% 0px 1% 0%; margin-left: 1%;'onchange='CalculatorPlus();'><option value='円'>円</option><option value='ヵ月'>ヵ月</option></select><input type='button' id='remove' name='remove' class='btn-remove' value='削除' onClick='removePlus(this)' /></td> <td class='form1'></td><td class='form2'></td> </tr>");
                        if (fieldCount == 1)
                            CalculatorPlus();
                        fieldCount++;
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
                                                    $('#search_house').keyup(function(e) {
                                                        var search = $('#search_house').val();
                                                        $('#error_introduce_house_id').html("");
                                                        //    showloadgif();
                                                        $.post("include/function_ajax.php", {search: search, action: 'create_order', task: 'getHouseSearch'},
                                                        function(result) {
                                                            if (result) {
                                                                $('#introduce_house_id').empty();
                                                                $('#introduce_house_id').html(result);
                                                                $('#introduce_house').click();
                                                                //   hideloadgif();
                                                            } else {
                                                                $('#introduce_house_id').empty();
                                                                $('#introduce_room_id').empty();
                                                                $('#introduce_house_content').html("");
                                                                $('#error_introduce_house_id').html("物件名が見つかりませんでした。");
                                                                //     hideloadgif();
                                                            }
                                                        });
                                                    });
                                                    $('#introduce_house').click(function() {
                                                        var house_id = $('#introduce_house_id').val();

                                                        $.post('include/function_ajax.php', {house_id: house_id, action: 'create_order', task: 'getContentHouse'},
                                                        function(result) {
                                                            var json = $.parseJSON(result);
                                                            $('#introduce_house_content').html(json.house_description);
                                                            get_introduce_room(house_id, 0);
                                                        });
                                                    });
                                                    $('#introduce_house_id').change(function() {
                                                        var house_id = $('#introduce_house_id').val();
                                                        $.post('include/function_ajax.php', {house_id: house_id, action: 'create_order', task: 'getContentHouse'},
                                                        function(result) {
                                                            var json = $.parseJSON(result);
                                                            $('#introduce_house_contrent').html(json.house_description);
                                                            get_introduce_room(house_id, 0);
                                                        });
                                                    });
                                                    // $('#history table').find('tr:nth-child(2)').css('display', 'none');
                                                    $('#history table').find('tr:nth-child(3)').css('display', 'none');
                                                    $('#history table').find('tr:nth-child(4)').css('display', 'none');
                                                    if ($('#log_time_call_type').is(':checked')) {
                                                        $('#history table').find('tr:nth-child(8)').css('display', 'none');
                                                    }
                                                    if ($('#log_time_mail_type').is(':checked')) {
                                                        $('#history table').find('tr:nth-child(7)').css('display', 'none');
                                                    }
                                                    if ($('#log_time_arrive_company_type').is(':checked')) {
                                                        $('#history table').find('tr:nth-child(7)').css('display', 'none');
                                                        $('#history table').find('tr:nth-child(8)').css('display', 'none');
                                                    }
                                                    $('#log_time_call_type').click(function() {
                                                        $('#history table').find('tr:nth-child(2)').css('display', '');
                                                        $('#history table').find('tr:nth-child(3)').css('display', 'none');
                                                        $('#history table').find('tr:nth-child(4)').css('display', 'none');
                                                        $('#history table').find('tr:nth-child(8)').css('display', 'none');
                                                        $('#history table').find('tr:nth-child(7)').css('display', '');
                                                        $('#log_time_mail').val('');
                                                        $('#log_time_mail_date').val('');
                                                        $('#log_time_arrive_company').val('');
                                                        $('#log_time_arrive_company_date').val('');
                                                        $('#log_mail').removeAttr('checked');
                                                        $('#log_mail_status').removeAttr('checked');
                                                        $('#log_contact_head_office').removeAttr('checked');

                                                    });
                                                    $('#log_time_mail_type').click(function() {
                                                        $('#history table').find('tr:nth-child(4)').css('display', '');
                                                        $('#history table').find('tr:nth-child(2)').css('display', 'none');
                                                        $('#history table').find('tr:nth-child(3)').css('display', 'none');
                                                        $('#history table').find('tr:nth-child(7)').css('display', 'none');
                                                        $('#history table').find('tr:nth-child(8)').css('display', '');
                                                        $('#log_time_call').val('');
                                                        $('#log_time_call_date').val('');
                                                        $('#log_time_arrive_company').val('');
                                                        $('#log_time_arrive_company_date').val('');
                                                        $('#log_tel').removeAttr('checked');
                                                        $('#log_tel_status').removeAttr('checked');
                                                        $('#log_contact_head_office').removeAttr('checked');
                                                    });
                                                    $('#log_time_arrive_company_type').click(function() {
                                                        $('#history table').find('tr:nth-child(3)').css('display', '');
                                                        $('#history table').find('tr:nth-child(2)').css('display', 'none');
                                                        $('#history table').find('tr:nth-child(4)').css('display', 'none');
                                                        $('#history table').find('tr:nth-child(7)').css('display', 'none');
                                                        $('#history table').find('tr:nth-child(8)').css('display', 'none');
                                                        $('#log_time_mail').val('');
                                                        $('#log_time_mail_date').val('');
                                                        $('#log_time_call').val('');
                                                        $('#log_time_call_date').val('');
                                                        $('#log_mail').removeAttr('checked');
                                                        $('#log_mail_status').removeAttr('checked');
                                                        $('#log_tel').removeAttr('checked');
                                                        $('#log_tel_status').removeAttr('checked');
                                                    });
                                                });
                                                function get_introduce_room(house_id, room_id) {
                                                    $('#error_introduce_room_id').html("");
                                                    $.post("include/function_ajax.php", {house_id: house_id, room_id: room_id, action: 'create_order', task: 'getRoomContent'},
                                                    function(result) {
                                                        if (result) {
                                                            $('#introduce_room_id').empty();
                                                            $('#introduce_room_id').html(result);
                                                        } else {
                                                            $('#introduce_room_id').empty();
                                                            $('#introduce_house_content').html("");
                                                            if (house_id)
                                                                $('#error_introduce_room_id').html("この物件は部屋番号が存在していません。");
                                                        }
                                                    });
                                                }
                                                function removePlus(childElem) {
                                                    var row = $(childElem).closest("tr"); // find <tr> parent
                                                    if (row) {
                                                        row.remove();
                                                        CalculatorPlus();
                                                    }
                                                }

        </script>
    {/literal}
    {if $introduce_house_id ne ""}
        {literal}
            <script type="text/javascript">
                $(document).ready(function() {
                    var introduce_house_id ={/literal}{$introduce_house_id}{literal}
                    var introduce_room_id ={/literal} '{$introduce_room_id}'{literal}
                    get_introduce_room(introduce_house_id, introduce_room_id);
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
{/if}
<div id="loadgif">Loading...</div>


{include file='footer.tpl'}
