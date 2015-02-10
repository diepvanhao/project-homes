{include file='header_global.tpl'}
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
        <h3>
            <b>
                {if !empty($error)}
                    {$error['message']}
                {/if}
            </b>
        </h3>
        <form action='forgot.php' method='POST' name='login'>
            <table cellpadding='0' cellspacing='0' width="40%" >
                <tr>
                    <td class='form1'>Ｅメール</td>
                    <td class='form2'><input type='email' value='{$email}' class='text' name='email' id='email' value='' maxlength='70' style="height:26px; width: 220px;"></td>
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