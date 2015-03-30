{include file='header.tpl'}
<div>
    <h1>Messages</h1>
    {if count($messages)}
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>N0</th>
                    <th>物件種別</th>
                    <th>名称</th>
                    <th>番地</th>
                    <th>賃料</th>
                    <th>名称</th>
                    {*<th>フリガナ</th>*}
                    {*<th>Client Address</th>*}
                   {* <th>Client Email</th>*}
                    <th>クライアントの電話</th>
                    <th>媒体</th>
                    <th>ステータス</th>
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
                    <td>{if $message.status eq '1'}作成{else}新しい{/if}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
    <span>No message</span>
    {/if}
</div>
{include file='footer.tpl'}