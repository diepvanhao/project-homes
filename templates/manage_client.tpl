{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">クライアント管理</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_client.php" method="post">
                <table style="width:32%">
                    <tr>
                        <td style='font-size: 1.4em;font-weight: bold;'>検索</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="検索には名前を入力します。" style="height:26px; width: 190px;"/>
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
                        <th>生年月日</th>
                        <th>住所</th>
                        <th>電話番号</th>
                        <th>収入</th>
                        <th>職業</th>
                        <th>会社名</th>
                        <th>ファックス</th>
                        <th>性別</th>
                        <th>Eメール</th>
                        <th>引越の理由</th>
                        <th>引越予定時期</th>
                        <th>入居者の名前</th>
                        <th>入居者の電話番号</th>
                        <th>賃料</th>
                        <th>間取り</th>
                        <th>オプション</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$clients key=k item=client}
                        {assign var="link" value="edit&{$client.id}"}
                        {assign var="add" value="assign&{$client.id}"}
                        <tr>
                            <td>{$k+1}</td>
                            <td>{$client.client_name}</td>
                            <td>{$client.client_birthday}</td>
                            <td>{$client.client_address}</td>
                            <td>{$client.client_phone}</td>
                            <td>{$client.client_income}</td>
                            <td>{$client.client_occupation}</td>
                            <td>{$client.client_company}</td>
                            <td>{$client.client_fax}</td>
                            <td>{$client.client_gender}</td>
                            <td>{$client.client_email}</td>
                            <td>{$client.client_reason_change}</td>
                            <td>{$client.client_time_change}</td>
                            <td>{$client.client_resident_name}</td>
                            <td>{$client.client_resident_phone}</td>
                            <td>{$client.client_rent}</td>
                            <td>{$client.client_room_type}</td>
                            <td style="width:9%"><a href="edit_client.php?url={$link|base64_encode}">編集</a><a href="javascript:void" onclick="deleteItem({$client.id})" style="margin: 0% 10% 0% 10%;">削除</a><!--a href="house_detail.php?url={$add|base64_encode}">Detail</a--></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <center>
            ページ:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_house.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>
{literal}
    <script type="text/javascript">
        function deleteItem(id) {
            if (confirm("確かですか?")) {
                 $.post("include/function_ajax.php", {id:id, action: 'deleteClient'},
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