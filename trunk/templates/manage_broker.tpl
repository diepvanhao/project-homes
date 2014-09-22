{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">管理会社</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_broker.php" method="post">
                <table style="">
                    <tr>
                        <td  style='font-size: 13.33px;'>検索</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="検索には名前または電話番号を入力します。" title="Enter broker company name or phone to search"style="height:26px; width: 248px;"/>
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
                        <th>担当者</th>
                        <th>活動</th>
                        <th>ログイン</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$brokers key=k item=broker}
                        {assign var="link" value="edit&{$broker.id}"}
                        {assign var="add" value="assign&{$broker.id}"}
                        <tr>
                            <td>{$k+1}</td>
                            <td>{$broker.broker_company_name}</td>
                            <td>{$broker.broker_company_address}</td>
                            <td>{$broker.broker_company_phone}</td>
                            <td><a href="mail_broker.php?id={$broker.id}">{$broker.broker_company_email}</a></td> 
                            <td>{$broker.broker_company_fax}</td>
                            <td>{$broker.broker_company_undertake}</td>
                            <td><a href="edit_broker.php?url={$link|base64_encode}">編集</a><a href="#" onclick="deleteItem({$broker.id},{$broker.broker_company_lock})" style="margin: 0% 10% 0% 10%;">{if $broker.broker_company_lock eq 0}削除{else}回復{/if}</a><a href="add_house_broker.php?url={$add|base64_encode}">お部屋を追加します</a>
                                {*<a href="mail_broker.php?id={$broker.id}">MAIL</a>*}
                            </td>
                            <td>
                                {if $broker.name ne ""}
                                    <form action="{$broker.action}" method="post" target="blank">
                                        <input type="hidden" name="{$broker.idlogname}" value="{$broker.username}" />
                                        <input type="hidden" name="{$broker.passlogname}" value="{$broker.password}" />
                                        {if $broker.inputhidden ne ""}
                                            {$broker.inputhidden}
                                        {/if}
                                        <input type="submit" class="btn" value="ログイン" {if $broker.submitname ne ""}name="{$broker.submitname}"{/if}/>	
                                    </form>
                                
                                {/if}
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <center>
            ページ:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_broker.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>
{literal}
    <script type="text/javascript">
        function deleteItem(id, broker_company_lock) {
            if (confirm("確かですか?")) {
                $.post("include/function_ajax.php", {broker_id: id, broker_company_lock: broker_company_lock, action: 'deleteBroker'},
                function(result) {
                    if (result)
                        window.location.reload(true);
                    else
                        alert('Delete fail :(');
                });
            }
        }

    </script>

{/literal}

{include file="footer.tpl"}