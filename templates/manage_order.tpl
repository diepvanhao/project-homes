{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">Manage Order</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_order.php" method="post">
                <table style="">
                    <tr>
                        <td style='font-size: 1.4em;font-weight: bold;'>Search</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="Enter house name to search" style="height:26px; "/>
                            <span>
                                <input type='submit' class='btn-search' value='Submit' id="submit" name="submit"/>&nbsp;                     
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
                        <th>No</th>
                        <th>Name</th>
                        <th>House</th>
                        <th>Room</th>
                        <th>Price</th>                                                
                        <th>Status</th>                       
                        <th>Date Create</th>
                        <th>Customer</th>
                        <th>Assign</th>                                                                      
                        <th>Action</th>
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
                            <td>{if $order.order_status eq 1}Processing{else} Canceled{/if}</td>
                            <td>{$order.order_day_create}</td>                           
                            <td>{$order.client_name}</td>
                            <td>{if $order.user_id ne 0}assigned {else} 
                                <select id="staff_id" name="staff_id" >                                                                       
                                    <option value="{$user->user_info.id}">{$user->user_info.user_fname} {$user->user_info.user_lname}</option>                                           
                                </select>
                                {/if}</td>                                                                     
                                <td style="width:15%">{if $order.user_id eq 0}<a href="edit_order.php?url={$link|base64_encode}" id="registry" style="margin-right: 10px;">Registry</a>{/if}{if (($order.user_id eq $user_id) or ($user->user_info.user_authorities lte 2)and ($order.user_id ne 0))}<a href="edit_order.php?url={$link|base64_encode}" style="margin-right: 10px;">Edit</a>{/if}{if ($order.user_id eq $user_id) or ($user->user_info.user_authorities lte 2)}<a href="javascript:void" onclick="deleteItem({$order.id})" style="margin-right: 10px;">Delete</a>{/if}<a href="order_detail.php?url={$add|base64_encode}">Detail</a></td>
                            </tr>
                            {/foreach}
                            </tbody> 
                        </table>
                    </div>
                    <center>
                        Page:
                        {for $i=1 to $totalPage }
                            {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_house.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
                        {/for}
                    </center>
                </div>
            </center>
            {literal}
                <script type="text/javascript">
                    function deleteItem(id) {
                        if (confirm("Are you sure?")) {
                            $.post("include/function_ajax.php", {house_id: id, action: 'deleteHouse'},
                            function(result) {
                                if (result)
                                    window.location.reload(true);
                                else
                                    alert('Delete fail :(');
                            });
                        }
                    }
                    $('#registry').click(function() {
                        if (confirm("Are you sure resgistry this order ?")) {
                            return true;
                        }else{
                            return false;
                        }

                    });
                </script>

            {/literal}

            {include file="footer.tpl"}