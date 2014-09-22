{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">アカウント管理</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_account.php" method="post">
                <table style="">
                    <tr>
                        <td  style='font-size: 13.33px;'>検索</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="検索に名称を入力してください。" style="height:26px; width: 190px;"/>
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
                        <th>性別</th>
                        <th>生年月日</th>
                        <th>ユーザー</th>
                        <th>役職</th>
                        <th>ターゲット</th>
                        <th>レベル</th>
                        <th>有効</th>
                        <th>活動</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$users key=k item=user}
                        {assign var="link" value="edit&{$user.id}"}
                        {assign var="add" value="assign&{$user.id}"}
                        <tr>
                            <td>{$k+1}</td>
                            <td>{$user.user_lname} {$user.user_fname} </td>
                            <td>{$user.user_address|truncate:50}</td>
                            <td>{$user.user_phone}</td>
                            <td>{$user.user_email}</td>
                            <td>{$user.user_gender}</td>
                            <td>{$user.user_birthday}</td>
                            <td>{$user.user_username}</td>
                            <td>{$user.user_position}</td>
                            <td>{$user.user_target}</td>
                            <td>{if $user.user_authorities eq '1'}管理{elseif $user.user_authorities eq '2'}スーパーマネージャー{elseif $user.user_authorities eq '3'}マネージャー{else}スタッフ{/if}</td>
                            <td>{if $user.user_locked ne '1'}有効{else}無効{/if}</td>
                            <td>{if $user.user_authorities ne '1'}<a href="edit_account.php?url={$link|base64_encode}">編集</a><a href="javascript:void" onclick="deleteItem({$user.id})" style="margin: 0% 10% 0% 10%; ">{if $user.user_locked eq 1}アンロック{else}ロック{/if}</a>{/if}<a href="account_detail.php?url={$add|base64_encode}">詳細</a></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <center>
            ページ:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_account.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>
{literal}
    <script type="text/javascript">
        function deleteItem(id) {
            if (confirm("確かですか?")) {
                 $.post("include/function_ajax.php", {user_id:id, action: 'deleteAccount'},
                    function(result) {
                        if(result)
                            window.location.reload(true);
                        else
                            alert('削除が失敗しました。 :(');
                    });
            }
        }

    </script>

{/literal}

{include file="footer.tpl"}