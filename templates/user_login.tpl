{include file='header.tpl'}
{literal}
    <style type="text/css">
        .content{
            float: none;
            width:100%;
        }
    </style>
{/literal}
{nocache}
    <center>
        {if $error ne ""}
            <table cellpadding='0' cellspacing='0'>
                <tr>
                    <td class='error'><img src='./include/images/error.gif' border='0' class='icon'>
                        {$error}
                    </td>
                </tr>
            </table>
            <br>
        {/if}

        <form action='user_login.php' method='POST' name='login'>
            <table cellpadding='0' cellspacing='0' width="40%" >
                <tr>
                    <td class='form1'>Ｅメール</td>
                    <td class='form2'><input type='text' class='text' name='email' id='email' value='' maxlength='70' style="height:26px; width: 220px;"></td>
                </tr>
                <tr>
                    <td class='form1'>パスワード</td>
                    <td class='form2'><input type='password' class='text' name='password' id='password' size='25' maxlength='50' style="height:26px; width: 220px;"> <div style="margin-top: 10px;"><a href='lostpass.php'>パースワードを忘れました?</a></div></td>
                </tr>

                <tr>
                    <td class='form1'>&nbsp;</td>
                    <td class='form2'>

                        <div style="margin-top:10px">
                            <input type='submit' class='smt_btn' id="submit" name="submit" value='サインイン'  style="border: none; height: 22px"/>&nbsp; 
                            <input type='reset' class='smt_btn' value='リセット'  style="border: none; height: 22px;"/>&nbsp; 

                        </div>
                    </td>
                </tr>

            </table>
        </form></center>
{/nocache}
{include file='footer.tpl'}