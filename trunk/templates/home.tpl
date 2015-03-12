{include file='header.tpl'}
<div>
    <h1>Messages</h1>
    {if count($messages)}
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
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
                </tr>
            </thead>
            <tbody>
                {foreach from=$messages item=message}
                <tr>
                    <td>{$message.id}</td>
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
                </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
    <span>No message</span>
    {/if}
</div>
{include file='footer.tpl'}