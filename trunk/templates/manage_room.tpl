{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">Manage Room</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_room.php" method="post">
                <table style="">
                    <tr>
                        {*<td  style='font-size: 13.33px;'>検索</td>*}
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="" style="height:26px; width: 270px;"/>
                            <span>
                                <input type='submit' class='btn-search' value='Search' id="submit" name="submit"/>&nbsp;                     
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
                        <th>N0</th>
                        <th>Room number</th>
                        <th>Apartment name</th> 
                        <th>Broker company name</th>   
                        <th>Type</th>
                        <th>Status</th>
                        <th>Rent</th>
                        <th>Size</th>                        
                        <th>Deposite</th>                                                                                                                                                         
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$rooms key=k item=room}
                        {assign var="link" value="edit&{$room.id}&{$room.broker_id}&{$room.house_id}"}
                        {assign var="add" value="assign&{$room.room_detail_id}"}
                        <tr>
                            <td>{$k+1}</td>                                
                            <td>{$room.room_number}</td>
                            <td>{$room.house_name}</td>
                            <td>{$room.broker_company_name}</td>
                            <td>{$room.room_type_number}{$room.room_type}</td>
                            <td>{if $room.room_status eq 1}Rent {elseif $room.room_status eq 2}Incomplete{else} Empty {/if}</td>                           
                            <td>{$room.room_rent}</td>                           
                            <td>{$room.room_size}</td>
                            <td>{$room.room_deposit}</td>                           
                            <td style="width:9%"><a href="edit_room.php?url={$link|base64_encode}">Edit</a><a href="javascript:void" onclick="deleteItem({$room.room_id},{$room.broker_id},{$room.house_id},{$room.room_lock})" style="margin: 0% 10% 0% 10%;">{if $room.room_lock eq 0}Lock{else}Unlock{/if}</a><a href="room_detail.php?url={$add|base64_encode}">Detail</a></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <center>
            Page:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_room.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>
{literal}
    <script type="text/javascript">
        function deleteItem(id, broker_id, house_id,room_lock) {
            if (confirm("Are you sure?")) {
                $.post("include/function_ajax.php", {id: id, broker_id: broker_id, house_id: house_id, room_lock: room_lock, action: 'deleteRoom'},
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