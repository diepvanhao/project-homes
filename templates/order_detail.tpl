<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/report.css" />
{include file="header_global.tpl"}
<div id="site_content" class="report-content">
    <div class="report-title">
        <h3>オーダーの詳細</h3>
    </div>
    {if $order}
        <div class="house-title">
            <span>オーダー</span>
        </div>
        <ul class="house-info">
            <li>
                <strong>オーダーＩＤ : </strong>
                <span>{$order.order_name}</span>
            </li>
            <li>
                <strong>賃料 : </strong>
                <span>{$order.order_rent_cost}</span>
            </li>
            <li>
                <strong>連絡の現況 : </strong>
                <span>{$status}</span>
            </li>
            <li>
                <strong>備考 : </strong>
                <span>{$order.order_comment}</span>
            </li>
            <li>
                <strong>登録日付 : </strong>
                <span>{date('Y-m-d',$order.order_day_create)}</span>
            </li>
            <li>
                <strong>引越予定日 : </strong>
                <span>{date('Y-m-d',$order.order_day_create)}</span>
            </li>
        </ul>
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
                    <span>{$room.room_name}</span>
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
        {/if}
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
                    <span>{$house.house_address}</span>
                </li>
                <li>
                    <strong>エリア : </strong>
                    <span>{$house.house_area}</span>
                </li>
                <li>
                    <strong>間取り : </strong>
                    <span>{$house_type}</span>
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
                    <strong>Ｅメール : </strong>
                    <span>{$house.house_owner_email}</span>
                </li>
            </ul>
        {/if}
        {if $client}
            <div class="house-title">
                <span>クライアント情報</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>名称 : </strong>
                    <span>{$client.client_name}</span>
                </li>
                <li>
                    <strong>住所 : </strong>
                    <span>{$client.client_address}</span>
                </li>
                <li>
                    <strong>電話番号 : </strong>
                    <span>{$client.client_phone}</span>
                </li>
                <li>
                    <strong>ファックス : </strong>
                    <span>{$client.client_fax}</span>
                </li>
                <li>
                    <strong>Ｅメール : </strong>
                    <span>{$client.client_email}</span>
                </li>
                <li>
                    <strong>生年月日 : </strong>
                    <span>{$client.client_birthday}</span>
                </li>
                <li>
                    <strong>性別 : </strong>
                    <span>{$client.client_gender}</span>
                </li>
                <li>
                    <strong>年収 : </strong>
                    <span>{$client.client_income}</span>
                </li>
                <li>
                    <strong>職業  : </strong>
                    <span>{$client.client_occupation}</span>
                </li>
                <li>
                    <strong>会社名 : </strong>
                    <span>{$client.client_company}</span>
                </li>
            </ul>
        {/if}
        {if $broker}
            <div class="house-title">
                <span>管理会社</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>名称 : </strong>
                    <span>{$broker.broker_company_name} </span>
                </li>
                <li>
                    <strong>住所 : </strong>
                    <span>{$broker.broker_company_address} </span>
                </li>
                <li>
                    <strong>電話番号 : </strong>
                    <span>{$broker.broker_company_phone} </span>
                </li>
                <li>
                    <strong>Ｅメール : </strong>
                    <span>{$broker.broker_company_email} </span>
                </li>
                <li>
                    <strong>ファックス : </strong>
                    <span>{$broker.broker_company_fax} </span>
                </li>
                <li>
                    <strong>担当者 : </strong>
                    <span>{$broker.broker_company_undertake} </span>
                </li>
            </ul>
        {/if}
        {if $history}
            <div class="house-title">
                <span>履歴</span>
            </div>
            <ul class="house-info">
                <li>
                    <strong>通話時間 : </strong>
                    <span>{$history.log_time_call} </span>
                </li>
                <li>
                    <strong>来店時間 : </strong>
                    <span>{$history.log_time_arrive_company} </span>
                </li>
                <li>
                    <strong>備考 : </strong>
                    <span>{$history.log_comment} </span>
                </li>
                <li>
                    <strong>来店予定日時 : </strong>
                    <span>{$history.log_date_appointment_from} </span>
                </li>
                <li>
                    <strong>予約の現況 : </strong>
                    <span>
                        {if !empty($history.log_status_appointment)}
                        はい。
                        {else}
                         いいえ。
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>店看板 : </strong>
                    <span>
                        {if !empty($history.log_shop_sign)}
                        はい。
                        {else}
                         いいえ。
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>現地看板 : </strong>
                    <span>
                        {if !empty($history.log_local_sign)}
                        はい。
                        {else}
                         いいえ。
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>紹介 : </strong>
                    <span>
                        {if !empty($history.log_introduction)}
                        はい。
                        {else}
                         いいえ。
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>電話で連絡 : </strong>
                    <span>
                        {if !empty($history.log_tel)}
                        はい。
                        {else}
                         いいえ。
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>メールで連絡 : </strong>
                    <span>
                        {if !empty($history.log_mail)}
                        はい。
                        {else}
                         いいえ。
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>チラシ : </strong>
                    <span>
                        {if !empty($history.log_flyer)}
                        はい。
                        {else}
                         いいえ。
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>ＬＩＮＥ: </strong>
                    <span>
                        {if !empty($history.log_line)}
                        はい。
                        {else}
                         いいえ。
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>本社反響 : </strong>
                    <span>
                        {if !empty($history.log_contact_head_office)}
                        はい。
                        {else}
                         いいえ。
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>電話の現況 : </strong>
                    <span>
                        {if !empty($history.log_tel_status)}
                        はい。
                        {else}
                         いいえ。
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>メールの現況 : </strong>
                    <span>
                        {if !empty($history.log_mail_status)}
                        はい。
                        {else}
                         いいえ。
                        {/if}
                    </span>
                </li>
                <li>
                    <strong>再来店 : </strong>
                    <span>
                        {if !empty($history.log_revisit)}
                        はい。
                        {else}
                         いいえ。
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
                    <span>前の号室</span>
                </div>
                <ul class="house-info detail-list-items">
                     <li>
                        <ul>
                            <li>
                                <strong>部屋情報 : </strong>
                                <span>{$old_room.room_number}</span>
                            </li>
                            <li>
                                <strong>間取り : </strong>
                                <span>{$old_room.room_name}</span>
                            </li>
                            {if $old_house}
                                <li>
                                    <strong>物件名 : </strong>
                                    <span>{$old_house.house_name}</span>
                                </li>
                            {/if}
                            {if $old_broker}
                                <li>
                                    <strong>管理会社 : </strong>
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