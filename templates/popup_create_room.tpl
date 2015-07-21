<div style="background-color: #F1F5FE; width: 100%;height:25px; text-align: center;font-size: 1.8em;margin-bottom: 2%;">Room Registry</div>
{nocache}
    <div class="error" id="popup_error" style="display: none;">Please complete the required fields</div>
    <form action="popup_create_room.php" method="post" enctype="multipart/form-data">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>            
                <td class='form1'>Apartment: <span class="required">*</span></td>
                <td class='form2'>
                    <select id="popup_house_id" name="house_id" style="height:26px; width: 300px;"></select>
                </td>
            </tr>
            <tr>            
                <td class='form1'>Desription: </td>
                <td class='form2'>
                    <textarea style="width: 300px;height: 70px;" disabled="1" id="popup_house_description"></textarea>
                </td>
            </tr>
            <tr>            
                <td class='form1'>Broker: <span class="required">*</span></td>
                <td class='form2'>
                    <select id="popup_broker_id" name="broker_id" style="height:26px; width: 300px;"></select>
                </td>
            </tr>   
            <tr>
                <td class='form1'>Room number: <span class="required">*</span></td>
                <td class='form2'>
                    <input type='text' class='text' name='room_number' id='popup_room_number'style="height:26px; width: 300px;">
                </td>
            </tr>
            <tr>
                <td class='form1'>Type:  <span class="required">*</span></td>
                <td class='form2'>     
                    <input type='text' class='text' name='room_type_number' id='popup_room_type_number'  style="height:26px; width: 100px;"/>
                    <select id="popup_room_type" name="room_type" style="height:28px; width: 190px; position: absolute;margin-left: 0.5%;">
                        <option value=""></option>
                        {foreach from=$roomTypes item=roomType}
                            <option value="{$roomType.id}" >{$roomType.room_name}</option>        
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>Size: <span class="required">*</span></td>
                <td class='form2'>
                    <input type='text' class='text' name='room_size' id='popup_room_size' style="height:26px; width: 300px;margin-right: 1%;">㎡
                </td>
            </tr>
            <tr>
                <td class='form1'>Rent: <span class="required">*</span></td>
                <td class='form2'>
                    <input type='text' class='text' name='room_rent' id='popup_room_rent' style="height:26px; width: 232px;">
                    <label style="padding: 1% 4.7% 1% 4.7%;background-color: white;">円</label>
                </td>
            </tr>
            <tr>
                <td class='form1'>Key fee: </td>
                <td class='form2'>
                    <input type='text' class='text' name='room_key_money'  id='popup_room_key_money'  style="height:26px; width: 232px;">
                    <select id="popup_room_key_money_unit" name="room_key_money_unit" style="width: 12.5%;padding: 1% 0px 1% 0%;">
                        <option value="円">円</option>
                        <option value="ヵ月">ヵ月</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>Administrative expense: </td>
                <td class='form2'><input type='text' class='text' name='room_administrative_expense' id='popup_room_administrative_expense'   style="height:26px; width: 232px;">
                    <select id="popup_room_administrative_expense_unit" name="room_administrative_expense_unit" style="width: 12.5%;padding: 1% 0px 1% 0%;">
                        <option value="円">円</option>
                        <option value="ヵ月">ヵ月</option>
                    </select>
            </tr>

            <tr>
                <td class='form1'>Deposit: </td>
                <td class='form2'>
                    <input type='text' class='text' name='room_deposit' id='popup_room_deposit'   style="height:26px; width: 232px;">
                    <select id="popup_room_deposit_unit" name="room_deposit_unit" style="width: 12.5%;padding: 1% 0px 1% 0%;">
                        <option value="円">円</option>
                        <option value="ヵ月">ヵ月</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>Discount: </td>
                <td class='form2'>
                    <input type='text' class='text' name='room_discount' id='popup_room_discount'  style="height:26px; width: 300px;">
                </td>
            </tr>
            <tr>            
                <td class='form1'>Status: </td>
                <td class='form2'>
                    <select id="popup_room_status" name="room_status" style="height:26px; width: 300px;">
                        <option value="0">Empty</option>
                        <option value="1" >Rent</option>
                        <option value="2" >Incomplete</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" class='form2'>
                    <div style="margin-left: 20%;">
                        <input type='button' class='btn-signup' value='Submit' id="popup_submit" name="submit"/>&nbsp;          
                    </div>
                </td>
            </tr>
        </table>
    </form>
{/nocache}
{literal}
    <script type="text/javascript">
            /*
            $('#popup_search').keyup(function(e) {
                var search = $('#popup_search').val();
                $.post("include/function_ajax.php", {search: search, action: 'create_order', task: 'getHouseSearch'},
                function(result) {
                    $('#popup_house_id').empty();
                    $('#popup_house_id').html(result);
                    $('#popup_step').click();
                });
            });
            $('#popup_step').click(function() {
                var house_id = $('#popup_house_id').val();
                $.post('include/function_ajax.php', {house_id: house_id, action: 'create_order', task: 'getContentHouse'},
                function(result) {
                    var json = $.parseJSON(result);
                    $('#popup_house_description').html(json.house_description);
                });
            });
            $('#popup_house_id').change(function() {
                var house_id = $('#popup_house_id').val();
                $.post('include/function_ajax.php', {house_id: house_id, action: 'create_order', task: 'getContentHouse'},
                function(result) {
                    var json = $.parseJSON(result);
                    $('#popup_house_description').html(json.house_description);
                });
            });
            */
           var house = $('#house_id').find(":selected");
           if(house){
               $("#popup_house_id").append(house.clone());
           }
           var broker_company = $('#broker_id').find(":selected");
           if(broker_company){
               $("#popup_broker_id").append(broker_company.clone());
           }
           
           $('#popup_house_description').html($('#house_description').val());
            var checkPrice = function(el){
                if(isNaN(el.val())){
                    if(isNaN(parseFloat(el.val()))){
                        el.val('');
                    }else{
                        el.val(parseFloat(el.val()));
                    }
               }
            }
            $('#popup_room_rent').keyup(function(){
                checkPrice($('#popup_room_rent'));
            });
            $('#popup_room_key_money').keyup(function(){
                checkPrice($('#popup_room_key_money'));
            });
            $('#popup_room_administrative_expense').keyup(function(){
                checkPrice($('#popup_room_administrative_expense'));
            });
            $('#popup_room_deposit').keyup(function(){
                checkPrice($('#popup_room_deposit'));
            });
            
            $('#popup_submit').click(function() {
                if (!$('#popup_house_id').val()||
                        !$('#popup_broker_id').val()||
                        !$('#popup_room_number').val()||
                        !$('#popup_room_type_number').val()||
                        !$('#popup_room_type').val()||
                        !$('#popup_room_size').val()||
                        !$('#popup_room_rent').val()
                ) {
                    $('#popup_error').html('Please complete the required fields');
                    $('#popup_error').show();
                } else {
                    $('#popup_error').hide();
                    $.post(
                            "popup_create_room.php", 
                            {
                                house_id: $('#popup_house_id').val(),
                                broker_id: $('#popup_broker_id').val(), 
                                room_number: $('#popup_room_number').val(),
                                room_type_number: $('#popup_room_type_number').val(),
                                room_type: $('#popup_room_type').val(),
                                room_size: $('#popup_room_size').val(),
                                room_rent: $('#popup_room_rent').val(),
                                room_key_money: $('#popup_room_key_money').val(),
                                room_key_money_unit: $('#popup_room_key_money_unit').val(),
                                room_administrative_expense: $('#popup_room_administrative_expense').val(),
                                room_deposit: $('#popup_room_deposit').val(),
                                room_deposit_unit: $('#popup_room_deposit_unit').val(),
                                room_discount: $('#popup_room_discount').val(),
                                room_status: $('#popup_room_status').val(),
                                submit: 1
                            },
                            function(data) {
                                var result = JSON.parse(data);
                                if(result.status == 1){
                                    $("#room_id").append(new Option(result.data.name, result.data.id,true,true));
                                    $('#order_rent_cost').val(result.data.room_rent);
                                    $('#room_id').change();
                                    popup.close();
                                    $('#error_room').html("");
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