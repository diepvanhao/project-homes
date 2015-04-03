<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/report.css" />
<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/style.min.css" />
{include file="header_global.tpl"}
<script type="text/javascript" src="{$url->url_base}include/js/jquery.bpopup.min.js"></script>
<div id="site_content" class="report-content">
    <div class="report-title">
        <h3>日　計　表</h3>
    </div>
    {if count($error)}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    {if count($agents) gt 0}
    <div class="report-filter">
        <form method="POST">
            <table cellpadding="0" cellspacing="0" style="margin-left: 0px;" width="100%">      
                <tbody>
                    <tr>
                        <td>
                            店舗を選択します:
                            <select id="agent_id" name="agent_id" style="height:26px; width: 251px;">
                                <option value=""></option>
                                {foreach from=$agents key=k item=agent}
                                <option value="{$agent.id}" {if isset($params.agent_id) && $params.agent_id eq $agent.id }selected="selected" {/if}>{$agent.agent_name}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            日付 <input type='text' name='fromdate' id='fromdate'  value="{$fromdate}"  style="height:26px; width: 250px;" readonly>
                            ～ <input type='text' name='date' id='todate'  value="{$date}"  style="height:26px; width: 250px;" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" class="btn-signup" value="プレビュー" id="submit" name="submit" style="width: 150px;" onclick="showloadgif()">&nbsp;  
                            <input id="my-button" type="button" class="btn-signup" value="エクスポート"  style="width: 150px;" onclick="showloadgif()">&nbsp;  
                            <input type="submit" style="display: none;" id="export" name="export" value='1'>
                            <input type="hidden" id='type' name="type" value='xls'>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <div id="popup" style="left: 710px; position: absolute; top: 1027px; z-index: 9999; opacity: 1; display: none;">
                <span class="button b-close"><span>X</span></span>
                <br><br><br>
                <center>
                    <label for="type_confirm">フォーマット</label>
                    <select id="type_confirm">
                        <option value="xls">XLS</option>
                        <option value="csv">CSV</option>
                    </select> 
                    <br><br><br>
                    <button class="btn-signup" id='export_confirm' style="width: 150px;">エクスポート</button>
                </center>
                <!--If you can't get it up use<br><span class="logo">bPopup</span>-->
            </div>
    </div>
    {if isset($params.agent_id) && count($users) && !count($error)}
    <div class="agent-content">
        <div class="report-lable">
            <div class="agent-name">
                <span>店舗名</span>
                <span>{$agent_name}</span>
            </div>
            <br>
            <div class="agent-date">
                <span>日付 : </span>
                <span> 
                    {if $date}
                       {$fromdate} - {$date}
                    {else}
                        {date("m/d/Y")}
                    {/if}
                </span>
            </div>
<!--            <div class="agent-rate">
                <span>Achievement rate: </span>
                <span></span>
            </div>-->
        </div>
        <table>
            <tr>
                <th></th>
                <th>氏名</th>
                <th>役職</th>
                <th>今月目標</th>
                <th>合計</th>
                <th>売上済み</th>
                <th>未契約</th>
                <th>次月に繰越</th>
                <th>前月からの繰越</th>
                <th>フリー</th>
                <th>紹介</th>
                <th>ＴＥＬ呼</th>
                <th>ＭＡＩＬ呼</th>
                <th>ＴＥＬ呼率</th>
                <th>ＭＡＩＬ呼率</th>
                <th>呼実</th>
                <th>呼率</th>
                <th>ＴＥＬ反響</th>
                <th>ＭＡＩＬ反響</th>
                <th>反響合計</th>
                <th>再来店</th>
                <th>申込</th>
                <th>申率</th>
                <th>キャ</th>
                <th>振替</th>
                <th>成約</th>
                <th>成約率</th>
                <th>自社申込</th>
            </tr>
            {foreach $users as $key => $user}
            {$info = $report ->getUserInfo($user.id,$date,$fromdate)}
            <tr>
                <td rowspan="2">{$key + 1}</td>
                <td rowspan="2">{$user.user_lname} {$user.user_fname} </td>
                <td rowspan="2">{$user.user_position}</td>
                <td rowspan="2">
                    {$user_target = (int) $report ->getUserTarget($user.id,$date,$fromdate)}
                    {$user_target}
                    {$month.target = $month.target + $user_target}
                </td>
                <td>当日</td>
                <!--<td>{$info.cost_today}</td>-->
                {$commission = $report ->userCommission($user.id,$date,$fromdate)}
                {$today.cost = $today.cost + $commission.today_already_recorded}
                {$today.unsigned = $today.unsigned + $commission.today_unsigned}
                <td>{$commission.today_already_recorded}</td> <!--Already Recorded-->
                <td>{$commission.today_unsigned}</td> <!--未契約-->
                <td></td>
                <td></td>
                <td>
                    {(int) ($info.today_shop_sign + $info.today_local_sign)}
                    {$today.signboard = $today.signboard + (int) ($info.today_shop_sign + $info.today_local_sign)}
                </td>
                <td>
                    {(int) $info.today_introduction}
                    {$today.introduction = $today.introduction + (int) $info.today_introduction}
                </td>
                <td>
                    {(int) $info.today_tel_status}
                    {$today.tel_status = $today.tel_status + (int) $info.today_tel_status}
                </td>
                <td>
                    {(int) $info.today_mail_status}
                    {$today.mail_status = $today.mail_status + (int) $info.today_mail_status}
                </td>
                <td>{($info.today_tel)?round($info.today_tel_status*100/$info.today_tel,2):0}%</td>
                <td>{($info.today_mail)?round($info.today_mail_status*100/$info.today_mail,2):0}%</td>
                <td>
                    {(int)($info.today_tel_status + $info.today_mail_status)}
                    {$today.track_record = $today.track_record + (int)($info.today_tel_status + $info.today_mail_status)}
                </td>
                <td>{round(($info.today_tel + $info.today_mail)?(100*($info.today_tel_status + $info.today_mail_status)/($info.today_tel + $info.today_mail)):0,2)}%</td>
                <td>
                    {(int)$info.today_tel}
                    {$today.tel = $today.tel + (int)$info.today_tel}
                </td>
                <td>
                    {(int)$info.today_mail}
                    {$today.mail = $today.mail + (int)$info.today_mail}
                </td>
                <td>{($info.today_tel + $info.today_mail)?($info.today_tel + $info.today_mail):0}</td>
                <td>
                    {(int) $info.today_revisit}
                    {$today.revisit = $today.revisit + (int) $info.today_revisit}
                </td>
                <td>
                    {(int) $info.today_application}
                    {$today.application = $today.application + (int) $info.today_application}
                </td>
                <td>
                    {$tmp = (int) ($info.today_shop_sign + $info.today_local_sign) + (int) $info.today_introduction + (int) $info.today_tel_status + (int) $info.today_mail_status}
                    {round(($tmp > 0) ? (100*($info.today_application)/$tmp):0,2)}%
                </td>
                <td>
                    {$info.today_cancel}
                    {$today.cancel = $today.cancel + $info.today_cancel}
                </td>
                <td>
                    {$info.today_change}
                    {$today.change = $today.change + $info.today_change}
                </td>
                <td>
                    {$info.today_agreement}
                    {$today.agreement = $today.agreement + $info.today_agreement}
                </td>
                <td>{round(($tmp > 0) ? (100*($info.today_agreement)/$tmp):0,2)}%</td>
                <td>
                    {(int)$info.today_ambition}
                    {$today.done = $today.done + $info.today_ambition}
                </td>
            </tr>
            <tr>
                <td>累計</td>
                    {$month.cost = $month.cost + $commission.month_already_recorded}
                    {$month.unsigned = $month.unsigned + $commission.month_unsigned}
                <td>{$commission.month_already_recorded}</td> <!--Already Recorded-->
                <td>{$commission.month_unsigned}</td> <!--未契約-->
                <td>
                    {$cost_next_month = $report ->otherMonth($user.id,$date,1)}
                    {$cost_next_month}
                    {$month.cost_next = $month.cost_next + $cost_next_month}
                </td>
                <td>
                    {$cost_previous_month = $report ->otherMonth($user.id,$date,-1)}
                    {$cost_previous_month}
                    {$month.cost_previous = $month.cost_previous + $cost_previous_month}
                </td>
                <td>
                    {(int)($info.month_shop_sign + $info.month_local_sign)}
                    {$month.signboard = $month.signboard + (int)($info.month_shop_sign + $info.month_local_sign)}
                </td>
                <td>
                    {(int) $info.month_introduction}
                    {$month.introduction = $month.introduction + (int) $info.month_introduction}
                </td>
                <td>
                    {$month.tel_status = $month.tel_status + (int)$info.month_tel_status}
                    {(int)$info.month_tel_status}
                </td>
                <td>
                    {(int)$info.month_mail_status}
                    {$month.mail_status = $month.mail_status + (int)$info.month_mail_status}
                </td>
                <td>{($info.month_tel)?round($info.month_tel_status*100/$info.month_tel,2):0}%</td>
                <td>{($info.month_mail)?round($info.month_mail_status*100/$info.month_mail,2):0}%</td>
                <td>
                    {(int) ($info.month_tel_status + $info.month_mail_status)}
                    {$month.track_record = $month.track_record + (int) ($info.month_tel_status + $info.month_mail_status)}
                </td>
                <td>{round(($info.month_tel + $info.month_mail)?(100*($info.month_tel_status + $info.month_mail_status)/($info.month_tel + $info.month_mail)):0,2)}%</td>
                <td>
                    {(int)$info.month_tel}
                    {$month.tel = $month.tel + (int)$info.month_tel}
                </td>
                <td>
                    {(int)$info.month_mail}
                    {$month.mail = $month.mail + (int)$info.month_mail}
                </td>
                <td>{($info.month_tel + $info.month_mail)?($info.month_tel + $info.month_mail):0}</td>
                <td>
                    {(int)$info.month_revisit}
                    {$month.revisit = $month.revisit + (int)$info.month_revisit}
                </td>
                <td>
                    {(int)$info.month_application}
                    {$month.application = $month.application + (int)$info.month_application}
                </td>
                <td>
                    {$tmp = (int) ($info.month_shop_sign + $info.month_local_sign) + (int) $info.month_introduction + (int) $info.month_tel_status + (int) $info.month_mail_status}
                    {round(($tmp > 0) ? (100*($info.month_application)/$tmp):0,2)}%
                </td>
                <td>
                    {(int)$info.month_cancel}
                    {$month.cancel = $month.cancel + (int)$info.month_cancel}
                </td>
                <td>
                    {(int)$info.month_change}
                    {$month.change = $month.change + (int)$info.month_change}
                </td>
                <td>
                    {(int)$info.month_agreement}
                    {$month.agreement = $month.agreement + (int)$info.month_agreement}
                </td>
                <td>{round(($tmp > 0) ? (100*($info.month_agreement)/$tmp):0,2)}%</td>
                <td>
                    {(int)$info.month_ambition}
                    {$month.done = $month.done + (int)$info.month_ambition}
                </td>
            </tr>
            {/foreach}
            <tr>
            <tr>
                <td rowspan="2" colspan="3">累計</td>
                <td rowspan="2">
                    {$month.target}
                </td>
                <td>当日</td>
                <td>{$today.cost}</td>
                <td>{$today.unsigned}</td>
                <td></td>
                <td></td>
                <td>
                    {$today.signboard}
                </td>
                <td>
                    {$today.introduction}
                </td>
                <td>
                    {$today.tel_status}
                </td>
                <td>
                    {$today.mail_status}
                </td>
                <td>{($today.tel)?round($today.tel_status*100/$today.tel,2):0}%</td>
                <td>{($today.mail)?round($today.mail_status*100/$today.mail,2):0}%</td>
                <td>
                    {(int)($today.tel_status + $today.mail_status)}
                </td>
                <td>{round(($today.tel + $today.mail)?(100*($today.tel_status + $today.mail_status)/($today.tel + $today.mail)):0,2)}%</td>
                <td>
                    {(int)$today.tel}
                </td>
                <td>
                    {(int)$today.mail}
                </td>
                <td>{($today.tel + $today.mail)?($today.tel + $today.mail):0}</td>
                <td>
                    {(int) $today.revisit}
                </td>
                <td>
                    {(int) $today.application}
                </td>
                <td>
                    {$tmp = (int) ($today.signboard) + (int) $today.introduction + (int) $today.tel_status + (int) $today.mail_status}
                    {round(($tmp > 0) ? (100*($today.application)/$tmp):0,2)}%
                </td>
                <td>
                    {$today.cancel}
                </td>
                <td>
                    {$today.change}
                </td>
                <td>
                    {$today.agreement}
                </td>
                <td>{round(($tmp > 0) ? (100*($today.agreement)/$tmp):0,2)}%</td>
                <td>
                    {$today.done}
                </td>
            </tr>
            <tr>
                <td>合　計</td>
                <td>{$month.cost}</td>
                <td>{$month.unsigned}</td>
                <td>{$month.cost_next}</td>
                <td>{$month.cost_previous}</td>
                <td>{(int)($month.signboard)}</td>
                <td>{(int) $month.introduction}</td>
                <td>{(int)$month.tel_status}</td>
                <td>{(int)$month.mail_status}</td>
                <td>{($month.tel)?round($month.tel_status*100/$month.tel,2):0}%</td>
                <td>{($month.mail)?round($month.mail_status*100/$month.mail,2):0}%</td>
                <td>{(int) ($month.tel_status + $month.mail_status)}</td>
                <td>{round(($month.tel + $month.mail)?((100*$month.tel_status + $month.mail_status)/($month.tel + $month.mail)):0,2)}%</td>
                <td>{(int)$month.tel}</td>
                <td>{(int)$month.mail}</td>
                <td>{($month.tel + $month.mail)?($month.tel + $month.mail):0}</td>
                <td>{(int)$month.revisit}</td>
                <td>{(int)$month.application}</td>
                <td>
                    {$tmp = (int) ($month.signboard) + (int) $month.introduction + (int) $month.tel_status + (int) $month.mail_status}
                    {round(($tmp > 0) ? (100*($month.application)/$tmp):0,2)}%
                </td>
                <td>{$month.cancel}</td>
                <td>{$month.change}</td>
                <td>{$month.agreement}</td>
                <td>{round(($tmp > 0) ? (100*($month.agreement)/$tmp):0,2)}%</td>
                <td>{$month.done}</td>
            </tr>
        </table>
    </div>
    <div class="agent-content no-wrap">
        <div class="last-year-title">
            <h3>媒体</h3>
        </div>
        <table>
            <tr>
                <th></th>
                <th>媒体</th>
                <th>累計</th>
                <th>反響</th>
                <th>呼実</th>
                <th>フリー</th>
                <th>再来店</th>
                <th>申込</th>
                <th>キャ</th>
                <th>自社</th>
                <th>台帳</th>
            </tr>
            {$yearReport = $report ->getLastyearInfo($agent_id,$date,$fromdate)}
            <tbody>
                <tr>
                    <td rowspan="2">
                        1
                    </td>
                    <td rowspan="2">
                        インターネット（メール）   
                    </td>
                    <td>当日</td>
                    <td>{(int) ($yearReport.todaymail_mail + $yearReport.todaymail_tel)}</td>
                    <td>{(int)($yearReport.todaymail_tel_status + $yearReport.todaymail_mail_status)}</td>
                    <td>{(int)$yearReport.todaymail_shop_sign + $yearReport.todaymail_local_sign}</td>
                    <td>{(int) $yearReport.todaymail_revisit}</td>
                    <td>{(int) $yearReport.todaymail_application}</td>
                    <td>{$yearReport.todaymail_cancel}</td>
                    <td>{$yearReport.todaymail_ambition}</td>
                    <td>{$yearReport.todaymail_agreement}</td>
                </tr>
                <tr>
                    <td>累計</td>
                    <td>{(int) ($yearReport.yearmail_mail + $yearReport.yearmail_tel)}</td>
                    <td>{(int)($yearReport.yearmail_tel_status + $yearReport.yearmail_mail_status)}</td>
                    <td>{(int)$yearReport.yearmail_shop_sign + $yearReport.yearmail_local_sign}</td>
                    <td>{(int) $yearReport.yearmail_revisit}</td>
                    <td>{(int) $yearReport.yearmail_application}</td>
                    <td>{$yearReport.yearmail_cancel}</td>
                    <td>{$yearReport.yearmail_ambition}</td>
                    <td>{$yearReport.yearmail_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        2
                    </td>
                    <td rowspan="2">
                        インターネット（電話）   
                    </td>
                    <td>当日</td>
                    <td>{(int) ($yearReport.todayphone_mail + $yearReport.todayphone_tel)}</td>
                    <td>{(int)($yearReport.todayphone_tel_status + $yearReport.todayphone_mail_status)}</td>
                    <td>{(int)$yearReport.todayphone_shop_sign + $yearReport.todayphone_local_sign}</td>
                    <td>{(int) $yearReport.todayphone_revisit}</td>
                    <td>{(int) $yearReport.todayphone_application}</td>
                    <td>{$yearReport.todayphone_cancel}</td>
                    <td>{$yearReport.todayphone_ambition}</td>
                    <td>{$yearReport.todayphone_agreement}</td>
                </tr>
                <tr>
                    <td>累計</td>
                    <td>{(int) ($yearReport.yearphone_mail + $yearReport.yearphone_tel)}</td>
                    <td>{(int)($yearReport.yearphone_tel_status + $yearReport.yearphone_mail_status)}</td>
                    <td>{(int)$yearReport.yearphone_shop_sign + $yearReport.yearphone_local_sign}</td>
                    <td>{(int) $yearReport.yearphone_revisit}</td>
                    <td>{(int) $yearReport.yearphone_application}</td>
                    <td>{$yearReport.yearphone_cancel}</td>
                    <td>{$yearReport.yearphone_ambition}</td>
                    <td>{$yearReport.yearphone_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        3
                    </td>
                    <td rowspan="2">
                        定借(TEL、メール）
                    </td>
                    <td>当日</td>
                    <td>{(int)($yearReport.todaydiscount_mail + $yearReport.todaydiscount_tel)}</td>
                    <td>{(int)($yearReport.todaydiscount_tel_status + $yearReport.todaydiscount_mail_status)}</td>
                    <td>{(int)$yearReport.todaydiscount_shop_sign + $yearReport.todaydiscount_local_sign}</td>
                    <td>{(int) $yearReport.todaydiscount_revisit}</td>
                    <td>{(int) $yearReport.todaydiscount_application}</td>
                    <td>{$yearReport.todaydiscount_cancel}</td>
                    <td>{$yearReport.todaydiscount_ambition}</td>
                    <td>{$yearReport.todaydiscount_agreement}</td>
                </tr>
                <tr>
                    <td>累計</td>
                    <td>{(int) ($yearReport.yeardiscount_mail + $yearReport.yeardiscount_tel)}</td>
                    <td>{(int)($yearReport.yeardiscount_tel_status + $yearReport.yeardiscount_mail_status)}</td>
                    <td>{(int)$yearReport.yeardiscount_shop_sign + $yearReport.yeardiscount_local_sign}</td>
                    <td>{(int) $yearReport.yeardiscount_revisit}</td>
                    <td>{(int) $yearReport.yeardiscount_application}</td>
                    <td>{$yearReport.yeardiscount_cancel}</td>
                    <td>{$yearReport.yeardiscount_ambition}</td>
                    <td>{$yearReport.yeardiscount_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        4
                    </td>
                    <td rowspan="2">
                        現地看板
                    </td>
                    <td>当日</td>
                    <td>{(int) ($yearReport.todaylocalsign_mail + $yearReport.todaylocalsign_tel)}</td>
                    <td>{(int)($yearReport.todaylocalsign_tel_status + $yearReport.todaylocalsign_mail_status)}</td>
                    <td>{(int)$yearReport.todaylocalsign_shop_sign + $yearReport.todaylocalsign_local_sign}</td>
                    <td>{(int) $yearReport.todaylocalsign_revisit}</td>
                    <td>{(int) $yearReport.todaylocalsign_application}</td>
                    <td>{$yearReport.todaylocalsign_cancel}</td>
                    <td>{$yearReport.todaylocalsign_ambition}</td>
                    <td>{$yearReport.todaylocalsign_agreement}</td>
                </tr>
                <tr>
                    <td>累計</td>
                    <td>{(int) ($yearReport.yearlocalsign_mail + $yearReport.yearlocalsign_tel) }</td>
                    <td>{(int)($yearReport.yearlocalsign_tel_status + $yearReport.yearlocalsign_mail_status)}</td>
                    <td>{(int)$yearReport.yearlocalsign_shop_sign + $yearReport.yearlocalsign_local_sign}</td>
                    <td>{(int) $yearReport.yearlocalsign_revisit}</td>
                    <td>{(int) $yearReport.yearlocalsign_application}</td>
                    <td>{$yearReport.yearlocalsign_cancel}</td>
                    <td>{$yearReport.yearlocalsign_ambition}</td>
                    <td>{$yearReport.yearlocalsign_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        5
                    </td>
                    <td rowspan="2">
                        紹介
                    </td>
                    <td>当日</td>
                    <td>{(int) ($yearReport.todayintroduction_mail + $yearReport.todayintroduction_tel)}</td>
                    <td>{(int)($yearReport.todayintroduction_tel_status + $yearReport.todayintroduction_mail_status)}</td>
                    <td>{(int)$yearReport.todayintroduction_shop_sign + $yearReport.todayintroduction_local_sign}</td>
                    <td>{(int) $yearReport.todayintroduction_revisit}</td>
                    <td>{(int) $yearReport.todayintroduction_application}</td>
                    <td>{$yearReport.todayintroduction_cancel}</td>
                    <td>{$yearReport.todayintroduction_ambition}</td>
                    <td>{$yearReport.todayintroduction_agreement}</td>
                </tr>
                <tr>
                    <td>累計</td>
                    <td>{(int) ($yearReport.yearintroduction_mail + $yearReport.yearintroduction_tel)}</td>
                    <td>{(int)($yearReport.yearintroduction_tel_status + $yearReport.yearintroduction_mail_status)}</td>
                    <td>{(int)$yearReport.yearintroduction_shop_sign + $yearReport.yearphone_local_sign}</td>
                    <td>{(int) $yearReport.yearintroduction_revisit}</td>
                    <td>{(int) $yearReport.yearintroduction_application}</td>
                    <td>{$yearReport.yearintroduction_cancel}</td>
                    <td>{$yearReport.yearintroduction_ambition}</td>
                    <td>{$yearReport.yearintroduction_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        6
                    </td>
                    <td rowspan="2">
                        店看板				
                    </td>
                    <td>当日</td>
                    <td>{(int) ($yearReport.todayshopsign_mail + $yearReport.todayshopsign_tel)}</td>
                    <td>{(int)($yearReport.todayshopsign_tel_status + $yearReport.todayshopsign_mail_status)}</td>
                    <td>{(int)$yearReport.todayshopsign_shop_sign + $yearReport.todayshopsign_local_sign}</td>
                    <td>{(int) $yearReport.todayshopsign_revisit}</td>
                    <td>{(int) $yearReport.todayshopsign_application}</td>
                    <td>{$yearReport.todayshopsign_cancel}</td>
                    <td>{$yearReport.todayshopsign_ambition}</td>
                    <td>{$yearReport.todayshopsign_agreement}</td>
                </tr>
                <tr>
                    <td>累計</td>
                    <td>{(int) ($yearReport.yearshopsign_mail + $yearReport.yearshopsign_tel)}</td>
                    <td>{(int)($yearReport.yearshopsign_tel_status + $yearReport.yearshopsign_mail_status)}</td>
                    <td>{(int)$yearReport.yearshopsign_shop_sign + $yearReport.yearshopsign_local_sign}</td>
                    <td>{(int) $yearReport.yearshopsign_revisit}</td>
                    <td>{(int) $yearReport.yearshopsign_application}</td>
                    <td>{$yearReport.yearshopsign_cancel}</td>
                    <td>{$yearReport.yearshopsign_ambition}</td>
                    <td>{$yearReport.yearshopsign_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        7
                    </td>
                    <td rowspan="2">
                        チラシ
                    </td>
                    <td>当日</td>
                    <td>{(int)($yearReport.todayflyer_mail + $yearReport.todayflyer_tel)}</td>
                    <td>{(int)($yearReport.todayflyer_tel_status + $yearReport.todayflyer_mail_status)}</td>
                    <td>{(int)$yearReport.todayflyer_shop_sign + $yearReport.todayflyer_local_sign}</td>
                    <td>{(int) $yearReport.todayflyer_revisit}</td>
                    <td>{(int) $yearReport.todayflyer_application}</td>
                    <td>{$yearReport.todayflyer_cancel}</td>
                    <td>{$yearReport.todayflyer_ambition}</td>
                    <td>{$yearReport.todayflyer_agreement}</td>
                </tr>
                <tr>
                    <td>累計</td>
                    <td>{(int) ($yearReport.yearflyer_mail + $yearReport.yearflyer_tel)}</td>
                    <td>{(int)($yearReport.yearflyer_tel_status + $yearReport.yearflyer_mail_status)}</td>
                    <td>{(int)$yearReport.yearflyer_shop_sign + $yearReport.yearflyer_local_sign}</td>
                    <td>{(int) $yearReport.yearflyer_revisit}</td>
                    <td>{(int) $yearReport.yearflyer_application}</td>
                    <td>{$yearReport.yearflyer_cancel}</td>
                    <td>{$yearReport.yearflyer_ambition}</td>
                    <td>{$yearReport.yearflyer_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        8
                    </td>
                    <td rowspan="2">
                        ＬＩＮＥ
                    </td>
                    <td>当日</td>
                    <td>{(int) ($yearReport.todayline_mail + $yearReport.todayline_tel)}</td>
                    <td>{(int)($yearReport.todayline_tel_status + $yearReport.todayline_mail_status)}</td>
                    <td>{(int)$yearReport.todayline_shop_sign + $yearReport.todayline_local_sign}</td>
                    <td>{(int) $yearReport.todayline_revisit}</td>
                    <td>{(int) $yearReport.todayline_application}</td>
                    <td>{$yearReport.todayline_cancel}</td>
                    <td>{$yearReport.todayline_ambition}</td>
                    <td>{$yearReport.todayline_agreement}</td>
                </tr>
                <tr>
                    <td>累計</td>
                    <td>{(int) ($yearReport.yearline_mail + $yearReport.yearline_tel)}</td>
                    <td>{(int)($yearReport.yearline_tel_status + $yearReport.yearline_mail_status)}</td>
                    <td>{(int)$yearReport.yearline_shop_sign + $yearReport.yearline_local_sign}</td>
                    <td>{(int) $yearReport.yearline_revisit}</td>
                    <td>{(int) $yearReport.yearline_application}</td>
                    <td>{$yearReport.yearline_cancel}</td>
                    <td>{$yearReport.yearline_ambition}</td>
                    <td>{$yearReport.yearline_agreement}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="agent-content no-wrap">
        <div class="last-year-title">
            <h3>インターネット詳細</h3>
        </div>
        <table>
            <tr>
                <th></th>
                <th>会社名</th>
                <th>累計</th>
                <th>反響</th>
                <th>呼実</th>
                <th>フリー</th>
                <th>再来店</th>
                <th>申込</th>
                <th>キャ</th>
                <th>自社</th>
                <th>台帳</th>
            </tr>
            <tbody>
                {foreach from=$report->getAllSource() key=k item=company}
                {$com_info = $report->getSourceInfo($company.id,$date,$fromdate)}
                <tr>
                    <td rowspan="2">{$k + 1}</td>
                    <td rowspan="2">{$company.source_name}</td>
                    <td>当日</td>
                    <td>{(int)$com_info.today_tel + $com_info.today_mail}</td>
                    <td>{(int)($com_info.today_tel_status + $com_info.today_mail_status)}</td>
                    <td>{(int)$com_info.today_shop_sign + $com_info.today_local_sign}</td>
                    <td>{(int) $com_info.today_revisit}</td>
                    <td>{(int) $com_info.today_application}</td>
                    <td>{$com_info.today_cancel}</td>
                    <td>{$com_info.today_ambition}</td>
                    <td>{$com_info.today_agreement}</td>
                </tr>
                <tr>
                    <td>累計</td>
                    <td>{(int) $com_info.month_mail + $com_info.month_tel}</td>
                    <td>{(int)($com_info.month_tel_status + $com_info.month_mail_status)}</td>
                    <td>{(int)$com_info.month_shop_sign + $com_info.month_local_sign}</td>
                    <td>{(int) $com_info.month_revisit}</td>
                    <td>{(int) $com_info.month_application}</td>
                    <td>{$com_info.month_cancel}</td>
                    <td>{$com_info.month_ambition}</td>
                    <td>{$com_info.month_agreement}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
    <div class="agent-content no-wrap">

        {literal}
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">
            google.load("visualization", "1", {packages: ["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['name','計上済','目標'],
                {/literal}
                        {foreach $users as $key => $user}
                            {$commission = $report ->userCommission($user.id,$date,$fromdate)}
                            {$user_target = (int) $report ->getUserTarget($user.id,$date,$fromdate)}
                            {$today.chart_cost = $today.chart_cost + $commission.month_already_recorded}
                            {$today.chart_target = $today.chart_target + $user_target}
                            {literal}['{/literal}{$user.user_lname} {$user.user_fname}{literal}',{/literal}{$commission.month_already_recorded}{literal},{/literal}{$user_target}{literal}],{/literal}
                    {/foreach}
                    {literal}['{/literal}{$agent_name}{literal}',{/literal}{$today.chart_cost}{literal},{/literal}{$today.chart_target}{literal}],{/literal}
                    {literal}
                    ]);

                            var options = {
                                title: ' ',
                                vAxis: {title: ' ', titleTextStyle: {color: 'red'}}
                            };

                    var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
                    chart.draw(data, options);
                }
        </script>
        {/literal}
        <div id="chart_div" style="width: 900px; height: 500px;"></div>
    </div>
    {/if}
    {else}

    {/if}

</div>

{literal}
    <script type="text/javascript">
            $(document).ready(function() {
                birthday('fromdate');
                birthday('todate');
                $('#back').click(function() {
                    window.location.href = "manage_account.php";
                });
                $('#export_confirm').click(function(){
                    $("#type").val($("#type_confirm").val());
                    $('#export').click();
                });
            });
             ;(function($) {
                // DOM Ready
               $(function() {
                   // Binding a click event
                   // From jQuery v.1.7.0 use .on() instead of .bind()
                   $('#my-button').bind('click', function(e) {
                       // Prevents the default action to be triggered. 
                       e.preventDefault();
                       // Triggering bPopup when click event is fired
//                       $('#element_to_pop_up').bPopup();
                        $('#popup').bPopup({
                            speed: 650,
                            transition: 'slideIn',
                            transitionClose: 'slideBack'
                        });

                   });
               });
           })(jQuery);
    </script>
{/literal}

{include file="footer.tpl"}
