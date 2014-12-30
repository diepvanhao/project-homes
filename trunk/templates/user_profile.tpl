{include file="header_global.tpl"}
{literal}
    <style type="text/css">
        #profile{
            width:1000px;
            margin-left: auto;
            margin-right: auto;
        }
        #profile ul{
            list-style-type: none;            
            margin: 0;
            padding: 0;
            border-bottom: 1px solid #aaa;
            border-top: 1px solid #aaa;
            -webkit-margin-before: 1em;
            -webkit-margin-after: 1em;
            -webkit-margin-start: 0px;
            -webkit-margin-end: 0px;
            -webkit-padding-start: 40px;
            display: block;
        }

        #profile ul li{
            //  border-color: #e9e9e9;
            // border-style: solid;
            margin: 0px;
            border-bottom: 1px solid #e9e9e9;
            text-align: -webkit-match-parent;
            list-style: none;
        }


        #profile table tr th, table tr td { 
            background: #e9e9e9;
            color: #FFF;
            padding: 10px;
            text-align: left;
            text-shadow: none;
        }

        #profile table tr td { 
            background: #e9e9e9;
            color: #47433F;           
            // width: 100%;        
            border-top: 0px;
        }
        #name_panel{
            // width: 155px;
            // position: absolute;
            left: 0;
            float: left;
            margin: 7px 4px;
        }


        #profile ul li:hover{
            background: #F2F2F2;
        }
        #profile ul li a{
            background: transparent;
            display: block;
            text-decoration: none;
            padding:10px 5px 10px 5px;
            color: #3b5998;
            cursor: pointer;
            font-family: Helvetica, Arial, 'lucida grande',tahoma,verdana,arial,sans-serif;
        }
        #profile ul li a h3{
            color: #333;
            float: left;
            font-size: 12px;
            width: 165px;
            padding-left: 5px;
            display: block;
            -webkit-margin-before: 1em;
            -webkit-margin-after: 1em;
            -webkit-margin-start: 0px;
            -webkit-margin-end: 0px;
            font-weight: bold;
            line-height: 5px;
        }
        .edit_profile{
            color: #3b5998;
            float: right;
            padding-right: 5px;
            text-align: right;
        }
        .content_profile{
            margin-left: 175px;
            padding-left: 0;
            width: 445px;
            display: block;
            color: gray;
        }
        #general{
            line-height: 20px;
            min-height: 20px;
            padding-bottom: 2px;
            vertical-align: bottom;
            outline: medium none;
            //border-bottom: 1px solid #AAAAAA;
        }
        #general h2{
            color: #1C2A47;
            font-size: 20px;
        }
    </style>
    <script>
        $(document).ready(function() {
            $("a").hover(function() {
                $("a").each(function() {
                    $('a').find('#img_edit').css('display', "none");
                });
                $(this).find('#img_edit').css('display', "");
            }, function() {
                $("a").each(function() {
                    $('a').find('#img_edit').css('display', "none");
                });
            });
            $('#cancel').click(function(e) {
                window.location.reload(true);
            });
            //edit name
            $('li').click(function(e) {

                $(this).parent().each(function() {
                    $('li').find('a').css('display', '');
                    $('#edit_profile li').css('background-color', 'white');
                    $('li').find('#edit_content').css('display', 'none');
                });
                $(this).find('a').css('display', 'none');
                $(this).find('#edit_content').css('display', '');
                $(this).css('background-color', '#e9e9e9');
                return false;
                e.preventDefault();
            });
            $('#cancel_name').click(function(e) {
                $("#cancel").click();
                e.preventDefault();
            });
            $('#cancel_username').click(function(e) {
                $("#cancel").click();
                e.preventDefault();
            });
            $('#cancel_email').click(function(e) {
                $("#cancel").click();
                e.preventDefault();
            });
            $('#cancel_password').click(function(e) {
                $("#cancel").click();
                e.preventDefault();
            });
            $('#cancel_photo').click(function(e) {
                $("#cancel").click();
                e.preventDefault();
            });
            $("#edit_name_sub").click(function(e) {
                e.preventDefault();
                var fname = $('#firstname').val();
                var lname = $('#lastname').val();
                var password = $(this).parent().parent().find('#password').val();

                $.post("include/function_ajax.php", {fname: fname, lname: lname, password: password, action: 'edit_profile', task: 'editName'},
                function(result) {
                    if (result)
                        $('#error_password').html(result);
                    else
                        window.location.reload(true);
                });
            });

            $("#edit_username_sub").click(function(e) {
                e.preventDefault();
                $('#error_username').html("");
                $('#error_password_username').html("");
                var username = $('#username').val();
                var password = $(this).parent().parent().find('#password').val();

                $.post("include/function_ajax.php", {username: username, password: password, action: 'edit_profile', task: 'editUsername'},
                function(result) {
                    var json = $.parseJSON(result);

                    if (json.password)
                        $('#error_password_username').html(json.password);
                    else if (json.username)
                        $('#error_username').html(json.username);
                    else
                        window.location.reload(true);
                });
            });
            $("#edit_email_sub").click(function(e) {
                e.preventDefault();
                $('#error_email').html("");
                $('#error_password_email').html("");
                var email = $('#email').val();
                var password = $(this).parent().parent().find('#password').val();

                $.post("include/function_ajax.php", {email: email, password: password, action: 'edit_profile', task: 'editEmail'},
                function(result) {
                    var json = $.parseJSON(result);
                    if (json.password)
                        $('#error_password_email').html(json.password);
                    else if (json.email)
                        $('#error_email').html(json.email);
                    else
                        window.location.reload(true);
                });
            });

            $("#edit_pass_sub").click(function(e) {
                e.preventDefault();
                $('#error_current').html("");
                $('#error_new_password').html("");
                $('#error_re_password').html("");

                var current_password = $('#current_password').val();
                var new_password = $('#new_password').val();
                var re_new_password = $('#re_new_password').val();

                $.post("include/function_ajax.php", {current_password: current_password, new_password: new_password, re_new_password: re_new_password, action: 'edit_profile', task: 'editPassword'},
                function(result) {
                    var json = $.parseJSON(result);
                    if (json.current_password)
                        $('#error_current').html(json.current_password);
                    else if (json.new_password)
                        $('#error_new_password').html(json.new_password);
                    else if (json.re_new_password)
                        $('#error_re_password').html(json.re_new_password);
                    else
                        window.location.reload(true);
                });
            });
            $('#photo').change(function(e) {
                $("#display_name_photo").html($("#photo").val());
                $('#edit_photo_sub').attr('disabled', '');
                $('#edit_photo_sub').css('color', 'white');
            });

            $("#edit_photo_sub").click(function(e) {
                e.preventDefault();
                $('#error_photo').html("");
                $('#error_password_photo').html("");

                var password = $(this).parent().parent().find('#password').val();
                var photoname = $("#photo").val();
                $.post("include/function_ajax.php", {photoname: photoname, password: password, action: 'edit_profile', task: 'editPhoto'},
                function(result) {
                    var json = $.parseJSON(result);
                    if (json.password)
                        $('#error_password_photo').html(json.password);
                    else
                        $('#submit').click();
                });
            });
        });

        function removeDisabledName() {

            if (($('#firstname').val() != $('#firstVal').val()) || ($('#lastname').val() != $('#lastVal').val())) {
                $('#edit_name_sub').attr('disabled', '');
                $('#edit_name_sub').css('color', 'white');
            } else {
                $('#edit_name_sub').attr('disabled', '1');
                $('#edit_name_sub').css('color', '#ADBAD4');
            }
        }
        function removeDisabledUsername() {

            if ($('#username').val() != $('#oldusername').val()) {
                $('#edit_username_sub').attr('disabled', '');
                $('#edit_username_sub').css('color', 'white');
            } else {
                $('#edit_username_sub').attr('disabled', '1');
                $('#edit_username_sub').css('color', '#ADBAD4');
            }
        }
        function removeDisabledEmail() {
            if ($('#email').val() != $('#oldemail').val()) {
                $('#edit_email_sub').attr('disabled', '');
                $('#edit_email_sub').css('color', 'white');
            } else {
                $('#edit_email_sub').attr('disabled', '1');
                $('#edit_email_sub').css('color', '#ADBAD4');
            }
        }
        function removeDisabledPassword() {
            if ($('#new_password').val() != "" || $('#re_new_password').val() != "") {
                $('#edit_pass_sub').attr('disabled', '');
                $('#edit_pass_sub').css('color', 'white');
            } else {
                $('#edit_pass_sub').attr('disabled', '1');
                $('#edit_pass_sub').css('color', '#ADBAD4');
            }
        }
        function removeDisabledPhoto() {
            if ($('#photo').val() != "") {
                $('#edit_photo_sub').attr('disabled', '');
                $('#edit_photo_sub').css('color', 'white');
            } else {
                $('#edit_photo_sub').attr('disabled', '1');
                $('#edit_photo_sub').css('color', '#ADBAD4');
            }
        }
        function selectfile() {
            $('#photo').click();
        }

        function show() {
            alert($('#photo').val());
        }

    </script>
{/literal}
{nocache}
    <div id="profile">
        <div id='general'><h2>アカウント設定</h2></div>
        <div id='edit_profile'>
            <ul>
                <li>
                    <a href="javascript:void" >
                        <h3>名称</h3>
                        <span class="edit_profile"><img id="img_edit" style="display: none;" src="include/images/edit.jpg"/>編集</span>
                        <span class="content_profile"><strong>{$user->user_info.user_lname} {$user->user_info.user_fname} </strong></span>
                    </a>
                    <div id="edit_content" style="display:none;">
                        <div id="name_panel"><strong>名称</strong></div>
                        <center>
                            <div>
                                <table>                                   
                                    <tr>
                                        <td>名字</td>
                                        <td><input type="text" id="lastname" name="lastname" value="{$user->user_info.user_lname}"onkeyup="removeDisabledName();"style="height:26px; width: 151px;"/></td>
                                    </tr>
                                    <tr>
                                        <td>名前</td>
                                        <td><input type="text" id="firstname" name="firstname" value="{$user->user_info.user_fname}" onkeyup="removeDisabledName();"style="height:26px; width: 151px;"/></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><div>設定を保存するには、パスワードを入力してください。</div></td>
                                    </tr>
                                    <tr>
                                        <td>パスワード</td>
                                        <td><input type="password" id="password" name="password" value=""style="height:26px; width: 151px;"/><span id="error_password" style="padding-left: 16px;left: 0;vertical-align: middle;color: red;"></span></td>
                                    </tr>
                                </table>
                                <div>
                                    <input type="button" value="変更" id="edit_name_sub"  disabled="1" style="background-color: #617AAC; color: #ADBAD4;">
                                    <input type="button" value="キャンセル" id="cancel_name">
                                    <input type="hidden" id="firstVal" value="{$user->user_info.user_fname}">
                                    <input type="hidden" id="lastVal" value="{$user->user_info.user_lname}">
                                </div>
                            </div>
                        </center>
                    </div>            
                </li>   
                <li>
                    <a href="javascript:void" >
                        <h3>ユーザー</h3>
                        <span class="edit_profile"><img id="img_edit" style="display: none;" src="include/images/edit.jpg"/> 編集</span>
                        <span class="content_profile"><strong>{$user->user_info.user_username}</strong></span>
                    </a>
                    <div id="edit_content" style="display:none;">
                        <div id="name_panel"><strong>ユーザー</strong></div>
                        <center>
                            <div>
                                <table>
                                    <tr>
                                        <td>ユーザー</td>
                                        <td><input type="text" id="username" name="username" value="{$user->user_info.user_username}" onkeyup="removeDisabledUsername();"style="height:26px; width: 151px;"/><span id="error_username" style="padding-left: 16px;left: 0;vertical-align: middle;color: red;"></span></td>
                                    </tr>

                                    <tr>
                                        <td colspan="2"><div>設定を保存するには、パスワードを入力してください。</div></td>
                                    </tr>
                                    <tr>
                                        <td>パスワード</td>
                                        <td><input type="password" id="password" name="password" value=""style="height:26px; width: 151px;"/><span id="error_password_username" style="padding-left: 16px;left: 0;vertical-align: middle;color: red;"></span></td>
                                    </tr>
                                </table>
                                <div>
                                    <input type="button" value="変更" id="edit_username_sub"  disabled="1" style="background-color: #617AAC; color: #ADBAD4;">
                                    <input type="button" value="キャンセル" id="cancel_username">
                                    <input type="hidden" id="oldusername" value="{$user->user_info.user_username}">

                                </div>
                            </div>
                        </center>
                    </div>              
                </li>   
                <li>
                    <a href="javascript:void" >
                        <h3>Eメール</h3>
                        <span class="edit_profile"><img id="img_edit" style="display: none;" src="include/images/edit.jpg"/> 編集</span>
                        <span class="content_profile"><strong>{$user->user_info.user_email}</strong></span>
                    </a>
                    <div id="edit_content" style="display:none;">
                        <div id="name_panel"><strong>Eメール</strong></div>
                        {if $user->user_info.user_authorities  lte 2}
                            <center>
                                <div>
                                    <table>
                                        <tr>
                                            <td>Eメール</td>
                                            <td><input type="text" id="email" name="email" value="{$user->user_info.user_email}" onkeyup="removeDisabledEmail();"style="height:26px; width: 151px;"/><span id="error_email" style="padding-left: 16px;left: 0;vertical-align: middle;color: red;"></span></td>
                                        </tr>

                                        <tr>
                                            <td colspan="2"><div>設定を保存するには、パスワードを入力してください。</div></td>
                                        </tr>
                                        <tr>
                                            <td>パスワード</td>
                                            <td><input type="password" id="password" name="password" value=""style="height:26px; width: 151px;"/><span id="error_password_email" style="padding-left: 16px;left: 0;vertical-align: middle;color: red;"></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><div><strong><i>注意：編集した後、システムは自動的にアカウントをログアウトします。</i></strong></div></td>
                                        </tr>
                                    </table>
                                    <div>
                                        <input type="button" value="変更" id="edit_email_sub"  disabled="1" style="background-color: #617AAC; color: #ADBAD4;">
                                        <input type="button" value="キャンセル" id="cancel_email">
                                        <input type="hidden" id="oldemail" value="{$user->user_info.user_email}">                                    
                                    </div>
                                </div>
                            </center>
                        {else}
                            <center>
                                <div>
                                    <h3>あなたのアカウントは、電子メールを変更することはできません。詳細については、管理者にお問い合わせください。!!!</h3>
                                </div>
                                <div>
                                    <input type="button" id="cancel_email" value="閉じる"/>
                                </div>
                            </center>
                        {/if}
                    </div>            
                </li>   
                <li>
                    <a href="javascript:void" >
                        <h3>パスワード</h3>
                        <span class="edit_profile"><img id="img_edit" style="display: none;" src="include/images/edit.jpg"/> 編集</span>
                        <span class="content_profile"><strong>パスワードのセキュリティを守ってください。</strong></span>
                    </a>
                    <div id="edit_content" style="display:none;">
                        <div id="name_panel"><strong>パスワード</strong></div>
                        <center>
                            <div>
                                <table>
                                    <tr>
                                        <td>現在</td>
                                        <td><input type="password" id="current_password" name="current_password" value="" style="height:26px; width: 151px;"/><span id="error_current" style="padding-left: 16px;left: 0;vertical-align: middle;color: red;"></span></td>
                                    </tr>
                                    <tr>
                                        <td>新しいパスワード</td>
                                        <td><input type="password" id="new_password" name="new_password" value="" onkeyup="removeDisabledPassword();"style="height:26px; width: 151px;"/><span id="error_new_password" style="padding-left: 16px;left: 0;vertical-align: middle;color: red;"></span></td>
                                    </tr>
                                    <tr>
                                        <td>新しい再入力</td>
                                        <td><input type="password" id="re_new_password" name="re_new_password" value="" onkeyup="removeDisabledPassword();"style="height:26px; width: 151px;"/><span id="error_re_password" style="padding-left: 16px;left: 0;vertical-align: middle;color: red;"></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><div><strong><i>注意：編集した後、システムは自動的にアカウントをログアウトします。</i></strong></div></td>
                                    </tr>
                                </table>
                                <div>
                                    <input type="button" value="変更" id="edit_pass_sub"  disabled="1" style="background-color: #617AAC; color: #ADBAD4;">
                                    <input type="button" value="キャンセル" id="cancel_password">                                                                        
                                </div>
                            </div>
                        </center>
                    </div>              
                </li>
                <li>
                    <a href="javascript:void" >
                        <h3>写真</h3>
                        <span class="edit_profile"><img id="img_edit" style="display: none;" src="include/images/edit.jpg"/> 編集</span>
                        <span class="content_profile"><strong><img src="{$user->user_info.user_path_photo}{$user->user_info.user_photo}"/></strong></span>
                    </a>
                    <div id="edit_content" style="display:none;">
                        <div id="name_panel"><strong>写真</strong></div>
                        <center>
                            <div>

                                <table>
                                    <tr>
                                        <td>写真アップロード</td>
                                        <td><input type='button' name='upload' id='upload' value="Choose File"   style="height:26px; width: 151px;" onclick="selectfile();"><span style="margin-left:5px;"id="display_name_photo"></span><span id="error_photo" style="padding-left: 16px;left: 0;vertical-align: middle;color: red;"></span></td>
                                    </tr>
                                    <tr>
                                        <td>現在イメージ</td>
                                        <td><span class="content_profile"><strong><img src="{$user_path_photo}{$user_photo}"/></strong></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><div>設定を保存するには、パスワードを入力してください。</div></td>
                                    </tr>
                                    <tr>
                                        <td>新しい再入力</td>
                                        <td><input type="password" id="password" name="password" value=""style="height:26px; width: 151px;"/><span id="error_password_photo" style="padding-left: 16px;left: 0;vertical-align: middle;color: red;"></span></td>
                                    </tr>
                                </table>
                                <div>
                                    <input type="button" value="変更" id="edit_photo_sub"  disabled="1" style="background-color: #617AAC; color: #ADBAD4;">
                                    <input type="button" value="キャンセル" id="cancel_photo">                                       
                                </div>

                            </div>
                        </center>
                    </div>            
                </li>
                <form action="user_profile.php" method="post" enctype="multipart/form-data" id="change_image" name="change_image">
                    <input type='submit' class='btn-search' value='変更' id="submit" name="submit" style="display: none;"/>
                    <input type="file" id="photo"name="photo" style="display: none;"/>
                </form>
                <input type="button" id="cancel" onclick="close();"style="display: none;"/>
            </ul>
        </div>
    </div>

    <div style="margin-bottom: 60px;"></div>
{/nocache}
{include file="footer.tpl"}