{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">部屋管理</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_room.php" method="post">
                <table style="">
                    <tr>
                        <td style='font-size: 1.4em;font-weight: bold;'>検索</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="検索には号室、物件名、管理会社を入力します。" style="height:26px; width: 270px;"/>
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
                        <th>号室</th>
                        <th>物件名</th> 
                        <th>管理会社</th>   
                        <th>間取り</th>
                        <th>現況</th>
                        <th>Room Rent</th>
                        <th>面積</th>                        
                        <th>敷金・保証金</th>                                                                                                                                                         
                        <th>活動</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$rooms key=k item=room}
                        {assign var="link" value="edit&{$room.id}&{$room.broker_id}&{$room.house_id}"}
                        {assign var="add" value="assign&{$room.id}"}
                        <tr>
                            <td>{$k+1}</td>                                
                            <td>{$room.room_number}</td>
                            <td>{$room.house_name}</td>
                            <td>{$room.broker_company_name}</td>
                            <td>{$room.room_type}</td>
                            <td>{if $room.room_status eq 1}賃貸中 {elseif $room.room_status eq 2}未完成{else} 空家 {/if}</td>                           
                            <td>{$room.room_rent}</td>                           
                            <td>{$room.room_size}</td>
                            <td>{$room.room_deposit}</td>                           
                            <td style="width:9%"><a href="edit_room.php?url={$link|base64_encode}">編集</a><a href="javascript:void" onclick="deleteItem({$room.id},{$room.broker_id},{$room.house_id})" style="margin: 0% 10% 0% 10%;">削除</a><a href="room_detail.php?url={$add|base64_encode}">詳細</a></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <center>
            ページ:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_room.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>
{literal}
    <script type="text/javascript">
        function deleteItem(id, broker_id, house_id) {
            if (confirm("確かですか?")) {
                $.post("include/function_ajax.php", {id: id, broker_id: broker_id, house_id: house_id, action: 'deleteRoom'},
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