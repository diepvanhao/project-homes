
{literal}
    <script>

        $(document).ready(function() {
            $('#create_new').click(function(){location.reload();});
            $('#signature_day').removeAttr('checked');
            $('#handover_day').removeAttr('checked');
            $('#payment_day').removeAttr('checked');
            $('#appointment_day').removeAttr('checked');
            $('#holiday').removeAttr('checked');
            $('#period').removeAttr('checked');
            $('#birthday').removeAttr('checked');
            
                //location.reload()        
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            var currentLangCode = 'en';

            // build the language selector's options
            $.each($.fullCalendar.langs, function(langCode) {
                $('#lang-selector').append(
                        $('<option/>')
                        .attr('value', langCode)
                        .prop('selected', langCode == currentLangCode)
                        .text(langCode)
                        );
            });

            // rerender the calendar when the selected option changes
            $('#lang-selector').on('change', function() {
                if (this.value) {
                    currentLangCode = this.value;
                    $('#calendar').fullCalendar('destroy');
                    renderCalendar();
                }
            });


            function renderCalendar() {
                if ($('#create_new').is(":checked")) {
                    var flag = true;
                } else {
                    var flag = false;
                }
                $('#calendar').fullCalendar({
                    theme: true,
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    defaultDate: new Date(),
                    lang: currentLangCode,
                    buttonIcons: false, // show the prev/next text
                    //weekNumbers: true,
                    selectable: flag,
                    selectHelper: true,
                    select: function(start, end, allDay) {
                        var title = prompt('Event Title:');
                        var url = prompt('Type Event url, if exits:');
                        if (title) {
                            //replace timze zone                             
                            var event_start = new Date(start);
                            var start_year = event_start.getFullYear();
                            var start_month = event_start.getMonth() + 1;
                            start_month = start_month < 10 ? "0" + start_month : start_month;

                            var start_day = event_start.getDate();
                            start_day = start_day < 10 ? "0" + start_day : start_day;
                            var start_hour = event_start.getHours();
                            start_hour = start_hour < 10 ? "0" + start_hour : start_hour;
                            var start_minute = event_start.getMinutes();
                            start_minute = start_minute < 10 ? "0" + start_minute : start_minute;
                            var start_second = event_start.getSeconds();
                            start_second = start_second < 10 ? "0" + start_second : start_second;
                            start = start_year + "-" + start_month + "-" + start_day + "T" + start_hour + ':' + start_minute + ":" + start_second;

                            var event_end = new Date(end);
                            var end_year = event_end.getFullYear();
                            var end_month = event_end.getMonth() + 1;
                            end_month = end_month < 10 ? "0" + end_month : end_month;

                            var end_day = event_end.getDate();
                            end_day = end_day < 10 ? "0" + end_day : end_day;
                            var end_hour = event_end.getHours();
                            end_hour = end_hour < 10 ? "0" + end_hour : end_hour;
                            var end_minute = event_end.getMinutes();
                            end_minute = end_minute < 10 ? "0" + end_minute : end_minute;
                            var end_second = event_end.getSeconds();
                            end_second = end_second < 10 ? "0" + end_second : end_second;
                            end = end_year + "-" + end_month + "-" + end_day + "T" + end_hour + ':' + end_minute + ":" + end_second;

                            $.ajax({
                                url: {/literal}"{$url->url_base}create_event.php",{literal}
                                data: 'title=' + title + '&start=' + start + '&end=' + end + '&url=' + url,
                                type: "POST",
                                success: function(json) {
                                    if (json) {
                                        alert('Added Successfully');
                                        location.reload();
                                    } else {
                                        alert("Event existed");
                                    }
                                }
                            });
                        }
                    },
                    editable: true,
                    events: {/literal}{$events}{literal}
                });

            }

            renderCalendar();

            //init invisible events load        
            $('.fc-event').each(function() {
                $(this).css('display', 'none');
            });
            //forward button event click

            $('.fc-button').click(function() {
                window.setInterval(function() {
                    display();
                }, 30);
            });
            //menu events click
            //click li
            $('ul li').click(function() {
                if ($(this).find('input').is(':checked'))
                    $(this).find('input').prop('checked', false);
                else
                    $(this).find('input').prop('checked', true);
                display();
            });
        });
        function display() {


            //get menu checked
            var event = new Array();
            var color = new Array();
            if ($('#signature_day').is(":checked")) {
                event.push('Signature date');
                color.push('Green');
            }
            if ($('#handover_day').is(":checked")) {
                event.push('Handover day');
                color.push('Blue')
            }
            if ($('#payment_day').is(":checked")) {
                event.push('Payment day');
                color.push('#62BBE9')
            }
            if ($('#appointment_day').is(":checked")) {
                event.push('Appointment day');
                color.push('Brown');
            }
            if ($('#holiday').is(":checked")) {
                event.push('(holiday)');
                color.push('Brown');
            }
            if ($('#period').is(":checked")) {
                event.push('Period time');
                color.push('#19FA6C')
            }
            if ($('#birthday').is(":checked")) {
                event.push('Birthday');
                color.push('Red');
            }

            if (event.length > 0) {
                //reset
                $('.fc-event').each(function() {
                    $(this).css('display', 'none');
                });
                for (var i = 0; i < event.length; i++) {
                    $('.fc-event').each(function() {
                        if ($(this).find('.fc-event-title').html() == event[i]) {
                            $(this).css('display', '');
                            $(this).css('background-color', color[i]);

                        }
                        if ($(this).find('.fc-event-title').html().substr(0, 8) == event[i]) {
                            $(this).css('display', '');
                            $(this).css('background-color', "red");
                        }//alert($(this).find('.fc-event-title').html().slice(-9));

                        if ($(this).find('.fc-event-title').html().slice(-9) == event[i]) {
                            $(this).css('display', '');
                            $(this).css('background-color', "#3B5998");
                        }
                    });
                }
                //display none event don't check

            } else {
                //reset
                $('.fc-event').each(function() {
                    $(this).css('display', 'none');
                });
            }
        }

    </script>
    <style>

        body {
            margin: 0;
            padding: 0;
            font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
            font-size: 14px;
        }

        #top {
            background: #eee;
            border-bottom: 1px solid #ddd;
            padding: 0 10px;
            line-height: 40px;
            font-size: 12px;
        }
        #wrapper{
            width:1000px;
            margin: 20px auto 0;
        }
        #calendar {
            width: 79%;
            margin: 40px auto;
            float: left;
            padding-left: 10px;
        }
        #sidebar{
            float: left;
            width: 20%;
            height: 72%;
            margin: 40px auto;
            background-color: #E6E6E6;
        }

        #sidebar ul li{
            list-style: none;            
            padding: 10px 0px 10px 0px;

        }
        #sidebar ul li:hover{
            background-color: #ADADAD;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 15px;
            color: white;
            cursor: pointer;

        }
        #schedule_title{
            text-align: center;
            background-color: black;
            color: whitesmoke;
            font-weight: bold;
            height: 35px;
            line-height: 30px;
            font-size: 1.4em;
        }
    </style>
{/literal}

<!--<div id='top'>

    Language:
    <select id='lang-selector'></select>

</div>-->
<div id="wrapper">
    <div style="width: 100%;font-size: 1.8em;background-color: #F1F5FE; height: 83px;line-height: 80px;">
        <a href="{$url->url_base}"><span class="logo_colour"><img src="{$url->url_base}include/images/logo.png" alt="AMBITION" width=""height="82px;"/></span></a>
        <label style="margin-left: 300px;position: absolute;">Schedule Report</label>

    </div>
    <div id="sidebar">
        <div id="schedule_title">Private Schedule</div>
        <ul>
            <li><input type="checkbox" id="signature_day" name="signature_day"/><label for="signature_day">Signature day</label></li>
            <li><input type="checkbox" id="handover_day" name="handover_day"/><label for="handover_day">Handover day</label></li>
            <li><input type="checkbox" id="payment_day" name="payment_day"/><label for="payment_day">Payment day</label></li>
            <li><input type="checkbox" id="appointment_day" name="appointment_day"/><label for="appointment">Appointment day</label></li>
            <li><input type="checkbox" id="holiday" name="holiday"/><label for="holiday">Holiday</label></li>
            <li><input type="checkbox" id="period" name="period"/><label for="period">Period to</label></li>
            <li><input type="checkbox" id="birthday" name="birthday"/><label for="birthday">Birthday</label></li>
            <li><input type="checkbox" id="create_new" name="create_new"/><label for="create_new">Create new</label></li>   
        </ul>
        <div id="schedule_title">User Information</div>
        <ul>
            <li>Name: {$user->user_info.user_fname} {$user->user_info.user_lname}</li>
            <li>Email: {$user->user_info.user_email}</li>
            <li>Target: {$user->user_info.user_target}</li>
            <li>Position: {$user->user_info.user_position}</li>
            <li>Birthday: {$user->user_info.user_birthday}</li>
        </ul>
    </div>
    <div id='calendar'>

    </div>

    <div style="width: 100%;text-align: center;font-size: 1.8em;background-color: #F1F5FE; height: 55px;line-height: 55px;color: red;">

        <label><p>Copyright &copy; Ambition</p></label>
    </div>
</div>