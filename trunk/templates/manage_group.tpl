{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">グループ管理</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_group.php" method="post">
                <table style="">
                    <tr>
                        <td  style='font-size: 13.33px;'>検索</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="" style="height:26px; width: 190px;"/>
                            <span>
                                <input type='submit' class='btn-search' value='送信' id="submit" name="submit"/>&nbsp;                     
                            </span>
                        </td>
                    </tr>

                </table>
            </form>   
        </div>
        <div>
            <table style="width: 50%;">
                <thead>
                    <tr>
                        <th>番号</th>
                        <th>名称</th>                        
                        <th>活動</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$groups key=k item=group}
                        {assign var="link" value="edit&{$group.id}"}                        
                        <tr>
                            <td style="width: 10%">{$k+1}</td>
                            <td>{$group.group_name}</td>                            
                            <td style="width: 10%"><a href="edit_group.php?url={$link|base64_encode}">編集</a><a href="#" onclick="deleteItem({$group.id},{$group.group_lock})" style="margin: 0% 10% 0% 10%;">{if $group.group_lock eq 0}削除{else}回復{/if}</a></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <center>
            ページ:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_group.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>
{literal}
    <script type="text/javascript">
        function deleteItem(id,group_lock) {
            if (confirm("確かですか?")) {
                 $.post("include/function_ajax.php", {group_id: id,group_lock: group_lock, action: 'deleteGroup'},
                    function(result) {
                        if(result)
                            window.location.reload(true);
                        else
                            alert('Delete fail :(');
                    });
            }
        }

    </script>

{/literal}

{include file="footer.tpl"}