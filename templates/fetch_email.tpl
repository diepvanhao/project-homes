{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">Order Messages</div>
<center>
    <div style="width: 100%;">
        <div>
           <!-- <form action="fetch_email.php" method="post">
                <table style="">
                    <tr>
                        <td  style='font-size: 13.33px;'>検索</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="検索に店舗名を入力してください。" style="height:26px; width: 190px;"/>
                            <span>
                                <input type='submit' class='btn-search' value='送信' id="submit" name="submit"/>&nbsp;                     
                            </span>
                        </td>
                    </tr>

                </table>
            </form>  --> 
            <form action="fetch_email.php" method="post" style=''>
                <input type='submit' class='btn-search' value='Fetch New' id="get_new" name="get_new"/>&nbsp;  
            </form>                
        </div>
        <div class="error">{if $create eq "0"}Create fail !!! {/if}</div>
        <div>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>N0</th>
                        <th>House Type</th>
                        <th>House Name</th>
                        <th>House Address</th>
                        <th>Rent Cost</th>
                        <th>名称</th>
                        <th>フリガナ</th>
                        <th>Client Address</th>
                        <th>Client Email</th>
                        <th>Client Phone</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$messages key=k item=message}

                        <tr>
                            <td>{$k+1}</td>
                            <td>{$message.house_type}</td>
                            <td>{$message.house_name}</td>
                            <td>{$message.house_address}</td>
                            <td>{$message.rent_cost}</td>
                            <td>{$message.client_name}</td>
                            <td>{$message.client_read_way}</td>
                            <td>{$message.client_address}</td>
                            <td>{$message.client_email}</td>
                            <td>{$message.client_phone}</td>
                            <td>{$message.source_name}</td>
                            <td>{if $message.status eq '1'}Created{else}New{/if}</td>
                            <td>{if $message.status eq '0'}
                                <form action='fetch_email.php' method="post">
                                    <input type='submit' class='btn-search' value='Create Order' id="create_new" name="create_new"/>
                                    <input type='hidden' class='btn-search' value='{$message.id}' id="message_id" name="message_id"/>
                                </form>{/if}
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <!-- <center>
             ページ:
        {for $i=1 to $totalPage }
            {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_agent.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
        {/for}
    </center>-->
    </div>
</center>
{literal}
    <script type="text/javascript">
        function deleteItem(id, agent_lock) {
            if (confirm("確かですか?")) {
                $.post("include/function_ajax.php", {agent_id: id, agent_lock: agent_lock, action: 'deleteAgent'},
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