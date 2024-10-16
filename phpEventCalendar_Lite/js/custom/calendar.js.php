<!--
|   ====================================================================================================================
|   JS FOR EVENTS
|   ====================================================================================================================
-->
<script type="text/javascript">
var reminder2Obj;
var reminder3Obj;
var shareForm;
var calEditForm;
$(document).ready(function () {
    /*
     $.bootstrapGrowl('<div style="text-align: left">This demo is a working preview of the full calendar control that is currently still under heavy development.' +
     'Check back here from time to time to see the latest changes and updates.' +
     '<b>We appreciate if you can <a href="#" style="color:orange;" data-uv-trigger>send us your feedback</a> so we can continue to improve our product. Thank you!</b></div>',{
     type: 'info',
     width: 550,
     //top_offset: 300,
     //ele: '#feedback_link',
     align: 'right'
     });
     */

    //=====Enable Bootstrap Select
    $('.selectpicker').selectpicker();

    //=====Format Buttons on load
    $('.fc-button-basicDay').removeClass('fc-corner-right');
    $('.fc-button-basicWeek').removeClass('fc-corner-left fc-corner-right');
    $('.fc-button-month').removeClass('fc-corner-left fc-corner-right');
    //$('.fc-button-agendaDay').removeClass('fc-corner-left fc-corner-right');
    $('.fc-button-agendaDay').removeClass('fc-corner-right');
    $('.fc-button-agendaWeek').removeClass('fc-corner-left');
    //===== Formatting Buttons Ends

    function timeFrom12To124Hours(time) {
        var hours = Number(time.match(/^(\d+)/)[1]);
        var minutes = Number(time.match(/:(\d+)/)[1]);
        var AMPM = time.match(/\s(.*)$/)[1];
        if (AMPM == "PM" && hours < 12) hours = hours + 12;
        if (AMPM == "AM" && hours == 12) hours = hours - 12;
        var sHours = hours.toString();
        var sMinutes = minutes.toString();
        if (hours < 10) sHours = "0" + sHours;
        if (minutes < 10) sMinutes = "0" + sMinutes;
        // alert(sHours + ":" + sMinutes)
        return sHours + ":" + sMinutes;
    }

    //==== add guest emails here
    var serial = 1

    $('#add-guest').click(function () {
        //=== count existing guest emails
        serial = $('.guest_email').length + 1;
        var guest = $('#guest').val();
        if (isValidEmailAddress(guest) == false || guest == '') {
            $.bootstrapGrowl("<div style='text-align: left'>Invalid Email</div>", {
                type: 'warning',
                width: 450
            });
            return false;
        }
        var guestView = "<div id='guest_" + serial + "'> <input class='form-control guest-view guest_email reminder_add_guest_in' id='guest_list_" + serial + "' name='guests[]' value='" + guest + "'><button class='close_guest' aria-hidden='true' data-dismiss='guest' type='button'>×</button></div>";
        $('#guest-list').append(guestView);
        $('.close_guest').click(function () {
            $(this).parent().remove();
        });
        $('#guest').val(null);
        serial++;
    });

    $('#guest').keyup(function (jsEventObj) {
        if (jsEventObj.keyCode == 13) {
            $('#add-guest').click();
        }
    });


    //==== reminder panel setup
    reminder2Obj = $('#reminder2').detach();
    reminder3Obj = $('#reminder3').detach();

    //==== add reminders

    var next_reminder_count;
    $('#add_reminder').click(function () {
        //=== count existing reminders
        next_reminder_count = $('.reminder-group').length + 1;
        if (next_reminder_count == 2) {
            $('.reminder-group').each(function () {
                if (this.id == 'reminder3') {
                    reminder2Obj.appendTo("#reminder-holder");
                }
                else if (this.id == 'reminder2') {
                    reminder3Obj.appendTo("#reminder-holder");
                }
                else
                    reminder2Obj.appendTo("#reminder-holder");
            });
        }
        if (next_reminder_count == 3) {
            $('.reminder-group').each(function () {
                if (this.id == 'reminder3') {
                    reminder2Obj.appendTo("#reminder-holder");
                }
                else if (this.id == 'reminder2') {
                    reminder3Obj.appendTo("#reminder-holder");
                }
                else
                    reminder3Obj.appendTo("#reminder-holder");

            });
        }

    });


    //================= Search ===================

    $('#search-btn').click(function () {

        var searchKey = $('#search-event-input').val();
        if (searchKey == '') {
            //=== show a warning message
            $.bootstrapGrowl("<div style='text-align: left'>Invalid or Empty Search Keyword</div>", {
                type: 'warning',
                width: 450
            });

            return false;
        }
        searchEventsBasedOnKeyword(searchKey, this);
    });

    $('#search-event-input').keyup(function (jsEventObj) {
        if (jsEventObj.keyCode == 13) {
            $('#search-btn').click();
        }
    });

    function searchEventsBasedOnKeyword(searchKey, laddaObj) {
        //=== ladda button animation starts
        var l = Ladda.create(laddaObj);
        l.start();

        //===Reusing calendar search code
        $.post("<?php echo ABS_PATH?>/server/ajax/events_manager.php",
            { searchKey: searchKey, action: 'LOAD_EVENTS_BASED_ON_SEARCH_KEY'},
            function (eventJSON) {
            }, "json")
            .always(function (eventJSON) { //==== no event found?
                if (eventJSON.title == 'NO___EVENT___FOUND') {
                    //=== show a warning message
                    $.bootstrapGrowl("<div style='text-align: left'>No match found</div>", {
                        type: 'warning',
                        width: 280
                    });
                }
                else { //=== results found?
                    $('#calendar').fullCalendar('changeView', 'list');
                    $('#calendar').fullCalendar('removeEvents');
                    $('#calendar').fullCalendar('addEventSource', eventJSON);
                }
                //==== ladda button animation stops
                l.stop();
            }, "json");
    }

    //====validating
    function validateEventCreateForm() {
        var errMsg = '';
        var errMsgTitle = '';

        var title = $('#title').val();
        if (title == '') errMsgTitle = "Title is required!<br/>";

        var start = moment($('#start-date').val());
        var end = moment($('#end-date').val());

        var startDate = start.format('X');
        var endDate = end.format('X');
        //alert(startDate);
        //alert(endDate);
        var allDay = $('#allDay').prop('checked');
        var startTime = parseInt(timeFrom12To124Hours($('#start-time').val()).replace(/:/, ''));
        var endTime = parseInt(timeFrom12To124Hours($('#end-time').val()).replace(/:/, ''));

        if (startDate > endDate && allDay == false) errMsg = errMsg + "End Date must need to set after Start Date!<br />";
        else if ((startTime > endTime) && (startDate >= endDate) && (allDay != 'on' || allDay != true)) errMsg = errMsg + "Sorry, you can't create an event that ends before it starts!<br />";
        if ((startTime == 2300 && endTime == 0) || (startTime == 2330 && endTime == 30) || (startTime == 2300 && endTime == 2330)) errMsg = '';
        if (startTime != 0 && endTime == 0) errMsg = '';
        if (startTime == endTime && allDay == false) errMsg = errMsg + "Sorry, start and end times can't be equal<br />";

        //=== if allDay is set to true, then empty the the time for last date
        if (allDay == true) $('#end-time').val('');

        return errMsg + errMsgTitle;
    }

    $('.repeat_on_day').click(function () {
        var tid = this.id;
        var tcheck = this.checked;

        if (tid == 'repeat_on_sun' && tcheck == false) repeat_on_sun = 0;
        if (tid == 'repeat_on_sun' && tcheck == true) repeat_on_sun = 1;

        if (tid == 'repeat_on_mon' && tcheck == false) repeat_on_mon = 0;
        if (tid == 'repeat_on_mon' && tcheck == true) repeat_on_mon = 1;

        if (tid == 'repeat_on_tue' && tcheck == false) repeat_on_tue = 0;
        if (tid == 'repeat_on_tue' && tcheck == true) repeat_on_tue = 1;

        if (tid == 'repeat_on_wed' && tcheck == false) repeat_on_wed = 0;
        if (tid == 'repeat_on_wed' && tcheck == true) repeat_on_wed = 1;

        if (tid == 'repeat_on_thu' && tcheck == false) repeat_on_thu = 0;
        if (tid == 'repeat_on_thu' && tcheck == true) repeat_on_thu = 1;

        if (tid == 'repeat_on_fri' && tcheck == false) repeat_on_fri = 0;
        if (tid == 'repeat_on_fri' && tcheck == true) repeat_on_fri = 1;

        if (tid == 'repeat_on_sat' && tcheck == false) repeat_on_sat = 0;
        if (tid == 'repeat_on_sat' && tcheck == true) repeat_on_sat = 1;

    });

    $('#myModal').on('hide.bs.modal', function (e) {
        $('#reminder_type_1').val('email');
        $('#reminder_time_1').val('1');
        $('#reminder_time_unit_1').val('minute');

        $('#reminder_type_2').val('email');
        $('#reminder_time_2').val('1');
        $('#reminder_time_unit_2').val('minute');

        $('#reminder_type_3').val('email');
        $('#reminder_time_3').val('1');
        $('#reminder_time_unit_3').val('minute');

        hideReminder2();
        hideReminder3();
        $('#guest-list div').remove();
        serial = 1; //reset event reminder guest serial
    })

    $('#create-event').click(function () {
        //==== start JS validating
        var errMsg = '';
        errMsg = validateEventCreateForm();

        //==== display error message if there is any error
        if (errMsg != '') {
            $.bootstrapGrowl("<div style='text-align: left'>" + errMsg + "</div>", {
                type: 'warning',
                width: 450
            });

            return false;
        }

        //==== check repeat week days are checked at least one, if none is checked, then check one by default
        var repeat_type;
        if (repeatChecked == true) {
            var repeatType = $('#repeat_type').val();

            //==== if repeat type is weekly
            if (repeatType == 'weekly') {
                //==== if no repeat day is checked
                /*
                 alert(repeat_on_sun);
                 alert(repeat_on_mon);
                 alert(repeat_on_tue);
                 alert(repeat_on_wed);
                 alert(repeat_on_thu);
                 alert(repeat_on_fri);
                 alert(repeat_on_sat);
                 */


                if (repeat_on_sun == 0 && repeat_on_mon == 0 && repeat_on_tue == 0 && repeat_on_wed == 0 && repeat_on_thu == 0 && repeat_on_fri == 0 && repeat_on_sat == 0) {
                    setRepeatOptionsForDays($('#start-date').val());
                }
            }
        }
        else {
            $('#repeat_type').val('none');
        }

        var formData = $('#eventForm').serializeArray();

        //=== reset repeat check box
        repeatChecked = false;
        $('#eventForm fieldset').attr('disabled', 'disabled');

        var jqxhr = $.ajax({
            type: "POST",
            url: "<?php echo ABS_PATH?>/server/ajax/events_manager.php",
            data: formData,
            dataType: "json"
        })
            .done(function (eventJSON) {
                //===Clearing Reminder Settings Panel
                //                    $('#reminder_type_1').val('email');
                //                    $('#reminder_time_1').val('1');
                //                    $('#reminder_time_unit_1').val('minute');
                //
                //                    $('#reminder_type_2').val('email');
                //                    $('#reminder_time_2').val('1');
                //                    $('#reminder_time_unit_2').val('minute');
                //
                //                    $('#reminder_type_3').val('email');
                //                    $('#reminder_time_3').val('1');
                //                    $('#reminder_time_unit_3').val('minute');
                //
                //                    hideReminder2();
                //                    hideReminder3();
                //$('#guest-list div').remove();
                //serial = 1; //reset event reminder guest serial

                //=== Look for if the event is being saved into a different calendar
                if (eventJSON.title == 'NO_EVENT_FOUND_FOR_SELECTED_CALENDARS') {
                    $('#myModal').modal('hide');
                    $.bootstrapGrowl("<div style='text-align: left'>Event Created Successfully, although it will be shown when you select the respective calendar</div>", {
                        type: 'success',
                        width: 450
                    });
                    return;
                }

                //=== Check if this is an update
                var uid = $('#update-event').val();
                if (parseInt(uid) > 0) {
                    $('#calendar').fullCalendar('removeEvents', uid);

                    //===get current view
                    var view = $('#calendar').fullCalendar('getView');


                    //=== if it is a agenda/list view then reload the page immediately
                    if (view.name == 'list') {
                        location.reload();
                        return;
                    }
                    else { //=== wait for 2 seconds for other views
                        setTimeout(function () {
                            location.reload();
                        }, 1000)
                    }
                }

                //alert(eventJSON);
                ///$('#calendar').fullCalendar('addEventSource', eventJSON);

                setTimeout(function () {
                    location.reload();
                }, 1000);

                $('#myModal').modal('hide');

                if (parseInt(uid) > 0) {
                    $.bootstrapGrowl("Event Modified Successfully", {
                        type: 'success',
                        width: 320
                    });
                }
                else {
                    $.bootstrapGrowl("Event Created Successfully", {
                        type: 'success',
                        width: 320
                    });
                }
            })
            .fail(function (eventMsg) {
                //alert(eventMsg)
                $.bootstrapGrowl("Something went wrong, please try again later", {
                    type: 'danger',
                    width: 350
                });
            })
    });

    $('#start-date').datetimepicker({
        startDate: '<?php echo date("Y-m-d")?>',
        startView: 2,
        minView: 2,
        maxView: 2,
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    $('#start-time').focus(function () {
        $('#time-panel-start').show();
    });
    $('#start-time').click(function () {
        $('#time-panel-start').show();
    });

    $('#time-panel-start ul li').click(function () {
        var selVal = $(this).attr('data-value');
        $('#start-time').val(formatTimeStr(selVal));
        $('#time-panel-start').hide();
    });


    $('#end-date').datetimepicker({
        startDate: '<?php echo date("Y-m-d")?>',
        startView: 2,
        minView: 2,
        maxView: 2,
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    $('#end-time').focus(function () {
        $('#time-panel-end').show();
    });
    $('#end-time').click(function () {
        $('#time-panel-end').show();
    });
    $('body').focus(function () {
        setTimeout(function () {
            $('#time-panel-start').hide();
        }, 200)
        setTimeout(function () {
            $('#time-panel-end').hide();
        }, 200)
    });


    $('#time-panel-end ul li').click(function () {
        var selVal = $(this).attr('data-value');
        $('#end-time').val(formatTimeStr(selVal));
        $('#time-panel-end').hide();
    });

    $('#date-picker').datetimepicker({
        startView: 2,
        minView: 2,
        maxView: 2,
        autoclose: true
    }).on('changeDate', function (ev) {

        //alert(ev.date)
        var startMoment = moment(ev.date).subtract('days', 1);

        //====Move calendar to the selected date
        $('#calendar').fullCalendar('gotoDate', startMoment);
    });


    $('#backgroundColor-control').colorpicker().on('changeColor', function (ev) {
        bodyStyle.backgroundColor = ev.color.toHex();
    });
    $('#backgroundColor').click(function () {
        $('#backgroundColor-control').colorpicker('show');
    });

    $('#borderColor-control').colorpicker().on('changeColor', function (ev) {
        bodyStyle.backgroundColor = ev.color.toHex();
    });
    $('#borderColor').click(function () {
        $('#borderColor-control').colorpicker('show');
    });

    $('#textColor-control').colorpicker().on('changeColor', function (ev) {
        bodyStyle.backgroundColor = ev.color.toHex();
    });
    $('#textColor').click(function () {
        $('#textColor-control').colorpicker('show');
    });

    $('#create-new-event').click(function () {
        //==== show this panel if it is hidden
        $('#end-group').show();
        $('#remove-block').hide();

        var dt = new Date();

        var mm = dt.getMonth();
        var dd = dt.getDate();
        var yyyy = dt.getFullYear();

        var hours = dt.getHours();
        var minutes = dt.getMinutes();
        if (parseInt(mm) <= 9) mm = '0' + (parseInt(mm)+1);
        if (parseInt(dd) <= 9) dd = '0' + dd;
        if (minutes > 30) minutes = 30;
        else minutes = 0;
        var ehours = hours + 1;
        if (ehours >= 24) ehours = '0';
        var eminutes = minutes;
        if (parseInt(minutes) <= 9) minutes = '0' + minutes;
        if (parseInt(ehours) <= 9) ehours = '0' + ehours;
        if (parseInt(eminutes) <= 9) eminutes = '0' + eminutes;

        var curDate = yyyy + '-' + mm + '-' + dd + ' ' + hours + ':' + minutes;
        var curDateInput = yyyy + '-' + mm + '-' + dd;

        //===Selecting Multiple Calendar
        $('#eventForm fieldset').removeAttr('disabled');

        $('#myModal').modal({backdrop: 'static', keyboard: false});
        $('#myModalLabel').html('Create New Event');
        $('#myTab a:first').tab('show');
        $('#create-event').html('Create Event');
        $('#update-event').val('');

        //==== resetting fields
        document.getElementById('eventForm').reset();
        $('.checkbox-inline input, #allDay').removeAttr('checked');
        $('.repeat-box').hide();
        $('#hide-standard-settings').click();
        //$('.color-box').removeClass('color-box-selected');
        $('#backgroundColor').val('#3a87ad');
        $('#repeat_end_on').val('');
        $('#repeat_end_after').val('');
        $('#repeat_never').val('1');
        $('#ends-db-val').datetimepicker('remove');
        $('#ends-db-val').attr('readonly', 'readonly');

        //====Setting Date Fields
        $('#start-date').val(curDateInput);
        $('#end-date').val(curDateInput);
        $('#repeat_start_date').val(curDateInput);
        $('#start-time').val(hours + ':' + minutes);
        $('#end-time').val(ehours + ':' + eminutes);


        var jqxhr = $.ajax({
            type: 'POST',
            url: '<?php echo ABS_PATH?>/server/ajax/events_manager.php',
            data: {action: 'LOAD_SELECTED_CALENDAR_FROM_SESSION'},
            dataType: 'json'
        })
            .done(function (selCal) {
                //====setting up values
                $('.selectpicker').selectpicker('val', selCal);
            })
            .fail(function () {
            });

        var jqxhr = $.ajax({
            type: 'POST',
            url: '<?php echo ABS_PATH?>/server/ajax/events_manager.php',
            data: {action: 'LOAD_SELECTED_CALENDAR_COLOR'}
        })
            .done(function (selCalColor) {
                //====setting up values
                $('#backgroundColor').val(selCalColor);
                var selCalColorData = selCalColor.split('#');
                var colorID = 'cid_' + selCalColorData[1];
                $('#' + colorID).click();
            })
            .fail(function () {
            });

    });

    var repeat_on_sun = 0;
    var repeat_on_mon = 0;
    var repeat_on_tue = 0;
    var repeat_on_wed = 0;
    var repeat_on_thu = 0;
    var repeat_on_fri = 0;
    var repeat_on_sat = 0;
    var repeat_week_days_checked = false;

    function setRepeatOptionsForDays(stDate) {
        var stdUnix = new Date(stDate);
        var weekDay = stdUnix.getDay(stdUnix);

        repeat_week_days_checked = false;

        //==== reset the checkboxes
        if (repeat_on_sun == 1) $('#repeat_on_sun').click(function () {
            this.checked
        });
        if (repeat_on_mon == 1) $('#repeat_on_mon').click(function () {
            this.checked
        });
        if (repeat_on_tue == 1) $('#repeat_on_tue').click(function () {
            this.checked
        });
        if (repeat_on_wed == 1) $('#repeat_on_wed').click(function () {
            this.checked
        });
        if (repeat_on_thu == 1) $('#repeat_on_thu').click(function () {
            this.checked
        });
        if (repeat_on_fri == 1) $('#repeat_on_fri').click(function () {
            this.checked
        });
        if (repeat_on_sat == 1) $('#repeat_on_sat').click(function () {
            this.checked
        });

        repeat_on_sun = 0;
        repeat_on_mon = 0;
        repeat_on_tue = 0;
        repeat_on_wed = 0;
        repeat_on_thu = 0;
        repeat_on_fri = 0;
        repeat_on_sat = 0;

        //==== set repeat day checkboxes based on start date
        switch (weekDay) {
            case 0:
                $('#repeat_on_sun').click();
                repeat_on_sun = 1;
                repeat_week_days_checked = true;
                break;
            case 1:
                $('#repeat_on_mon').click();
                repeat_on_mon = 1;
                repeat_week_days_checked = true;
                break;
            case 2:
                $('#repeat_on_tue').click();
                repeat_on_tue = 1;
                repeat_week_days_checked = true;
                break;
            case 3:
                $('#repeat_on_wed').click();
                repeat_on_wed = 1;
                repeat_week_days_checked = true;
                break;
            case 4:
                $('#repeat_on_thu').click();
                repeat_on_thu = 1;
                repeat_week_days_checked = true;
                break;
            case 5:
                $('#repeat_on_fri').click();
                repeat_on_fri = 1;
                repeat_week_days_checked = true;
                break;
            case 6:
                $('#repeat_on_sat').click();
                repeat_on_sat = 1;
                repeat_week_days_checked = true;
                break;
        }

    }


    $('#start-date').change(function () {
        var thisDate = $(this).val();
        $('#end-date').val(thisDate);

        //==== set repeat options
        $('#repeat_start_date').val(thisDate);
        setRepeatOptionsForDays(thisDate);
    });

    $('#repeat_type').change(function () {
        var repeatType = $(this).val();
        var intervalLabel = 'weeks';
        $('#repeat_interval_group').show();
        $('#repeat_on_group').show();
        $('#repeat_by_group').hide();
        $('.repeat_by').removeAttr('checked');
        //$('#repeat_on_wed').removeAttr('checked');

        switch (repeatType) {
            case 'daily':
                $('#repeat_on_group').hide();
                intervalLabel = 'Days';
                break;
            case 'everyWeekDay':
                intervalLabel = '';
                $('#repeat_interval_group').hide();
                $('#repeat_on_group').hide();
                break;
            case 'everyMWFDay':
                intervalLabel = '';
                $('#repeat_interval_group').hide();
                $('#repeat_on_group').hide();
                break;
            case 'everyTTDay':
                intervalLabel = '';
                $('#repeat_interval_group').hide();
                $('#repeat_on_group').hide();
                break;
            case 'weekly':
                intervalLabel = 'Weeks';
                //$('#repeat_on_wed').attr('checked','checked');
                setRepeatOptionsForDays($('#start-date').val());
                break;
            case 'monthly':
                $('#repeat_on_group').hide();
                $('#repeat_by_group').show();
                $('#repeat_by_day_of_the_month').click();
                intervalLabel = 'Months';
                break;
            case 'yearly':
                intervalLabel = 'Years';
                $('#repeat_on_group').hide();
                break;
            case 'none':
            default :
                $('#repeat_on_group').hide();
                intervalLabel = 'Days';
                break;
        }
        $('#repeat_interval_label').html(intervalLabel);
    });

    $('#show-standard-settings').click(function () {
        $('.standard').fadeIn();
        $('.basic .show-link').hide();
        $('.standard').css('display', 'inline-block');
    });

    $('#hide-standard-settings').click(function () {
        $('.basic .show-link').fadeIn();
        $('.standard').hide();
        //$('.repeat-box').fadeOut();
        //$('.repeat-box').css('display','none');
        //$('#repeat').removeAttr('checked')
    });

    $('#show-reminder-settings').click(function () {
        $('.reminder').fadeIn();
        $('.basic-remind .show-link-remind').hide();
        $('.reminder').css('display', 'inline-block');
    });

    $('#hide-reminder-settings').click(function () {
        $('.basic-remind .show-link-remind').fadeIn();
        $('.reminder').hide();
    });

    var repeatChecked = false;
    $('#repeat').click(function () {
        if (this.checked) {
            repeatChecked = true;
            $('.repeat-box').fadeIn();
            $('.repeat-box').css('display', 'inline-table');
            $('#repeat_type').val('daily');
            $('#repeat_on_group').hide();
            //setRepeatOptionsForDays($('#start-date').val());
        }
        else {
            repeatChecked = false;
            $('.repeat-box').fadeOut();
            $('.repeat-box').css('display', 'none');
        }
        //$('#repeat_on_group').hide();
    });

    var endsParamSelected = 'Never';
    $('.ends-params').click(function () {
        var endsVal = $(this).attr('data-value');
        //==setting label
        $('#ends-status').html(endsVal);

        //===resetting
        $('#ends-after-label').css('display', 'none');
        $('#repeat_end_on').val('');
        $('#repeat_end_after').val('');
        $('#repeat_never').val('');
        $('#ends-db-val').val('');

        endsParamSelected = endsVal;
        switch (endsVal) {
            case 'On':
                $('#ends-db-val').datetimepicker({
                    startView: 2,
                    minView: 2,
                    maxView: 2,
                    autoclose: true,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd'
                });
                $('#ends-db-val').removeAttr('readonly');
                //$('#repeat_end_on').val('');
                break;
            case 'Never':
                $('#ends-db-val').datetimepicker('remove');
                $('#ends-db-val').attr('readonly', 'readonly');
                $('#repeat_never').val('1');
                break;
            case 'After':
                $('#ends-db-val').datetimepicker('remove');
                $('#ends-db-val').removeAttr('readonly');
                //$('#repeat_end_after').val('');
                $('#ends-after-label').css('display', 'inline-block');
                break;
        }
    });

    $('#ends-db-val').change(function () {
        var endsDBVal = $('#ends-db-val').val();
        switch (endsParamSelected) {
            case 'On':
                $('#repeat_end_on').val(endsDBVal);
                break;
            case 'Never':
                $('#repeat_never').val('1');
                break;
            case 'After':
                $('#repeat_end_after').val(endsDBVal);
                break;
        }
    });

    $('.color-box').click(function () {
        $('.color-box').html('&nbsp;');
        $('.color-box').removeClass('color-box-selected');
        $(this).addClass('color-box-selected');
        $(this).html('&nbsp;✔');
        var cVal = $(this).attr('data-color');
        $('#backgroundColor').val(cVal);
    });

    $('.cal-color-box').click(function () {
        $('.cal-color-box').html('&nbsp;');
        $('.cal-color-box').removeClass('color-box-selected');
        $(this).addClass('color-box-selected');
        $(this).html('&nbsp;✔');
        var cVal = $(this).attr('data-color');
        $('#cal-color').val(cVal);
    });


    $('#add-calendar').click(function () {
        $('#myModalCalendarCreate').modal({backdrop: 'static', keyboard: false});
    });

    $('#manage-calendar').click(function () {
        $('#myModalCalendarManage').modal({backdrop: 'static', keyboard: false});
    });
    //====== Export Calendar ========

    $('#calManagerHolder').delegate('.exportCal','click',function(){
        var calID = $(this).data('vid');

        var jqxhr = $.ajax({
            type: "POST",
            url: "<?php echo ABS_PATH?>/server/ajax/calendar_manager.php",
            data: {cID:calID, action: 'EXPORT_CALENDAR'}
        })
            .done(function (directory) {
                if(directory == 'fail'){
                    $.bootstrapGrowl("This Calendar Has No Event!", {
                        type: 'danger',
                        width: 350
                    });
                }
                else{
                    $.bootstrapGrowl(" Calendar Exported Successfully ", {
                        type: 'success',
                        width: 350
                    });

                    setTimeout(function (){
                        location.href = directory;
                    }, 3000);
                }
            })
            .fail(function () {
                $.bootstrapGrowl("Something went wrong, please try again later", {
                    type: 'danger',
                    width: 350
                });
            });
    });

    //====== Edit Calendar ==========
    calEditForm = $('#calEditForm');
    calEditForm.detach();

    $('#calManagerHolder').delegate('.editCal','click',function(){
        $('#calName').val('');
        $('.color-box-selected').html('&nbsp;');
        $('.color-box-selected').removeClass('color-box-selected');

        shareForm.detach();
        calEditForm.detach();

        var calType = $(this).data('type');
        var calName = $(this).data('name');
        var calDesc = $(this).data('desc');
        var calColor = $(this).data('clr');
        var clrLine = calColor.split('#');
        clr = clrLine[1];
        var calPrivacy = "";
        calPrivacy = $(this).data('privacy');


        var parentId = $(this).parent().parent().attr('id');
        var calID = parentId.split('_');
        calID = calID[1];
        $('#'+parentId+' #shareCalendar').append(calEditForm);

         if(calType == 'url'){
             $('div#shareCalendar div#cal-edit-desc-group').hide();
             $('div#shareCalendar div#gcal-edit-desc-group').show();
             $('#gcalDescription').val(calDesc);
         }
         else{
            $('div#shareCalendar div#cal-edit-desc-group').show();
            $('div#shareCalendar div#gcal-edit-desc-group').hide();
            $('#calDescription').val(calDesc);
         }

        //console.log(calPrivacy);
        if(calPrivacy == 1){
            //$('input.cal-private').removeAttr("checked");
            $('input.cal-public').attr('checked','checked');
        }
        else{
           // $('.cal-public').removeAttr("checked");
            $('input.cal-private').attr('checked','checked');
        }

        var colorSelector = '#clr_'+clr;

        $('#calName').val(calName);
        $(colorSelector).addClass('color-box-selected');
        $(colorSelector).html('&nbsp;✔');
        var cVal = $(colorSelector).attr('data-color');
        $('#cal-color').val(cVal);
        $('#cal-id').val(calID);
    });
    //====== Update Calendar =======
    $('#calManagerHolder').delegate('#updateCalendar','click',function(){
        var l = Ladda.create(this);
        l.start();
        var name = $('#calName').val();
        var cal_desc = $('#calDescription').val();
        if(cal_desc == '') cal_desc = $('#gcalDescription').val();
        //alert(cal_desc);
        var clr = $('#cal-color').val();
        var privacy = ($('#public').is(':checked')) ? 'public' : 'private';
        var cID = $('#cal-id').val();

        var jqxhr = $.ajax({
            type: "POST",
            url: "<?php echo ABS_PATH?>/server/ajax/calendar_manager.php",
            data: {cID:cID, name:name, cal_desc:cal_desc, clr:clr, privacy:privacy, action: 'UPDATE_CALENDAR'}
        })
            .done(function (calJSON) {
                $.bootstrapGrowl(" Calendar Updated Successfully ", {
                    type: 'success',
                    width: 350
                });
                calEditForm.detach();
                 setTimeout(function (){
                 location.href = 'calendar.php';
                 }, 3000);
            })
            .fail(function () {
                $.bootstrapGrowl("Something went wrong, please try again later", {
                    type: 'danger',
                    width: 350
                });
            })
            .always(function (){
                l.stop();
            });

        //== Reload



    });
    //====== Load Calendar shareForm =====
    shareForm = $('#shareForm');
    shareForm.detach();

    $('span.share-icon').click(function () {
        shareForm.detach();
        calEditForm.detach();
        var parentId = $(this).parent().attr('id');
        $('#'+parentId+' #shareCalendar').append(shareForm);
        var calID = parentId.split('_');
        calID = calID[1];
        $('#link').val('');
        $('#email').val('');
        $('#message').val('');
        $('#link').val('<?php echo BASE_URL ?>guest/index.php?c='+calID);
        //http://localhost/highpitch_eventcal/guest/index.php?c=1
    });

    $('#calManagerHolder').delegate('#sendEmail','click',function (){
        //var parentId = $(this).parent().attr('id');
        var l = Ladda.create(this);
        l.start();

        var link = $('#link').val();
        var email = $('#email').val();
        var message = $('#message').val();
        if (isValidEmailAddress(email) == false || email == '') {
            $.bootstrapGrowl("<div style='text-align: left'>Invalid Email</div>", {
                type: 'warning',
                width: 450
            });
            l.stop();
            return false;
        }
        var jqxhr = $.ajax({
            type: "POST",
            url: "<?php echo ABS_PATH?>/server/ajax/calendar_manager.php",
            data: {link:link, email:email, message:message, action: 'SHARE_CALENDAR'}
        })
            .done(function (calJSON) {
                $.bootstrapGrowl(" Calendar Shared Successfully ", {
                    type: 'success',
                    width: 350
                });
                shareForm.detach();
            })
            .fail(function () {
                $.bootstrapGrowl("Something went wrong, please try again later", {
                    type: 'danger',
                    width: 350
                });
            })
            .always(function (){
                l.stop();
            });

    })

        //============= Change the Calendar Privace either Public or Private :)
    $('.public').click(function () {
        var l = Ladda.create(this);
        l.start();
        var vpublic = $(this).attr("data-val");
        var vid = $(this).attr("data-vid");
        var parentID = $(this).parent().parent().attr('id');

        var jqxhr = $.ajax({
            type: "POST",
            url: "<?php echo ABS_PATH?>/server/ajax/calendar_manager.php",
            data: {vid: vid, vpublic: vpublic, action: 'UPDATE_CAL_PUBLIC'}
        })
            .always(function (vPublic) {

                var chngPrivacy;
                var chngColor;
                var chngTxtColor;


                if (vpublic == 1) {
                    chngPrivacy = "Make Private";
                    chngColor = "#ffffff";
                    vpublic = 0;
                    chngTxtColor = "#000000";
                    $('#'+parentID+' span.share-icon').addClass('glyphicon glyphicon-globe');
                }
                else {
                    chngPrivacy = "Make Public";
                    chngColor = "#f3f3f3";
                    chngTxtColor = "#ffffff";
                    vpublic = 1;
                    $('#'+parentID+' span.share-icon').removeClass('glyphicon glyphicon-globe');
                }
                $.bootstrapGrowl("Calendar Privacy Changed Successfully ", {
                    type: 'success',
                    width: 350
                });
                $('#'+parentID+' div.cactionss div.public span.ladda-label').html(chngPrivacy);
                $('#'+parentID+' div.cactionss div.public span.ladda-label').css('color',chngTxtColor);
                $('#'+parentID+' div.cactionss div.public').attr('data-val',vPublic);

                $('#'+parentID).css('background-color',chngColor);
                // If any shareForm is open while changing privacy, detach it
                shareForm.detach();
                //data-val
                /*
                 setTimeout(function (){
                 location.href = 'calendar.php';
                 }, 2000);
                 */
            })
            .fail(function () {
                $.bootstrapGrowl("Something Public went wrong, please try again later", {
                    type: 'danger',
                    width: 350
                });
            });

        l.stop();
    });

    $('#create-calendar').click(function () {
        var calName = $('#name').val();
        if (calName == '') {
            $.bootstrapGrowl("<div style='text-align: left'>Calendar Name is required</div>", {
                type: 'warning',
                width: 450
            });

            return false;
        }

        var formData = $('#myModalCalendarCreateFrom').serializeArray();

        var jqxhr = $.ajax({
            type: "POST",
            url: "<?php echo ABS_PATH?>/server/ajax/calendar_manager.php",
            data: formData
        })
            .done(function (calJSON) {
                var uid = $('#update-calendar').val();
                var calData = $.parseJSON(calJSON);
                var calName = calData.name;
                var calID = calData.id;
                var calColor = calData.color;

                if (parseInt(uid) > 0) {
                    //edit calendar

                }
                else {
                    var calContent = '<a href="javascript:void(0);" class="list-group-item ladda-button new-cal" data-style="expand-right" style="background-color: ' + calColor + '; color:white;" id="' + calID + '" ><span class="ladda-label">' + calName + '</span></a>';
                    $('#my-calendars div.list-group').append(calContent);
                    $('.new-cal').click();
                    $('.new-cal').removeClass('new-cal');
                }
                $('#myModalCalendarCreate').modal('hide');
                $.bootstrapGrowl("Calendar Created Successfully", {
                    type: 'success',
                    width: 350
                });

                setTimeout(function () {
                    location.href = 'calendar.php';
                }, 2000);

            })
            .fail(function () {
                $.bootstrapGrowl("Something went wrong, please try again later", {
                    type: 'danger',
                    width: 350
                });
            })
    });

//        $('.list-group').delegate(".list-group-item .unselect-calendar","click", function (e){
//            alert($(this).attr('class'));
//        });

    $('#list-group').delegate(".list-group-item", "click", function (e) {
        e.preventDefault();
        var cid = new Array();
        var thisObj = new Array();
        var unselectClickedItem = false;

        //===clearing blocks
        $('.list-group-item .ladda-spinner').remove();
        $('.list-group-item .ladda-progress').remove();

        //===Get the current calendar item's ID
        var calendarItemClicked = $(this).attr('id');
        //alert(calendarItemClicked)

        //===Find if the clicked item is requested for unselect
        $('.list-group-item span').each(function () {
            if ($(this).hasClass('unselect-calendar')) {
                if ($(this).parent().attr('id') == calendarItemClicked) {
                    unselectClickedItem = true;
                }
                //alert($('#'+calendarItemClicked+' span.ladda-label').html())
            }
        });

        var l = Ladda.create(this);
        l.start();

        $('.list-group-item span').each(function () {
            if ($(this).hasClass('ladda-spinner')) {
                $(this).parent().addClass('selected');
            }
        });

        var tickHTMLLObjSelected = '<span class="glyphicon glyphicon-remove unselect-calendar"></span><span class="glyphicon glyphicon-ok" style="float: right"></span>';

        var i = 0;
        $('.list-group-item').each(function () {
            if ($(this).hasClass('selected')) {
                if (unselectClickedItem == true && $(this).attr('id') == calendarItemClicked) {
                    $('#' + calendarItemClicked + ' span.glyphicon').remove();
                    $('#' + calendarItemClicked).removeClass('selected');
                    unselectClickedItem = false;
                }
                else {
                    thisObj[i] = $(this);
                    cid[i] = $(this).attr('id');
                    //alert(cid[i])
                    i = i + 1;
                }
            }
        });

            //alert(thisObj.length)

        $.post("<?php echo ABS_PATH?>/server/ajax/events_manager.php",
            { calendarID: cid, action: 'LOAD_EVENTS_BASED_ON_CALENDAR_ID'},
            function (eventJSON) {
            }, "json")
            .always(function (eventJSON) {
                if (eventJSON.title == 'CALENDARS___HAVE___URL') {
                    location.href = '<?php echo ABS_PATH?>/calendar.php';
                }
                ;
                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('addEventSource', eventJSON);
                l.stop();

                $('.list-group-item span.glyphicon').remove();
                var j;
                for (j = 0; j < thisObj.length; j = j + 1) {
                    thisObj[j].append(tickHTMLLObjSelected);
                }
            }, "json");
        return false;
    });

//===== Public Calendar List Load
    $('#list-group-public').delegate(".list-group-item-public", "click", function (e) {
        e.preventDefault();
        var cid = new Array();
        var thisObj = new Array();
        var unselectClickedItem = false;

        //===clearing blocks
        $('.list-group-item-public .ladda-spinner').remove();
        $('.list-group-item-public .ladda-progress').remove();

        //===Get the current calendar item's ID
        var calendarItemClicked = $(this).attr('id');
        //alert(calendarItemClicked)

        //===Find if the clicked item is requested for unselect
        $('.list-group-item-public span').each(function () {
            if ($(this).hasClass('unselect-calendar')) {
                if ($(this).parent().attr('id') == calendarItemClicked) {
                    unselectClickedItem = true;
                }
                //alert($('#'+calendarItemClicked+' span.ladda-label').html())
            }
        });

        var l = Ladda.create(this);
        l.start();

        $('.list-group-item-public span').each(function () {
            if ($(this).hasClass('ladda-spinner')) {
                $(this).parent().addClass('selected');
            }
        });

        var tickHTMLLObjSelected = '<span class="glyphicon glyphicon-remove unselect-calendar"></span><span class="glyphicon glyphicon-ok" style="float: right"></span>';

        var i = 0;
        $('.list-group-item-public').each(function () {
            if ($(this).hasClass('selected')) {
                if (unselectClickedItem == true && $(this).attr('id') == calendarItemClicked) {
                    $('#' + calendarItemClicked + ' span.glyphicon').remove();
                    $('#' + calendarItemClicked).removeClass('selected');
                    unselectClickedItem = false;
                }
                else {
                    thisObj[i] = $(this);
                    cid[i] = $(this).attr('id');
                    //alert(cid[i])
                    i = i + 1;
                }
            }
        });

        //alert(thisObj.length)

        $.post("<?php echo ABS_PATH?>/server/ajax/events_manager.php",
            { calendarID: cid, action: 'LOAD_PUBLIC_EVENTS_BASED_ON_CALENDAR_ID'},
            function (eventJSON) {
            }, "json")
            .always(function (eventJSON) {
                if (eventJSON.title == 'CALENDARS___HAVE___URL') {
                    location.href = '<?php echo ABS_PATH?>/calendar.php';
                }
                ;

                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('addEventSource', eventJSON);
                l.stop();

                $('.list-group-item-public span.glyphicon').remove();
                var j;
                for (j = 0; j < thisObj.length; j = j + 1) {
                    thisObj[j].append(tickHTMLLObjSelected);
                }
            }, "json");
        return false;
    });


    //======= New Calendar Load =================

    $('.list-group').delegate(".new-cal", "click", function (e) {
        e.preventDefault();
        var cid = new Array();
        var thisObj = new Array();
        var unselectClickedItem = false;

        //===clearing blocks
        $('.list-group-item .ladda-spinner').remove();
        $('.list-group-item .ladda-progress').remove();

        //===Get the current calendar item's ID
        var calendarItemClicked = $(this).attr('id');
        //alert(calendarItemClicked)

        //===Find if the clicked item is requested for unselect
        $('.list-group-item span').each(function () {
            if ($(this).hasClass('unselect-calendar')) {
                if ($(this).parent().attr('id') == calendarItemClicked) {
                    unselectClickedItem = true;
                }
                //alert($('#'+calendarItemClicked+' span.ladda-label').html())
            }
        });

        var l = Ladda.create(this);
        l.start();

        $('.list-group-item span').each(function () {
            if ($(this).hasClass('ladda-spinner')) {
                $(this).parent().addClass('selected');
            }
        });

        var tickHTMLLObjSelected = '<span class="glyphicon glyphicon-remove unselect-calendar"></span><span class="glyphicon glyphicon-ok" style="float: right"></span>';

        var i = 0;
        $('.list-group-item').each(function () {
            if ($(this).hasClass('selected')) {
                if (unselectClickedItem == true && $(this).attr('id') == calendarItemClicked) {
                    $('#' + calendarItemClicked + ' span.glyphicon').remove();
                    $('#' + calendarItemClicked).removeClass('selected');
                    unselectClickedItem = false;
                }
                else {
                    thisObj[i] = $(this);
                    cid[i] = $(this).attr('id');
                    //alert(cid[i])
                    i = i + 1;
                }
            }
        });

        //alert(thisObj.length)

        $.post("<?php echo ABS_PATH?>/server/ajax/events_manager.php",
            { calendarID: cid, action: 'LOAD_EVENTS_BASED_ON_CALENDAR_ID'},
            function (eventJSON) {
            }, "json")
            .always(function (eventJSON) {
                if (eventJSON.title == 'CALENDARS___HAVE___URL') {
                    location.href = '<?php echo ABS_PATH?>/calendar.php';
                }
                ;

                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('addEventSource', eventJSON);
                l.stop();

                $('.list-group-item span.glyphicon').remove();
                var j;
                for (j = 0; j < thisObj.length; j = j + 1) {
                    thisObj[j].append(tickHTMLLObjSelected);
                }
            }, "json");
        return false;
    });
        //======= New Calendar Load End


    $('#cal-settings-link').click(function () {
        $('#myModalCalendarSettings').modal({backdrop: 'static', keyboard: false});
    });

    $('#calendar-settings-save').click(function () {
        var formData = $('#myModalCalendarSettingsFrom').serializeArray();

        var jqxhr = $.ajax({
            type: "POST",
            url: "<?php echo ABS_PATH?>/server/ajax/calendar_manager.php",
            data: formData
        })
            .done(function (calJSON) {
                $('#myModalCalendarCreate').modal('hide');
                $('#myModalCalendarSettingsFrom fieldset').attr('disabled', 'disabled')
                $.bootstrapGrowl("Calendar Settings Saved Successfully", {
                    type: 'success',
                    width: 350
                })

                setTimeout(function () {
                    location.href = '<?php echo ABS_PATH?>/calendar.php';
                }, 3000);
            })
            .fail(function () {
                $.bootstrapGrowl("Something went wrong, please try again later", {
                    type: 'danger',
                    width: 350
                });
            });


    });

        //=========== Show-Hide end-group on change allDay
    $('#allDay').change(function () {
        if (this.checked) {
            $('#end-group').hide();
        }
        else {
            $('#end-group').show();
        }
    });
    /*
     $('#allDay').click(function (){
     if(allDay == true){
     $('#end-group').hide();
     allDay = false;
     }
     else {
     $('#end-group').show();
     allDay = true;
     }
     });
     */
    $('#select-calendar').change(function () {
        //alert($(this).val());
        var color = $(this).find(':selected').attr('data-color')
        var colorData = color.split('#');
        var cid = 'cid_' + colorData[1];
        $('#' + cid).click();
    });

    $('#remove-link').click(function () {
        var l = Ladda.create(this);
        l.start();

        var eid = $(this).attr('data-event-id');

        $.post("<?php echo ABS_PATH?>/server/ajax/events_manager.php",
            { eventID: eid, action: 'REMOVE_THIS_EVENT'},
            function (eventJSON) {
            }, "json")
            .always(function (eventJSON) {
                $('#calendar').fullCalendar('removeEvents', eid);
                $('#myModal').modal('hide');
                $.bootstrapGrowl("<div style='text-align: left'>Event Removed Successfully</div>", {
                    type: 'success',
                    width: 450
                });
                l.stop();

            }, "json");
    });

    $('#gcal-add-link').click(function () {
        $('#gcal-add-link').attr('disabled', 'disabled');
        $('#gcal-add-link').addClass('active');
        $('#gcal-back-link').fadeIn();
        $('#gcal-add-desc-group').show();
        $('#cal-add-desc-group').hide();
        $('#type').val('url');

    });

    $('#gcal-back-link').click(function () {
        $('#gcal-add-link').removeAttr('disabled');
        $('#gcal-add-link').removeClass('active');
        $('#gcal-back-link').fadeOut();
        $('#gcal-add-desc-group').hide();
        $('#cal-add-desc-group').show();
        $('#type').val('user');
    });

});

function eventRenderer(calEvent,jsEvent,view,userRole,shortdateFormat, longdateFormat){

    $('#eventForm fieldset').removeAttr('disabled');
    document.getElementById('eventForm').reset();

    //===Clearing Reminder Settings Panel
    $('#hide-reminder-settings').click();
    serial = 1;
    $('#guest-list div').remove();

    //===get current view
    var view = $('#calendar').fullCalendar('getView');
    $('#currentView').val(view.name);

    $('.basic').show();
    $('.standard').hide();
    $('.repeat-box').fadeOut();
    $('.repeat-box').css('display','none');
    $('#repeat').removeAttr('checked')
    $('#show-link').show();
    $('#repeat_by_group').hide();

    var jqxhr = $.ajax({
        type: 'POST',
        url: '<?php echo BASE_URL ?>server/ajax/events_manager.php',
        data: {eventID:calEvent.id,action:'LOAD_SINGLE_EVENT_BASED_ON_EVENT_ID'},
        dataType: 'json'
    })
        .done(function(ed) {

            $('#myModal').modal({backdrop:'static',keyboard:false});
            var modalTitle = 'Edit Event: <b>'+ calEvent.title.toUpperCase() + '</b> <br >' +  $.fullCalendar.moment(calEvent.start).format(longdateFormat+' hh:mm A');
            $('#myModalLabel').html(modalTitle);
            $('#myTab a:first').tab('show');

            //====setting up values

            $('#title').val(calEvent.title);
            var startMoment = moment(ed.start_date+' '+ed.start_time)
            $('#start-date').val(startMoment.format('YYYY-MM-DD'));
            $('#start-time').val(startMoment.format('hh:mm A'));

            var endMiliseconds = Date.parse(calEvent.end);
            var endMoment = '';
            endMoment = moment(calEvent.end);

            if(calEvent.end == null && (calEvent.allDay!='on' || ed.allDay!='on'  )){
                if(ed.end_date == null || ed.end_date == '' || calEvent.end == null){
                    var dePrepDate = ed.start_date.split('-');
                    var dePrepTime = ed.start_time.split(':');
                    var dePrep = new Date(dePrepDate[0],dePrepDate[1]-1,dePrepDate[2],parseInt(dePrepTime[0])+1,dePrepTime[1],0,0);
                    endMoment = moment(dePrep)
                }
                else {
                    var dePrepDate = ed.end_date.split('-');
                    var dePrepTime = ed.end_time.split(':');
                    var dePrep = new Date(dePrepDate[0],dePrepDate[1],dePrepDate[2],dePrepTime[0],dePrepTime[1],0,0);
                    endMoment = moment(dePrep)
                }
            }

            if(calEvent.end != null){
                $('#end-date').val(endMoment.format('YYYY-MM-DD'));
                $('#end-time').val(endMoment.format('hh:mm A'));
            }

            if(calEvent.allDay == 'on' || calEvent.allDay == true || ed.allDay == 'on' || ed.allDay == true) {
                $('#end-group').hide();
                $('#allDay').attr('checked','checked');
            }
            else {
                $('#end-group').show();
                $('#allDay').removeAttr('checked');
            }

            $('#url').val(calEvent.url);
            $('#backgroundColor').val(calEvent.backgroundColor);
            $('#borderColor').val(calEvent.borderColor);
            $('#textColor').val(calEvent.textColor);
            $('#imageName').val(calEvent.image);
            $('#img-preview').show().attr('src','<?php echo BASE_URL ?>uploads/'+calEvent.image);

            $('#create-event').html('Update Event');
            $('#update-event').val(calEvent.id);
            //alert(calEvent.id)


            //====setting data from AJAX load
            $('#repeat_type').val('none');
            $('#repeat_interval').val('1');
            $('#repeat_on_mon').removeAttr('checked');
            $('#repeat_on_sun').removeAttr('checked');
            $('#repeat_on_tue').removeAttr('checked');
            $('#repeat_on_wed').removeAttr('checked');
            $('#repeat_on_thu').removeAttr('checked');
            $('#repeat_on_fri').removeAttr('checked');
            $('#repeat_on_sat').removeAttr('checked');
            $('#repeat_on_group').hide();

            $('#ends-status').html('Never');

            $('#repeat_start_date').val(ed.start_date);

            if(ed.repeat_type =='none' || ed.repeat_type == null) {}
            else {
                //==== If it is repeat event then get the date from eventClick Object
                var startMoment = moment(calEvent.start)
                $('#start-date').val(startMoment.format('YYYY-MM-DD'));
                $('#start-time').val(startMoment.format('hh:mm A'));

                var repeatType = ed.repeat_type;
                var intervalLabel = 'weeks';
                $('#repeat_interval_group').show();
                $('#repeat_on_group').show();

                switch (repeatType){
                    case 'daily':
                        $('#repeat_on_group').hide();
                        intervalLabel = 'Days';
                        break;
                    case 'everyWeekDay':
                        intervalLabel = '';
                        $('#repeat_interval_group').hide();
                        $('#repeat_on_group').hide();
                        break;
                    case 'everyMWFDay':
                        intervalLabel = '';
                        $('#repeat_interval_group').hide();
                        $('#repeat_on_group').hide();
                        break;
                    case 'everyTTDay':
                        intervalLabel = '';
                        $('#repeat_interval_group').hide();
                        $('#repeat_on_group').hide();
                        break;
                    case 'weekly':
                        intervalLabel = 'Weeks';
                        //$('#repeat_on_wed').attr('checked','checked');
                        break;
                    case 'monthly':
                        intervalLabel = 'Months';
                        $('#repeat_by_group').show();
                        $('#repeat_on_group').hide();
                        if(ed.repeat_by == 'repeat_by_day_of_the_month') $('#repeat_by_day_of_the_month').click();
                        if(ed.repeat_by == 'repeat_by_day_of_the_week') $('#repeat_by_day_of_the_week').click();

                        break;
                    case 'yearly':
                        intervalLabel = 'Years';
                        $('#repeat_on_group').hide();
                        break;
                    case 'none':
                    default :
                        var intervalLabel = 'weeks';
                        break;
                }
                $('#repeat_interval_label').html(intervalLabel);

                //$('#show-standard-settings').click();
                $('#repeat').click();
                $('#repeat_type').val(ed.repeat_type);

                if(ed.repeat_type == 'weekly')$('#repeat_on_group').show();

                $('#repeat_interval').val(ed.repeat_interval);
                if(ed.repeat_on_sun == '1') $('#repeat_on_sun').click();
                if(ed.repeat_on_mon == '1') $('#repeat_on_mon').click();
                if(ed.repeat_on_tue == '1') $('#repeat_on_tue').click();
                if(ed.repeat_on_wed == '1') $('#repeat_on_wed').click();
                if(ed.repeat_on_thu == '1') $('#repeat_on_thu').click();
                if(ed.repeat_on_fri == '1') $('#repeat_on_fri').click();
                if(ed.repeat_on_sat == '1') $('#repeat_on_sat').click();

                $('#repeat_start_date').val(ed.repeat_start_date);
                if(ed.repeat_end_on != '0000-01-01') {
                    $('#repeat_end_on').val(ed.repeat_end_on);
                    $('#ends-db-val').removeAttr('readOnly');
                    $('#ends-db-val').val(ed.repeat_end_on);
                    $('#repeat_never').val('');
                    $('#ends-status').html('On');
                }
                if(ed.repeat_end_after != '0') {
                    $('#repeat_end_after').val(ed.repeat_end_after);
                    $('#ends-db-val').removeAttr('readOnly');
                    $('#ends-db-val').val(ed.repeat_end_after);
                    $('#repeat_never').val('');
                    $('#ends-status').html('After');
                }
                if(ed.repeat_never != '0') {
                    $('#repeat_end_after').val(ed.repeat_end_after);
                    $('#ends-db-val').attr('readOnly','readOnly');
                    $('#ends-db-val').removeAttr('value');
                    $('#repeat_never').val('1');
                    $('#ends-status').html('Never');
                }
            }
            if(ed.allDay == 'on'){
                //$('#show-standard-settings').click();
            }

            //====setting up selected calendar values
            $('.selectpicker').selectpicker('val', [ed.cal_id]);
            //$('.select-calendar-cls').css('opacity','0.35');
            //$('#select-calendar').attr('disabled','disabled');

            //alert(ed.location);
            $('#location').val(ed.location);
            $('#url').val(ed.url);
            $('#description').val(ed.description);
            $('#backgroundColor').val(ed.backgroundColor);
            $('.color-box-selected').html(' ');
            $('.color-box').removeClass('color-box-selected');

            $('.color-box').each(function (){
                var cv = $(this).attr('data-color');
                if(cv == ed.backgroundColor) {
                    $(this).addClass('color-box-selected');
                    $(this).html('&nbsp;✔');
                }
            });

            $('#reminder_type').val(ed.reminder_type);
            $('#reminder_time').val(ed.reminder_time);
            $('#reminder_time_unit').val(ed.reminder_time_unit);
            $('#free_busy').val(ed.free_busy);
            $('#privacy').val(ed.privacy);

            //====User Previlleged section
            //===============================================
            //====Add event remove link
            if(userRole == 'super' || userRole == 'admin'){
                $('#remove-block').fadeIn(2500);
                $('#remove-link').attr('data-event-id',calEvent.id);
            }

            //====Standard Settings
            //===============================================
            $('#hide-standard-settings').click()
            if((ed.location != null && ed.location!='') || (ed.url != null && ed.url!='')  || (ed.description != null && ed.description!='')) $('#show-standard-settings').click();

            //===Setting Data for Event Reminder if any

            if(ed.reminderData && ed.reminderData.length > 0){
                var i;
                var reminderType;
                var reminderTime;
                var reminderTimeUnit;
                for(i=0;i < ed.reminderData.length; i++){
                    //=== for first reminder option
                    //alert(i)
                    if(i==0){
                        $('#reminder_type_1').val(ed.reminderData[i].type);
                        $('#reminder_time_1').val(ed.reminderData[i].time);
                        $('#reminder_time_unit_1').val(ed.reminderData[i].time_unit);
                    }
                    if(i==1){
                        //alert(reminder2Obj)
                        reminder2Obj.appendTo('#reminder-holder');
                        $('#reminder_type_2').val(ed.reminderData[i].type);
                        $('#reminder_time_2').val(ed.reminderData[i].time);
                        $('#reminder_time_unit_2').val(ed.reminderData[i].time_unit);
                    }
                    if(i==2){
                        //alert(reminder3Obj)
                        reminder3Obj.appendTo('#reminder-holder');
                        $('#reminder_type_3').val(ed.reminderData[i].type);
                        $('#reminder_time_3').val(ed.reminderData[i].time);
                        $('#reminder_time_unit_3').val(ed.reminderData[i].time_unit);
                    }
                }
            }

            if(ed.reminderGuests && ed.reminderGuests.length > 0){
                var i;
                var guestEmail;
                var reminderID;
                serial = 1;
                var guestView;

                for(i = 0; i < ed.reminderGuests.length; i++){
                    guestEmail = ed.reminderGuests[i].email;
                    reminderID = ed.reminderGuests[i].id;
                    guestView = '<div id=\"guest_'+serial+'\"> <input class=\"form-control guest-view guest_email reminder_add_guest_in\" id=\"guest_list_'+serial+'\" name=\"guests[]\" value=\"'+guestEmail+'\"><button class=\"close_guest\" aria-hidden=\"true\" data-dismiss=\"guest\" type=\"button\">×</button></div>';
                    $('#guest-list').append(guestView);
                    serial = serial + 1;
                }
                $('.close_guest').click(function(){
                    $(this).parent().remove();
                });

            }

        })
        .fail(function() {
        });
    /*
     var jqxhr = $.ajax({
     type: 'POST',
     url: '$this->webURL/server/ajax/user_manager.php',
     data: {action:'LOAD_USER_DATA'},
     })
     .done(function(selCalColor) {
     })
     .fail(function() {
     });
     */

}

function formatTime(dateStr) {
    var d = new Date(dateStr);
    var hh = d.getHours();
    var m = d.getMinutes();
    var dd = "AM";
    var h = hh;
    if (h >= 12) {
        h = hh - 12;
        dd = "PM";
    }
    if (h == 0) {
        h = 12;
    }
    m = m < 10 ? "0" + m : m;

    h = h < 10 ? "0" + h : h;

    var replacement = h + ":" + m;
    replacement += " " + dd;

    return replacement;
}

function formatTimeStr(dateStr) {
    var timeData = dateStr.split(':');

    var hh = parseInt(timeData[0]);
    var m = parseInt(timeData[1]);
    var dd = "AM";
    var h = hh;
    if (h >= 12) {
        h = hh - 12;
        dd = "PM";
    }
    if (h == 0) {
        h = 12;
    }
    m = m < 10 ? "0" + m : m;

    h = h < 10 ? "0" + h : h;

    var replacement = h + ":" + m;
    replacement += " " + dd;

    return replacement;
}

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}
;

function hideReminder2() {
    reminder2Obj.appendTo('#reminder-holder');
    reminder2Obj = $('#reminder2').detach();
}
function hideReminder3() {
    reminder3Obj.appendTo('#reminder-holder');
    reminder3Obj = $('#reminder3').detach();
}

function processMovedEvent(event, revertFunc, jsEvent, ui, view) {
    var eventID = event.id;
    var title = event.title;
    var allDay = event.allDay;
    //====Full Calendar has a bug that returns allDay param as a HTML Object instead of boolean when hold time pointer and release event is triggered.
    //====But it seems OK for drag and drop event
    if (allDay && typeof(allDay) != 'object') allDay = '1';
    else allDay = '0';

    var startMoment = moment(event.start)
    var sdate = startMoment.format('YYYY-MM-DD');
    var stime = startMoment.format('hh:mm A');


    if (event.end != null) {
        var endMoment = moment(event.end)
        var edate = endMoment.format('YYYY-MM-DD');
        var etime = endMoment.format('hh:mm A');
    }
    else if(allDay == '0'){
        var edate = startMoment.format('YYYY-MM-DD');
        var etime = startMoment.add('h',1).format('hh:mm A');
    }

    if (!confirm('Are you sure about this change?')) {
        revertFunc();
    }
    else {
        var jqxhr = $.ajax({
            type: 'POST',
            url: '<?php echo ABS_PATH?>/server/ajax/events_manager.php',
            data: {action: 'SAVE_MOVED_EVENT', sdate: sdate, edate: edate, stime: stime, etime: etime, eventID: eventID, title: title, allDay: allDay}
        })
            .done(function (msg) {
                if (msg == 'failed') {
                    $.bootstrapGrowl('Something went wrong, please try again later', {
                        type: 'danger',
                        width: 350
                    });
                }
                else if (msg == 'repeating') {
                    revertFunc();
                    $.bootstrapGrowl('<div style="text-align: left">Sorry! This operation is not supported for repeating events. Please try Editing instead</div>', {
                        type: 'warning',
                        width: 350
                    });
                }
                else {
                    $.bootstrapGrowl('Event Modified Successfully', {
                        type: 'success',
                        width: 350
                    });
                }
            })
            .fail(function () {
                $.bootstrapGrowl('Something went wrong, please try again later', {
                    type: 'danger',
                    width: 350
                });
            });
    }
}


</script>