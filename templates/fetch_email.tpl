{include file="header_global.tpl"}
{literal}
    <script type="text/javascript">
        $(document).ready(function(){
            $('#new_message').click(function(){
                showloadgif();
                $('#get_new_message').submit();
            });
        });
    </script>    
{/literal}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">メッセージ</div>
<center>
    <div style="width: 100%;">
        <div >
            <form action="fetch_email.php" method="post">
                <table >
                    <tr>
                        <td  style='font-size: 13.33px;'>検索</td>
                        <td class="form2"><input title="You can enter house type, house name, house address, rent cost, client name, client email, client phone and source name for search" type="text" id="search" name="search" value="{$search}" placeholder="type search" style="height:26px; width: 190px;"/>
                            <span>
                                <input type='submit' class='btn-search' value='送信' id="submit" name="submit"/>&nbsp;                     
                            </span>                            
                                <input type='button' class='btn-search' value='Get Messages' id="new_message" name="new_message" title="Get new messages from email"/>&nbsp;                             
                        </td>
                    </tr>

                </table>
            </form>
            <form action="fetch_email.php" method="post" id="get_new_message">
                    <input type='hidden' class='btn-search' value='Fetch New' id="get_new" name="get_new" title="Get new messages from email"/>&nbsp;                                         
            </form>
        </div>
        
        <div class="error">{if $create eq "0"}Create fail !!! {/if}</div>
        <div>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>N0</th>
                        <th>物件種別</th>
                        <th>名称</th>
                        <th>番地</th>
                        <th>賃料</th>
                        <th>名称</th>
                        <th>フリガナ</th>
                        <th>クライアントアドレス</th>
                        <th>クライアントのメール</th>
                        <th>クライアントの電話</th>
                        <th>媒体</th>
                        <th>ステータス</th>
                        <th>活動</th>
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
                            <td>{if $message.status eq '1'}作成{else}新しい{/if}</td>
                            <td>{if $message.status eq '0'}
                                <form action='fetch_email.php' method="post">
                                    <input type='submit' class='btn-search' value='注文を作成' id="create_new" name="create_new"/>
                                    <input type='hidden' class='btn-search' value='{$message.id}' id="message_id" name="message_id"/>
                                </form>{/if}
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <center>
            ページ:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="fetch_email.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>

<div id="loadgif">Loading...</div>
{include file="footer.tpl"}