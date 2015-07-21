<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/report.css" />
{include file="header_global.tpl"}
<div id="site_content" class="report-content">
    <div class="report-title">
        <h3>Room detail</h3>
    </div>
    {if $room}
        <div class="house-title">
            <span>Room info</span>
        </div>
        <ul class="house-info">
            <li>
                <strong>Room number </strong>
                <span>{$room.room_number}</span>
            </li>
            <li>
                <strong>Type </strong>
                <span>{$room.room_type}</span>
            </li>
            <li>
                <strong>Size </strong>
                <span>{$room.room_size}</span>
            </li>
            <li>
                <strong>Rent </strong>
                <span>{$room.room_rent}</span>
            </li>
            <li>
                <strong>Key fee </strong>
                <span>{$room.room_key_money}</span>
            </li>
            <li>
                <strong>Administrative Expense </strong>
                <span>{$room.room_administrative_expense}</span>
            </li>
            <li>
                <strong>Deposit </strong>
                <span>{$room.room_deposit}</span>
            </li>
            <li>
                <strong>Discount </strong>
                <span>{$room.room_discount}</span>
            </li>
        </ul>
        {if $house}
            <div class="house-title">
                <span>Apartment info</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>Name </strong>
                    <span>{$house.house_name}</span>
                </li>
                <li>
                    <strong>Address </strong>
                    <span>{$address}</span>
                </li>
                <li>
                    <strong>Area </strong>
                    <span>{$house.house_area}</span>
                </li>
                <li>
                    <strong>Type </strong>
                    <span>{$house.house_type}</span>
                </li>
                <li>
                    <strong>Description </strong>
                    <span>{$house.house_description}</span>
                </li>
                <li>
                    <strong>Structure </strong>
                    <span>{$house.house_structure}</span>
                </li>
                <li>
                    <strong>Build time </strong>
                    <span>{$house.house_build_time}</span>
                </li>
            </ul>
        {/if}
        {if $house.house_owner_id}
            <div class="house-title">
                <span>Owner</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>Name </strong>
                    <span>{$house.house_owner_name}</span>
                </li>
                <li>
                    <strong>Address </strong>
                    <span>{$house.house_owner_address}</span>
                </li>
                <li>
                    <strong>Phone </strong>
                    <span>{$house.house_owner_phone}</span>
                </li>
                <li>
                    <strong>Fax </strong>
                    <span>{$house.house_owner_fax}</span>
                </li>
                <li>
                    <strong>Email </strong>
                    <span>{$house.house_owner_email}</span>
                </li>
            </ul>
        {/if}
        {if count($brokers)}
            <div class="house-title">
                <span>Broker company</span>
            </div>
            <ul class="house-info detail-list-items">
                {foreach from=$brokers key=k item=broker}
                <li>
                    <ul>
                        <li>
                            <span>Name </span>
                            <span>{$broker.broker_company_name} </span>
                        </li>
                        <li>
                            <span>Address </span>
                            <span>{$broker.broker_company_address} </span>
                        </li>
                        <li>
                            <span>Phone </span>
                            <span>{$broker.broker_company_phone} </span>
                        </li>
                        <li>
                            <span>Email </span>
                            <span>{$broker.broker_company_email} </span>
                        </li>
                        <li>
                            <span>Fax </span>
                            <span>{$broker.broker_company_fax} </span>
                        </li>
                        <li>
                            <span>Person in charge </span>
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