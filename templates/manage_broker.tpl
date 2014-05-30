{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">Manage Broker Company</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_broker.php" method="post">
                <table style="">
                    <tr>
                        <td style='font-size: 1.4em;font-weight: bold;'>Search</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="Type name or phone to search" title="Enter broker company name or phone to search"style="height:26px; width: 190px;"/>
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
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Fax</th>
                        <th>Person In Charge</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$brokers key=k item=broker}
                        {assign var="link" value="edit&{$broker.id}"}
                        {assign var="add" value="assign&{$broker.id}"}
                        <tr>
                            <td>{$broker.id}</td>
                            <td>{$broker.broker_company_name}</td>
                            <td>{$broker.broker_company_address}</td>
                            <td>{$broker.broker_company_phone}</td>
                            <td>{$broker.broker_company_email}</td>
                            <td>{$broker.broker_company_fax}</td>
                            <td>{$broker.broker_company_undertake}</td>
                            <td><a href="edit_broker.php?url={$link|base64_encode}">Edit</a><a href="#" onclick="deleteItem({$broker.id})" style="margin: 0% 10% 0% 10%;">Delete</a><a href="add_house_broker.php?url={$add|base64_encode}">Add Room</a></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <center>
            Page:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_broker.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>
{literal}
    <script type="text/javascript">
        function deleteItem(id) {
            if (confirm("Are you sure?")) {
                 $.post("include/function_ajax.php", {broker_id:id, action: 'deleteBroker'},
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