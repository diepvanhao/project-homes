<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/report.css" />
{include file="header_global.tpl"}
<div id="site_content" class="report-content">
    <div class="report-title">
        <h3>House Detail</h3>
    </div>
    {if $house}
        <div class="house-title">
            <span>House Information</span>
        </div>
        <ul class="house-info">
            <li>
                <strong>Name : </strong>
                <span>{$house.house_name}</span>
            </li>
            <li>
                <strong>Address : </strong>
                <span>{$house.house_address}</span>
            </li>
            <li>
                <strong>Area : </strong>
                <span>{$house.house_area}</span>
            </li>
            <li>
                <strong>Type : </strong>
                <span>{$house.house_type}</span>
            </li>
            <li>
                <strong>Description : </strong>
                <span>{$house.house_description}</span>
            </li>
            <li>
                <strong>Structure : </strong>
                <span>{$house.house_structure}</span>
            </li>
            <li>
                <strong>Build Time : </strong>
                <span>{$house.house_build_time}</span>
            </li>
        </ul>
        {if $house.house_owner_id}
            <div class="house-title">
                <span>House Owner Information</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>Name : </strong>
                    <span>{$house.house_owner_name}</span>
                </li>
                <li>
                    <strong>Address : </strong>
                    <span>{$house.house_owner_address}</span>
                </li>
                <li>
                    <strong>Phone : </strong>
                    <span>{$house.house_owner_phone}</span>
                </li>
                <li>
                    <strong>Fax : </strong>
                    <span>{$house.house_owner_fax}</span>
                </li>
                <li>
                    <strong>Email : </strong>
                    <span>{$house.house_owner_email}</span>
                </li>
            </ul>
        {/if}
        {if count($rooms)}
            <div class="house-title">
                <span>Rooms of House</span>
            </div>
            <ul class="house-info detail-list-items">
                {foreach from=$rooms key=k item=room}
                <li>
                    <ul>
                        <li>
                            <span>Number : </span>
                            <span>{$room.room_number} </span>
                        </li>
                        <li>
                            <span>Type : </span>
                            <span>{$room.room_type} </span>
                        </li>
                        <li>
                            <span>Size : </span>
                            <span>{$room.room_size} </span>
                        </li>
                        <li>
                            <span>Status : </span>
                            <span>{$room.room_status} </span>
                        </li>
                        <li>
                            <span>Rent : </span>
                            <span>{$room.room_rent} </span>
                        </li>
                        <li>
                            <span>Key Money : </span>
                            <span>{$room.room_key_money} </span>
                        </li>
                        <li>
                            <span>Administrative Expense : </span>
                            <span>{$room.room_administrative_expense} </span>
                        </li>
                        <li>
                            <span>Deposit : </span>
                            <span>{$room.room_deposit} </span>
                        </li>
                        <li>
                            <span>Discount : </span>
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