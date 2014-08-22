{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">オーダー管理</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_order.php" method="post">
                <table style="">
                    <tr>
                        <td style='font-size: 1.4em;font-weight: bold;'>検索</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="検索には物件名を入力します。" style="height:26px; width: 248px;"/>
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
                        <th>物件情報</th>
                        <th>部屋情報</th>
                        <th>賃料</th>                                                
                        <th>現況</th>                       
                        <th>登録日付</th>
                        <th>お客情報</th>
                        <th>担当</th>                                                                      
                        <th>活動</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$orders key=k item=order}
                        {assign var="link" value="edit&{$order.id}"}
                        {assign var="add" value="assign&{$order.id}"}
                        <tr>
                            <td>{$k+1}</td>
                            <td>{$order.order_name}</td>
                            <td>{$order.house_name}</td>
                            <td>{$order.room_id}</td>
                            <td>{$order.order_rent_cost}</td>                            
                            <td>{if $order.order_status eq 1}処理{else} キャンセル{/if}</td>
                            <td>{$order.order_day_create}</td>                           
                            <td>{$order.client_name}</td>
                            <td>{if $order.user_id ne 0}割り当てられた {else} 
                                <select id="staff_id" name="staff_id" >                                                                       
                                    <option value="{$user->user_info.id}">{$user->user_info.user_fname} {$user->user_info.user_lname}</option>                                           
                                </select>
                                {/if}</td>                                                                     
                                <td style="width:15%">{if $order.user_id eq 0}<a href="edit_order.php?url={$link|base64_encode}" id="registry" style="margin-right: 10px;">登録</a>{/if}{if (($order.user_id eq $user_id) or ($user->user_info.user_authorities lte 2)and ($order.user_id ne 0))}<a href="edit_order.php?url={$link|base64_encode}" style="margin-right: 10px;">編集</a>{/if}{if ($order.user_id eq $user_id) or ($user->user_info.user_authorities lte 2)}<a href="javascript:void" onclick="deleteItem({$order.id},{$order.house_id},{$order.broker_id},{$order.room_id})" style="margin-right: 10px;">削除</a>{/if}<a href="order_detail.php?url={$add|base64_encode}">詳細</a></td>
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
                    function deleteItem(id,house_id,broker_id,room_id) {
                        if (confirm("確かですか? システムは履歴、契約内容などが含んでオーダー情報を削除します。")) {
                            $.post("include/function_ajax.php", {order_id: id,house_id: house_id,broker_id: broker_id,room_id: room_id, action: 'deleteOrder'},
                            function(result) {
                                if (result)
                                    window.location.reload(true);
                                else
                                    alert('Delete fail :(');
                            });
                        }
                    }
                    $('#registry').click(function() {
                        if (confirm("このオーダーを登録しますか。 ?")) {
                            return true;
                        }else{
                            return false;
                        }

                    });
                </script>

            {/literal}

            {include file="footer.tpl"}