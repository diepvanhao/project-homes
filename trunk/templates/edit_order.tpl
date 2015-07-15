<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/style.min.css" />
{include file='header.tpl'}
<script type="text/javascript" src="{$url->url_base}include/js/jquery.bpopup.min.js"></script> 
<div id="content_order_title" style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin-bottom: 2%;">オーダー情報編集</div>
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
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
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
            log_date('.log_revisit');
//            $( ".log_revisit" ).datepicker();
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
            birthday('contract_cancel_date');
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
                        /*$('#error_room').html("入居中です。別の部屋を選択してください。");
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
                            $('#save').attr('disabled', true);
                            $("#save").css('color', 'grey');
                            $('#for_client').attr('disabled', true);
                            $("#for_client").css('color', 'grey');
                            $('#error_room').css("color", '');

                        } else {
                            $('#order_rent_cost').val(json.room_rent);
                            $('#save').attr('disabled', false);
                            $('#for_client').attr('disabled', false);
                            $("#save").css('color', '#fff');
                            $("#for_client").css('color', '#fff');
                        }
                    }
                });
            });
            $('#contract_broker_fee').change(function(e) {
                $('#contract_broker_fee').val($('#contract_broker_fee').val().replace(",", ""));
                checkPrice($('#contract_broker_fee'));
                var contract_key_money = parseFloat($('#contract_key_money').val().replace(",", ""));
                var contract_cost = parseFloat($('#contract_cost').val().replace(",", ""));
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val());
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val().replace(",", ""));
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
                $('#contract_total').val(formatNumber($('#contract_total').val()));
                $('#contract_broker_fee').val(formatNumber($('#contract_broker_fee').val()));
            });
            $('#contract_ads_fee').change(function(e) {
                $('#contract_ads_fee').val($('#contract_ads_fee').val().replace(",", ""));
                checkPrice($('#contract_ads_fee'));
                var contract_key_money = parseFloat($('#contract_key_money').val().replace(",", ""));
                var contract_cost = parseFloat($('#contract_cost').val().replace(",", ""));
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val().replace(",", ""));
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
                $('#contract_ads_fee').val(formatNumber($('#contract_ads_fee').val()));
                $('#contract_total').val(formatNumber($('#contract_total').val()));
            });
            $('#contract_broker_fee_unit').change(function(e) {
                var contract_key_money = parseFloat($('#contract_key_money').val().replace(",", ""));
                var contract_cost = parseFloat($('#contract_cost').val().replace(",", ""));
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val().replace(",", ""));
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val().replace(",", ""));
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
                $('#contract_total').val(formatNumber($('#contract_total').val()));
            });
            $('#contract_ads_fee_unit').change(function(e) {
                var contract_key_money = parseFloat($('#contract_key_money').val().replace(",", ""));
                var contract_cost = parseFloat($('#contract_cost').val().replace(",", ""));
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val().replace(",", ""));
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val().replace(",", ""));
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
                $('#contract_total').val(formatNumber($('#contract_total').val()));
            });
            $('#contract_key_money_unit').change(function(e) {
                var contract_key_money = parseFloat($('#contract_key_money').val().replace(",", ""));
                var contract_cost = parseFloat($('#contract_cost').val().replace(",", ""));
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val().replace(",", ""));
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val().replace(",", ""));
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
                $('#contract_total').val(formatNumber($('#contract_total').val()));
            });
            $('#contract_cost').change(function(e) {
                $('#contract_cost').val($('#contract_cost').val().replace(",", ""));
                checkPrice($('#contract_cost'));
                var contract_key_money = parseFloat($('#contract_key_money').val().replace(",", ""));
                var contract_cost = parseFloat($('#contract_cost').val());
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val().replace(",", ""));
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val().replace(",", ""));
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
                $('#contract_total').val(formatNumber($('#contract_total').val()));
                $('#contract_cost').val(formatNumber($('#contract_cost').val()));
            });
            $('#contract_plus_money').change(function(e) {
                var contract_key_money = parseFloat($('#contract_key_money').val().replace(",", ""));
                var contract_cost = parseFloat($('#contract_cost').val().replace(",", ""));
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val().replace(",", ""));
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val().replace(",", ""));
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
                $('#contract_total').val(formatNumber($('#contract_total').val()));
            });
            $('#calculator').click(function(e) {

                $("input[name^='contract_plus_money[]']").keyup(function(e) {
                    var contract_key_money = parseFloat($('#contract_key_money').val().replace(",", ""));
                    var contract_cost = parseFloat($('#contract_cost').val().replace(",", ""));
                    var contract_key_money_unit = $('#contract_key_money_unit').val();
                    if (contract_key_money_unit == 'ヵ月') {
                        contract_key_money = contract_key_money * contract_cost;
                    }
                    var contract_broker_fee = parseFloat($('#contract_broker_fee').val().replace(",", ""));
                    var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                    if (contract_broker_fee_unit == 'ヵ月') {
                        contract_broker_fee = contract_broker_fee * contract_cost;
                    }
                    var contract_ads_fee = parseFloat($('#contract_ads_fee').val().replace(",", ""));
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
                    $('#contract_total').val(formatNumber($('#contract_total').val()));
                });
            });
            $('#contract_key_money').change(function(e) {
                $('#contract_key_money').val($('#contract_key_money').val().replace(",", ""));
                checkPrice($('#contract_key_money'));
                var contract_key_money = parseFloat($('#contract_key_money').val());
                var contract_cost = parseFloat($('#contract_cost').val().replace(",", ""));
                var contract_key_money_unit = $('#contract_key_money_unit').val();
                if (contract_key_money_unit == 'ヵ月') {
                    contract_key_money = contract_key_money * contract_cost;
                }
                var contract_broker_fee = parseFloat($('#contract_broker_fee').val().replace(",", ""));
                var contract_broker_fee_unit = $('#contract_broker_fee_unit').val();
                if (contract_broker_fee_unit == 'ヵ月') {
                    contract_broker_fee = contract_broker_fee * contract_cost;
                }
                var contract_ads_fee = parseFloat($('#contract_ads_fee').val().replace(",", ""));
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
                $('#contract_key_money').val(formatNumber($('#contract_key_money').val()));
                $('#contract_total').val(formatNumber($('#contract_total').val()));
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
                        $('#keep_active_tab').val(id);
                    }
                });

            });
            $('#for_client').click(function() {
                $('#edit_order').css('display', 'none');
                $('#customer').css('display', 'none');
                $("#page").css('display', 'none');
                $("#done").css('display', 'none');
                $('#transaction table:first').css('display', 'none');
                $('#client_info ul li').each(function() {
                    if ($(this).attr('title') == 'history' || $(this).attr('title') == 'introduce' || $(this).attr('title') == 'contract') {
                        $(this).css('display', 'none');

                    }
                });
                $('#security_code').val('require');
                $('#for_client').css('display', 'none');
                $('#client_detail #history').css('display', 'none');
                //$('#client_detail #aspirations').css('display', 'none');
                $('#client_detail #introduce').css('display', 'none');
                $('#client_detail #contract').css('display', 'none');
                $('#menu_container ul').css('display', 'none');
                $('#content_order_title').css('display', 'none');
                $('#client_info ul li:first').click();
                //prevent client click back button
                history.pushState(null, null, 'create_order.php');
                window.addEventListener('popstate', function(event) {
                    history.pushState(null, null, 'create_order.php');
                });
                $('#logo #logo_text a').click(function(e) {
                    e.preventDefault();
                });
            });
            $('#edit_order ul li').click(function() {
                $('#edit_order ul li').each(function() {
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
                if ($('#order_detail').find('#edit_room').attr('class') == 'active') {
                    $('#order_detail').find('#edit_room').removeClass('active');
                    $('#order_detail').find('#edit_room').addClass('inactive');
                }
                if ($('#order_detail').find('#edit_client').attr('class') == 'active') {
                    $('#order_detail').find('#edit_client').removeClass('active');
                    $('#order_detail').find('#edit_client').addClass('inactive');
                }
                $('#order_detail').find('div').each(function() {
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
                    if ($('#security_code').val() != "") {
                        var security_code = prompt('Please input security code', "");

                        if (security_code == "1234") {
                            $('#transaction').submit();
                        } else if (security_code == null) {
                            e.preventDefault();
                        } else {
                            alert("Wrong code, try again.");
                            $('#client_detail').find('#save').click();
                            e.preventDefault();
                        }
                    } else {
                        $('#transaction').submit();
                    }
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
                        var log_revisit_bk = $('#log_revisit_bk').val();
                        var log_revisit_arr = $('#log_revisit_arr').val();
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
                            log_introduction: log_introduction, log_flyer: log_flyer, log_line: log_line, log_revisit: log_revisit, log_revisit_arr: log_revisit_arr, log_revisit_bk: log_revisit_bk, source_id: source_id,
                            log_status_appointment: log_status_appointment, client_id: client_id, order_id: order_id, action: 'customer', task: 'history'},
                        function(result) {
                            var json = $.parseJSON(result);
                            if (json.id != "")
                                alert('保存');
                            else if (json.id == "")
                                alert("更新が完了しました。");
                            $('#log_revisit_bk').val(log_revisit);
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

//                            if (sendmail && ($('#contract_application').attr('checked') || $('#contract_transaction_finish').attr('checked'))) {
//                                $.post("include/mail_ajax.php", {application: $('#contract_application').attr('checked'), transaction: $('#contract_transaction_finish').attr('checked'), order_id: $('#order_id').val()},
//                                function(result) {
//                                    //                            alert(result);
//                                }
//                                );
//                            }
                        });
                    }
                });
            });
            $('#done').click(function() {
                showloadgif();
                window.location.href = "manage_order.php";
            });
            $('#contract_deposit_1').change(function() {
                $('#contract_deposit_1').val($('#contract_deposit_1').val().replace(",", ""));
                checkPrice($('#contract_deposit_1'));
                $('#contract_deposit_1').val(formatNumber($('#contract_deposit_1').val()));
            });
            $('#contract_deposit_2').change(function() {
                $('#contract_deposit_2').val($('#contract_deposit_2').val().replace(",", ""));
                checkPrice($('#contract_deposit_2'));
                $('#contract_deposit_2').val(formatNumber($('#contract_deposit_2').val()));
            });
            $('#client_income').change(function() {
                $('#client_income').val($('#client_income').val().replace(",", ""));
                checkPrice($('#client_income'));
                $('#client_income').val(formatNumber($('#client_income').val()));
            });
            $('#client_rent').change(function() {
                $('#client_rent').val($('#client_rent').val().replace(",", ""));
                checkPrice($('#client_rent'));
                $('#client_rent').val(formatNumber($('#client_rent').val()));
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
                        $('#city_id option').removeAttr('selected');
                    });
                    $('#district').each(function() {
                        $('#district option').removeAttr('selected');
                    });
                    $('#street').each(function() {
                        $('#street option').removeAttr('selected');
                    });
                    $('#ward').each(function() {
                        $('#ward option').removeAttr('selected');
                    });

                    $('#client_occupation').val("");
                    $('#client_company').val("");
                    $('#client_income').val("");
                    //$('#client_room_type').val(json.client_room_type);
                    $('#client_room_type').each(function() {
                        $('#client_room_type option').removeAttr('selected');
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
{nocache}

    <div id="edit_order">
        <ul>
            <li class="select_menu" title="edit_client">顧客情報編集</li>          
        </ul>
    </div>
    <div id="order_detail">                    
        {literal}
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#order_name').attr('disabled', 'disabled');
                    $('#filter_broker').keyup(function(e) {
                        var filter = $('#filter_broker').val();
                        $('#error_broker').css("color", '#ddd');
                        //showloadgif();
                        $.post("include/function_ajax.php", {filter: filter, action: 'create_order', task: 'getBrokerFilter'},
                        function(result) {
                            if (result) {
                                //  hideloadgif();
                                $('#broker_id').empty();
                                $('#broker_id').html(result);
                                $('#yoke_muscle').click();
                            } else {
                                // hideloadgif();
                                $('#broker_id').empty();
                                // $('#house_id').empty();
                                //$('#house_description').html("");                                    
                                $('#error_broker').html("仲介会社が見つかりませんでした。");
                                $('#error_broker').css("color", '');
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
                        $('#error_edit').html("");
                        $('#error_house').html("");
                        $('#error_order_name').html("");
                        $('#error_room').html("");
                        var client_id = $('#client_id').val();
                        var order_id = $('#order_id').val();
                        var broker_id = $('#broker_id').val();
                        var house_id = $('#house_id').val();
                        var broker_id_bk = $('#broker_id_bk').val();
                        var house_id_bk = $('#house_id_bk').val();
                        var order_name = $('#order_name').val();
                        var room_id = $('#room_id').val();
                        var room_id_bk = $('#room_id_bk').val();
                        var order_rent_cost = $('#order_rent_cost').val();
                        var order_comment = $('#order_comment').val();
                        var change_house_array = $('#change_house_array').val();

                        if (broker_id == "" || broker_id == null) {
                            $('#error_broker').html('仲介会社を選択ください。');
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
                            $('#error_order_name').html('オーダー名は必要です。.');
                            e.preventDefault();
                            return false;
                        } else {
                            //showloadgif();

                            $.post("include/function_ajax.php", {house_id: house_id, room_id: room_id, broker_id: broker_id,
                                order_rent_cost: order_rent_cost, order_comment: order_comment, change_house_array: change_house_array,
                                room_id_bk: room_id_bk, house_id_bk: house_id_bk, broker_id_bk: broker_id_bk,
                                client_id: client_id, order_id: order_id, action: 'create_order', task: 'edit_room'},
                            function(result) {
                                var json = $.parseJSON(result);
                                if (json) {
                                    alert('保存');
                                    $('#room_id_bk').val(room_id);
                                    $('#house_id_bk').val(house_id);
                                    $('#broker_id_bk').val(broker_id);
                                    $('#room_administrative_expense').val(json);
                                }
                                else {
                                    $('#error_edit').html('更新に失敗しました。もう一度試してください。 !!!');
                                }
                            });
                        }

                    });
                    $('#broker_id').change(function() {
                        //active form
                        $('#error_broker').html("");
                        $('#error_house').html("");
                        var broker_id = $('#broker_id').val();
                        if (broker_id) {
                            $('#yoke_muscle').click();
                        } else {
                        }
                    });
                    var broker_id = $('#broker_id').val();

                    var house_id = $('#house_id').val();

                    // var room_id ={/literal}{if $room_id ne ""}{$room_id}{else}0{/if}{';'}{literal}
                    var room_id = $('#room_id_bk').val();

                    $.post('include/function_ajax.php', {house_id: house_id, action: 'create_order', task: 'getContentHouse'},
                    function(result) {
                        var json = $.parseJSON(result);
                        $('#house_description').html(json.house_description);
                        get_room(house_id, room_id);
                    });
                });
            </script>
        {/literal}

        <div id="edit_client" class="active">
            {if $error|@count gt 0}
                {foreach from=$error item=val}
                    <div class="error">{$val}</div>
                {/foreach}
            {/if}
            {if $notify ne ""}
                {$notify}
            {/if}

            {if $errorHouseExist ne ""}
                <div class="error"></div>
            {/if}
            <form  method="post" id="transaction">
                <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="">
                    <tr>
                        <td>顧客検索</td>
                        <td><input type="text" id="filter" name="filter"value="{$filter}" style="height: 26px; width: 300px;" placeholder="顧客名を入力"/>
                            <span>
                                <input type='submit' class='btn-search' value='送信'  name="submit"/>&nbsp;                     
                            </span>
                        </td>
                    <input type="hidden" id="step" name="step" value="registry"/><div style="float: right;"><input type="button" value="完了" id="done" name="done"class='btn-search'/></div>
                    <input type="hidden" id="page_number" name="page_number" value="{$page_number}"/>
                    </tr>
                </table>

                <div style="margin-bottom:10px;" id="page">
                    <center>
                        ページ:
                        {for $i=1 to $totalPage }
                            {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<span onclick="selectpage({$i})" style='margin-left: 10px;color: black;cursor: pointer;'>{$i}{/if}</span>
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
                            <li {if $item@iteration is div by 2}class="odd"{/if} onclick="selectCustomer({$item.id})">{$item.client_address|truncate:20}</li>
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
                        <li class="noselect_menu" title="introduce">部屋情報編集</li>
                        <li class="noselect_menu" title="contract">申込・契約</li>
                    </ul>
                </div>
                <div id="client_detail">

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
                        {* <form action="edit_order.php" method="post"> *}       
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
                                <td class='form1' nowrap>会社名:</td>
                                <td class='form2'> <input type='text' id="client_company" name="client_company"  value="{$client_company}" style="height: 26px; width: 215px;"/></td>

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
                                <td class='form2'>
                                    <select id="client_occupation" name="client_occupation" style="margin-left: 0.5%;height:28px; width: 115px;">
                                        <option value=""></option>
                                        {foreach from=$careers item=career}
                                            <option value="{$career.id}" {if $career.id eq $client_occupation}selected="selected"{/if}>{$career.name}</option>        
                                        {/foreach}
                                    </select>
                                    <!--<input type="text" id="client_occupation" name="client_occupation" value="{$client_occupation}" style="height: 26px; width: 215px;"/>-->
                                </td>
                                <td class='form1' nowrap>番地:</td>
                                <td class='form2'> <input type='text' id="client_address" name="client_address" value="{$client_address}" style="height: 26px; width: 215px;"/></td>
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
                                <td class='form2'> 
                                    <select id="client_reason_change" name="client_reason_change" style="margin-left: 0.5%;height:28px; width: 115px;">
                                        <option value=""></option>
                                        {foreach from=$reasons item=reason}
                                            <option value="{$reason.id}" {if $reason.id eq $client_reason_change}selected="selected"{/if}>{$reason.name}</option>        
                                        {/foreach}
                                    </select>
                                    <!--<input type='text' id="client_reason_change" name="client_reason_change" value="{$client_reason_change}"style="height: 26px; width: 215px;"/>-->
                                </td>
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
                        {* <form action="edit_order.php" method="post">  *}      
                        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                            <tr>
                                <td colspan="2" style="text-align: right;">反響種別</td>
                                <td colspan="2">
                                    <input type="radio" checked="checked" id="log_time_call_type" name="choose_contact_type"/><label for="log_time_call_type">ＴＥＬ</label>
                                    <input type="radio" id="log_time_mail_type" name="choose_contact_type"/><label for="log_time_mail_type">Eメール</label>
                                    <input type="radio" id="log_time_arrive_company_type" name="choose_contact_type"/><label for="log_time_arrive_company_type">来店</label>
                                </td>
                            </tr>
                            <tr>
                                <td class='form1' id="contact_method">通話日時: </td>
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
                                <td class='form1'nowrap>予約日付:</td>
                                <td class='form2'>
                                    <input type="text" id="log_date_appointment_from_date" name="log_date_appointment_from_date" value="{$log_date_appointment_from_date}"style="height: 26px; width: 115px;"/>
                                    <input type="text" id="log_date_appointment_from" name="log_date_appointment_from" value="{$log_date_appointment_from}"style="height: 26px; width: 95px;"/>
                                </td>
                                <td class='form1' nowrap>ご来店:</td>
                                <td class='form2'>
                                    <input type='radio' id="log_status_appointment_yes" name="log_status_appointment" value="1" {if $log_status_appointment eq '1'}checked="checked" {/if}/><label for="log_status_appointment_yes">済</label> &nbsp; &nbsp; 
                                    <input type='radio' id="log_status_appointment_no" name="log_status_appointment" value="0" {if $log_status_appointment eq '0'}checked="checked" {/if}/><label for="log_status_appointment_no">未済</label>
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
                                <td class='form1' nowrap></td>
                                <td class='form2'></td>
                            </tr>

                            <tr>
                                <td class='form1'>TEL:</td>
                                <td class='form2'><input type="checkbox" value="1" id="log_tel" name="log_tel" {if $log_tel eq '1'}checked="checked" {/if} /></td>
                                <td class='form1' nowrap>{*現況:*}</td>
                                <td class='form2'> {*<input type='checkbox' value="1" id="log_tel_status" name="log_tel_status" {if $log_tel_status eq '1'}checked="checked" {/if}style="height: 26px; width: 15px;"/>*}</td>
                            </tr>
                            <tr>
                                <td class='form1'>MAIL:</td>
                                <td class='form2'><input type="checkbox" value="1" id="log_mail" name="log_mail" {if $log_mail eq '1'}checked="checked" {/if}/></td>
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
                                <td class='form2' id="log_revisit_container" style="width: 336px;">
                                    {if count($arr)}
                                        {foreach from=$arr key=i item=revisit_item}
                                            {if $i == 0}
                                                <input type='text' class="log_revisit" id="log_revisit" name="log_revisit[]" value="{$revisit_item}" style="height: 26px; width: 215px;"/>
                                                <button type="button" id="add_log">Add</button>
                                            {else}
                                                <div type="text" style="height: 26px; width: 250px; margin-top: 5px;float: left;" id="revisit_item_{$i}">
                                                    <input type="text" value="{$revisit_item}" class="log_revisit" style="height: 26px; width: 215px;display: block;margin-right: 3px;float: left;" name="log_revisit[]" >
                                                    <img src="include/images/DeleteRed.png" style="height: 26px; width: 26px;float: left;cursor: pointer;" onclick="removeLogvisit('revisit_item_{$i}');">
                                                </div>
                                            {/if}
                                        {/foreach}
                                    {else}
                                        <input type='text' class="log_revisit" id="log_revisit" name="log_revisit[]" style="height: 26px; width: 215px;"/>
                                        <button type="button" id="add_log">Add</button>
                                    {/if}

                                </td>
                                <td class='form1' nowrap>備考:</td>
                                <td class='form2'> <input type='text' id="log_comment" name="log_comment" value="{$log_comment}" style="height: 26px; width: 215px;"/></td>
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
                                        <input type="hidden" id="log_revisit_arr" name="log_revisit_arr" value="{$log_revisit_arr}"/>
                                        <input type="hidden" id="log_revisit_bk" name="log_revisit_bk" value="{$log_revisit}"/>
                                    </div>                        
                                </td>
                            </tr>
                        </table>
                        {* </form>*}
                    </div>
                    <div id="aspirations" class="inactive">
                        {*<form action="edit_order.php" method="post">    *}    
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
                                <td class='form2'>
                                    <select id="aspirations_build_time" name="aspirations_build_time" style="height:26px; width: 215px;">
                                        <option value="">指定なし</option>
                                        <option value="0"{if $aspirations_build_time eq "0"} selected{/if}>新築</option>
                                        <option value="3"{if $aspirations_build_time eq "3"} selected{/if}>3年以内</option>
                                        <option value="5"{if $aspirations_build_time eq "5"} selected{/if}>5年以内</option>
                                        <option value="10"{if $aspirations_build_time eq "10"} selected{/if}>10年以内</option>
                                        <option value="15"{if $aspirations_build_time eq "15"} selected{/if}>15年以内</option>
                                        <option value="20"{if $aspirations_build_time eq "20"} selected{/if}>20年以内</option>
                                    </select> 
                                </td>
                                <td class='form1' nowrap>エリア・沿線:</td>
                                <td class='form2'> 
                                    <!--<input type='text' id="aspirations_area" name="aspirations_area" value="{$aspirations_area}"style="height: 26px; width: 215px;"/>-->
                                    <select id="pref" name="aspirations_area" style="height:28px; width: 90px;" onChange="setMenuItem(0, this[this.selectedIndex].value)">
                                        <option value="0" >-----
                                        <option value="1" {if $aspirations_area eq '1'}selected="selected"{/if}>北海道
                                        <option value="2" {if $aspirations_area eq '2'}selected="selected"{/if}>青森県
                                        <option value="3" {if $aspirations_area eq '3'}selected="selected"{/if}>岩手県
                                        <option value="4" {if $aspirations_area eq '4'}selected="selected"{/if}>宮城県
                                        <option value="5" {if $aspirations_area eq '5'}selected="selected"{/if}>秋田県
                                        <option value="6" {if $aspirations_area eq '6'}selected="selected"{/if}>山形県
                                        <option value="7" {if $aspirations_area eq '7'}selected="selected"{/if}>福島県
                                        <option value="8" {if $aspirations_area eq '8'}selected="selected"{/if}>茨城県
                                        <option value="9" {if $aspirations_area eq '9'}selected="selected"{/if}>栃木県
                                        <option value="10" {if $aspirations_area eq '10'}selected="selected"{/if}>群馬県
                                        <option value="11" {if $aspirations_area eq '11'}selected="selected"{/if}>埼玉県
                                        <option value="12" {if $aspirations_area eq '12'}selected="selected"{/if}>千葉県
                                        <option value="13" {if $aspirations_area eq '13'}selected="selected"{/if}>東京都
                                        <option value="14" {if $aspirations_area eq '14'}selected="selected"{/if}>神奈川県
                                        <option value="15" {if $aspirations_area eq '15'}selected="selected"{/if}>新潟県
                                        <option value="16" {if $aspirations_area eq '16'}selected="selected"{/if}>富山県
                                        <option value="17" {if $aspirations_area eq '17'}selected="selected"{/if}>石川県
                                        <option value="18" {if $aspirations_area eq '18'}selected="selected"{/if}>福井県
                                        <option value="19" {if $aspirations_area eq '19'}selected="selected"{/if}>山梨県
                                        <option value="20" {if $aspirations_area eq '20'}selected="selected"{/if}>長野県
                                        <option value="21" {if $aspirations_area eq '21'}selected="selected"{/if}>岐阜県
                                        <option value="22" {if $aspirations_area eq '22'}selected="selected"{/if}>静岡県
                                        <option value="23" {if $aspirations_area eq '23'}selected="selected"{/if}>愛知県
                                        <option value="24" {if $aspirations_area eq '24'}selected="selected"{/if}>三重県
                                        <option value="25" {if $aspirations_area eq '25'}selected="selected"{/if}>滋賀県
                                        <option value="26" {if $aspirations_area eq '26'}selected="selected"{/if}>京都府
                                        <option value="27" {if $aspirations_area eq '27'}selected="selected"{/if}>大阪府
                                        <option value="28" {if $aspirations_area eq '28'}selected="selected"{/if}>兵庫県
                                        <option value="29" {if $aspirations_area eq '29'}selected="selected"{/if}>奈良県
                                        <option value="30" {if $aspirations_area eq '30'}selected="selected"{/if}>和歌山県
                                        <option value="31" {if $aspirations_area eq '31'}selected="selected"{/if}>鳥取県
                                        <option value="32" {if $aspirations_area eq '32'}selected="selected"{/if}>島根県
                                        <option value="33" {if $aspirations_area eq '33'}selected="selected"{/if}>岡山県
                                        <option value="34" {if $aspirations_area eq '34'}selected="selected"{/if}>広島県
                                        <option value="35" {if $aspirations_area eq '35'}selected="selected"{/if}>山口県
                                        <option value="36" {if $aspirations_area eq '36'}selected="selected"{/if}>徳島県
                                        <option value="37" {if $aspirations_area eq '37'}selected="selected"{/if}>香川県
                                        <option value="38" {if $aspirations_area eq '38'}selected="selected"{/if}>愛媛県
                                        <option value="39" {if $aspirations_area eq '39'}selected="selected"{/if}>高知県
                                        <option value="40" {if $aspirations_area eq '40'}selected="selected"{/if}>福岡県
                                        <option value="41" {if $aspirations_area eq '41'}selected="selected"{/if}>佐賀県
                                        <option value="42" {if $aspirations_area eq '42'}selected="selected"{/if}>長崎県
                                        <option value="43" {if $aspirations_area eq '43'}selected="selected"{/if}>熊本県
                                        <option value="44" {if $aspirations_area eq '44'}selected="selected"{/if}>大分県
                                        <option value="45" {if $aspirations_area eq '45'}selected="selected"{/if}>宮崎県
                                        <option value="46" {if $aspirations_area eq '46'}selected="selected"{/if}>鹿児島県
                                        <option value="47" {if $aspirations_area eq '47'}selected="selected"{/if}>沖縄県
                                    </select>
                                    <select id="s0" name="aspirations_area2" style="height:28px; width: 90px;" onChange="setMenuItem(1, this[this.selectedIndex].value)">
                                        <option selected>----
                                    </select> 
                                    <select id="s1" name="aspirations_area3" style="height:28px; width: 90px;">
                                        <option selected>----
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class='form1'>面積:</td>
                                <td class='form2'>
<!--                                    <input type="text" id="aspirations_size" name="aspirations_size" value="{$aspirations_size}" style="height: 26px; width: 90px;"/> ㎡ 〜 
                                    <input type="text" id="aspirations_size2" name="aspirations_size2" value="{$aspirations_size2}" style="height: 26px; width: 90px;"/> ㎡-->
                                    <select id="aspirations_size" name="aspirations_size" style="height:28px; width: 90px;">
                                        <option value="">指定なし</option>
                                        <option value="20 m²" {if $aspirations_size eq '20 m²'}selected="selected"{/if}>20 m²</option>
                                        <option value="25 m²" {if $aspirations_size eq '25 m²'}selected="selected"{/if}>25 m²</option>
                                        <option value="30 m²" {if $aspirations_size eq '30 m²'}selected="selected"{/if}>30 m²</option>
                                        <option value="35 m²" {if $aspirations_size eq '35 m²'}selected="selected"{/if}>35 m²</option>
                                        <option value="40 m²" {if $aspirations_size eq '40 m²'}selected="selected"{/if}>40 m²</option>
                                        <option value="50 m²" {if $aspirations_size eq '50 m²'}selected="selected"{/if}>50 m²</option>
                                        <option value="60 m²" {if $aspirations_size eq '60 m²'}selected="selected"{/if}>60 m²</option>
                                        <option value="70 m²" {if $aspirations_size eq '70 m²'}selected="selected"{/if}>70 m²</option>
                                        <option value="80 m²" {if $aspirations_size eq '80 m²'}selected="selected"{/if}>80 m²</option>
                                        <option value="90 m²" {if $aspirations_size eq '90 m²'}selected="selected"{/if}>90 m²</option>
                                    </select>〜
                                    <select id="aspirations_size2" name="aspirations_size2" style="height:28px; width: 90px;">
                                        <option value="">指定なし</option>
                                        <option value="20 m²" {if $aspirations_size2 eq '20 m²'}selected="selected"{/if}>20 m²</option>
                                        <option value="25 m²" {if $aspirations_size2 eq '25 m²'}selected="selected"{/if}>25 m²</option>
                                        <option value="30 m²" {if $aspirations_size2 eq '30 m²'}selected="selected"{/if}>30 m²</option>
                                        <option value="35 m²" {if $aspirations_size2 eq '35 m²'}selected="selected"{/if}>35 m²</option>
                                        <option value="40 m²" {if $aspirations_size2 eq '40 m²'}selected="selected"{/if}>40 m²</option>
                                        <option value="50 m²" {if $aspirations_size2 eq '50 m²'}selected="selected"{/if}>50 m²</option>
                                        <option value="60 m²" {if $aspirations_size2 eq '60 m²'}selected="selected"{/if}>60 m²</option>
                                        <option value="70 m²" {if $aspirations_size2 eq '70 m²'}selected="selected"{/if}>70 m²</option>
                                        <option value="80 m²" {if $aspirations_size2 eq '80 m²'}selected="selected"{/if}>80 m²</option>
                                        <option value="90 m²" {if $aspirations_size2 eq '90 m²'}selected="selected"{/if}>90 m²</option>
                                    </select>
                                </td>
                                <td class='form1' nowrap>賃料:</td>
                                <td class='form2'> 
<!--                                    <input type='text' id="aspirations_rent_cost" name="aspirations_rent_cost" value="{$aspirations_rent_cost}" style="height: 26px; width: 90px;"/>円 〜 
                                    <input type='text' id="aspirations_rent_cost2" name="aspirations_rent_cost2" value="{$aspirations_rent_cost2}" style="height: 26px; width: 90px;"/>円-->
                                    <select id="aspirations_rent_cost" name="aspirations_rent_cost" style="height:28px; width: 90px;">
                                        <option value="">指定なし</option>
                                        <option value="5万円" {if $aspirations_rent_cost eq '5万円'}selected="selected"{/if}>5万円</option>
                                        <option value="6万円" {if $aspirations_rent_cost eq '6万円'}selected="selected"{/if}>6万円</option>
                                        <option value="7万円" {if $aspirations_rent_cost eq '7万円'}selected="selected"{/if}>7万円</option>
                                        <option value="8万円" {if $aspirations_rent_cost eq '8万円'}selected="selected"{/if}>8万円</option>
                                        <option value="9万円" {if $aspirations_rent_cost eq '9万円'}selected="selected"{/if}>9万円</option>
                                        <option value="10万円" {if $aspirations_rent_cost eq '10万円'}selected="selected"{/if}>10万円</option>
                                        <option value="11万円" {if $aspirations_rent_cost eq '11万円'}selected="selected"{/if}>11万円</option>
                                        <option value="12万円" {if $aspirations_rent_cost eq '12万円'}selected="selected"{/if}>12万円</option>
                                        <option value="13万円" {if $aspirations_rent_cost eq '13万円'}selected="selected"{/if}>13万円</option>
                                        <option value="14万円" {if $aspirations_rent_cost eq '14万円'}selected="selected"{/if}>14万円</option>
                                        <option value="15万円" {if $aspirations_rent_cost eq '15万円'}selected="selected"{/if}>15万円</option>
                                        <option value="20万円" {if $aspirations_rent_cost eq '20万円'}selected="selected"{/if}>20万円</option>
                                    </select>〜
                                    <select id="aspirations_rent_cost2" name="aspirations_rent_cost2" style="height:28px; width: 90px;">
                                        <option value="">指定なし</option>
                                        <option value="5万円" {if $aspirations_rent_cost2 eq '5万円'}selected="selected"{/if}>5万円</option>
                                        <option value="6万円" {if $aspirations_rent_cost2 eq '6万円'}selected="selected"{/if}>6万円</option>
                                        <option value="7万円" {if $aspirations_rent_cost2 eq '7万円'}selected="selected"{/if}>7万円</option>
                                        <option value="8万円" {if $aspirations_rent_cost2 eq '8万円'}selected="selected"{/if}>8万円</option>
                                        <option value="9万円" {if $aspirations_rent_cost2 eq '9万円'}selected="selected"{/if}>9万円</option>
                                        <option value="10万円" {if $aspirations_rent_cost2 eq '10万円'}selected="selected"{/if}>10万円</option>
                                        <option value="11万円" {if $aspirations_rent_cost2 eq '11万円'}selected="selected"{/if}>11万円</option>
                                        <option value="12万円" {if $aspirations_rent_cost2 eq '12万円'}selected="selected"{/if}>12万円</option>
                                        <option value="13万円" {if $aspirations_rent_cost2 eq '13万円'}selected="selected"{/if}>13万円</option>
                                        <option value="14万円" {if $aspirations_rent_cost2 eq '14万円'}selected="selected"{/if}>14万円</option>
                                        <option value="15万円" {if $aspirations_rent_cost2 eq '15万円'}selected="selected"{/if}>15万円</option>
                                        <option value="20万円" {if $aspirations_rent_cost2 eq '20万円'}selected="selected"{/if}>20万円</option>
                                    </select>
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

                        <div class="title"><label >登録完了致しました</label></div>
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
                                    <a href="javascript:void(0);" id="popup_create_broker" >管理会社</a>
                                    <div id="error_broker" class="error"></div>
                                </td>
                            </tr> 
                            {*<tr> 
                            {assign var=broker_link value='次のリンクで、新しい管理会社の情報を追加することができます。 <a href="./create_broker_company.php">管理会社登録</a>'}
                            <td colspan="2" nowrap><div>次のリンクで、新しい管理会社の情報を追加することができます。<a href="./create_broker_company.php">管理会社登録</a></div></td>
                            </tr>   *}         
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
                                    </select>
                                    <a href="javascript:void(0);" id="popup_create_house" >物件情報</a>
                                    <div id="error_house" class="error"></div>
                                </td>
                            </tr>
                            <tr>            
                                <td class='form1'>物件備考: </td>
                                <td class='form2'><textarea style="width: 340px;height: 129px;" disabled="1" id="house_description"></textarea></td>
                            </tr>
                            {* <tr>            
                            <td colspan="2"><div>次のリンクで、新しい物件情報を追加することができます。<a href="./create_house.php">物件登録</a></div></td>
                            </tr>*}

                            <tr>            
                                <td class='form1'>部屋選択: </td>
                                <td class='form2'><select id="room_id" name="room_id" style="height:26px; width: 215px;">
                                        <option value=""></option>
                                    </select>
                                    <a href="javascript:void(0);" id="popup_create_room" >部屋情報</a>
                                    <div id="error_room" class="error"></div>
                                </td>
                            </tr>
                            {* <tr>            
                            <td colspan="2"><div>次のリンクで、新しい物件情報を追加することができます。 <a href="./create_room.php">部屋情報</a></div></td>
                            </tr>*}
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
                                        <input type="hidden" id="yoke_muscle" name="yoke_muscle"/>
                                        <input type="hidden" id="room_id_bk" name="room_id_bk" value="{$room_id}"/>
                                        <input type="hidden" id="house_id_bk" name="house_id_bk" value="{$house_id}"/>
                                        <input type="hidden" id="broker_id_bk" name="broker_id_bk" value="{$broker_id}"/>                                    
                                        <input type="hidden" id="change_house_array" name="change_house_array" value="{$change_house_array}"/>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        {* </form>*}
                    </div>
                    <div id="contract" class="inactive">
                        {*<form action="edit_order.php" method="post">  *}      
                        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%" id="contract_plus">
                            <tr>
                                <td class='form1' nowrap>賃料:</td>
                                <td class='form2'> <input type='text' id="contract_cost" name="contract_cost" value="{$contract_cost}"style="height: 26px; width: 215px;"/>
                                    <label style="padding: 2% 5.5% 1% 5.5%;background-color: white;">円</label>
                                </td>
                                <td class='form1'>礼金:</td>
                                <td class='form2'><input type="text" id="contract_key_money" name="contract_key_money" value="{$contract_key_money}"style="height: 26px; width: 215px;"/>
                                    <select id="contract_key_money_unit" name="contract_key_money_unit" style="width: 16%;padding: 1% 0px 1% 0%;">
                                        <option value="円"{if $contract_key_money_unit eq "円"} selected{/if}>円</option>
                                        <option value="ヵ月"{if $contract_key_money_unit eq "ヵ月"} selected{/if}>ヵ月</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>                    
                                <td class='form1'>仲介手数料:</td>
                                <td class='form2'><input type="text" id="contract_broker_fee" name="contract_broker_fee" value="{$contract_broker_fee}"style="height: 26px; width: 215px;"/>
                                    <select id="contract_broker_fee_unit" name="contract_broker_fee_unit" style="width: 16%;padding: 1% 0px 1% 0%;">
                                        <option value="円"{if $contract_broker_fee_unit eq "円"} selected{/if}>円</option>
                                        <option value="ヵ月" {if $contract_broker_fee_unit eq "ヵ月"} selected{/if}>ヵ月</option>
                                    </select>
                                </td>
                                <td class='form1'>広告費:</td>
                                <td class='form2'><input type="text" id="contract_ads_fee" name="contract_ads_fee" value="{$contract_ads_fee}"style="height: 26px; width: 215px;"/>
                                    <select id="contract_ads_fee_unit" name="contract_ads_fee_unit" style="width: 16%;padding: 1% 0px 1% 0%;">
                                        <option value="円"{if $contract_ads_fee_unit eq "円"} selected{/if}>円</option>
                                        <option value="ヵ月"{if $contract_ads_fee_unit eq "ヵ月"} selected{/if}>ヵ月</option>
                                    </select>
                                </td>                                           
                            </tr>
                            <tr>                    
                                <td class='form1'>敷金・保証金（預かり）:</td>
                                <td class='form2'><input type="text" id="contract_deposit_1" name="contract_deposit_1" value="{$contract_deposit_1}"style="height: 26px; width: 215px;"/>
                                    <select id="contract_deposit1_money_unit" name="contract_deposit1_money_unit" style="width: 16%;padding: 1% 0px 1% 0%;">
                                        <option value="円"{if $contract_deposit1_money_unit eq "円"} selected{/if}>円</option>
                                        <option value="ヵ月"{if $contract_deposit1_money_unit eq "ヵ月"} selected{/if}>ヵ月</option>
                                    </select></td>
                                <td class='form1' nowrap>敷金・保証金（償却）:</td>
                                <td class='form2'><input type="text" id="contract_deposit_2" name="contract_deposit_2"value="{$contract_deposit_2}" style="height: 26px; width: 215px;"/>
                                    <select id="contract_deposit2_money_unit" name="contract_deposit2_money_unit" style="width: 16%;padding: 1% 0px 1% 0%;">
                                        <option value="円"{if $contract_deposit2_money_unit eq "円"} selected{/if}>円</option>
                                        <option value="ヵ月"{if $contract_deposit2_money_unit eq "ヵ月"} selected{/if}>ヵ月</option>
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
                                <td class='form1'>店舗:
                                
                                </td>
                                <td class='form2'>
                                    <select id="agent_id"name="agent_id"style="height: 26px; width: 95px;">
                                        <option value=""></option>
                                        {foreach from=$agents item=agent}
                                            <option value="{$agent.id}">{$agent.agent_name}</option>
                                        {/foreach}
                                    </select>
                                    按分先:
                                    <select id="partner_id"name="partner_id" style="height:26px; width: 95px;">
                                        <option value=""></option>
                                        {foreach from=$partners item=partner}
                                            <option value="{$partner.id}"{if $partner.id eq $partner_id}selected{/if}>{$partner.user_lname} {$partner.user_fname} </option>
                                        {/foreach}
                                    </select>
                                </td>
                                <td class='form1' nowrap>仲介手数料:</td>
                                <td class='form2'>
                                    <input type="text" id="partner_percent"name="partner_percent" value="{$partner_percent}" style="height: 23px; width: 100px;margin-left: 0.5%"/>                                    
                                    広告料:
                                    <input type="text" id="partner_ads"name="partner_ads" value="{$partner_ads}" style="height: 23px; width: 100px;margin-left: 0.5%"/>
                                    <label style="float: right;margin: 0% 6% 0 0;">円</label>
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
                                <td class='form2'><input type="checkbox" value="1" onClick="javascript:sendMail(this);" id="contract_transaction_finish" name="contract_transaction_finish" {if $contract_transaction_finish eq '1'}checked="checked"{/if}style="height: 26px; width: 15px;"/></td>
                                <td class='form1' nowrap>キャンセル:</td>
                                <td class='form2'>
                                    <input type="checkbox" value="1" id="contract_cancel" name="contract_cancel" {if $contract_cancel eq '1'}checked="checked"{/if}style="height: 26px; width: 15px;"/>
                                    <input type="text" id="contract_cancel_date"name="contract_cancel_date" value="{$contract_cancel_date}"placeholder="キャンセル日" style="height: 26px; width: 215px;position: absolute;margin-left: 1%;"/>
                                </td>
                            </tr>
                            <tr>                    
                                <td class='form1'></td>
                                <td class='form2'>
                                    <input type="button" id="add" class='btn-signup' name="add" value="その他、付帯" style="width: 140px;"/> 
                                </td>       
                                <td class='form1' nowrap>自社物件: </td>
                                <td class='form2'><input type="checkbox" value="1" id="contract_ambition" name="contract_ambition" {if $contract_ambition eq '1'}checked="checked"{/if}style="height: 26px; width: 15px;"/></td>
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
                                            <select name="type">
                                                <option value="xls">XLS</option>
                                                <option value="csv">CSV</option>
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
                        <input type="button" class="btn-signup" value="For Client" id="for_client" name="for_client" style="width: 110px;margin-right: 1%;background: #617AAC;"/>
                        <input type="submit" class='btn-signup' value="保存" id="save" name="save" style="width: 100px;background: #617AAC;"/>                        
                        <input type="hidden" value="{$keep_active_tab}" id="keep_active_tab" name="keep_active_tab"/>
                        <input type="hidden" value="" id="security_code" />
                    </div>
            </form>
            <input type="hidden" id="cus_id" name="cus_id" value="{$client_id}"/>
        </div>
    </div>
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
        #edit_order ul li.select_menu{
            background: url(include/images/bg-btn-forget.gif) repeat-x;
            float: left;
            //background: beige;
            padding: 0px;
            width: 100%;
            cursor: pointer;
            text-align: center;
        }
        #edit_order ul li.noselect_menu{
            float: left;
            background: beige;
            padding: 0px;
            width: 50%;
            cursor: pointer;
            text-align: center;
        }
        #edit_order ul li:hover{

            background: url(include/images/bg-btn-forget.gif) repeat-x;

        }
        #edit_order{
            width: 100%; 
            padding-bottom: 4%;
        }
    </style>
    <script type="text/javascript">
        function openImport() {
            $('#export_form').show();
            $('#order_table').hide();
        }
        function closeImport() {
            $('#export_form').hide();
            $('#order_table').show();
        }
        function sendMail(el) {
//                if (el.checked && confirm("Do you want to send mail?")) {
//                    sendmail = 1;
//                }
        }
        $(document).ready(function() {
            //active for field selected
            var kept_active = $('#keep_active_tab').val();
            if (kept_active != "") {
                //set li click
                $('#client_info ul li').each(function() {
                    if ($(this).attr('class') == 'select_menu') {
                        $(this).removeClass('select_menu');
                        $(this).addClass('noselect_menu');
                    }
                });
                $('#client_info ul li').each(function() {
                    if ($(this).attr('title') == kept_active) {
                        $(this).removeClass('noselect_menu');
                        $(this).addClass('select_menu');
                    }
                });
                //end li click
                $('#basic').removeClass('active');
                $('#basic').addClass('inactive');

                $('#' + kept_active).removeClass('inactive');
                $('#' + kept_active).addClass('active');
            }
            //end active field        
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
                                                            $('#error_introduce_house_id').html("物件名のキーワードが見つかりませんでした。");
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
                                                    $('#history table').find('tr:nth-child(5)').css('display', '');
                                                    $('#log_time_mail').val('');
                                                    $('#log_time_mail_date').val('');
                                                    $('#log_time_arrive_company').val('');
                                                    $('#log_time_arrive_company_date').val('');
                                                    $('#log_mail').removeAttr('checked');
                                                    $('#log_mail_status').removeAttr('checked');
                                                    $('#log_contact_head_office').removeAttr('checked');
                                                    $('#contact_method').html('通話日時:');
                                                    $('#log_tel').attr('checked', "checked");
                                                    //$('#log_mail').attr('checked', "");
                                                    $('#log_mail').removeAttr('checked');
                                                });
                                                $('#log_time_mail_type').click(function() {
                                                    //$('#history table').find('tr:nth-child(4)').css('display', '');
                                                    $('#history table').find('tr:nth-child(2)').css('display', '');
                                                    $('#history table').find('tr:nth-child(3)').css('display', 'none');
                                                    $('#history table').find('tr:nth-child(7)').css('display', 'none');
                                                    $('#history table').find('tr:nth-child(8)').css('display', '');
                                                    $('#history table').find('tr:nth-child(5)').css('display', '');
                                                    //$('#log_time_call').val('');
                                                    // $('#log_time_call_date').val('');
                                                    $('#log_time_arrive_company').val('');
                                                    $('#log_time_arrive_company_date').val('');
                                                    $('#log_tel').removeAttr('checked');
                                                    $('#log_tel_status').removeAttr('checked');
                                                    $('#log_contact_head_office').removeAttr('checked');
                                                    $('#contact_method').html('メール送信時刻:');
                                                    $('#log_mail').attr('checked', "checked");
                                                    // $('#log_tel').attr('checked', "");
                                                    $('#log_tel').removeAttr('checked');
                                                });
                                                $('#log_time_arrive_company_type').click(function() {
                                                    $('#history table').find('tr:nth-child(3)').css('display', '');
                                                    $('#history table').find('tr:nth-child(2)').css('display', 'none');
                                                    $('#history table').find('tr:nth-child(4)').css('display', 'none');
                                                    $('#history table').find('tr:nth-child(7)').css('display', 'none');
                                                    $('#history table').find('tr:nth-child(8)').css('display', 'none');
                                                    $('#history table').find('tr:nth-child(5)').css('display', 'none');
                                                    $('#log_date_appointment_from_date').val('');
                                                    $('#log_date_appointment_from').val('');
                                                    $('#log_time_mail').val('');
                                                    $('#log_time_mail_date').val('');
                                                    $('#log_time_call').val('');
                                                    $('#log_time_call_date').val('');
                                                    $('#log_mail').removeAttr('checked');
                                                    $('#log_mail_status').removeAttr('checked');
                                                    $('#log_tel').removeAttr('checked');
                                                    $('#log_tel_status').removeAttr('checked');
                                                });
                                                if ($('#log_tel').is(':checked')) {
                                                    $('#log_time_call_type').click();
                                                }
                                                else if ($('#log_mail').is(':checked')) {
                                                    $('#log_time_mail_type').click();
                                                } else if ($('#log_time_arrive_company').val() != "" || $('#log_time_arrive_company_date').val() != "") {
                                                    $('#log_time_arrive_company_type').click();
                                                } else {
                                                    $('#log_tel').attr('checked', "checked");
                                                }
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
{/nocache}
{literal}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#back').click(function() {
                window.location.href = "manage_order.php";
            });
        });
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
<div id="loadgif">Loading...</div>
<div id="popup" style="left: 710px; position: absolute; top: 127px; z-index: 9999; opacity: 1; display: none;">
    <span class="button b-close"><span>X</span></span>
    <center id="popup_content"></center>
</div>
{literal}
    <script type="text/javascript">
        function selectpage(page) {
            $('#page_number').val(page);
            $('#search').click();
        }
        ;
        (function($) {
            $(function() {
                //broker
                $('#popup_create_broker').bind('click', function(e) {
                    e.preventDefault();
                    $.get('popup_create_broker.php', function(result) {
                        document.getElementById('popup_content').innerHTML = result;
                        eval($(result)[1].innerHTML);
                    }, 'html');
                    popup = $('#popup').bPopup({
                        speed: 650,
                        transition: 'slideIn',
                        transitionClose: 'slideBack'
                    });

                });
                //house
                $('#popup_create_house').bind('click', function(e) {
                    e.preventDefault();
                    document.getElementById('popup_content').innerHTML = '';
//                        $.get('popup_create_house.php', function(result){
//                            document.getElementById('popup_content').innerHTML = result;
//                            eval($(result)[1].innerHTML);
//                        }, 'html');
                    popup = $('#popup').bPopup({
                        contentContainer: '#popup_content',
                        loadUrl: 'popup_create_house.php' //Uses jQuery.load()
                    });

                });
                //Room
                $('#popup_create_room').bind('click', function(e) {
                    e.preventDefault();
                    popup = $('#popup').bPopup({
                        contentContainer: '#popup_content',
                        loadUrl: 'popup_create_room.php'
                    });

                });
            });
        })(jQuery);
        $('#add_log').click(function() {
            var idtmp = Math.random().toString(36);
            var div = $('<div>').attr({
                type: 'text',
                style: 'height: 26px; width: 250px; margin-top: 5px;float: left;',
                id: idtmp,
            }).appendTo('#log_revisit_container');
            $('<input>').attr({
                type: 'text',
                class: 'log_revisit',
                style: 'height: 26px; width: 215px;display: block;margin-right: 3px;float: left;',
                name: 'log_revisit[]'
            }).appendTo(div);
            $('<img>').attr({
                src: 'include/images/DeleteRed.png',
                style: 'height: 26px; width: 26px;float: left;cursor: pointer;',
                Onclick: "removeLogvisit('" + idtmp + "');"
            }).appendTo(div);
            log_date('.log_revisit');
        });
        function removeLogvisit(id) {
            document.getElementById(id).remove();
        }
        var xml = {};
        function setMenuItem(type, code) {

            var s = document.getElementsByTagName("head")[0].appendChild(document.createElement("script"));
            s.type = "text/javascript";
            s.charset = "utf-8";

            var optionIndex0 = document.getElementById("s0").options.length;	//沿線のOPTION数取得
            var optionIndex1 = document.getElementById("s1").options.length;	//駅のOPTION数取得

            if (type == 0) {
                for (i = 0; i <= optionIndex0; i++) {
                    document.getElementById("s0").options[0] = null
                }	//沿線削除
                for (i = 0; i <= optionIndex1; i++) {
                    document.getElementById("s1").options[0] = null
                }	//駅削除
                document.getElementById("s1").options[0] = new Option("----", 0);	//駅OPTIONを空に
                if (code == 0) {
                    document.getElementById("s0").options[0] = new Option("----", 0);	//沿線OPTIONを空に
                } else {
                    s.src = "http://www.ekidata.jp/api/p/" + code + ".json";	//沿線JSONデータURL
                }
            } else {
                for (i = 0; i <= optionIndex1; i++) {
                    document.getElementById("s1").options[0] = null
                }	//駅削除
                if (code == 0) {
                    document.getElementById("s1").options[0] = new Option("----", 0);	//駅OPTIONを空に
                } else {
                    s.src = "http://www.ekidata.jp/api/l/" + code + ".json";	//駅JSONデータURL
                }
            }
            xml.onload = function(data) {
                var line = data["line"];
                var station_l = data["station_l"];
                if (line != null) {
                    document.getElementById("s0").options[0] = new Option("----", 0);	//OPTION1番目はNull
                    for (i = 0; i < line.length; i++) {
                        ii = i + 1	//OPTIONは2番目から表示
                        var op_line_name = line[i].line_name;
                        var op_line_cd = line[i].line_cd;
                        document.getElementById("s0").options[ii] = new Option(op_line_name, op_line_cd);
                    }
                }
                if (station_l != null) {
                    document.getElementById("s1").options[0] = new Option("----", 0);	//OPTION1番目はNull
                    for (i = 0; i < station_l.length; i++) {
                        ii = i + 1	//OPTIONは2番目から表示
                        var op_station_name = station_l[i].station_name;
                        var op_station_cd = station_l[i].station_cd;
                        document.getElementById("s1").options[ii] = new Option(op_station_name, op_station_cd);
                    }
                }
            }
        }
        $(document).ready(function() {
            if ('{/literal}{$aspirations_area}{literal}') {
                setMenuItem(0, '{/literal}{$aspirations_area}{literal}');
                if ('{/literal}{$aspirations_area2}{literal}') {
                    setMenuItem(1, '{/literal}{$aspirations_area2}{literal}');
                    setTimeout(function() {
                        // Do something after 5 seconds
                        $('select#s0').val('{/literal}{$aspirations_area2}{literal}');
                        $('select#s1').val('{/literal}{$aspirations_area3}{literal}');
                    }, 2000);
                }
            }
        });

    </script>
{/literal}
{include file='footer.tpl'}