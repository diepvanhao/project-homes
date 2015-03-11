{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; ">グループ編集</div>
{nocache}
    {if $error|@count gt 0}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    {if $notify ne ""}
        {$notify}
    {/if}
    <form action="edit_group.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>名称: <span class="required">*</span></td>
                <td class='form2'><input type='text' class='text' name='group_name' id='source_name' value="{$group_name}"  style="height:26px; width: 351px;"><div id="group_name_error"class="error"></div></td>
            </tr>
            <tr>
                <td class='form1'>表示: <span class="required">*</span></td>
                <td class='form2'>
                    <select id="display" name="display" style="height:28px; width: 115px;">
                        <option value="0">いいえ</option>
                        <option value="1">はい</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-search' value='変更' id="submit" name="submit"/>&nbsp;  &nbsp; 
                        <input type='button' class='btn-search' value='戻る' id="back" name="back" onclick="back();"/>&nbsp;  
                        <input type='hidden'  value='{$group_id}' id="group_id" name="group_id"/>  
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
                window.location.href = "manage_group.php";
            });
        });
    </script>
{/literal}
{include file='footer.tpl'}