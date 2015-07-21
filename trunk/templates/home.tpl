{include file='header.tpl'}
<div>
    <h1>Messages</h1>
    {if count($messages)}
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>N0</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Cost</th>
                    <th>Customer</th>
                    {*<th>フリガナ</th>*}
                    {*<th>Client Address</th>*}
                   {* <th>Client Email</th>*}
                    <th>Client Phone</th>
                    <th>Source</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$messages key=k item=message}
                <tr>
                    <td>{$k+1}</td>
                    <td>{$message.house_type}</td>
                    <td>{$message.house_name}</td>
                    <td>{$message.house_address|truncate:20}</td>
                    <td>{$message.rent_cost}</td>
                    <td>{$message.client_name}</td>
                    {*<td>{$message.client_read_way}</td>*}
                    {*<td>{$message.client_address|truncate:20}</td>*}
                    {*<td>{$message.client_email|truncate:15}</td>*}
                    <td>{$message.client_phone}</td>
                    <td>{$message.source_name}</td>
                    <td>{if $message.status eq '1'}created{else}new{/if}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
    <span>No message</span>
    {/if}
</div>
{include file='footer.tpl'}