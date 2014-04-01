{include file="header.tpl"}
{literal}
    <script type="text/javascript">
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
                    password=$('#password').val();
                    $.post("include/function_ajax.php", {password: password, confirm_password:confirm_password, action: 'check_password'},
                    function(result) {
                        $('#confirm_password_error').html(result);
                    });
                }
            });
        });

    </script>
{/literal}
<div style="text-align: center;font-size: 1.4em;padding-bottom: 10px; ">
    <label >Sign Up Account</label>
</div>

{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    <form action='user_account.php' method='POST' name='create' id="create" enctype="multipart/form-data">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>Email: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='email' id='email' value="{$email}" maxlength='70' style="height:26px; width: 351px;"><div id="email_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Password: <span class="required">*</span></td>
                <td class='form2'><input type='password' class='text' name='password' id='password' value="{$password}"size='25' style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>Confirm Password: <span class="required">*</span></td>
                <td class='form2'><input type='password' class='text' name='confirm_password' id='confirm_password' value="{$confirm_password}" size='25'  style="height:26px; width: 351px;"><div id="confirm_password_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>Username: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='username' id='username' value="{$username}"   style="height:26px; width: 351px;"><div id="username_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>First Name: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='firstname' id='firstname' value="{$firstname}"  style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>Last Name: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='lastname' id='lastname' value="{$lastname}"   style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>Address: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='address' id='address' value="{$address}"   style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>Cell Phone: </td>
                <td class='form2'><input type='text' class='text' name='phone' id='phone' value="{$phone}"   style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>Gender: </td>
                <td class='form2'>
                    <select id="gender"name="gender" style="height:26px; width: 351px;">
                        <option value="male"{if $gender eq "male"}selected{/if}>Male</option>
                        <option value="female"{if $gender eq "female"}selected{/if}>Female</option>
                        <option value="other"{if $gender eq "other"}selected{/if}>Other</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>Birthday: </td>
                <td class='form2'><input type='text' name='birthday' id='birthday'  value="{$birthday}"  style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>Position: </td>
                <td class='form2'><input type='text' name='position' id='position' value="{$position}"   style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>Target: </td>
                <td class='form2'><input type='text' name='target' id='target' value="{$target}"   style="height:26px; width: 351px;"></td>
            </tr>
            <tr>
                <td class='form1'>Level: </td>
                <td class='form2'>
                    <select id="level" name="level" style="height:26px; width: 351px;">
                        <option value="4"{if $level eq "4"}selected{/if}>Staff</option>
                        <option value="3"{if $level eq "3"}selected{/if}> Manager</option>
                        <option value="2"{if $level eq "2"}selected{/if}>Super Manager</option>                                                           
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>Agent: </td>
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
                <td class='form1'>Photo: </td>
                <td class='form2'><input type='file' name='photo' id='photo' size='25'  style="height:26px; width: 351px;"><div id="display_photo" name="display_photo" ></div></td>
            </tr>        
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-signup' value='Sign Up Now' id="submit" name="submit"/>&nbsp;                     
                    </div>
                </td>
            </tr>

        </table>
    </form>
{/nocache}
{include file ="footer.tpl"}