<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/report.css" />
{include file="header_global.tpl"}
<div id="site_content" class="report-content">
    <div class="report-title">
        <h3>Daily Report</h3>
    </div>
    {if count($agents) gt 0}
    <div class="report-filter">
        <form method="POST">
            <table cellpadding="0" cellspacing="0" style="margin-left: 0px;" width="100%">      
                <tbody>
                    <tr>
                        <td class="form1">
                            Select Agent:
                        </td>
                        <td class="form2">
                            <select id="agent_id" name="agent_id" style="height:26px; width: 251px;">
                                <option value=""></option>
                                {foreach from=$agents key=k item=agent}
                                <option value="{$agent.id}" {if isset($params.agent_id) && $params.agent_id eq $agent.id }selected="selected" {/if}>{$agent.agent_name}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'>Date: </td>
                        <td class='form2'><input type='text' name='date' id='birthday'  value="{$date}"  style="height:26px; width: 250px;"></td>
                    </tr>
                    <tr>
                        <td class="form1">&nbsp;</td>
                        <td class="form2">
                            <div style="margin-top:10px">
                                <input type="submit" class="btn-signup" value="View" id="submit" name="submit" style="width: 100px;" onclick="showloadgif()">&nbsp;  
                                <input type="submit" class="btn-signup" value="Export" name="export" style="width: 100px;" onclick="showloadgif()">&nbsp;  
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    {if isset($params.agent_id) && count($users)}
    <div class="agent-content">
        <div class="report-lable">
            <div class="agent-name">
                <span>Shop Name/ Agent Name:</span>
                <span>{$agent_name}</span>
            </div>
            <div class="agent-date">
                <span>DATE: </span>
                <span> 
                    {if $date}
                       {$date}
                    {else}
                        {date("m/d/Y")}
                    {/if}
                </span>
            </div>
            <div class="agent-rate">
                <span>Achievement rate: </span>
                <span></span>
            </div>
        </div>
        <table>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Official Position</th>
                <th>Monthly Target</th>
                <th>Total</th>
                <th>Already recorded</th>
                <th>Unsigned</th>
                <th>Carry-over to next month</th>
                <th>Carry-over from the previous month</th>
                <th>Signboard</th>
                <th>Introduction</th>
                <th>Tel</th>
                <th>Mail</th>
                <th>Percentage of Call</th>
                <th>Percentage of Mail</th>
                <th>Track record</th>
                <th>Call rate</th>
                <th>Tell Feedback</th>
                <th>Mail Feedback</th>
                <th>Contact Total number</th>
                <th>Re-visit</th>
                <th>Application</th>
                <th>Application Rate</th>
                <th>Cancel</th>
                <th>Change</th>
                <th>Contract Agreement</th>
                <th>Contract Ratio</th>
                <th>Company Registeration</th>
            </tr>
            {foreach $users as $key => $user}
            {$info = $report ->getUserInfo($user.id,$date)}
            <tr>
                <td rowspan="2">{$key + 1}</td>
                <td rowspan="2">{$user.user_fname} {$user.user_lname}</td>
                <td rowspan="2">{$user.user_position}</td>
                <td rowspan="2">
                    {$user.user_target}
                    {$month.target = $month.target + $user.user_target}
                </td>
                <td>Today</td>
                <td>{$info.cost_today}{$today.cost = $today.cost + $info.cost_today}</td>
                <td></td>
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
                    {$info.today_agreement}
                    {$today.done = $today.done + $info.today_agreement}
                </td>
            </tr>
            <tr>
                <td>Total</td>
                <td>
                    {$info.cost_month}
                    {$month.cost = $month.cost + $info.cost_month}
                </td>
                <td></td>
                <td></td>
                <td>
                    {$info.cost_previous_month - $user.user_target}
                    {$month.cost_previous = $month.cost_previous + $info.cost_previous_month - $user.user_target}
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
                    {(int)$info.month_agreement}
                    {$month.done = $month.done + (int)$info.month_agreement}
                </td>
            </tr>
            {/foreach}
            <tr>
            <tr>
                <td rowspan="2" colspan="3">Total</td>
                <td rowspan="2">
                    {$month.target}
                </td>
                <td>Today</td>
                <td>{$today.cost}</td>
                <td></td>
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
                <td>Total</td>
                <td>{$month.cost}</td>
                <td></td>
                <td></td>
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
            </tr>
        </table>
    </div>
    <div class="agent-content no-wrap">
        <div class="last-year-title">
            <h3>Last Year Performance</h3>
        </div>
        <table>
            <tr>
                <th></th>
                <th>Media</th>
                <th>Total</th>
                <th>Feedback</th>
                <th>Track record</th>
                <th>Signboard</th>
                <th>Revisit</th>
                <th>Application</th>
                <th>Cancel</th>
                <th>自社</th>
                <th>Ledger</th>
            </tr>
            {$yearReport = $report ->getLastyearInfo($agent.id,$date)}
            <tbody>
                <tr>
                    <td rowspan="2">
                        1
                    </td>
                    <td rowspan="2">
                        Internet(e-mail)
                    </td>
                    <td>Today</td>
                    <td>{(int) ($yearReport.todaymail_mail + $yearReport.todaymail_tel)}</td>
                    <td>{(int)($yearReport.todaymail_tel_status + $yearReport.todaymail_mail_status)}</td>
                    <td>{(int)$yearReport.todaymail_shop_sign + $yearReport.todaymail_local_sign}</td>
                    <td>{(int) $yearReport.todaymail_revisit}</td>
                    <td>{(int) $yearReport.todaymail_application}</td>
                    <td>{$yearReport.todaymail_cancel}</td>
                    <td>{$yearReport.todaymail_change}</td>
                    <td>{$yearReport.todaymail_agreement}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>{(int) ($yearReport.yearmail_mail + $yearReport.yearmail_tel)}</td>
                    <td>{(int)($yearReport.yearmail_tel_status + $yearReport.yearmail_mail_status)}</td>
                    <td>{(int)$yearReport.yearmail_shop_sign + $yearReport.yearmail_local_sign}</td>
                    <td>{(int) $yearReport.yearmail_revisit}</td>
                    <td>{(int) $yearReport.yearmail_application}</td>
                    <td>{$yearReport.yearmail_cancel}</td>
                    <td>{$yearReport.yearmail_change}</td>
                    <td>{$yearReport.yearmail_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        2
                    </td>
                    <td rowspan="2">
                        Internet (phone)
                    </td>
                    <td>Today</td>
                    <td>{(int) ($yearReport.todayphone_mail + $yearReport.todayphone_tel)}</td>
                    <td>{(int)($yearReport.todayphone_tel_status + $yearReport.todayphone_mail_status)}</td>
                    <td>{(int)$yearReport.todayphone_shop_sign + $yearReport.todayphone_local_sign}</td>
                    <td>{(int) $yearReport.todayphone_revisit}</td>
                    <td>{(int) $yearReport.todayphone_application}</td>
                    <td>{$yearReport.todayphone_cancel}</td>
                    <td>{$yearReport.todayphone_change}</td>
                    <td>{$yearReport.todayphone_agreement}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>{(int) ($yearReport.yearphone_mail + $yearReport.yearphone_tel)}</td>
                    <td>{(int)($yearReport.yearphone_tel_status + $yearReport.yearphone_mail_status)}</td>
                    <td>{(int)$yearReport.yearphone_shop_sign + $yearReport.yearphone_local_sign}</td>
                    <td>{(int) $yearReport.yearphone_revisit}</td>
                    <td>{(int) $yearReport.yearphone_application}</td>
                    <td>{$yearReport.yearphone_cancel}</td>
                    <td>{$yearReport.yearphone_change}</td>
                    <td>{$yearReport.yearphone_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        3
                    </td>
                    <td rowspan="2">
                        (TEL, email) term building lease
                    </td>
                    <td>Today</td>
                    <td>{(int)($yearReport.todaydiscount_mail + $yearReport.todaydiscount_tel)}</td>
                    <td>{(int)($yearReport.todaydiscount_tel_status + $yearReport.todaydiscount_mail_status)}</td>
                    <td>{(int)$yearReport.todaydiscount_shop_sign + $yearReport.todaydiscount_local_sign}</td>
                    <td>{(int) $yearReport.todaydiscount_revisit}</td>
                    <td>{(int) $yearReport.todaydiscount_application}</td>
                    <td>{$yearReport.todaydiscount_cancel}</td>
                    <td>{$yearReport.todaydiscount_change}</td>
                    <td>{$yearReport.todaydiscount_agreement}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>{(int) ($yearReport.yeardiscount_mail + $yearReport.yeardiscount_tel)}</td>
                    <td>{(int)($yearReport.yeardiscount_tel_status + $yearReport.yeardiscount_mail_status)}</td>
                    <td>{(int)$yearReport.yeardiscount_shop_sign + $yearReport.yeardiscount_local_sign}</td>
                    <td>{(int) $yearReport.yeardiscount_revisit}</td>
                    <td>{(int) $yearReport.yeardiscount_application}</td>
                    <td>{$yearReport.yeardiscount_cancel}</td>
                    <td>{$yearReport.yeardiscount_change}</td>
                    <td>{$yearReport.yeardiscount_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        4
                    </td>
                    <td rowspan="2">
                        Local sign
                    </td>
                    <td>Today</td>
                    <td>{(int) ($yearReport.todaylocalsign_mail + $yearReport.todaylocalsign_tel)}</td>
                    <td>{(int)($yearReport.todaylocalsign_tel_status + $yearReport.todaylocalsign_mail_status)}</td>
                    <td>{(int)$yearReport.todaylocalsign_shop_sign + $yearReport.todaylocalsign_local_sign}</td>
                    <td>{(int) $yearReport.todaylocalsign_revisit}</td>
                    <td>{(int) $yearReport.todaylocalsign_application}</td>
                    <td>{$yearReport.todaylocalsign_cancel}</td>
                    <td>{$yearReport.todaylocalsign_change}</td>
                    <td>{$yearReport.todaylocalsign_agreement}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>{(int) ($yearReport.yearlocalsign_mail + $yearReport.yearlocalsign_tel) }</td>
                    <td>{(int)($yearReport.yearlocalsign_tel_status + $yearReport.yearlocalsign_mail_status)}</td>
                    <td>{(int)$yearReport.yearlocalsign_shop_sign + $yearReport.yearlocalsign_local_sign}</td>
                    <td>{(int) $yearReport.yearlocalsign_revisit}</td>
                    <td>{(int) $yearReport.yearlocalsign_application}</td>
                    <td>{$yearReport.yearlocalsign_cancel}</td>
                    <td>{$yearReport.yearlocalsign_change}</td>
                    <td>{$yearReport.yearlocalsign_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        5
                    </td>
                    <td rowspan="2">
                        Introduction
                    </td>
                    <td>Today</td>
                    <td>{(int) ($yearReport.todayintroduction_mail + $yearReport.todayintroduction_tel)}</td>
                    <td>{(int)($yearReport.todayintroduction_tel_status + $yearReport.todayintroduction_mail_status)}</td>
                    <td>{(int)$yearReport.todayintroduction_shop_sign + $yearReport.todayintroduction_local_sign}</td>
                    <td>{(int) $yearReport.todayintroduction_revisit}</td>
                    <td>{(int) $yearReport.todayintroduction_application}</td>
                    <td>{$yearReport.todayintroduction_cancel}</td>
                    <td>{$yearReport.todayintroduction_change}</td>
                    <td>{$yearReport.todayintroduction_agreement}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>{(int) ($yearReport.yearintroduction_mail + $yearReport.yearintroduction_tel)}</td>
                    <td>{(int)($yearReport.yearintroduction_tel_status + $yearReport.yearintroduction_mail_status)}</td>
                    <td>{(int)$yearReport.yearintroduction_shop_sign + $yearReport.yearphone_local_sign}</td>
                    <td>{(int) $yearReport.yearintroduction_revisit}</td>
                    <td>{(int) $yearReport.yearintroduction_application}</td>
                    <td>{$yearReport.yearintroduction_cancel}</td>
                    <td>{$yearReport.yearintroduction_change}</td>
                    <td>{$yearReport.yearintroduction_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        6
                    </td>
                    <td rowspan="2">
                        Shop sign
                    </td>
                    <td>Today</td>
                    <td>{(int) ($yearReport.todayshopsign_mail + $yearReport.todayshopsign_tel)}</td>
                    <td>{(int)($yearReport.todayshopsign_tel_status + $yearReport.todayshopsign_mail_status)}</td>
                    <td>{(int)$yearReport.todayshopsign_shop_sign + $yearReport.todayshopsign_local_sign}</td>
                    <td>{(int) $yearReport.todayshopsign_revisit}</td>
                    <td>{(int) $yearReport.todayshopsign_application}</td>
                    <td>{$yearReport.todayshopsign_cancel}</td>
                    <td>{$yearReport.todayshopsign_change}</td>
                    <td>{$yearReport.todayshopsign_agreement}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>{(int) ($yearReport.yearshopsign_mail + $yearReport.yearshopsign_tel)}</td>
                    <td>{(int)($yearReport.yearshopsign_tel_status + $yearReport.yearshopsign_mail_status)}</td>
                    <td>{(int)$yearReport.yearshopsign_shop_sign + $yearReport.yearshopsign_local_sign}</td>
                    <td>{(int) $yearReport.yearshopsign_revisit}</td>
                    <td>{(int) $yearReport.yearshopsign_application}</td>
                    <td>{$yearReport.yearshopsign_cancel}</td>
                    <td>{$yearReport.yearshopsign_change}</td>
                    <td>{$yearReport.yearshopsign_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        7
                    </td>
                    <td rowspan="2">
                        Local Flyers
                    </td>
                    <td>Today</td>
                    <td>{(int)($yearReport.todayflyer_mail + $yearReport.todayflyer_tel)}</td>
                    <td>{(int)($yearReport.todayflyer_tel_status + $yearReport.todayflyer_mail_status)}</td>
                    <td>{(int)$yearReport.todayflyer_shop_sign + $yearReport.todayflyer_local_sign}</td>
                    <td>{(int) $yearReport.todayflyer_revisit}</td>
                    <td>{(int) $yearReport.todayflyer_application}</td>
                    <td>{$yearReport.todayflyer_cancel}</td>
                    <td>{$yearReport.todayflyer_change}</td>
                    <td>{$yearReport.todayflyer_agreement}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>{(int) ($yearReport.yearflyer_mail + $yearReport.yearflyer_tel)}</td>
                    <td>{(int)($yearReport.yearflyer_tel_status + $yearReport.yearflyer_mail_status)}</td>
                    <td>{(int)$yearReport.yearflyer_shop_sign + $yearReport.yearflyer_local_sign}</td>
                    <td>{(int) $yearReport.yearflyer_revisit}</td>
                    <td>{(int) $yearReport.yearflyer_application}</td>
                    <td>{$yearReport.yearflyer_cancel}</td>
                    <td>{$yearReport.yearflyer_change}</td>
                    <td>{$yearReport.yearflyer_agreement}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        8
                    </td>
                    <td rowspan="2">
                        Line
                    </td>
                    <td>Today</td>
                    <td>{(int) ($yearReport.todayline_mail + $yearReport.todayline_tel)}</td>
                    <td>{(int)($yearReport.todayline_tel_status + $yearReport.todayline_mail_status)}</td>
                    <td>{(int)$yearReport.todayline_shop_sign + $yearReport.todayline_local_sign}</td>
                    <td>{(int) $yearReport.todayline_revisit}</td>
                    <td>{(int) $yearReport.todayline_application}</td>
                    <td>{$yearReport.todayline_cancel}</td>
                    <td>{$yearReport.todayline_change}</td>
                    <td>{$yearReport.todayline_agreement}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>{(int) ($yearReport.yearline_mail + $yearReport.yearline_tel)}</td>
                    <td>{(int)($yearReport.yearline_tel_status + $yearReport.yearline_mail_status)}</td>
                    <td>{(int)$yearReport.yearline_shop_sign + $yearReport.yearline_local_sign}</td>
                    <td>{(int) $yearReport.yearline_revisit}</td>
                    <td>{(int) $yearReport.yearline_application}</td>
                    <td>{$yearReport.yearline_cancel}</td>
                    <td>{$yearReport.yearline_change}</td>
                    <td>{$yearReport.yearline_agreement}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="agent-content no-wrap">
        <div class="last-year-title">
            <h3>Detail from WEBS</h3>
        </div>
        <table>
            <tr>
                <th></th>
                <th>Company</th>
                <th>Total</th>
                <th>Feedback</th>
                <th>Track record</th>
                <th>Signboard</th>
                <th>Revisit</th>
                <th>Application</th>
                <th>Cancel</th>
                <th>自社</th>
                <th>Ledger</th>
            </tr>
            <tbody>
                {foreach from=$report->getAllSource() key=k item=company}
                {$com_info = $report->getSourceInfo($company.id,$date)}
                <tr>
                    <td rowspan="2">{$k + 1}</td>
                    <td rowspan="2">{$company.source_name}</td>
                    <td>Today</td>
                    <td>{(int)$com_info.today_tel + $com_info.today_mail}</td>
                    <td>{(int)($com_info.today_tel_status + $com_info.today_mail_status)}</td>
                    <td>{(int)$com_info.today_shop_sign + $com_info.today_local_sign}</td>
                    <td>{(int) $com_info.today_revisit}</td>
                    <td>{(int) $com_info.today_application}</td>
                    <td>{$com_info.today_cancel}</td>
                    <td>{$com_info.today_change}</td>
                    <td>{$com_info.today_agreement}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>{(int) $com_info.month_mail + $com_info.month_tel}</td>
                    <td>{(int)($com_info.month_tel_status + $com_info.month_mail_status)}</td>
                    <td>{(int)$com_info.month_shop_sign + $com_info.month_local_sign}</td>
                    <td>{(int) $com_info.month_revisit}</td>
                    <td>{(int) $com_info.month_application}</td>
                    <td>{$com_info.month_cancel}</td>
                    <td>{$com_info.month_change}</td>
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
                    ['name','Actually','Target'],
                {/literal}
                        {foreach from=$agents key=k item=agent}
                            {literal}['{/literal}{$agent.agent_name}{literal}',{/literal}{$report->getAgentCostOfMonth($agent.id,$date)}{literal},{/literal}{$agent.target}{literal}],{/literal}
                    {/foreach}
                    {literal}
                    ]);

                            var options = {
                                title: 'Agent Performance',
                                vAxis: {title: 'month', titleTextStyle: {color: 'red'}}
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
                birthday('birthday');
                $('#back').click(function() {
                    window.location.href = "manage_account.php";
                });
            });
    </script>
{/literal}

{include file="footer.tpl"}
