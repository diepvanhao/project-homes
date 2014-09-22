{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">管理会社</div>
<center>
    <div style="width: 100%;">

        <div>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>番号</th>
                        <th>名称</th>                        
                        <th>ログイン</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$brokers key=k item=broker}

                        <tr>
                            <td>{$k+1}</td>
                            <td>{$broker.name}</td>                           
                            <td>
                                {if $broker.name ne ""}
                                    <form action="{$broker.action}" method="post" target="blank">
                                        <input type="hidden" name="{$broker.idlogname}" value="{$broker.username}" />
                                        <input type="hidden" name="{$broker.passlogname}" value="{$broker.password}" />
                                        {if $broker.inputhidden ne ""}
                                            {$broker.inputhidden}
                                        {/if}
                                        <input type="submit" class="btn" value="ログイン" {if $broker.submitname ne ""}name="{$broker.submitname}"{/if}/>	
                                    </form>

                                {/if}
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>

    </div>
</center>


{include file="footer.tpl"}