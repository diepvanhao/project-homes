{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">店舗管理</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_agent.php" method="post">
                <table style="width:32%">
                    <tr>
                        <td style='font-size: 1.4em;font-weight: bold;'>検索</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="検索に店舗名を入力してください。" style="height:26px; width: 190px;"/>
                            <span>
                                <input type='submit' class='btn-search' value='送信' id="submit" name="submit"/>&nbsp;                     
                            </span>
                        </td>
                    </tr>

                </table>
            </form>   
        </div>
        <div>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>番号</th>
                        <th>名称</th>
                        <th>住所</th>
                        <th>電話番号</th>
                        <th>Eメール</th>
                        <th>ファックス</th>
                        <th>活動</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$agents key=k item=agent}
                        {assign var="link" value="edit&{$agent.id}"}
                        {assign var="add" value="assign&{$agent.id}"}
                        <tr>
                            <td>{$k+1}</td>
                            <td>{$agent.agent_name}</td>
                            <td>{$agent.agent_address}</td>
                            <td>{$agent.agent_phone}</td>
                            <td>{$agent.agent_email}</td>
                            <td>{$agent.agent_fax}</td>
                            <td><a href="edit_agent.php?url={$link|base64_encode}">編集</a><a href="#" onclick="deleteItem({$agent.id})" style="margin: 0% 10% 0% 10%;">削除</a><a href="add_staff_agent.php?url={$add|base64_encode}">担当</a></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <center>
            ページ:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_agent.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>
{literal}
    <script type="text/javascript">
        function deleteItem(id) {
            if (confirm("確かですか?")) {
                 $.post("include/function_ajax.php", {agent_id:id, action: 'deleteAgent'},
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