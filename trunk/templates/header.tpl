{include file="header_global.tpl"}
{if $url_page  eq 'user_login.php' }
<div style="margin: 50px 0px 0px 480px;background-color: #F1F5FE;width: 50%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px;">サインイン</div>

{/if}
    <div id="site_content">
        <div id="sidebar_container">
            <div class="sidebar">
                {if $url_page  ne 'user_login.php' }
                    {if $user->user_exists ne 1}
                        <form action='user_login.php' method='POST' name='login'>
                            <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                                <tr>
                                    <td class='form1'>Ｅメール</td>
                                    <td class='form2'><input type='text' class='text' name='email' id='email' value='' maxlength='70' style="height:26px; width: 172px;"></td>
                                </tr>
                                <tr>
                                    <td class='form1'>パスワード</td>
                                    <td class='form2'><input type='password' class='text' name='password' id='password' size='25' maxlength='50' style="height:26px; width: 172px;"> <div style="margin-top: 10px;"><a href='lostpass.php'>パースワードを忘れました。?</a></div></td>
                                </tr>

                                <tr>
                                    <td class='form1'>&nbsp;</td>
                                    <td class='form2'>

                                        <div style="margin-top:10px">
                                            <input type='submit' class='smt_btn' id="submit" name="submit" value='送信'  style="border: none; height: 22px"/>&nbsp; 
                                            <input type='reset' class='smt_btn' value='リセット'  style="border: none; height: 22px;"/>&nbsp; 

                                        </div>

                                    </td>
                                </tr>

                            </table>
                        </form>
                    {elseif $user->user_exists eq 1}
                        <div class="user_exist"><center>
                            <div>
                                <div>
                                    {if $user->user_info.user_path_photo eq ""}
                                        <a target="_top" href="./user_profile.php"><img width="100" height="100" src="./include/images/nouserphoto.gif"></a>
                                    {else}
                                    <a target="_top" href="./user_profile.php"><img width="100" height="100" src="{$user->user_info.user_path_photo}{$user->user_info.user_path_thumb}"/></a>
                                    {/if}
                                </div>
                                <br>
                                Welcome, <a target="_top" href="./user_profile.php">{$user->user_info.user_lname} {$user->user_info.user_fname} </a>
                                &nbsp;&nbsp;<br><br>

                                <a target="_top" href="help.php">ご質問・ご要望</a>&nbsp;&nbsp;
                                <form target="_top" style="display:inline;margin:0;" action="user_logout.php" id="user_logout" method="POST"><a onclick="$('#user_logout').submit();
                                        return false;" class="" href="user_logout.php">ログアウト</a>
                                </form>
                            </div></center>
                        </div>
                    {/if}
                {/if}
            </div>
            {if $url_page  ne 'user_login.php' }
            <div class="sidebar">
                <h3>カレンダー</h3>
                <div id="datepicker"></div>               
            </div>   
            {/if}
        </div>
        <div class="content">  
            {if $url_page eq 'home.php'}
                <h1>Welcome to the ARP management system</h1>
            {/if}
            <!--your content write here -->   