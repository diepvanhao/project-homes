{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; ">編集接触</div>
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    {if $notify ne ""}
        {$notify}
    {/if}
    <form action="edit_contact.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>名称:</td>
                <td class='form2'><input type='text' class='text' name='name' id='name' value="{$name}"  style="height:26px; width: 351px;"><div id="name_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>ユーザー:</td>
                <td class='form2'><input type='text' class='text' name='username' id='username' value="{$username}"  style="height:26px; width: 351px;"><div id="username_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>パスワード:</td>
                <td class='form2'><input type='text' class='text' name='password' id='password' value="{$password}"  style="height:26px; width: 351px;"><div id="password_error"class="error"></div></td>
            </tr>
     
            
            <tr>
                <td class='form1'>コメント: </td>
                <td class='form2'><input type='text' class='text' name='comment' id='comment' value="{$comment}" style="height:26px; width: 351px;"><div id="comment_error"class="error"></div></td>
            </tr>
            
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-search' value='変更' id="submit" name="submit"/>&nbsp;  &nbsp; 
                        <input type='button' class='btn-search' value='戻る' id="back" name="back" onclick="back();"/>&nbsp;  
                        <input type='hidden'  value='{$id}' id="id" name="id"/>                   
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
                window.location.href = "contact.php";
            });
        });
    </script>
{/literal}
{include file='footer.tpl'}