<?php
//====Load all calendars
$allCals = new C_Calendar('LOAD_MY_CALENDARS');

//====Load calendar properties
$calendarProperties = $allCals->calendarProperties;

//====Load calendars
$allCalendars = $allCals->allCalendars;

?>
<style>
    .standard {
        display: none;
    }

    .repeat-box {
        display: none;
    }
    .well {
        background: transparent;
    }
    .event-form-break {
        margin-top: 10px;
    }
    .event-create-btn-input {
        background-image: none;
    }
    .color-box {
        display: inline-block;
        border: 0 solid;
        height: 18px;
        width: 18px;
        margin-right: 15px;
        cursor: pointer;
        border-radius: 10px;
        color: #ffffff;
        line-height: 22px;
    }
    .color-box:hover{
        border: 0 solid;
    }
    .color-box:active{
        border-radius: 0;
    }

    .color-box-selected {
        border-radius: 0;
    }

    .panel {
        margin: 0;
    }

    .col-sm-4, .col-xs-6, .col-lg-6, .col-xs-12, .col-lg-12 {
        padding-left: 0;
        padding-right: 0;
    }
    button .multiple-select-option-label {
        font-size: 9px;
        border: 1px solid darkgrey;
        border-radius: 5px;
        margin-top: 0;
        display: inline-block;
        padding-top: 4px;
        padding-bottom: 4px;
        padding-left: 2px;
        padding-right: 2px;
        background-color: #ffffff;
    }
</style>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="text-align:left;">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title" id="myModalLabel"></h5>
</div>
<form role="form" id="eventForm" class="form-horizontal">
<div class="modal-body">
<fieldset>
<div class="panel panel-default">
    <div class="panel-body">

        <div class="form-group">
            <label for="title" class="col-sm-3 control-label">Title</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="title" name="title" placeholder="Event Title" />
            </div>
        </div>

        <div class="form-group">
            <label for="start" class="col-sm-3 control-label">Start</label>
            <div class="input-group col-sm-9 date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="start" data-link-format="yyyy-mm-dd" >
                <input type="text" class="form-control" id="start" name="start" placeholder="Start Date & Time" value="<?php echo date('Y-m-d H:m')?>" />
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <div class="form-group">
            <label for="end" class="col-sm-3 control-label">End</label>
            <div class="input-group col-sm-9 date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="end" data-link-format="yyyy-mm-dd" >
                <input type="text" class="form-control" placeholder="End Date & Time" name="end" id="end" />
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>
    </div>

</div>
<!--- Action Links -->

<div class="well well-sm" style="margin-top: 10px">
                            <span class="basic">
                                <a href="javascript:void(0);" id="show-standard-settings">Show Standard Settings</a>
                            </span>

                            <div class="form-inline standard">
                                <div class="checkbox" style="padding-top: 0">
                                    <label for="allDay" style="padding-right: 5px">
                                        <input type="checkbox" name="allDay" id="allDay"> All Day
                                    </label>
                                    <label for="repeat" style="padding-right: 5px">
                                        <input type="checkbox" name="repeat" id="repeat" value="1"> Repeat
                                    </label>
                                    <label for="hide-standard-settings"  style="padding-right: 5px">
                                        <a href="javascript:void(0);" id="hide-standard-settings">Hide Standard Settings</a>
                                    </label>
                                </div>
                            </div>

                            <!-- Repeat Box -->
                            <div class="panel panel-info repeat-box col-sm-12" style="margin-top: 8px; margin-bottom: 8px;">
                                <div class="panel-body">

                                    <div class="form-group">
                                        <label for="repeat_type" class="col-sm-3 control-label">Repeats</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="repeat_type" id="repeat_type">
                                                <option value="none">None</option>
                                                <option value="daily">Daily</option>
                                                <option value="everyWeekDay">Every Weekday (Monday to Friday)</option>
                                                <option value="everyMWFDay">Every Monday, Wednesday, and Friday</option>
                                                <option value="everyTTDay">Every Tuesday, and Thursday</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="yearly">Yearly</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="repeat_interval" class="col-sm-3 control-label">Repeat Every</label>
                                        <div class="input-group col-sm-9">
                                            <select class="form-control" name="repeat_interval" id="repeat_interval">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                                <option value="25">25</option>
                                                <option value="26">26</option>
                                                <option value="27">27</option>
                                                <option value="28">28</option>
                                                <option value="29">29</option>
                                                <option value="30">30</option>
                                            </select>
                                            <span class="input-group-addon">weeks</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="repeat_type" class="col-sm-3 control-label">Repeat on</label>
                                        <div class="input-group col-sm-9">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="repeat_on_sun" name="repeat_on_sun" value="1"> S
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="repeat_on_mon" name="repeat_on_mon" value="1"> M
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="repeat_on_tue" name="repeat_on_tue" value="1"> T
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="repeat_on_wed" name="repeat_on_wed" value="1"> W
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="repeat_on_thu" name="repeat_on_thu" value="1"> T
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="repeat_on_fri" name="repeat_on_fri" value="1"> F
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="repeat_on_sat" name="repeat_on_sat" value="1"> S
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="repeat_start_date" class="col-sm-3 control-label">Starts on</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="repeat_start_date" name="repeat_start_date" value="<?php echo date('Y-m-d')?>" readonly style="background: transparent" />
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="ends-db-val" class="col-sm-3 control-label">Ending Condition</label>
                                        <div class="col-sm-9">
                                            <div class="input-group event-form-break">
                                                <div class="input-group-btn dropup">
                                                    <button class="btn btn-default dropdown-toggle event-create-btn-input" type="button" data-toggle="dropdown">
                                                        <span id="ends-text">Ends <span id="ends-status">Never</span></span>&nbsp;<span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a id="ends-never" href="javascript:void(0);" data-value="Never" class="ends-params">Never</a></li>
                                                        <li><a id="ends-after" href="javascript:void(0);" data-value="After" class="ends-params">After</a></li>
                                                        <li><a id="ends-on" href="javascript:void(0);" data-value="On" class="ends-params">On</a></li>
                                                    </ul>
                                                    <input type="hidden" name="repeat_end_on" id="repeat_end_on" value="" />
                                                    <input type="hidden" name="repeat_end_after" id="repeat_end_after" value="" />
                                                    <input type="hidden" name="repeat_never" id="repeat_never" value="1" />
                                                </div>
                                                <input type="text" class="form-control" id="ends-db-val" readonly style="width: 130px" /> <span style="display: none; margin-left: 10px;" id="ends-after-label">occurrences</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Repeat Box Ends -->

                            <!-- Standard Settings -->
                            <div class="standard col-sm-12" style="margin-top: 8px">
                                <div class="form-group">
                                    <label for="location" class="col-sm-3 control-label">Location</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="location" name="location" placeholder="Location" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="url" class="col-sm-3 control-label">URL</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="url" name="url" placeholder="URL (if any)" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="description" name="description"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="repeat_start_date" class="col-sm-3 control-label">Calendars</label>
                                    <div class="col-sm-9">
                                        <!--<select class="selectpicker show-tick" data-selected-text-format="count" multiple>-->
                                        <select id="select-calendar" class="selectpicker show-tick col-lg-12 select-calendar-cls" name="selected_calendars[]">
                                            <?php if($allCalendars != NULL) foreach($allCalendars as $k => $v){ ?>
                                                <?php
                                                //print_r($_SESSION['userData']['active_calendar_id']);
                                                $selectedDone = false;
                                                if(!$selectedDone && in_array($v['id'],$_SESSION['userData']['active_calendar_id'])){
                                                    $active = 'selected="selected"';
                                                    $selectedDone = true;
                                                }
                                                else {
                                                    $active = '';
                                                }
                                                ?>
                                                <option <?php echo $active?> value="<?php echo $v['id']?>" data-content='<span class="multiple-select-option-label"><?php echo $v['name']?></span>'><?php echo $v['name']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="backgroundColor" class="col-sm-3 control-label">Event Color</label>
                                    <div class="col-sm-9">
                                        <div class="form-control" style="padding-bottom: 2px;">
                                            <span style="background-color: #3a87ad" class="color-box color-box-selected" data-color="#3a87ad">&nbsp;✔</span>
                                            <span style="background-color: #eaff00" class="color-box" data-color="#eaff00">&nbsp;</span>
                                            <span style="background-color: #f903a5" class="color-box" data-color="#f903a5">&nbsp;</span>
                                            <span style="background-color: #1a9b05" class="color-box" data-color="#1a9b05">&nbsp;</span>
                                            <span style="background-color: #0c2ddd" class="color-box" data-color="#0c2ddd">&nbsp;</span>
                                            <span style="background-color: #ff4206" class="color-box" data-color="#ff4206">&nbsp;</span>
                                            <span style="background-color: #17cccc" class="color-box" data-color="#17cccc">&nbsp;</span>
                                            <span style="background-color: #0a0003" class="color-box" data-color="#0a0003">&nbsp;</span>
                                            <span style="background-color: #a8a8a8" class="color-box" data-color="#a8a8a8">&nbsp;</span>
                                        </div>
                                        <input type="hidden" name="backgroundColor" id="backgroundColor" value="#3a87ad" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-sm-3 control-label">Alert</label>
                                    <div class="col-sm-9">
                                        <div class="col-sm-4">
                                            <select name="reminder_type" id="reminder_type" class="form-control">
                                                <option value="email">Email</option>
                                                <option value="popup">Popup</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4" style="padding-left: 5px;">
                                            <select name="reminder_time" id="reminder_time" class="form-control">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                                <option value="25">25</option>
                                                <option value="30">30</option>
                                                <option value="45">45</option>
                                                <option value="50">50</option>
                                                <option value="55">55</option>
                                                <option value="60">60</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4" style="padding-left: 5px">
                                            <select name="reminder_time_unit" id="reminder_time_unit" class="form-control">
                                                <option value="minute">Minute</option>
                                                <option value="hour">Hour</option>
                                                <option value="day">Day</option>
                                                <option value="week">Week</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="free_busy" class="col-sm-3 control-label">Show as</label>
                                    <div class="col-sm-9">
                                        <select name="free_busy" id="free_busy" class="form-control">
                                            <option value="free">Free</option>
                                            <option value="busy">Busy</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="privacy" class="col-sm-3 control-label">Privacy</label>
                                    <div class="col-sm-9">
                                        <select name="privacy" id="privacy" class="form-control ">
                                            <option value="public">Public</option>
                                            <option value="private">Private</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <!-- Standard Settings Ends -->


</div>



</fieldset>
</div>
<div class="modal-footer">
    <input type="hidden" value="" name="update-event" id="update-event" />
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" id="create-event">Create Event</button>
</div>
</form>

</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->