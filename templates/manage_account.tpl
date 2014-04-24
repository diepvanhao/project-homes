{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">Manage Account</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_account.php" method="post">
                <table style="width:32%">
                    <tr>
                        <td style='font-size: 1.4em;font-weight: bold;'>Search</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="Enter  name to search" style="height:26px; width: 351px;"/>
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
                        <th>Sex</th>
                        <th>Birthday</th>
                        <th>Username</th>
                        <th>Position</th>
                        <th>Target</th>
                        <th>Level</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$users key=k item=user}
                        {assign var="link" value="edit&{$user.id}"}
                        {assign var="add" value="assign&{$user.id}"}
                        <tr>
                            <td>{$user.id}</td>
                            <td>{$user.user_fname} {$user.user_lname}</td>
                            <td>{$user.user_address|truncate:50}</td>
                            <td>{$user.user_phone}</td>
                            <td>{$user.user_email}</td>
                            <td>{$user.user_gender}</td>
                            <td>{$user.user_birthday}</td>
                            <td>{$user.user_username}</td>
                            <td>{$user.user_position}</td>
                            <td>{$user.user_target}</td>
                            <td>{if $user.user_authorities eq '1'}Admin{elseif $user.user_authorities eq '2'}Super Manager{elseif $user.user_authorities eq '3'}Manager{else}Staff{/if}</td>
                            <td>{if $user.user_locked ne '1'}Active{else}Inactive{/if}</td>
                            <td>{if $user.user_authorities ne '1'}<a href="edit_account.php?url={$link|base64_encode}">Edit</a><a href="javascript:void" onclick="deleteItem({$user.id})" style="margin: 0% 10% 0% 10%; ">{if $user.user_locked eq 1}Unlock{else}Lock{/if}</a>{/if}<a href="account_detail.php?url={$add|base64_encode}">Detail</a></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <center>
            Page:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_account.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>
{literal}
    <script type="text/javascript">
        function deleteItem(id) {
            if (confirm("Are you sure?")) {
                 $.post("include/function_ajax.php", {user_id:id, action: 'deleteAccount'},
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