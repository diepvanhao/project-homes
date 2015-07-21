<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/report.css" />
{include file="header_global.tpl"}
<div id="site_content" class="report-content">
    <div class="report-title">
        <h3>Account detail</h3>
    </div>
    {if $account}
        <div class="house-title">
            <span>アカウント</span>
        </div>
        <ul class="house-info">
            <li>
                <strong>Username : </strong>
                <span>{$account.user_username}</span>
            </li>
            <li>
                <strong>Last name : </strong>
                <span>{$account.user_lname}</span>
            </li>
            <li>
                <strong>First name : </strong>
                <span>{$account.user_fname}</span>
            </li>
            <li>
                <strong>Email : </strong>
                <span>{$account.user_email}</span>
            </li>
            <li>
                <strong>Phone : </strong>
                <span>{$account.user_phone}</span>
            </li>
            <li>
                <strong>Gender : </strong>
                <span>{$account.user_gender}</span>
            </li>
            <li>
                <strong>Birthday : </strong>
                <span>{$account.user_birthday}</span>
            </li>
            <li>
                <strong>Position : </strong>
                <span>{$account.user_position}</span>
            </li>
        </ul>
      {*  {if $targets}
            <div class="house-title">
                <span>目標</span>
            </div>
            <ul class="house-info">
                {foreach from=$targets key=k item=target}
                    <li>
                        <strong>{$target.date}</strong>
                        <span>{money_format('%(#10n',$target.target)} 円</span>
                    </li>
                {/foreach}
            </ul>
        {/if}*}
        {if $agent}
            <div class="house-title">
                <span>Agent</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>Name : </strong>
                    <span>{$agent.agent_name}</span>
                </li>
                <li>
                    <strong>Address : </strong>
                    <span>{$agent.agent_address}</span>
                </li>
                <li>
                    <strong>Phone : </strong>
                    <span>{$agent.agent_phone}</span>
                </li>
                
                <li>
                    <strong>Email : </strong>
                    <span>{$agent.agent_email}</span>
                </li>
            </ul>
        {/if}
    {else}

    {/if}
</div>

{include file='footer.tpl'}