<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/report.css" />
{include file="header_global.tpl"}
<div id="site_content" class="report-content">
    <div class="report-title">
        <h3>ハウスの詳細</h3>
    </div>
    {if $house}
        <div class="house-title">
            <span>物件情報</span>
        </div>
        <ul class="house-info">
            <li>
                <strong>名称 : </strong>
                <span>{$house.house_name}</span>
            </li>
            <li>
                <strong>住所 : </strong>
                <span>{$address}</span>
            </li>
            <li>
                <strong>アリアー : </strong>
                <span>{$house.house_area}</span>
            </li>
            <li>
                <strong>間取り : </strong>
                <span>{$house.house_type}</span>
            </li>
            <li>
                <strong>備考 : </strong>
                <span>{$house.house_description}</span>
            </li>
            <li>
                <strong>建物構造 : </strong>
                <span>{$house.house_structure}</span>
            </li>
            <li>
                <strong>築年月 : </strong>
                <span>{$house.house_build_time}</span>
            </li>
        </ul>
        {if $house.house_owner_id}
            <div class="house-title">
                <span>オーナー名</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>名称 : </strong>
                    <span>{$house.house_owner_name}</span>
                </li>
                <li>
                    <strong>住所 : </strong>
                    <span>{$house.house_owner_address}</span>
                </li>
                <li>
                    <strong>電話番号 : </strong>
                    <span>{$house.house_owner_phone}</span>
                </li>
                <li>
                    <strong>ファックス : </strong>
                    <span>{$house.house_owner_fax}</span>
                </li>
                <li>
                    <strong>Eメール : </strong>
                    <span>{$house.house_owner_email}</span>
                </li>
            </ul>
        {/if}
        {if count($rooms)}
            <div class="house-title">
                <span>部屋情報</span>
            </div>
            <ul class="house-info detail-list-items">
                {foreach from=$rooms key=k item=room}
                <li>
                    <ul>
                        <li>
                            <span>号室 : </span>
                            <span>{$room.room_number} </span>
                        </li>
                        <li>
                            <span>間取り : </span>
                            <span>{$room.room_type} </span>
                        </li>
                        <li>
                            <span>面積 : </span>
                            <span>{$room.room_size} </span>
                        </li>
                        <li>
                            <span>現況 : </span>
                            <span>{$room.room_status} </span>
                        </li>
                        <li>
                            <span>賃料 : </span>
                            <span>{$room.room_rent} </span>
                        </li>
                        <li>
                            <span>礼金 : </span>
                            <span>{$room.room_key_money} </span>
                        </li>
                        <li>
                            <span>管理費 : </span>
                            <span>{$room.room_administrative_expense} </span>
                        </li>
                        <li>
                            <span>敷金・保証金 : </span>
                            <span>{$room.room_deposit} </span>
                        </li>
                        <li>
                            <span>割引 : </span>
                            <span>{$room.room_discount} </span>
                        </li>
                    </ul>
                </li>
                {/foreach}
            </ul>
        {/if}
    {else}

    {/if}
</div>

{include file='footer.tpl'}