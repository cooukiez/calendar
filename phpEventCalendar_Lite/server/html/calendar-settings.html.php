<style>
    .cal-color-box {
        display: inline-block;
        border: 0 solid;
        height: 18px;
        width: 18px;
        margin-right: 16px;
        cursor: pointer;
        border-radius: 10px;
        color: #ffffff;
        line-height: 22px;
    }
    .cal-color-box:hover{
        border: 0 solid;
    }
    .cal-color-box:active{
        border-radius: 0;
    }
    .color-box-selected {
        border-radius: 0;
    }

</style>
<div class="modal fade" id="myModalCalendarSettings" tabindex="-1" role="dialog" aria-labelledby="myModalCalendarSettingsLabel" aria-hidden="true" style="text-align:left;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalCalendarSettingsLabel">Calendar Settings</h3>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" id="myModalCalendarSettingsFrom">
                    <fieldset>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">APPLICATION</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="language" class="col-sm-4 control-label">Language</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="language" id="language">
                                            <option value="English" selected="selected">English</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="time_zone" class="col-sm-4 control-label">Time Zone</label>
                                    <div class="col-sm-8">
                                        <select  id="time_zone" name="time_zone" class="form-control">
                                            <option gmtAdjustment="GMT-12:00" useDaylightTime="0" value="-12" <?php echo (($calendarProperties['time_zone']==-12)?'selected':''); ?>>(GMT-12:00) International Date Line West</option>
                                            <option gmtAdjustment="GMT-11:00" useDaylightTime="0" value="-11" <?php echo (($calendarProperties['time_zone']==-11)?'selected':''); ?>>(GMT-11:00) Midway Island, Samoa</option>
                                            <option gmtAdjustment="GMT-10:00" useDaylightTime="0" value="-10" <?php echo (($calendarProperties['time_zone']==-10)?'selected':''); ?>>(GMT-10:00) Hawaii</option>
                                            <option gmtAdjustment="GMT-09:00" useDaylightTime="1" value="-09" <?php echo (($calendarProperties['time_zone']==-9)?'selected':''); ?>>(GMT-09:00) Alaska</option>
                                            <option gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-08" <?php echo (($calendarProperties['time_zone']==-8)?'selected':''); ?>>(GMT-08:00) Pacific Time (US & Canada)</option>
                                            <option gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-07" <?php echo (($calendarProperties['time_zone']==-7)?'selected':''); ?>>(GMT-07:00) Mountain Time (US & Canada)</option>
                                            <option gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-06" <?php echo (($calendarProperties['time_zone']==-6)?'selected':''); ?>>(GMT-06:00) Central America</option>
                                            <option gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-05" <?php echo (($calendarProperties['time_zone']==-5)?'selected':''); ?>>(GMT-05:00) Eastern Time (US & Canada)</option>
                                            <option gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-04" <?php echo (($calendarProperties['time_zone']==-4)?'selected':''); ?>>(GMT-04:00) Atlantic Time (Canada)</option>
                                            <option gmtAdjustment="GMT-03:30" useDaylightTime="1" value="-03.5" <?php echo (($calendarProperties['time_zone']==-3.5)?'selected':''); ?>>(GMT-03:30) Newfoundland</option>
                                            <option gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-03" <?php echo (($calendarProperties['time_zone']==-3)?'selected':''); ?>>(GMT-03:00) Brazil, Buenos Aires, Georgetown</option>
                                            <option gmtAdjustment="GMT-02:00" useDaylightTime="1" value="-02" <?php echo (($calendarProperties['time_zone']==-2)?'selected':''); ?>>(GMT-02:00) Mid-Atlantic</option>
                                            <option gmtAdjustment="GMT-01:00" useDaylightTime="0" value="-01" <?php echo (($calendarProperties['time_zone']==-1)?'selected':''); ?>>(GMT-01:00) Azores, Cape Verde Island</option>
                                            <option gmtAdjustment="GMT+00:00" useDaylightTime="0" value="00" <?php echo (($calendarProperties['time_zone']==0)?'selected':''); ?>>(GMT+00:00) Western Europe Time, London, Lisbon, Casablanca, Greenwich Mean Time</option>
                                            <option gmtAdjustment="GMT+01:00" useDaylightTime="1" value="01" <?php echo (($calendarProperties['time_zone']==1)?'selected':''); ?>>(GMT+01:00) Amsterdam, Berlin, Brussels, Copenhagen, Madrid</option>
                                            <option gmtAdjustment="GMT+02:00" useDaylightTime="1" value="02" <?php echo (($calendarProperties['time_zone']==2)?'selected':''); ?>>(GMT+02:00) Kaliningrad, South Africa</option>
                                            <option gmtAdjustment="GMT+03:00" useDaylightTime="0" value="03" <?php echo (($calendarProperties['time_zone']==3)?'selected':''); ?>>(GMT+03:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
                                            <option gmtAdjustment="GMT+03:30" useDaylightTime="1" value="03.5" <?php echo (($calendarProperties['time_zone']==3.5)?'selected':''); ?>>(GMT+03:30) Tehran</option>
                                            <option gmtAdjustment="GMT+04:00" useDaylightTime="0" value="04" <?php echo (($calendarProperties['time_zone']==4)?'selected':''); ?>>(GMT+04:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
                                            <option gmtAdjustment="GMT+04:30" useDaylightTime="0" value="04.5" <?php echo (($calendarProperties['time_zone']==4.5)?'selected':''); ?>>(GMT+04:30) Kabul</option>
                                            <option gmtAdjustment="GMT+05:00" useDaylightTime="1" value="05" <?php echo (($calendarProperties['time_zone']==5)?'selected':''); ?>>(GMT+05:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                                            <option gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5" <?php echo (($calendarProperties['time_zone']==5.5)?'selected':''); ?>>(GMT+05:30) Bombay, Calcutta, Madras, New Delhi</option>
                                            <option gmtAdjustment="GMT+05:45" useDaylightTime="0" value="5.75" <?php echo (($calendarProperties['time_zone']==5.75)?'selected':''); ?>>(GMT+05:45) Kathmandu</option>
                                            <option gmtAdjustment="GMT+06:00" useDaylightTime="1" value="06" <?php echo (($calendarProperties['time_zone']==6)?'selected':''); ?>>(GMT+06:00) Almaty, Dhaka, Colombo</option>
                                            <option gmtAdjustment="GMT+06:30" useDaylightTime="0" value="6.5" <?php echo (($calendarProperties['time_zone']==6.5)?'selected':''); ?>>(GMT+06:30) Yangon (Rangoon)</option>
                                            <option gmtAdjustment="GMT+07:00" useDaylightTime="0" value="07" <?php echo (($calendarProperties['time_zone']==7)?'selected':''); ?>>(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                            <option gmtAdjustment="GMT+08:00" useDaylightTime="0" value="08" <?php echo (($calendarProperties['time_zone']==8)?'selected':''); ?>>(GMT+08:00) Beijing, Taipei, Hong Kong, Singapore</option>
                                            <option gmtAdjustment="GMT+09:00" useDaylightTime="0" value="09" <?php echo (($calendarProperties['time_zone']==9)?'selected':''); ?>>(GMT+09:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
                                            <option gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5" <?php echo (($calendarProperties['time_zone']==9.5)?'selected':''); ?>>(GMT+09:30) Adelaide, Darwin</option>
                                            <option gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10" <?php echo (($calendarProperties['time_zone']==10)?'selected':''); ?>>(GMT+10:00) Eastern Australia, Guam, Vladivostok</option>
                                            <option gmtAdjustment="GMT+11:00" useDaylightTime="1" value="11" <?php echo (($calendarProperties['time_zone']==11)?'selected':''); ?>>(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                            <option gmtAdjustment="GMT+12:00" useDaylightTime="1" value="12" <?php echo (($calendarProperties['time_zone']==12)?'selected':''); ?>>(GMT+12:00) Auckland, Wellington, Fiji, Kamchatka</</option>
                                            <option gmtAdjustment="GMT+13:00" useDaylightTime="0" value="13" <?php echo (($calendarProperties['time_zone']==13)?'selected':''); ?>>(GMT+13:00) Nuku'alofa</option>
                                        </select>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email_server" class="col-sm-4 control-label">Email Server</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="email_server" id="email_server">
                                            <option value="PHPMailer" selected="selected">PHP Mailer</option>
                                            <option value="SendGrid">Send Grid</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br/>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">CALENDAR</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="default_view" class="col-sm-4 control-label">Default View</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="default_view" id="default_view">
                                            <option value="month" <?php echo ($calendarProperties['default_view'] == 'month') ? 'selected="selected"':'' ?>>Month</option>
<!--                                            <option value="basicWeek" --><?//=($calendarProperties['default_view'] == 'basicWeek') ? 'selected="selected"':'' ?><!-->Basic Week</option>-->
<!--                                            <option value="basicDay" --><?//=($calendarProperties['default_view'] == 'basicDay') ? 'selected="selected"':'' ?><!-->Basic Day</option>-->
                                            <option value="agendaWeek" <?php echo ($calendarProperties['default_view'] == 'agendaWeek') ? 'selected="selected"':'' ?>>Week</option>
                                            <option value="agendaDay" <?php echo ($calendarProperties['default_view'] == 'agendaDay') ? 'selected="selected"':'' ?>>Day</option>
                                            <option value="list" <?php echo ($calendarProperties['default_view'] == 'list') ? 'selected="selected"':'' ?>>Agenda</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="shortdate_format" class="col-sm-4 control-label">Short Date Format</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="shortdate_format" id="shortdate_format">
                                            <option value="MM/DD/YYYY" <?php echo ($calendarProperties['shortdate_format'] == 'MM/DD/YYYY') ? 'selected="selected"':'' ?>>12/31/2013</option>
                                            <option value="DD/MM/YYYY" <?php echo ($calendarProperties['shortdate_format'] == 'DD/MM/YYYY') ? 'selected="selected"':'' ?>>31/12/2013</option>
                                            <option value="DD-MM-YYYY" <?php echo ($calendarProperties['shortdate_format'] == 'DD-MM-YYYY') ? 'selected="selected"':'' ?>>31-12-2013</option>
                                            <option value="MM-DD-YYYY" <?php echo ($calendarProperties['shortdate_format'] == 'MM-DD-YYYY') ? 'selected="selected"':'' ?>>12-31-2013</option>
                                            <option value="DD-MM-YY" <?php echo ($calendarProperties['shortdate_format'] == 'DD-MM-YY') ? 'selected="selected"':'' ?>>12-31-13</option>
                                            <option value="MMM DD, YYYY" <?php echo ($calendarProperties['shortdate_format'] == 'MMM DD, YYYY') ? 'selected="selected"':'' ?>>Dec 31, 2013</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="longdate_format" class="col-sm-4 control-label">Long Date Format</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="longdate_format" id="longdate_format">
                                            <option value="dddd, DD MMMM YYYY" <?php echo ($calendarProperties['shortdate_format'] == 'dddd, DD MMMM YYYY') ? 'selected="selected"':'' ?>>Monday, 01 March 2013</option>
                                            <option value="dddd, MMMM DD, YYYY" <?php echo ($calendarProperties['shortdate_format'] == 'dddd, MMMM DD, YYYY') ? 'selected="selected"':'' ?>>Monday, March 01, 2013</option>
                                            <option value="MMMM DD, YYYY" <?php echo ($calendarProperties['shortdate_format'] == 'MMMM DD, YYYY') ? 'selected="selected"':'' ?>>March 01, 2013</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="timeformat" class="col-sm-4 control-label">Time Format</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="timeformat" id="timeformat">
                                            <option value="core" <?php echo ($calendarProperties['timeformat'] == 'core') ? 'selected="selected"':'' ?>>Core</option>
                                            <option value="standard" <?php echo ($calendarProperties['timeformat'] == 'standard') ? 'selected="selected"':'' ?>>Standard</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="start_day" class="col-sm-4 control-label">Week Start Day</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="start_day" id="start_day">
                                            <option value="0" <?php echo ($calendarProperties['start_day'] == '0') ? 'selected="selected"':'' ?>>Sunday</option>
                                            <option value="1" <?php echo ($calendarProperties['start_day'] == '1') ? 'selected="selected"':'' ?>>Monday</option>
                                            <option value="2" <?php echo ($calendarProperties['start_day'] == '2') ? 'selected="selected"':'' ?>>Tuesday</option>
                                            <option value="3" <?php echo ($calendarProperties['start_day'] == '3') ? 'selected="selected"':'' ?>>Wednesday</option>
                                            <option value="4" <?php echo ($calendarProperties['start_day'] == '4') ? 'selected="selected"':'' ?>>Thursday</option>
                                            <option value="5" <?php echo ($calendarProperties['start_day'] == '5') ? 'selected="selected"':'' ?>>Friday</option>
                                            <option value="6" <?php echo ($calendarProperties['start_day'] == '6') ? 'selected="selected"':'' ?>>Saturday</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </fieldset>

                    <input type="hidden" name="calendar-settings" id="calendar-settings" value="1" />
                    <input type="hidden" name="calendar-settings-update" id="calendar-settings-update" value="0" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="calendar-settings-save">Save</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->