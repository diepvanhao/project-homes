{include file="header.tpl"}
{literal}
    <script type="text/javascript">
        var checkPrice = function(el){
            if(isNaN(el.val())){
                if(isNaN(parseFloat(el.val()))){
                    el.val('');
                }else{
                    el.val(parseFloat(el.val()));
                }
           }
        }
        $('#target_1').keyup(function(){
            checkPrice($('#target_1'));
        });
         $('#target_2').keyup(function(){
            checkPrice($('#target_2'));
        });
        $('#target_3').keyup(function(){
            checkPrice($('#target_3'));
        });
        $('#target_4').keyup(function(){
            checkPrice($('#target_4'));
        });
        $('#target_5').keyup(function(){
            checkPrice($('#target_5'));
        });
        $('#target_6').keyup(function(){
            checkPrice($('#target_6'));
        });
        $('#target_7').keyup(function(){
            checkPrice($('#target_7'));
        });
        $('#target_8').keyup(function(){
            checkPrice($('#target_8'));
        });
        $('#target_9').keyup(function(){
            checkPrice($('#target_9'));
        });
        $('#target_10').keyup(function(){
            checkPrice($('#target_10'));
        });
        $('#target_11').keyup(function(){
            checkPrice($('#target_11'));
        });
        $('#target_12').keyup(function(){
            checkPrice($('#target_12'));
        });
        $(document).ready(function() {
           
            birthday('birthday');
            $('#email').blur(function() {
                if ($('#email').val() != "") {
                    email = $("#email").val();
                    $.post("include/function_ajax.php", {email: email, action: 'check_email'},
                    function(result) {
                        $('#email_error').html(result);
                    });
                }
            });
            $('#username').blur(function() {
                if ($('#username').val() != "") {
                    username = $("#username").val();
                    $.post("include/function_ajax.php", {username: username, action: 'check_username'},
                    function(result) {
                        $('#username_error').html(result);
                    });
                }
            });
            $('#confirm_password').blur(function() {
                if ($('#confirm_password').val() != "") {
                    confirm_password = $("#confirm_password").val();
                    password = $('#password').val();
                    $.post("include/function_ajax.php", {password: password, confirm_password: confirm_password, action: 'check_password'},
                    function(result) {
                        $('#confirm_password_error').html(result);
                    });
                }
            });
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
                                            });

    </script>
{/literal}
<!--<div style="text-align: center;font-size: 1.4em;padding-bottom: 10px; ">
    <label >サインアップ</label>
</div>-->
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin-bottom: 2%;">アカウント登録</div>
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    <form action='user_account.php' method='POST' name='create' id="create" enctype="multipart/form-data">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>Ｅメール: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='email' id='email' value="{$email}" maxlength='70' style="height:26px; width: 351px;"><div id="email_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>パスワード: <span class="required">*</span></td>
                <td class='form2'><input type='password' class='text' name='password' id='password' value="{$password}"size='25' style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>パスワード確認: <span class="required">*</span></td>
                <td class='form2'><input type='password' class='text' name='confirm_password' id='confirm_password' value="{$confirm_password}" size='25'  style="height:26px; width: 351px;"><div id="confirm_password_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>ユーザー: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='username' id='username' value="{$username}"   style="height:26px; width: 351px;"><div id="username_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>名字: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='lastname' id='lastname' value="{$lastname}"   style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>名前: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='firstname' id='firstname' value="{$firstname}"  style="height:26px; width: 351px;"></td>
            </tr>            
            <tr>
                <td class='form1'>都道府県:  <span class="required">*</span></td>
                <td class='form2'><select id="city_id" name="city_id" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$cities item=city}
                            <option value="{$city.id}" {if $city.id eq $city_id}selected="selected"{/if}>{$city.city_name}</option>        
                        {/foreach}
                    </select><div id="error_city_id" class="error"></div>
                </td>
            </tr>      
            <tr>
                <td class='form1'>市区町村:  <span class="required">*</span></td>
                <td class='form2'><select id="district_id" name="district_id" style="height:26px; width: 351px;">                       

                    </select><div id="error_district_id" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>大字・通称:  <span class="required">*</span></td>
                <td class='form2'><select id="street_id" name="street_id" style="height:26px; width: 351px;">

                    </select><div id="error_street_id" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>字・丁目:  <span class="required">*</span></td>
                <td class='form2'><select id="ward_id" name="ward_id" style="height:26px; width: 351px;">

                    </select><div id="error_ward_id" class="error"></div>
                </td>
            </tr>
            <tr>
                <td class='form1'>番地: </td>
                <td class='form2'><input type='text' class='text' name='address' id='address' value="{$address}" style="height:26px; width: 351px;"><div id="address_error"class="error"></div></td>
            </tr>

            <tr>
                <td class='form1'>携帯電話: </td>
                <td class='form2'><input type='text' class='text' name='phone' id='phone' value="{$phone}"   style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>性別: </td>
                <td class='form2'>
                    <select id="gender"name="gender" style="height:26px; width: 351px;">
                        <option value="male"{if $gender eq "male"}selected{/if}>男性</option>
                        <option value="female"{if $gender eq "female"}selected{/if}>女性</option>
                        <option value="other"{if $gender eq "other"}selected{/if}>その他</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>生年月日: </td>
                <td class='form2'><input type='text' name='birthday' id='birthday'  value="{$birthday}"  style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>役職: </td>
                <td class='form2'><input type='text' name='position' id='position' value="{$position}"   style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>目標: </td>
                <td class='form2'><input type='text' name='target_1' id='target_1' value="{$target_1}" placeholder="今月目標 1"  style="height:26px; width: 252px; margin-right: 4%;"><lable>{$year}-01</lable></td>
            </tr>
            <tr>
                <td class='form1'></td>
                <td class='form2'><input type='text' name='target_2' id='target_2' value="{$target_2}" placeholder="今月目標 2"  style="height:26px; width: 252px;margin-right: 4%;"><lable>{$year}-02</lable></td>
            </tr>
            <tr>
                <td class='form1'></td>
                <td class='form2'><input type='text' name='target_3' id='target_3' value="{$target_3}"  placeholder="今月目標 3" style="height:26px; width: 252px;margin-right: 4%;"><lable>{$year}-03</lable></td>
            </tr>
            <tr>
                <td class='form1'></td>
                <td class='form2'><input type='text' name='target_4' id='target_4' value="{$target_4}"  placeholder="今月目標 4" style="height:26px; width: 252px;margin-right: 4%;"><lable>{$year}-04</lable></td>
            </tr>
            <tr>
                <td class='form1'></td>
                <td class='form2'><input type='text' name='target_5' id='target_5' value="{$target_5}" placeholder="今月目標 5"  style="height:26px; width: 252px;margin-right: 4%;"><lable>{$year}-05</lable></td>
            </tr>
            <tr>
                <td class='form1'></td>
                <td class='form2'><input type='text' name='target_6' id='target_6' value="{$target_6}" placeholder="今月目標 6"  style="height:26px; width: 252px;margin-right: 4%;"><lable>{$year}-06</lable></td>
            </tr>
            <tr>
                <td class='form1'></td>
                <td class='form2'><input type='text' name='target_7' id='target_7' value="{$target_7}" placeholder="今月目標 7"  style="height:26px; width: 252px;margin-right: 4%;"><lable>{$year}-07</lable></td>
            </tr>
            <tr>
                <td class='form1'></td>
                <td class='form2'><input type='text' name='target_8' id='target_8' value="{$target_8}" placeholder="今月目標 8"  style="height:26px; width: 252px;margin-right: 4%;"><lable>{$year}-08</lable></td>
            </tr>
            <tr>
                <td class='form1'></td>
                <td class='form2'><input type='text' name='target_9' id='target_9' value="{$target_9}" placeholder="今月目標 9"  style="height:26px; width: 252px;margin-right: 4%;"><lable>{$year}-09</lable></td>
            </tr>
            <tr>
                <td class='form1'></td>
                <td class='form2'><input type='text' name='target_10' id='target_10' value="{$target_10}" placeholder="今月目標 10"  style="height:26px; width: 252px;margin-right: 4%;"><lable>{$year}-10</lable></td>
            </tr>
            <tr>
                <td class='form1'></td>
                <td class='form2'><input type='text' name='target_11' id='target_11' value="{$target_11}" placeholder="今月目標 11"  style="height:26px; width: 252px;margin-right: 4%;"><lable>{$year}-11</lable></td>
            </tr>
            <tr>
                <td class='form1'></td>
                <td class='form2'><input type='text' name='target_12' id='target_12' value="{$target_12}" placeholder="今月目標 12"  style="height:26px; width: 252px;margin-right: 4%;"><lable>{$year}-12</lable></td>
            </tr>
            <tr>
                <td class='form1'>レベル: </td>
                <td class='form2'>
                    <select id="level" name="level" style="height:26px; width: 351px;">
                        <option value="4"{if $level eq "4"}selected{/if}>スタッフ</option>
                        <option value="3"{if $level eq "3"}selected{/if}>マネージャー</option>
                        <option value="2"{if $level eq "2"}selected{/if}>スーパーマネージャー</option>                                                           
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>店舗: </td>
                <td class='form2'>
                    <select id="agent" name="agent" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$agents key=k item=val}
                            <option value="{$val.id}" {if $agent eq $val.id}selected{/if}>{$val.agent_name}</option>                  
                        {/foreach}                                                   
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>グループ: </td>
                <td class='form2'>
                    <select id="group" name="group" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$groups key=k item=val}
                            <option value="{$val.id}" {if $group eq $val.id}selected{/if}>{$val.group_name}</option>                  
                        {/foreach}                                                   
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>写真: </td>
                <td class='form2'><input type='file' name='photo' id='photo' size='25'  style="height:26px; width: 351px;"><div id="display_photo" name="display_photo" ></div></td>
            </tr>        
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-signup' value='サインアップ' id="submit" name="submit"/>&nbsp;                     
                    </div>
                </td>
            </tr>

        </table>
    </form>
{/nocache}
{include file ="footer.tpl"}