{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">管理会社</div>
<center>
    <div style="width: 100%;">
         <div>
            <form action="contact.php" method="post">
                <table style="">
                    <tr>
                        <td  style='font-size: 13.33px;'>検索</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="検索には号室、物件名、管理会社を入力します。" style="height:26px; width: 270px;"/>
                            <span>
                                <input type='submit' class='btn-search' value='送信' id="submit" name="submit"/>&nbsp;                     
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
                        <th>番号</th>
                        <th>名称</th>                        
                        <th>ログイン</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$brokers key=k item=broker}
                        {if $minkey > $k || $maxkey <= $k}
                            {continue}
                        {/if}
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
        <center>
            ページ:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="contact.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>


{include file="footer.tpl"}