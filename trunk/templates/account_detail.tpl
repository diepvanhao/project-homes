<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/report.css" />
{include file="header_global.tpl"}
<div id="site_content" class="report-content">
    <div class="report-title">
        <h3>アカウントの詳細</h3>
    </div>
    {if $account}
        <div class="house-title">
            <span>アカウント</span>
        </div>
        <ul class="house-info">
            <li>
                <strong>ユーザー : </strong>
                <span>{$account.user_username}</span>
            </li>
            <li>
                <strong>名前 : </strong>
                <span>{$account.user_fname}</span>
            </li>
            <li>
                <strong>ラストネーム : </strong>
                <span>{$account.user_lname}</span>
            </li>
            <li>
                <strong>Eメール : </strong>
                <span>{$account.user_email}</span>
            </li>
            <li>
                <strong>電話番号 : </strong>
                <span>{$account.user_phone}</span>
            </li>
            <li>
                <strong>性別 : </strong>
                <span>{$account.user_gender}</span>
            </li>
            <li>
                <strong>生年月日 : </strong>
                <span>{$account.user_birthday}</span>
            </li>
            <li>
                <strong>ポジション : </strong>
                <span>{$account.user_position}</span>
            </li>
        </ul>
        {if $targets}
            <div class="house-title">
                <span>ターゲット</span>
            </div>
            <ul class="house-info">
                {foreach from=$targets key=k item=target}
                    <li>
                        <strong>{$target.date}</strong>
                        <span>{money_format('%(#10n',$target.target)} 円</span>
                    </li>
                {/foreach}
            </ul>
        {/if}
        {if $agent}
            <div class="house-title">
                <span>店舗</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>名称 : </strong>
                    <span>{$agent.agent_name}</span>
                </li>
                <li>
                    <strong>住所 : </strong>
                    <span>{$agent.agent_address}</span>
                </li>
                <li>
                    <strong>電話番号 : </strong>
                    <span>{$agent.agent_phone}</span>
                </li>
                <li>
                    <strong>ファックス : </strong>
                    <span>{$agent.agent_email}</span>
                </li>
                <li>
                    <strong>Eメール : </strong>
                    <span>{$agent.agent_email}</span>
                </li>
            </ul>
        {/if}
    {else}

    {/if}
</div>

{include file='footer.tpl'}