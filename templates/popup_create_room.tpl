<div style="background-color: #F1F5FE; width: 100%;height:25px; text-align: center;font-size: 1.8em;line-height: 55px; margin-bottom: 2%;">部屋登録</div>
{nocache}
    <div class="error" id="popup_error" style="display: none;">Please complete the required fields</div>
    <form action="popup_create_room.php" method="post" enctype="multipart/form-data">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>            
                <td class='form1'>物件選択: <span class="required">*</span></td>
                <td class='form2'>
                    <select id="popup_house_id" name="house_id" style="height:26px; width: 300px;"></select>
                </td>
            </tr>
            <tr>            
                <td class='form1'>物件備考: </td>
                <td class='form2'>
                    <textarea style="width: 300px;height: 70px;" disabled="1" id="popup_house_description"></textarea>
                </td>
            </tr>
            <tr>            
                <td class='form1'>管理会社選択: <span class="required">*</span></td>
                <td class='form2'>
                    <select id="popup_broker_id" name="broker_id" style="height:26px; width: 300px;"></select>
                </td>
            </tr>   
            <tr>
                <td class='form1'>号室: <span class="required">*</span></td>
                <td class='form2'>
                    <input type='text' class='text' name='room_number' id='popup_room_number'style="height:26px; width: 300px;">
                </td>
            </tr>
            <tr>
                <td class='form1'>間取り:  <span class="required">*</span></td>
                <td class='form2'>     
                    <input type='text' class='text' name='room_type_number' id='popup_room_type_number'  style="height:26px; width: 100px;"/>
                    <select id="popup_room_type" name="room_type" style="height:28px; width: 190px; position: absolute;margin-left: 0.5%;">
                        <option value=""></option>
                        {foreach from=$roomTypes item=roomType}
                            <option value="{$roomType.id}" {if $roomType.id eq $room_type}selected="selected"{/if}>{$roomType.room_name}</option>        
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>面積: <span class="required">*</span></td>
                <td class='form2'>
                    <input type='text' class='text' name='room_size' id='popup_room_size' style="height:26px; width: 300px;margin-right: 1%;">㎡
                </td>
            </tr>
            <tr>
                <td class='form1'>賃料: <span class="required">*</span></td>
                <td class='form2'>
                    <input type='text' class='text' name='room_rent' id='popup_room_rent' style="height:26px; width: 232px;">
                    <label style="padding: 1% 4.7% 1% 4.7%;background-color: white;">円</label>
                </td>
            </tr>
            <tr>
                <td class='form1'>礼金: </td>
                <td class='form2'>
                    <input type='text' class='text' name='room_key_money'  id='popup_room_key_money'  style="height:26px; width: 232px;">
                    <select id="popup_room_key_money_unit" name="room_key_money_unit" style="width: 12.5%;padding: 1% 0px 1% 0%;">
                        <option value="円"{if $room_key_money_unit eq "円"}selected{/if} >円</option>
                        <option value="ヵ月"{if $room_key_money_unit eq "ヵ月"}selected{/if} >ヵ月</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>管理費: </td>
                <td class='form2'><input type='text' class='text' name='room_administrative_expense' id='popup_room_administrative_expense'   style="height:26px; width: 232px;">
                    <select id="popup_room_administrative_expense_unit" name="room_administrative_expense_unit" style="width: 12.5%;padding: 1% 0px 1% 0%;">
                        <option value="円"{if $room_administrative_expense_unit eq "円"}selected{/if}>円</option>
                        <option value="ヵ月"{if $room_administrative_expense_unit eq "ヵ月"}selected{/if}>ヵ月</option>
                    </select>
            </tr>

            <tr>
                <td class='form1'>敷金・保証金: </td>
                <td class='form2'>
                    <input type='text' class='text' name='room_deposit' id='popup_room_deposit'   style="height:26px; width: 232px;">
                    <select id="popup_room_deposit_unit" name="room_deposit_unit" style="width: 12.5%;padding: 1% 0px 1% 0%;">
                        <option value="円"{if $room_deposit_unit eq "円"}selected{/if}>円</option>
                        <option value="ヵ月"{if $room_deposit_unit eq "ヵ月"}selected{/if}>ヵ月</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>割引: </td>
                <td class='form2'>
                    <input type='text' class='text' name='room_discount' id='popup_room_discount'  style="height:26px; width: 300px;">
                </td>
            </tr>
            <tr>            
                <td class='form1'>現況: </td>
                <td class='form2'>
                    <select id="popup_room_status" name="room_status" style="height:26px; width: 300px;">
                        <option value="0"{if $room_status eq "0"}selected{/if}>空家</option>
                        <option value="1" {if $room_status eq "1"}selected{/if}>賃貸中</option>
                        <option value="2" {if $room_status eq "2"}selected{/if}>未完成</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" class='form2'>
                    <div style="margin-left: 20%;">
                        <input type='button' class='btn-signup' value='登録' id="popup_submit" name="submit"/>&nbsp;          
                    </div>
                </td>
            </tr>
        </table>
    </form>
{/nocache}
{literal}
    <script type="text/javascript">
        $(document).ready(function() {
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
               $("#popup_house_id").append(house);
           }
           var broker_company = $('#broker_id').find(":selected");
           if(broker_company){
               $("#popup_broker_id").append(broker_company);
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
        });
    </script>
{/literal}