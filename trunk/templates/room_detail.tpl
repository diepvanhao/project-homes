<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/report.css" />
{include file="header_global.tpl"}
<div id="site_content" class="report-content">
    <div class="report-title">
        <h3>部屋の詳細</h3>
    </div>
    {if $room}
        <div class="house-title">
            <span>部屋情報</span>
        </div>
        <ul class="house-info">
            <li>
                <strong>号室 : </strong>
                <span>{$room.room_number}</span>
            </li>
            <li>
                <strong>間取り : </strong>
                <span>{$room.room_type}</span>
            </li>
            <li>
                <strong>面積 : </strong>
                <span>{$room.room_size}</span>
            </li>
            <li>
                <strong>賃料 : </strong>
                <span>{$room.room_rent}</span>
            </li>
            <li>
                <strong>礼金 : </strong>
                <span>{$room.room_key_money}</span>
            </li>
            <li>
                <strong>管理費 : </strong>
                <span>{$room.room_administrative_expense}</span>
            </li>
            <li>
                <strong>敷金・保証金 : </strong>
                <span>{$room.room_deposit}</span>
            </li>
            <li>
                <strong>割引 : </strong>
                <span>{$room.room_discount}</span>
            </li>
        </ul>
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
        {/if}
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
        {if count($brokers)}
            <div class="house-title">
                <span>管理会社</span>
            </div>
            <ul class="house-info detail-list-items">
                {foreach from=$brokers key=k item=broker}
                <li>
                    <ul>
                        <li>
                            <span>名称 : </span>
                            <span>{$broker.broker_company_name} </span>
                        </li>
                        <li>
                            <span>住所 : </span>
                            <span>{$broker.broker_company_address} </span>
                        </li>
                        <li>
                            <span>電話番号 : </span>
                            <span>{$broker.broker_company_phone} </span>
                        </li>
                        <li>
                            <span>Eメール : </span>
                            <span>{$broker.broker_company_email} </span>
                        </li>
                        <li>
                            <span>ファックス : </span>
                            <span>{$broker.broker_company_fax} </span>
                        </li>
                        <li>
                            <span>引き受ける : </span>
                            <span>{$broker.broker_company_undertake} </span>
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