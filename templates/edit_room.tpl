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
        <div><label class="title">Room Infomation</label></div>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>Room Number: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='room_number' id='room_number' value="{$room_number}"  style="height:26px; width: 351px;"><div id="room_number_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Type:  <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='room_type' id='room_type' value="{$room_type}"  style="height:26px; width: 351px;"><div id="room_type_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Size: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='room_size' id='room_size' value="{$room_size}" style="height:26px; width: 351px;"><div id="room_size_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Price: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='room_rent' id='room_rent' value="{$room_rent}" style="height:26px; width: 351px;"><div id="room_rent_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Key Money: </td>
                <td class='form2'><input type='text' class='text' name='room_key_money'  id='room_key_money' value="{$room_key_money}"  style="height:26px; width: 351px;"><div id="room_key_money_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Administrative Expense: </td>
                <td class='form2'><input type='text' class='text' name='room_administrative_expense' id='room_administrative_expense' value="{$room_administrative_expense}"  style="height:26px; width: 351px;"><div id="room_administrative_expense_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Deposit: </td>
                <td class='form2'><input type='text' class='text' name='room_deposit' id='room_deposit' value="{$room_deposit}"  style="height:26px; width: 351px;"><div id="room_deposit_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Discount: </td>
                <td class='form2'><input type='text' class='text' name='room_discount' id='room_discount' value="{$room_discount}"  style="height:26px; width: 351px;"><div id="room_discount_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Photo: </td>
                <td class='form2'><input type='file' class='text' name='room_photo' id='room_photo' value="{$room_photo}"  style="height:26px; width: 351px;"><div id="room_photo_error"class="error"></div></td>
            </tr>                                
            <tr>            
                <td class='form1'>Room Status: </td>
                <td class='form2'>
                    <select id="room_status" name="room_status" style="height:26px; width: 351px;">
                        <option value="0"{if $room_status eq 0}selected{/if}>Empty</option>
                        <option value="1"{if $room_status eq 1}selected{/if}>For rent</option>        
                    </select>
                </td>
            </tr>
        </table>
        <div style="margin-bottom: 20px;"><label >House Infomation</label></div>        
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class="form1">Filter House</td>
                <td class="form2"><input type="text" id="search" name="search" value="" placeholder="Enter house name to filter for selection house" style="height:26px; width: 351px;"/>
                </td>
            </tr>
            <tr>            
                <td class='form1'>Select House: </td>
                <td class='form2'>
                    <select id="house_id" name="house_id" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$houses item=house}
                            <option value="{$house.id}" {if $house.id eq $house_id}selected="selected"{/if}>{$house.house_name}</option>        
                        {/foreach}
                    </select><span id="error_house" class="error"></span>
                </td>
            </tr>
            <tr>            
                <td class='form1'>Description House: </td>
                <td class='form2'><textarea style="width: 351px;height: 129px;" disabled="1" id="house_description"></textarea></td>
            </tr>
            <tr>            
                <td colspan="2"><div>If not house that you want. You can add new house by link <a href="./create_house.php">Create House</a></div></td>
            </tr>            
        </table>
        <div style="margin-bottom: 20px;"><label >Broker Company Infomation</label></div>      
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">           
            <tr>            
                <td class='form1'>Select Broker Company: </td>
                <td class='form2'>
                    <select id="broker_id" name="broker_id" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$brokers item=broker}
                            <option value="{$broker.id}" {if $broker.id eq $broker_id}selected="selected"{/if}>{$broker.broker_company_name}</option>        
                        {/foreach}
                    </select><span id="error_broker" class="error"></span>
                </td>
            </tr>   
            <tr> 
                {assign var=broker_link value='If not broker company that you want. You can add new broker company by link <a href="./create_broker_company.php">Create Broker</a>'}
                <td colspan="2" nowrap><div>{$broker_link|wordwrap:70:"<br />\n"}</div></td>
            </tr>
        </table>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">           
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin:0px 0px 0px 13%;">
                        <input type='submit' class='btn-search' value='Change' id="submit" name="submit"/>&nbsp;  &nbsp; 
                        <input type='button' class='btn-search' value='Back' id="back" name="back" onclick="back();"/>&nbsp;  
                        <input type="hidden" id="room_detail_id" name="room_detail_id" value="{$room_detail_id}"/>
                        <input type="hidden" id="house_id_bk" name="house_id_bk" value="{$house_id_bk}"/>
                        <input type="hidden" id="broker_id_bk" name="broker_id_bk" value="{$broker_id_bk}"/>
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