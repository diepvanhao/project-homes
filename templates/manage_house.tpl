{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">Manage House</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_house.php" method="post">
                <table style="width:32%">
                    <tr>
                        <td style='font-size: 1.4em;font-weight: bold;'>Search</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="Enter house name to search" style="height:26px; width: 351px;"/>
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
                        <th>Id</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Size</th>
                        <th>Area</th>
                        <th>Price</th>
                        <th>Build Time</th>
                        <th>House Type</th>
                        <th>Room Type</th>
                        <th>Structure</th>
                        <th>Description</th>
                        <th>Administrative Expense</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$houses key=k item=house}
                        {assign var="link" value="edit&{$house.id}"}
                        {assign var="add" value="assign&{$house.id}"}
                        <tr>
                            <td>{$house.id}</td>
                            <td>{$house.house_name}</td>
                            <td>{$house.house_address}</td>
                            <td>{$house.house_size}</td>
                            <td>{$house.house_area}</td>
                            <td>{$house.house_original_price}</td>
                            <td>{$house.house_build_time}</td>
                            <td>{$house.house_type}</td>
                            <td>{$house.house_room_type}</td>
                            <td>{$house.house_structure}</td>
                            <td>{$house.house_description}</td>
                            <td>{$house.house_administrative_expense}</td>
                            <td>{$house.house_discount}</td>
                            <td>{$house.house_status}</td>
                            <td style="width:9%"><a href="edit_house.php?url={$link|base64_encode}">Edit</a><a href="javascript:void" onclick="deleteItem({$house.id})" style="margin: 0% 10% 0% 10%;">Delete</a><a href="house_detail.php?url={$add|base64_encode}">Detail</a></td>
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
                 $.post("include/function_ajax.php", {house_id:id, action: 'deleteHouse'},
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