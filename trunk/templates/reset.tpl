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
        
        {if $check['status']}
            <h3>
                <b>
                    {if !empty($error)}
                        {$error['message']}
                    {/if}
                </b>
            </h3>
            <form  method='POST' id='resetpass'>
                <table cellpadding='0' cellspacing='0' width="40%" >
                    <tr>
                        <td class='form1'>Password</td>
                        <td class='form2'><input type='password' class='text' name='password' id='password' value='' maxlength='70' style="height:26px; width: 220px;"></td>
                    </tr>
                    <tr>
                        <td class='form1'>Confirm Password</td>
                        <td class='form2'><input type='password' class='text' name='cfpassword' id='cfpassword' value='' maxlength='70' style="height:26px; width: 220px;"></td>
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
            </form>
        {else}
            <h3>
                <b>
                    {$check['message']}
                </b>
            </h3>
        {/if}
    </center>
{/nocache}
{include file='footer.tpl'}