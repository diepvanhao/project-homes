{include file="header_global.tpl"}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">物件管理</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_house.php" method="post">
                <table style="">
                    <tr>
                        <td  style='font-size: 13.33px;'>検索</td>
                        <td class="form2"><input type="text" id="search" name="search" value="{$search}" placeholder="検索に名称及び住所を入力してください。" style="height:33px; width: 230px;"/>
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
                        <th>住所</th>                       
                        <th>エリア</th>                        
                        <th>築年月</th>
                        <th>物件種別</th>                        
                        <th>建物構造</th>
                        <th>備考</th>                                                                  
                        <th>活動</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$houses key=k item=house}
                        {assign var="link" value="edit&{$house.id}"}
                        {assign var="add" value="assign&{$house.id}"}
                        <tr>
                            <td>{$k+1}</td>
                            <td>{$house.house_name}</td>
                            <td>{$house.house_address}</td>                            
                            <td>{$house.house_area}</td>                           
                            <td>{$house.house_build_time}</td>
                            <td>{$house.house_type}</td>                           
                            <td>{$house.house_structure}</td>
                            <td>{$house.house_description}</td>                                                                             
                            <td style="width:9%"><a href="edit_house.php?url={$link|base64_encode}">編集</a><a href="javascript:void" onclick="deleteItem({$house.id},{$house.house_lock})" style="margin: 0% 10% 0% 10%;">{if $house.house_lock eq 0}削除{else}回復{/if}</a><a href="house_detail.php?url={$add|base64_encode}">詳細</a></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <center>
            ページ:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_house.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>
{literal}
    <script type="text/javascript">
        function deleteItem(id, lock) {
            if (confirm("確かですか?")) {
                $.post("include/function_ajax.php", {house_id: id, house_lock: lock, action: 'deleteHouse'},
                function(result) {
                    if (result) {
                        window.location.reload(true);
                    } else {
                        alert('削除が失敗しました。 :(');
                    }
                });
            }
        }

    </script>

{/literal}

{include file="footer.tpl"}