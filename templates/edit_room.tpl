{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin-bottom: 2%;">Edit Room</div>
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    {if $notify ne ""}
        {$notify}
    {/if}
    <form action="edit_room.php" method="post" enctype="multipart/form-data">
        <div style="margin-bottom: 20px;"><label >物件情報</label></div>        
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class="form1">物件フィルタ</td>
                <td class="form2"><input type="text" id="search" name="search" value="" placeholder="Enter house name to filter for selection house" style="height:26px; width: 300px;"/>
                </td>
            </tr>
            <tr>            
                <td class='form1'>物件選択: </td>
                <td class='form2'>
                    <select id="house_id" name="house_id" style="height:26px; width: 300px;">
                        <option value=""></option>
                        {foreach from=$houses item=house}
                            <option value="{$house.id}" {if $house.id eq $house_id}selected="selected"{/if}>{$house.house_name}</option>        
                        {/foreach}
                    </select><span id="error_house" class="error"></span>
                </td>
            </tr>
            <tr>            
                <td class='form1'>物件備考: </td>
                <td class='form2'><textarea style="width: 300px;height: 129px;" disabled="1" id="house_description"></textarea></td>
            </tr>
            <tr>            
                <td colspan="2"><div>次のリンクで、新しい物件情報を追加することができます。 <a href="./create_house.php">物件登録</a></div></td>
            </tr>            
        </table>
        <div style="margin-bottom: 20px;"><label >管理会社情報</label></div>      
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">           
            <tr>            
                <td class='form1'>管理会社選択: </td>
                <td class='form2'>
                    <select id="broker_id" name="broker_id" style="height:26px; width: 300px;">
                        <option value=""></option>
                        {foreach from=$brokers item=broker}
                            <option value="{$broker.id}" {if $broker.id eq $broker_id}selected="selected"{/if}>{$broker.broker_company_name}</option>        
                        {/foreach}
                    </select><span id="error_broker" class="error"></span>
                </td>
            </tr>   
            <tr> 
                {assign var=broker_link value='次のリンクで、新しい管理会社の情報を追加することができます。 <a href="./create_broker_company.php">管理会社登録</a>'}
                <td colspan="2" nowrap><div>次のリンクで、新しい管理会社の情報を追加することができます。<a href="./create_broker_company.php">管理会社登録</a></div></td>
            </tr>
        </table>
        <div><label class="title">部屋情報</label></div>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>号室: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='room_number' id='room_number' value="{$room_number}"  style="height:26px; width: 300px;"><div id="room_number_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>間取り:  <span class="required">*</span></td>
                <td class='form2'><select id="room_type" name="room_type" style="height:26px; width: 300px;">
                        <option value=""></option>
                        {foreach from=$roomTypes item=roomType}
                            <option value="{$roomType.id}" {if $roomType.id eq $room_type}selected="selected"{/if}>{$roomType.room_name}</option>        
                        {/foreach}
                    </select><div id="error_room_type" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>面積: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='room_size' id='room_size' value="{$room_size}" style="height:26px; width: 300px;"><div id="room_size_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>賃料: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='room_rent' id='room_rent' value="{$room_rent}" style="height:26px; width: 232px;">
                    <label style="padding: 1% 5.5% 1% 5.5%;background-color: white;">円</label><div id="room_rent_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>礼金: </td>
                <td class='form2'>
                    <input type='text' class='text' name='room_key_money'  id='room_key_money' value="{$room_key_money}"  style="height:26px; width: 232px;">
                    <select id="room_key_money_unit" name="room_key_money_unit" style="width: 15%;padding: 1% 0px 1% 0%;">
                        <option value="円"{if $room_key_money_unit eq "円"}selected{/if} >円</option>
                        <option value="ヵ月"{if $room_key_money_unit eq "ヵ月"}selected{/if} >ヵ月</option>
                    </select>
                    <div id="room_key_money_error"class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>管理費: </td>
                <td class='form2'><input type='text' class='text' name='room_administrative_expense' id='room_administrative_expense' value="{$room_administrative_expense}"  style="height:26px; width: 232px;">
                    <select id="room_administrative_expense_unit" name="room_administrative_expense_unit" style="width: 15%;padding: 1% 0px 1% 0%;">
                        <option value="円"{if $room_administrative_expense_unit eq "円"}selected{/if}>円</option>
                        <option value="ヵ月"{if $room_administrative_expense_unit eq "ヵ月"}selected{/if}>ヵ月</option>
                    </select>
                    <div id="room_administrative_expense_error"class="error"></div></td>
            </tr>

            <tr>
                <td class='form1'>敷金・保証金: </td>
                <td class='form2'>
                    <input type='text' class='text' name='room_deposit' id='room_deposit' value="{$room_deposit}"  style="height:26px; width: 232px;">
                    <select id="room_deposit_unit" name="room_deposit_unit" style="width: 15%;padding: 1% 0px 1% 0%;">
                        <option value="円"{if $room_deposit_unit eq "円"}selected{/if}>円</option>
                        <option value="ヵ月"{if $room_deposit_unit eq "ヵ月"}selected{/if}>ヵ月</option>
                    </select>
                    <div id="room_deposit_error"class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>割引: </td>
                <td class='form2'><input type='text' class='text' name='room_discount' id='room_discount' value="{$room_discount}"  style="height:26px; width: 300px;"><div id="room_discount_error"class="error"></div></td>
            </tr>
            <!--<tr>
                <td class='form1'>Photo: </td>
                <td class='form2'><input type='file' class='text' name='room_photo' id='room_photo' value="{$room_photo}"  style="height:26px; width: 300px;"><div id="room_photo_error"class="error"></div></td>
            </tr>-->                                
            <tr>            
                <td class='form1'>現況: </td>
                <td class='form2'>
                    <select id="room_status" name="room_status" style="height:26px; width: 300px;">
                        <option value="0"{if $room_status eq "0"}selected{/if}>空家</option>
                        <option value="1" {if $room_status eq "1"}selected{/if}>賃貸中</option>
                        <option value="2" {if $room_status eq "2"}selected{/if}>未完成</option>
                    </select>
                </td>
            </tr>
        </table>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">           
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin:0px 0px 0px 13%;">
                        <input type='submit' class='btn-search' value='Change' id="submit" name="submit"/>&nbsp;  &nbsp; 
                        <input type='button' class='btn-search' value='戻る' id="back" name="back" onclick="back();"/>&nbsp;  
                        <input type="hidden" id="room_detail_id" name="room_detail_id" value="{$room_detail_id}"/>
                        <input type="hidden" id="house_id_bk" name="house_id_bk" value="{$house_id_bk}"/>
                        <input type="hidden" id="broker_id_bk" name="broker_id_bk" value="{$broker_id_bk}"/>
                        <input type="hidden" id="room_number_bk" name="room_number_bk" value="{$room_number_bk}"/>
                    </div>
                </td>
            </tr>
        </table>
    </form>
{/nocache}
{literal}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#search').keyup(function(e) {
                var search = $('#search').val();
                $.post("include/function_ajax.php", {search: search, action: 'create_order', task: 'getHouseSearch'},
                function(result) {
                    $('#house_id').empty();
                    $('#house_id').html(result);
                    $('#step').click();
                });
            });
            $('#step').click(function() {
                var house_id = $('#house_id').val();
                $.post('include/function_ajax.php', {house_id: house_id, action: 'create_order', task: 'getContentHouse'},
                function(result) {
                    var json = $.parseJSON(result);
                    $('#house_description').html(json.house_description);
                });
            });
            $('#house_id').change(function() {
                var house_id = $('#house_id').val();
                $.post('include/function_ajax.php', {house_id: house_id, action: 'create_order', task: 'getContentHouse'},
                function(result) {
                    var json = $.parseJSON(result);
                    $('#house_description').html(json.house_description);
                });
            });
            $('#back').click(function() {
                window.location.href = "manage_room.php";
            });
        });
    </script>
{/literal}

{include file='footer.tpl'}