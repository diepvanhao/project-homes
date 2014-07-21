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
                <strong>オーダーID : </strong>
                <span>{$order.order_name}</span>
            </li>
            <li>
                <strong>賃料 : </strong>
                <span>{$order.order_rent_cost}</span>
            </li>
            <li>
                <strong>現況 : </strong>
                <span>{$status}</span>
            </li>
            <li>
                <strong>備考 : </strong>
                <span>{$order.order_comment}</span>
            </li>
            <li>
                <strong>登録日付 : </strong>
                <span>{$order.order_day_create}</span>
            </li>
            <li>
                <strong>Update Date : </strong>
                <span>{$order.order_day_create}</span>
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
                    <strong>アリアー : </strong>
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
                    <strong>Eメール : </strong>
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
                    <strong>Eメール : </strong>
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
                    <strong>収入 : </strong>
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
        {if $client}
            <div class="house-title">
                <span>管理会社</span>
            </div>
            <ul class="house-info">
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
                    <span>担当者 : </span>
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
                    <span>通話時間 : </span>
                    <span>{$history.log_time_call} </span>
                </li>
                <li>
                    <span>来店時間 : </span>
                    <span>{$history.log_time_arrive_company} </span>
                </li>
                <li>
                    <span>備考 : </span>
                    <span>{$history.log_comment} </span>
                </li>
                <li>
                    <span>予約日付　（～まで） : </span>
                    <span>{$history.log_date_appointment_from} </span>
                </li>
                <li>
                    <span>予約現況 : </span>
                    <span>{$history.log_status_appointment} </span>
                </li>
                <li>
                    <span>店看板 : </span>
                    <span>{$history.log_shop_sign} </span>
                </li>
                <li>
                    <span>ローカルののサイン : </span>
                    <span>{$history.log_local_sign} </span>
                </li>
                <li>
                    <span>紹介 : </span>
                    <span>{$history.log_introduction} </span>
                </li>
                <li>
                    <span>電話で連絡 : </span>
                    <span>{$history.log_tel} </span>
                </li>
                <li>
                    <span>メールで連絡 : </span>
                    <span>{$history.log_mail} </span>
                </li>
                <li>
                    <span>チラシ : </span>
                    <span>{$history.log_flyer} </span>
                </li>
                <li>
                    <span>ライン : </span>
                    <span>{$history.log_line} </span>
                </li>
                <li>
                    <span>本社へ連絡 : </span>
                    <span>{$history.log_contact_head_office} </span>
                </li>
                <li>
                    <span>電話現況 : </span>
                    <span>{$history.log_tel_status} </span>
                </li>
                <li>
                    <span>メール現況 : </span>
                    <span>{$history.log_mail_status} </span>
                </li>
                <li>
                    <span>再来店 : </span>
                    <span>{$history.log_revisit} </span>
                </li>
            </ul>
        {/if}
        
        
    {else}

    {/if}
</div>

{include file='footer.tpl'}