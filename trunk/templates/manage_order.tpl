{include file="header_global.tpl"}
{literal}
    <script type="text/javascript">
        $(document).ready(function() {
            birthday('order_day_create');
            birthday('log_revisit');
            birthday('contract_application_date');
            birthday('contract_signature_day');
            birthday('contract_payment_date_from');
            birthday('contract_payment_date_to');
            birthday('contract_handover_day');
        });
    </script>
{/literal}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">オーダー管理</div>
<center>
    <div style="width: 100%;">
        <form action="manage_order.php" method="post">
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
                            <th><span style="color: #1166E7;">最終連絡日</span></th>
                            <th><span style="color: #D33F2A;">申込日</span></th>
                            <th><span style="color: #FFB301;">申込金</span></th>
                            <th><span style="color: #009C58;">契約日</span></th>
                            <th><span style="color: #76009D;">契約金</span></th>
                            <th><span style="color: #628DB6;">広告費入金日</span></th>
                            <th><span style="color: #9D000A;">鍵渡日</span></th>                                                                      
                            <th>活動</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="order_filter">
                            <td></td>
                            <td><input type="text" name="order_name"id="order_name" value="{$order_name}"/></td>
                            <td><input type="text" name="house_name"id="house_name" value="{$house_name}"/></td>
                            <td><input type="text" name="room_id"id="room_id" value="{$room_id}"/></td>
                            <td><input type="text" name="order_rent_cost"id="order_rent_cost" value="{$order_rent_cost}"/></td>
                            <td>
                                <select name="order_status" id="order_status">
                                    <option value="0" {if $order_status eq '0'}selected {/if}>  </option>                                    
                                    <option value="1"{if $order_status eq '1'}selected {/if}>他決</option>
                                    <option value="2"{if $order_status eq '2'}selected {/if}>キャンセル</option>
                                    <option value="3"{if $order_status eq '3'}selected {/if}>契約済</option>
                                    <option value="4"{if $order_status eq '4'}selected {/if}>申込中</option>
                                    <option value="5"{if $order_status eq '5'}selected {/if}>追客中</option>
                                </select>
                            </td>
                            <td><input type="text" name="order_day_create"id="order_day_create" value="{$order_day_create}"/></td>
                            <td><input type="text" name="client_name"id="client_name" value="{$client_name}"/></td>
                            <td><input type="text" name="log_revisit"id="log_revisit" value="{$log_revisit}"/></td>
                            <td><input type="text" name="contract_application_date"id="contract_application_date" value="{$contract_application_date}"/></td>
                            <td><input type="text" name="money_payment"id="money_payment" value="{$money_payment}"/></td>
                            <td><input type="text" name="contract_signature_day"id="contract_signature_day" value="{$contract_signature_day}"/></td>
                            <td><input type="text" name="contract_payment_date_from"id="contract_payment_date_from" value="{$contract_payment_date_from}"/></td>
                            <td><input type="text" name="contract_payment_date_to"id="contract_payment_date_to" value="{$contract_payment_date_to}"/></td>
                            <td><input type="text" name="contract_handover_day"id="contract_handover_day" value="{$contract_handover_day}"/></td>
                            <td nowrap>
                                <input type='submit' class='btn-search' value='送信' id="submit" name="submit"/>&nbsp;                     
                                <a href="create_order.php"><input type='button' class='btn-search' value='登録' id="submit" /></a>
                            </td>
                        </tr>
                        {foreach from=$orders key=k item=order}
                            {assign var="link" value="edit&{$order.id}"}
                            {assign var="add" value="assign&{$order.id}"}
                            <tr>
                                <td>{$k+1}</td>
                                <td>{$order.order_name}</td>
                                <td>{$order.house_name}</td>
                                <td>{$order.room_id}</td>
                                <td>{$order.order_rent_cost}</td>                            
                                <!--<td>{if $order.order_status eq 1}処理{else} キャンセル{/if}</td>-->
                                <td>
                                    {if $order.room_rented}
                                        他決
                                    {elseif $order.contract_cancel }
                                        キャンセル
                                    {elseif $order.contract_signature_day }
                                        契約済
                                    {elseif $order.contract_application}
                                        申込中
                                    {else}
                                        追客中
                                    {/if}
                                </td>
                                <td>{$order.order_day_create}</td>                           
                                <td>{$order.client_name}</td>
    <!--                            <td>{if $order.user_id ne 0}割り当てられた {else} 
                                    <select id="staff_id" name="staff_id" >                                                                       
                                        <option value="{$user->user_info.id}">{$user->user_info.user_lname} {$user->user_info.user_fname} </option>                                           
                                    </select>
                                {/if}
                            </td>                                                                     -->
                                    <td>
                                        {*   {if !$house->isSerialized($order.log_revisit) && empty($order.contract_application_date)
                                        && empty($order.money_payment) && empty($order.contract_signature_day)
                                        && empty($order.contract_payment_date_from) && empty($order.contract_payment_date_to)
                                        && empty($order.contract_handover_day)}
                                        -
                                        {else}*}
                                        {if $house->isSerialized($order.log_revisit)}
                                            <span style="color: #1166E7;">{end(unserialize($order.log_revisit))}</span>
                                        {else}
                                            -
                                        {/if}
                                    </td>       
                                    <td><span style="color: #D33F2A;">{($order.contract_application_date)?date('Y/m/d',$order.contract_application_date):'-'}</span></td>

                                    <td><span style="color: #FFB301;">{($order.money_payment)?$order.money_payment:'-'}</span></td>

                                    <td><span style="color: #009C58;">{($order.contract_signature_day)?date('Y/m/d H:i',$order.contract_signature_day):'-'}</span></td>

                                    <td><span style="color: #76009D;">{($order.contract_payment_date_from)?date('Y/m/d',$order.contract_payment_date_from):'-'}</span></td>

                                    <td><span style="color: #628DB6;">{($order.contract_payment_date_to)?date('Y/m/d',$order.contract_payment_date_to):'-'}</span></td>

                                    <td><span style="color: #9D000A;">{($order.contract_handover_day)?date('Y/m/d H:i',$order.contract_handover_day):'-'}</span></td>
                                        {*   {/if}*}

                                    <td style="width:15%">
                                        {if $order.user_id eq 0}
                                            <a href="edit_order.php?url={$link|base64_encode}" id="registry" style="margin-right: 10px;">登録</a>
                                        
                                         {else if (($order.user_id eq $user_id) and ($order.user_id ne 0))} 
                                            <a href="edit_order.php?url={$link|base64_encode}" style="margin-right: 10px;">編集</a>
                                        {/if}
                                        {if ($order.user_id eq $user_id) or ($user->user_info.user_authorities lte 2)}
                                            <a href="javascript:void" onclick="deleteItem({$order.id},{$order.order_status})" style="margin-right: 10px;">{if $order.order_status eq 1}削除{else}回復{/if}
                                            </a>
                                        {/if}
                                        <a href="order_detail.php?url={$add|base64_encode}">詳細</a>
                                    </td>
                                </tr>
                                {/foreach}
                                </tbody> 
                            </table>
                        </div>
                    </form>   
                    <center>
                        ページ:
                        {for $i=1 to $totalPage }
                            {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_order.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
                        {/for}
                    </center>
                </div>
            </center>
            {literal}
                <script type="text/javascript">
                    function deleteItem(id, order_status) {
                        if (confirm("確かですか?")) {
                            $.post("include/function_ajax.php", {order_id: id, order_status: order_status, action: 'deleteOrder'},
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
                        } else {
                            return false;
                        }

                    });
                </script>

            {/literal}

            {include file="footer.tpl"}