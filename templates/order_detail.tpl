<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/report.css" />
{include file="header_global.tpl"}
<div id="site_content" class="report-content">
    <div class="report-title">
        <h3>Order Detail</h3>
    </div>
    {if $order}
        <div class="house-title">
            <span>Order Information</span>
        </div>
        <ul class="house-info">
            <li>
                <strong>Name : </strong>
                <span>{$order.order_name}</span>
            </li>
            <li>
                <strong>Rent Cost : </strong>
                <span>{$order.order_rent_cost}</span>
            </li>
            <li>
                <strong>Status : </strong>
                <span>{$order.order_status}</span>
            </li>
            <li>
                <strong>Comment : </strong>
                <span>{$order.order_comment}</span>
            </li>
            <li>
                <strong>Creation Date : </strong>
                <span>{$order.order_day_create}</span>
            </li>
            <li>
                <strong>Update Date : </strong>
                <span>{$order.order_day_create}</span>
            </li>
        </ul>
        {if $room}
            <div class="house-title">
                <span>Room Information</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>Number : </strong>
                    <span>{$room.room_number}</span>
                </li>
                <li>
                    <strong>Type : </strong>
                    <span>{$room.room_type}</span>
                </li>
                <li>
                    <strong>Size : </strong>
                    <span>{$room.room_size}</span>
                </li>
                <li>
                    <strong>Rent : </strong>
                    <span>{$room.room_rent}</span>
                </li>
                <li>
                    <strong>Key Money : </strong>
                    <span>{$room.room_key_money}</span>
                </li>
                <li>
                    <strong>Administrative Expense : </strong>
                    <span>{$room.room_administrative_expense}</span>
                </li>
                <li>
                    <strong>Deposit : </strong>
                    <span>{$room.room_deposit}</span>
                </li>
                <li>
                    <strong>Discount : </strong>
                    <span>{$room.room_discount}</span>
                </li>
            </ul>
        {/if}
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
        {/if}
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
        {if $client}
            <div class="house-title">
                <span>Client Information</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>Name : </strong>
                    <span>{$client.client_name}</span>
                </li>
                <li>
                    <strong>Address : </strong>
                    <span>{$client.client_address}</span>
                </li>
                <li>
                    <strong>Phone : </strong>
                    <span>{$client.client_phone}</span>
                </li>
                <li>
                    <strong>Fax : </strong>
                    <span>{$client.client_fax}</span>
                </li>
                <li>
                    <strong>Email : </strong>
                    <span>{$client.client_email}</span>
                </li>
                <li>
                    <strong>Birthday : </strong>
                    <span>{$client.client_birthday}</span>
                </li>
                <li>
                    <strong>Gender : </strong>
                    <span>{$client.client_gender}</span>
                </li>
                <li>
                    <strong>Income : </strong>
                    <span>{$client.client_income}</span>
                </li>
                <li>
                    <strong>Occupation : </strong>
                    <span>{$client.client_occupation}</span>
                </li>
                <li>
                    <strong>Company : </strong>
                    <span>{$client.client_company}</span>
                </li>
            </ul>
        {/if}
        {if $client}
            <div class="house-title">
                <span>Broker Information</span>
            </div>
            <ul class="house-info">
                <li>
                    <span>Name : </span>
                    <span>{$broker.broker_company_name} </span>
                </li>
                <li>
                    <span>Address : </span>
                    <span>{$broker.broker_company_address} </span>
                </li>
                <li>
                    <span>Phone : </span>
                    <span>{$broker.broker_company_phone} </span>
                </li>
                <li>
                    <span>Email : </span>
                    <span>{$broker.broker_company_email} </span>
                </li>
                <li>
                    <span>Fax : </span>
                    <span>{$broker.broker_company_fax} </span>
                </li>
                <li>
                    <span>Undertake : </span>
                    <span>{$broker.broker_company_undertake} </span>
                </li>
            </ul>
        {/if}
        {if $history}
            <div class="house-title">
                <span>History Information</span>
            </div>
            <ul class="house-info">
                <li>
                    <span>Time Call : </span>
                    <span>{$history.log_time_call} </span>
                </li>
                <li>
                    <span>Time Arrive Company : </span>
                    <span>{$history.log_time_arrive_company} </span>
                </li>
                <li>
                    <span>Comment : </span>
                    <span>{$history.log_comment} </span>
                </li>
                <li>
                    <span>Date Appointment From : </span>
                    <span>{$history.log_date_appointment_from} </span>
                </li>
                <li>
                    <span>Status Appointment : </span>
                    <span>{$history.log_status_appointment} </span>
                </li>
                <li>
                    <span>Shop Sign : </span>
                    <span>{$history.log_shop_sign} </span>
                </li>
                <li>
                    <span>Local Sign : </span>
                    <span>{$history.log_local_sign} </span>
                </li>
                <li>
                    <span>Introduction : </span>
                    <span>{$history.log_introduction} </span>
                </li>
                <li>
                    <span>Tel : </span>
                    <span>{$history.log_tel} </span>
                </li>
                <li>
                    <span>Mail : </span>
                    <span>{$history.log_mail} </span>
                </li>
                <li>
                    <span>Flyer : </span>
                    <span>{$history.log_flyer} </span>
                </li>
                <li>
                    <span>Line : </span>
                    <span>{$history.log_line} </span>
                </li>
                <li>
                    <span>Contact Head Office : </span>
                    <span>{$history.log_contact_head_office} </span>
                </li>
                <li>
                    <span>Tel Status : </span>
                    <span>{$history.log_tel_status} </span>
                </li>
                <li>
                    <span>Mail Status : </span>
                    <span>{$history.log_mail_status} </span>
                </li>
                <li>
                    <span>Revisit : </span>
                    <span>{$history.log_revisit} </span>
                </li>
            </ul>
        {/if}
        
        
    {else}

    {/if}
</div>

{include file='footer.tpl'}