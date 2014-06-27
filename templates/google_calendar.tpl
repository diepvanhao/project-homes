
{literal}
    <script>

        $(document).ready(function() {
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
                    selectable: true,
                    selectHelper: true,
                    select: function(start, end, allDay) {
                        var title = prompt('Event Title:');
                        var url = prompt('Type Event url, if exits:');
                        if (title) {
                            var start = date.getTime();
                            var end = date.getTime();
                            $.ajax({
                                url: 'http://localhost/fullcalendar/add_events.php',
                                data: 'title=' + title + '&start=' + start + '&end=' + end + '&url=' + url,
                                type: "POST",
                                success: function(json) {
                                    alert('Added Successfully');
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
            $('.ui-icon-circle-triangle-e').click(function() {
                window.setInterval(function() {
                    $('.fc-event').each(function() {
                        $(this).css('background-color', 'green');
                    });
                }, 30);

            });
            //menu events click
            //click li
            $('ul li').click(function() {
                if ($(this).find('input').is(':checked'))
                    $(this).find('input').prop('checked', false);
                else
                    $(this).find('input').prop('checked', true);

                //get menu checked
                var event = new Array();
                if ($('#signature_day').is(":checked"))
                    event.push('Signature date');
                if ($('#handover_day').is(":checked"))
                    event.push('Handover day');
                if ($('#payment_day').is(":checked"))
                    event.push('Payment day');
                if ($('#appointment_day').is(":checked"))
                    event.push('Appointment day');
                if ($('#period').is(":checked"))
                    event.push('Period time');
                if ($('#birthday').is(":checked"))
                    event.push('Birthday');
                for (var i = 0; i < event.length; i++) {
                    $('.fc-event').each(function() {
                        if ($(this).find('.fc-event-title').html() == event[i]) {
                            var rString = randomString(6, '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
                            $(this).css('display', '');
                            $(this).css('background-color', rString);
                        }
                    });
                }
            });
        });
function randomString(length, chars) {
    var result = '#';
    for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
    return result;
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
            width: 70%;
            margin: 40px auto;
            float: left;
            padding-left: 10px;
        }
        #sidebar{
            float: left;
            width: 20%;
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
    </div>
    <div id='calendar'>

    </div>


</div>