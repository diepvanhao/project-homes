{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; ">部屋番号を管理会社管理に追加します</div>
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}        
    {/if}
    {if $notify ne ""}
        {$notify}
    {/if}
    <form action="add_house_broker.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>名称: </td>
                <td class='form2'><input type='text'  value="{$broker_company_name}"  style="height:26px; width: 351px;" disabled><div id="broker_company_name_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>住所: </td>
                <td class='form2'><input type='text' value="{$broker_company_address}"  style="height:26px; width: 351px;"disabled><div id="broker_company_address_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>電話番号: </td>
                <td class='form2'><input type='text'  value="{$broker_company_phone}" style="height:26px; width: 351px;"disabled><div id="broker_company_phone_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Eメール: </td>
                <td class='form2'><input type='text'  value="{$broker_company_email}"  style="height:26px; width: 351px;"disabled><div id="broker_company_email_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>ファックス: </td>
                <td class='form2'><input type='text'  value="{$broker_company_fax}"  style="height:26px; width: 351px;"disabled><div id="broker_company_fax_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>担当者: </td>
                <td class='form2'><input type='text'  value="{$broker_company_undertake}"  style="height:26px; width: 351px;"disabled><div id="broker_company_undertake_error"class="error"></div></td>
            </tr>
            <tr>
                <td class="form1">物件フィルター</td>
                <td class="form2"><input type="text" id="search" name="search" value="" placeholder="Enter house name to filter for selection house" style="height:26px; width: 351px;"/>
                </td>
            </tr>
            <tr>            
                <td class='form1'>物件名を選択してください。: </td>
                <td class='form2'>
                    <select id="house_id" name="house_id" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$houses item=house}
                            <option value="{$house.id}" {if $house.id eq $house_id}selected{/if}>{$house.house_name}</option>        
                        {/foreach}
                    </select><div id="error_house" class="error"></div>
                </td>
            </tr>
            <tr>            
                <td class='form1'>備考: </td>
                <td class='form2'><textarea style="width: 340px;height: 129px;"  id="house_description" name="house_description">{$house_description}</textarea></td>
            </tr>
            <tr>            
                <td colspan="2"><div>次のリンクで、新しい物件情報を追加することができます。 <a href="./create_house.php">物件登録</a></div></td>
            </tr>
            <tr>            
                <td class='form1'>部屋選択: </td>
                <td class='form2'><select id="room_id" name="room_id" style="height:26px; width: 351px;">
                        <option value=""></option>

                    </select><div id="error_room_assign" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-search' value='加える' id="submit" name="submit"/>&nbsp;  &nbsp; 
                        <input type='button' class='btn-search' value='戻る' id="back" name="back" onclick="back();"/>&nbsp;  
                        <input type='hidden'  value='{$broker_id}' id="broker_id" name="broker_id"/>                                       
                        <input type='hidden'  name='broker_company_name' id='broker_company_name' value="{$broker_company_name}"/>
                        <input type='hidden'  name='broker_company_address' id='broker_company_address' value="{$broker_company_address}"/>
                        <input type='hidden'  name='broker_company_phone' id='broker_company_phone' value="{$broker_company_phone}"/>
                        <input type='hidden'  name='broker_company_email' id='broker_company_email' value="{$broker_company_email}"/>
                        <input type='hidden'  name='broker_company_fax' id='broker_company_fax' value="{$broker_company_fax}"/>
                        <input type='hidden'  name='broker_company_undertake' id='broker_company_undertake' value="{$broker_company_undertake}"/>
                        <input type="hidden" id="step" name="step" value=""/>
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
                window.location.href = "manage_broker.php";
            });
            
             $('#submit').click(function(e) {                    
                    $('#error_house').html("");                    
                    $('#error_room_assign').html("");                    
                    var house_id = $('#house_id').val();                  
                    var room_id = $('#room_id').val();
                    
                  if (house_id == "" || house_id == null) {
                        $('#error_house').html('Please choose house.');
                        e.preventDefault();
                        return false;
                    } else if (room_id == "" || room_id == null) {
                        $('#error_room_assign').html('Please choose room.');
                        e.preventDefault();
                        return false;
                    } else {                       
                        $('#submit').submit();
                    }

                });
            
            $('#search').keyup(function(e) {
                var search = $('#search').val();
                $('#error_house').html("");
                $.post("include/function_ajax.php", {search: search, action: 'create_order', task: 'getHouseSearch'},
                function(result) {
                    if (result) {
                        $('#house_id').empty();
                        $('#house_id').html(result);
                        $('#step').click();
                    } else {
                        $('#house_id').empty();
                        $('#room_id').empty();
                        $('#house_description').html("");
                        $('#error_house').html("No any house for your keyword");
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
                    get_room(house_id);
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
                    get_room(house_id);
                });
            });
            $('#room_id').change(function() {
                var room_id = $('#room_id').val();
                var broker_id = $('#broker_id').val();
                var house_id = $('#house_id').val();
                $('#error_room_assign').html("");
                $.post('include/function_ajax.php', {house_id: house_id, room_id: room_id, broker_id: broker_id, action: 'add_room', task: 'checkRoomExist'},
                function(result) {
                    var json = $.parseJSON(result);
                    if (json.flag == 'true') {
                        //$('#error_room_assign').html("This room had added. Please choose other room");
                        //$('#submit').attr('disabled', true);
                        //$("#submit").css('color', 'grey');
                    } else if (json.flag == 'false') {
                       // $('#submit').attr('disabled', false);
                       // $("#submit").css('color', '#fff');
                    }
                });
            });
        });
        function get_room(house_id) {
            $('#error_room_assign').html("");
            $.post("include/function_ajax.php", {house_id: house_id, action: 'create_order', task: 'getRoomContent'},
            function(result) {
                if (result) {
                    $('#room_id').empty();
                    $('#room_id').html(result);
                } else {
                    $('#room_id').empty();
                    $('#house_description').html("");
                    $('#error_room_assign').html("This house haven't been room yet");
                }
            });
        }
    </script>
{/literal}
{include file='footer.tpl'}