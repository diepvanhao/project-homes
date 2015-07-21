{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">Manage Agent</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_agent.php" method="post">
                <table style="">
                    <tr>
                        {*<td  style='font-size: 13.33px;'>Search</td>*}
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="Type name to search" style="height:26px; width: 190px;"/>
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
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Fax</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$agents key=k item=agent}
                        {assign var="link" value="edit&{$agent.id}"}
                        {assign var="add" value="assign&{$agent.id}"}
                        <tr>
                            <td>{$k+1}</td>
                            <td>{$agent.agent_name}</td>
                            <td>{$agent.agent_address}</td>
                            <td>{$agent.agent_phone}</td>
                            <td>{$agent.agent_email}</td>
                            <td>{$agent.agent_fax}</td>
                            <td>
                                {if $canEdit}
                                    <a href="edit_agent.php?url={$link|base64_encode}">Edit</a>
                                    <a href="#" onclick="deleteItem({$agent.id},{$agent.agent_lock})" style="margin: 0% 10% 0% 10%;">{if $agent.agent_lock eq 0}Lock{else}Unlock{/if}</a>
                                    <a href="add_staff_agent.php?url={$add|base64_encode}">Asign staff to agent</a>
                                {/if}
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <center>
            Page:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_agent.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>
{literal}
    <script type="text/javascript">
        function deleteItem(id,agent_lock) {
            if (confirm("確かですか?")) {
                 $.post("include/function_ajax.php", {agent_id:id,agent_lock: agent_lock, action: 'deleteAgent'},
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