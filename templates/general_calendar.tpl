{include file='header_global.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin-bottom: 2%;">Agent Schedule</div>
{literal}
    <style>
        #sidebar_container{
            display: none;
        }
        .content{
            width: 100%;
        }
        #calendar{
            float: left;
            width: 79%;
        }
        table {
            margin: 1px 0 30px;
        }
        #schedule_title{
            text-align: center;
            background-color: black;
            color: whitesmoke;
            font-weight: bold;
            height: 29px;
            line-height: 30px;
            font-size: 1.4em;
        }
        #wrapper{
            width:1000px;
            margin: 10px auto 0;
        }
        #sidebar_schedule{
            display: "";
            float: left;
            width: 20%;            
            margin: 2px auto;
            margin-right: 10px;
            margin-bottom: 30px;            
            background-color: #E6E6E6;
        }
        #sidebar_schedule ul li{
            list-style: none;
            padding: 10px 0px 10px 0px;
            background: none;   
            font-size: 1.2em;
        }
        #sidebar_schedule ul li label{
            padding-left: 10px;

        }
        #sidebar_schedule ul li:hover{
            background-color: #ADADAD;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 15px;
            color: white;
            cursor: pointer;

        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            birthday('date_from');
            birthday('date_to');
            birthday('expire_from');
            birthday('expire_to');
            $('#sidebar_schedule ul li input[type=checkbox]').click(function() {
                //handling filter
                if ($('#signature_day').is(':checked'))
                    var signature_day = 1;
                else
                    var signature_day = 0;
                if ($('#handover_day').is(':checked'))
                    var handover_day = 1;
                else
                    var handover_day = 0;
                if ($('#payment_day').is(':checked'))
                    var payment_day = 1;
                else
                    var payment_day = 0;
                if ($('#appointment_day').is(':checked'))
                    var appointment_day = 1;
                else
                    var appointment_day = 0;
                if ($('#period').is(':checked'))
                    var period = 1;
                else
                    var period = 0;
                if ($('#birthday').is(':checked'))
                    var birthday = 1;
                else
                    var birthday = 0;
                if ($('#all_agent').is(':checked'))
                    var all_agent = 1;
                else
                    var all_agent = 0;

                var agent_id = $('#agent_id').val();
                var position = $('#position').val();
                var assign_id = $('#assign_id').val();
                var date_from = $('#date_from').val();
                var date_to = $('#date_to').val();
                var expire_from = $('#expire_from').val();
                var expire_to = $('#expire_to').val();
                $.post("include/function_ajax.php", {signature_day: signature_day, handover_day: handover_day, payment_day: payment_day, appointment_day: appointment_day, period: period, birthday: birthday,
                    all_agent: all_agent, agent_id: agent_id, position: position, assign_id: assign_id, date_from: date_from, date_to: date_to, expire_from: expire_from, expire_to: expire_to,
                    action: 'schedule', task: 'general'},
                function(result) {
                    if (result) {
                        $('#calendar').find('tbody').empty();
                        $('#calendar').find('tbody').html(result);
                    } else {
                        $('#calendar').find('tbody').empty();
                    }
                });
            });
            $('#sidebar_schedule ul li select').change(function() {
                //handling filter
                if ($('#signature_day').is(':checked'))
                    var signature_day = 1;
                else
                    var signature_day = 0;
                if ($('#handover_day').is(':checked'))
                    var handover_day = 1;
                else
                    var handover_day = 0;
                if ($('#payment_day').is(':checked'))
                    var payment_day = 1;
                else
                    var payment_day = 0;
                if ($('#appointment_day').is(':checked'))
                    var appointment_day = 1;
                else
                    var appointment_day = 0;
                if ($('#period').is(':checked'))
                    var period = 1;
                else
                    var period = 0;
                if ($('#birthday').is(':checked'))
                    var birthday = 1;
                else
                    var birthday = 0;
                if ($('#all_agent').is(':checked'))
                    var all_agent = 1;
                else
                    var all_agent = 0;

                var agent_id = $('#agent_id').val();
                var position = $('#position').val();
                var assign_id = $('#assign_id').val();
                var date_from = $('#date_from').val();
                var date_to = $('#date_to').val();
                var expire_from = $('#expire_from').val();
                var expire_to = $('#expire_to').val();
                $.post("include/function_ajax.php", {signature_day: signature_day, handover_day: handover_day, payment_day: payment_day, appointment_day: appointment_day, period: period, birthday: birthday,
                    all_agent: all_agent, agent_id: agent_id, position: position, assign_id: assign_id, date_from: date_from, date_to: date_to, expire_from: expire_from, expire_to: expire_to,
                    action: 'schedule', task: 'general'},
                function(result) {
                    if (result) {
                        $('#calendar').find('tbody').empty();
                        $('#calendar').find('tbody').html(result);
                    } else {
                        $('#calendar').find('tbody').empty();
                    }
                });
            });

        });
        function selectDate() {
            //handling filter
            if ($('#signature_day').is(':checked'))
                var signature_day = 1;
            else
                var signature_day = 0;
            if ($('#handover_day').is(':checked'))
                var handover_day = 1;
            else
                var handover_day = 0;
            if ($('#payment_day').is(':checked'))
                var payment_day = 1;
            else
                var payment_day = 0;
            if ($('#appointment_day').is(':checked'))
                var appointment_day = 1;
            else
                var appointment_day = 0;
            if ($('#period').is(':checked'))
                var period = 1;
            else
                var period = 0;
            if ($('#birthday').is(':checked'))
                var birthday = 1;
            else
                var birthday = 0;
            if ($('#all_agent').is(':checked'))
                var all_agent = 1;
            else
                var all_agent = 0;

            var agent_id = $('#agent_id').val();
            var position = $('#position').val();
            var assign_id = $('#assign_id').val();
            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();
            var expire_from = $('#expire_from').val();
            var expire_to = $('#expire_to').val();
            $.post("include/function_ajax.php", {signature_day: signature_day, handover_day: handover_day, payment_day: payment_day, appointment_day: appointment_day, period: period, birthday: birthday,
                all_agent: all_agent, agent_id: agent_id, position: position, assign_id: assign_id, date_from: date_from, date_to: date_to, expire_from: expire_from, expire_to: expire_to,
                action: 'schedule', task: 'general'},
            function(result) {
                if (result) {
                    $('#calendar').find('tbody').empty();
                    $('#calendar').find('tbody').html(result);
                } else {
                    $('#calendar').find('tbody').empty();
                }
            });
        }
    </script>
{/literal}
<div id="wrapper">   
    <div id="sidebar_schedule">
        <div id="schedule_title">Plan</div>
        <ul>
            <li><input type="checkbox" id="signature_day" name="signature_day"/><label for="signature_day">Contract date</label></li>
            <li><input type="checkbox" id="handover_day" name="handover_day"/><label for="handover_day">Key transition date</label></li>
            <li><input type="checkbox" id="payment_day" name="payment_day"/><label for="payment_day">Deposit date</label></li>
            <li><input type="checkbox" id="appointment_day" name="appointment_day"/><label for="appointment_day">Appointment date</label></li>
            <!--<li><input type="checkbox" id="other" name="other"/><label for="other">Other</label></li>-->
            <li><input type="checkbox" id="period" name="period"/><label for="period">Contract period</label></li>
            <li><input type="checkbox" id="birthday" name="birthday"/><label for="birthday">Birthday</label></li>
            <!--<li><input type="checkbox" id="create_new" name="create_new"/><label for="create_new">Create new</label></li>   -->
        </ul>
        <div id="schedule_title">Groups</div>
        <ul>
            <li style="display:none;"><input type="checkbox" id="all_agent" name="all_agent"/><label for="all_agent">All agent</label></li>
            <li><lable>Agent:
                <select id="agent_id"name="agent_id" style="width:98%;height: 25px;">
                    <option value=""></option>
                    {foreach from=$agents item=agent}
                        <option value="{$agent.id}">{$agent.agent_name}</option>
                    {/foreach}
                </select></lable>
            </li>
            <li>
                <label>Position:
                    <select id="position" name="position" style="width:98%;height: 25px; ">
                        <option value=""></option>
                        <option value="2">super manager</option>
                        <option value="3">manager</option>
                        <option value="4">staff</option>
                    </select>
                </label>
            </li>
            <li><label>Responsible:
                    <select id="assign_id" name="assign_id" style="width:98%;height: 25px;">
                        <option value=""></option>
                        {foreach from=$staffs item=staff}
                            <option value="{$staff.id}">{$staff.user_lname} {$staff.user_fname} </option>
                        {/foreach}
                    </select>

                </label>
            </li>
            <li>
                <label>Date from:<input type="text" id="date_from" name="date_from" style="width:98%;height: 25px;" onchange="selectDate();"/></label>
                <label>Date to:<input type="text" id="date_to" name="date_to" style="width:98%;height: 25px;" onchange="selectDate();"/></label>
            </li>
            <li>
                <label>Expire from:<input type="text" title="For period" id="expire_from" name="expire_from" style="width:98%;height: 25px;" onchange="selectDate();"/></label>
                <label>Expire to:<input type="text" title="For period" id="expire_to" name="expire_to" style="width:98%;height: 25px;" onchange="selectDate();"/></label>
            </li>
        </ul>
    </div>   
    <div id="calendar">
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>N0</th>
                    <th>Agent</th>
                    <th>Position</th>
                    <th>Responsible</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Time</th>
                    <th>Title</th>
                    <th>Customer</th>
                    <th>Link</th>                    
                </tr>
            </thead>
            <tbody>    

                {foreach from=$events key=k item=event}
                    {assign var="detail" value="assign&{$event.id}"}
                    <tr>
                        <td>{$k+1}</td>
                        <td>{$event.agent}</td>
                        <td>{$event.position}</td>
                        <td>{$event.assigned}</td>
                        <td>{$event.start}</td>                            
                        <td>{$event.end}</td>
                        <td>{$event.time}</td>                           
                        <td>{$event.title}</td>
                        <td>{$event.customer}</td>
                        <td><a href="order_detail.php?url={$detail|base64_encode}">Detail</a></td>
                    </tr>
                {/foreach}

            </tbody>                        
        </table>
    </div>
</div>
<div style="clear: both"></div>
{include file='footer.tpl'}