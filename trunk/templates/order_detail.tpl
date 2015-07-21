<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/report.css" />
{include file="header_global.tpl"}
<div id="site_content" class="report-content">
    <div class="report-title">
        <h3>Order detail</h3>
    </div>
    {if $order}
        <div class="house-title">
            <span>Order</span>
        </div>
        <ul class="house-info">
            <li>
                <strong>Order name: </strong>
                <span>{$order.order_name}</span>
            </li>
            <li>
                <strong>Rent : </strong>
                <span>{$order.order_rent_cost}</span>
            </li>
            <li>
                <strong>Status : </strong>
                <span>{$status}</span>
            </li>
            <li>
                <strong>Comment : </strong>
                <span>{$order.order_comment}</span>
            </li>
            <li>
                <strong>Registry date : </strong>
                <span>
                    {if !empty($history.order_day_create)}
                            {date('Y-m-d',$history.order_day_create)} 
                        {/if}
                </span>
            </li>
            {*<li>
                <strong>引越予定日 : </strong>
                <span>
                    {if !empty($history.order_day_create)}
                        {date('Y-m-d',$history.order_day_create)} 
                    {/if}
                </span>
            </li>*}
        </ul>
        {if $room}
            <div class="house-title">
                <span>Room information</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>Room number : </strong>
                    <span>{$room.room_number}</span>
                </li>
                <li>
                    <strong>Name : </strong>
                    <span>{$room.room_name}</span>
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
                    <strong>Key fee : </strong>
                    <span>{$room.room_key_money}</span>
                </li>
                <li>
                    <strong>Administrative expense : </strong>
                    <span>{$room.room_administrative_expense}</span>
                </li>
                <li>
                    <strong>Deposit : </strong>
                    <span>{$room.room_deposit}</span>
                </li>
                <li>
                    <strong>discount : </strong>
                    <span>{$room.room_discount}</span>
                </li>
            </ul>
        {/if}
        {if $house}
            <div class="house-title">
                <span>Apartment information</span>
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
                    <span>{$house_type}</span>
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
                    <strong>Build time : </strong>
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
                <span>Client information</span>
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
                    <span>
                        {if $client.client_income > 10000}
                            {round($client.client_income/10000)}万{($client.client_income%10000)?($client.client_income%10000):''}円
                        {elseif $client.client_income}
                            {round($client.client_income)}円
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>Occupation  : </strong>
                    <span>{$client.client_occupation}</span>
                </li>
                <li>
                    <strong>Company : </strong>
                    <span>{$client.client_company}</span>
                </li>
            </ul>
        {/if}
        {if $broker}
            <div class="house-title">
                <span>Management company</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>Name : </strong>
                    <span>{$broker.broker_company_name} </span>
                </li>
                <li>
                    <strong>Address : </strong>
                    <span>{$broker.broker_company_address} </span>
                </li>
                <li>
                    <strong>Phone : </strong>
                    <span>{$broker.broker_company_phone} </span>
                </li>
                <li>
                    <strong>Email : </strong>
                    <span>{$broker.broker_company_email} </span>
                </li>
                <li>
                    <strong>Fax : </strong>
                    <span>{$broker.broker_company_fax} </span>
                </li>
                <li>
                    <strong>Person in charge : </strong>
                    <span>{$broker.broker_company_undertake} </span>
                </li>
            </ul>
        {/if}
        {if $history}
            <div class="house-title">
                <span>History</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>Time call : </strong>
                    <span>
                        {if !empty($history.log_time_call)}
                            {date('Y-m-d',$history.log_time_call)} 
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>Arrive office : </strong>
                    <span>
                        {if !empty($history.log_time_arrive_company)}
                            {date('Y-m-d',$history.log_time_arrive_company)} 
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>Comment : </strong>
                    <span>{$history.log_comment} </span>
                </li>
                <li>
                    <strong>Date book room : </strong>
                    <span>
                        {if !empty($history.log_date_appointment_from)}
                            {date('Y-m-d',$history.log_date_appointment_from)} 
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>View in report : </strong>
                    <span>
                        {if !empty($history.log_status_appointment)}
                        Yes
                        {else}
                         No
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>Shop sign : </strong>
                    <span>
                        {if !empty($history.log_shop_sign)}
                        Yes
                        {else}
                         No
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>Local sign : </strong>
                    <span>
                        {if !empty($history.log_local_sign)}
                        Yes
                        {else}
                         No
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>Introduce : </strong>
                    <span>
                        {if !empty($history.log_introduction)}
                        Yes
                        {else}
                         No
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>Contact by tel : </strong>
                    <span>
                        {if !empty($history.log_tel)}
                        Yes
                        {else}
                         No
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>Contact by email : </strong>
                    <span>
                        {if !empty($history.log_mail)}
                        Yes
                        {else}
                         No
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>Leaflets: </strong>
                    <span>
                        {if !empty($history.log_flyer)}
                        Yes
                        {else}
                         No
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>ＬＩＮＥ: </strong>
                    <span>
                        {if !empty($history.log_line)}
                        Yes
                        {else}
                         No
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>Contact by arrive office : </strong>
                    <span>
                        {if !empty($history.log_contact_head_office)}
                        Yes
                        {else}
                         No
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>Status of tel : </strong>
                    <span>
                        {if !empty($history.log_tel_status)}
                        Yes
                        {else}
                         No
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>Status of email : </strong>
                    <span>
                        {if !empty($history.log_mail_status)}
                        Yes
                        {else}
                         NO
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>Revisit : </strong>
                    <span>
                        {if !empty($history.log_revisit)}
                        Yes
                        {else}
                         No
                        {/if}
                    </span>
                </li>
            </ul>
        {/if}
        {if count($older)}
            {foreach from=$older item=val}
            {$arr = explode('_', $val)}
            {$old_room = $detail_class->getRoom($arr[0], $arr[1],$arr[2])}
            {$old_house = $house_class->getHouseById($arr[1])}
            {$old_broker = $detail_class->getBroker($arr[2])}
                <div class="house-title">
                    <span>Older room</span>
                </div>
                <ul class="house-info detail-list-items">
                     <li>
                        <ul>
                            <li>
                                <strong>Room number : </strong>
                                <span>{$old_room.room_number}</span>
                            </li>
                            <li>
                                <strong>Room name : </strong>
                                <span>{$old_room.room_name}</span>
                            </li>
                            {if $old_house}
                                <li>
                                    <strong>House name : </strong>
                                    <span>{$old_house.house_name}</span>
                                </li>
                            {/if}
                            {if $old_broker}
                                <li>
                                    <strong>Broker name : </strong>
                                    <span>{$old_broker.broker_company_name} </span>
                                </li>
                            {/if}
                         </ul>
                    </li>
                </ul>
            {/foreach}
        {/if}
        
    {else}

    {/if}
</div>

{include file='footer.tpl'}