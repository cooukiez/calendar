<?php
//====Load all calendars
$allCals = new C_Calendar('LOAD_MY_CALENDARS');

//====Load calendar properties
$calendarProperties = $allCals->calendarProperties;

//====Load calendars
$allCalendars = $allCals->allCalendars;

?>
<style>

    .guest-view {
        border: 0px none;
        box-shadow: none;
        padding-left: 0px;
        padding-right: 0px;
        width: 90%;
        float: left;
    }

    .close_guest {
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
        border: medium none;
        height: 21px;
        vertical-align: middle;
        border-bottom: 1px dotted #d4d4d4;
    }

    .close_reminder {
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
        border: medium none;
        height: 32px;
        margin-left: 0;
        padding-left: 0;
        vertical-align: middle;
    }

    .standard {
        display: none;
    }

    .reminder {
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

    .time-panel {
        background: none repeat scroll 0 0 #FAFAFA;
        border: 1px solid #D4D4D4;
        height: 140px;
        overflow: auto;
        position: absolute;
        width: 100px;
        z-index: 99999;
        display: none;
    }

    .time-panel-ul {
        width: 100%;
    }
    .time-panel-ul li {
        border: 1px solid #F0F0F0;
        float: none;
        list-style: none outside none;
        margin-left: -40px;
        padding: 0;
        text-align: left;
        width: 81px;
        border-right: 0;
        cursor: pointer;
        padding-left: 12px;
    }
    .time-panel-ul li:hover{
        background-color: #3A87AD;
        color: #ffffff;
    }
    #guest-list {
        margin-top: 5px;
        height: 100px;
        border: 1px solid #d9d9d9;
        border-radius: 4px;
        overflow:auto;
        padding-left: 4px;
        width: 345px;
        background-color: #F9F9F9;
    }
    #guest-list div {
        height: 22px;
        margin-top: 1px;
        margin-bottom: 1px;
    }
    #guest-list input {
        background-color: #F9F9F9;
        border-bottom: 1px dotted #D4D4D4;
        border-radius: 0;
        font-size: 13px;
        height: 22px;
        margin-bottom: 0;
        margin-top: 0;
        padding: 0;
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
            <div style="margin: 2px 20px 0px 4px; float: right; display: none" id="remove-block">
                <button type="button" class="btn btn-danger btn-xs ladda-button" data-style="expand-left" data-event-id="" id="remove-link"><span class="ladda-label">Remove This Event</span></button>
            </div>
            <div style="clear: both"></div>
            <form role="form" id="eventForm" class="form-horizontal">
                <div class="modal-body" style="padding-top: 10px">
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
                                    <label for="start-date" class="col-sm-3 control-label">Start</label>
                                    <div class="input-group col-sm-6 date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="start" data-link-format="yyyy-mm-dd" >
                                        <input type="text" class="form-control" id="start-date" name="start-date" placeholder="Start Date" readonly="readonly" style="background-color: white; cursor: default;" />
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                    <div class="col-sm-3">
                                        <input name="start-time" id="start-time" class="form-control"  readonly="readonly" style="background-color: white; cursor: default;"/>
                                        <div class="time-panel" id="time-panel-start">
                                            <ul class="time-panel-ul">
                                                <li data-value="00:00">00:00 AM</li>
                                                <li data-value="00:30">00:30 AM</li>
                                                <li data-value="01:00">01:00 AM</li>
                                                <li data-value="01:30">01:30 AM</li>
                                                <li data-value="02:00">02:00 AM</li>
                                                <li data-value="02:30">02:30 AM</li>
                                                <li data-value="03:00">03:00 AM</li>
                                                <li data-value="03:30">03:30 AM</li>
                                                <li data-value="04:00">04:00 AM</li>
                                                <li data-value="04:30">04:30 AM</li>
                                                <li data-value="05:00">05:00 AM</li>
                                                <li data-value="05:30">05:30 AM</li>
                                                <li data-value="06:00">06:00 AM</li>
                                                <li data-value="06:30">06:30 AM</li>
                                                <li data-value="07:00">07:00 AM</li>
                                                <li data-value="07:30">07:30 AM</li>
                                                <li data-value="08:00">08:00 AM</li>
                                                <li data-value="08:30">08:30 AM</li>
                                                <li data-value="09:00">09:00 AM</li>
                                                <li data-value="09:30">09:30 AM</li>
                                                <li data-value="10:00">10:00 AM</li>
                                                <li data-value="10:30">10:30 AM</li>
                                                <li data-value="11:00">11:00 AM</li>
                                                <li data-value="11:30">11:30 AM</li>
                                                <li data-value="12:00">12:00 PM</li>
                                                <li data-value="12:30">12:30 PM</li>
                                                <li data-value="13:00">01:00 PM</li>
                                                <li data-value="13:30">01:30 PM</li>
                                                <li data-value="14:00">02:00 PM</li>
                                                <li data-value="14:30">02:30 PM</li>
                                                <li data-value="15:00">03:00 PM</li>
                                                <li data-value="15:30">03:30 PM</li>
                                                <li data-value="16:00">04:00 PM</li>
                                                <li data-value="16:30">04:30 PM</li>
                                                <li data-value="17:00">05:00 PM</li>
                                                <li data-value="17:30">05:30 PM</li>
                                                <li data-value="18:00">06:00 PM</li>
                                                <li data-value="18:30">06:30 PM</li>
                                                <li data-value="19:00">07:00 PM</li>
                                                <li data-value="19:30">07:30 PM</li>
                                                <li data-value="20:00">08:00 PM</li>
                                                <li data-value="20:30">08:30 PM</li>
                                                <li data-value="21:00">09:00 PM</li>
                                                <li data-value="21:30">09:30 PM</li>
                                                <li data-value="22:00">10:00 PM</li>
                                                <li data-value="22:30">10:30 PM</li>
                                                <li data-value="23:00">11:00 PM</li>
                                                <li data-value="23:30">11:30 PM</li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" id="end-group">
                                    <label for="end" class="col-sm-3 control-label">End</label>
                                    <div class="input-group col-sm-6 form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="end" data-link-format="yyyy-mm-dd" >
                                        <input type="text" class="form-control" placeholder="End Date" name="end-date" id="end-date" readonly="readonly" style="background-color: white; cursor: default;" />
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                    <div class="col-sm-3">
                                        <input name="end-time" id="end-time" class="form-control"  readonly="readonly" style="background-color: white; cursor: default;" />
                                        <div class="time-panel" id="time-panel-end">
                                            <ul class="time-panel-ul">
                                                <li data-value="00:00">00:00 AM</li>
                                                <li data-value="00:30">00:30 AM</li>
                                                <li data-value="01:00">01:00 AM</li>
                                                <li data-value="01:30">01:30 AM</li>
                                                <li data-value="02:00">02:00 AM</li>
                                                <li data-value="02:30">02:30 AM</li>
                                                <li data-value="03:00">03:00 AM</li>
                                                <li data-value="03:30">03:30 AM</li>
                                                <li data-value="04:00">04:00 AM</li>
                                                <li data-value="04:30">04:30 AM</li>
                                                <li data-value="05:00">05:00 AM</li>
                                                <li data-value="05:30">05:30 AM</li>
                                                <li data-value="06:00">06:00 AM</li>
                                                <li data-value="06:30">06:30 AM</li>
                                                <li data-value="07:00">07:00 AM</li>
                                                <li data-value="07:30">07:30 AM</li>
                                                <li data-value="08:00">08:00 AM</li>
                                                <li data-value="08:30">08:30 AM</li>
                                                <li data-value="09:00">09:00 AM</li>
                                                <li data-value="09:30">09:30 AM</li>
                                                <li data-value="10:00">10:00 AM</li>
                                                <li data-value="10:30">10:30 AM</li>
                                                <li data-value="11:00">11:00 AM</li>
                                                <li data-value="11:30">11:30 AM</li>
                                                <li data-value="12:00">12:00 PM</li>
                                                <li data-value="12:30">12:30 PM</li>
                                                <li data-value="13:00">01:00 PM</li>
                                                <li data-value="13:30">01:30 PM</li>
                                                <li data-value="14:00">02:00 PM</li>
                                                <li data-value="14:30">02:30 PM</li>
                                                <li data-value="15:00">03:00 PM</li>
                                                <li data-value="15:30">03:30 PM</li>
                                                <li data-value="16:00">04:00 PM</li>
                                                <li data-value="16:30">04:30 PM</li>
                                                <li data-value="17:00">05:00 PM</li>
                                                <li data-value="17:30">05:30 PM</li>
                                                <li data-value="18:00">06:00 PM</li>
                                                <li data-value="18:30">06:30 PM</li>
                                                <li data-value="19:00">07:00 PM</li>
                                                <li data-value="19:30">07:30 PM</li>
                                                <li data-value="20:00">08:00 PM</li>
                                                <li data-value="20:30">08:30 PM</li>
                                                <li data-value="21:00">09:00 PM</li>
                                                <li data-value="21:30">09:30 PM</li>
                                                <li data-value="22:00">10:00 PM</li>
                                                <li data-value="22:30">10:30 PM</li>
                                                <li data-value="23:00">11:00 PM</li>
                                                <li data-value="23:30">11:30 PM</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--- Action Links -->

                        <div class="well well-sm" style="margin-top: 10px">
                            <span class="basic">
                               <label for="allDay" style="padding-right: 5px">
                                <input type="checkbox" name="allDay" id="allDay"> All Day
                               </label>
                               <label for="repeat" style="padding-right: 5px">
                                <input type="checkbox" name="repeat" id="repeat" value="1"> Repeat
                               </label>
                               &nbsp;
                               <div class="show-link" style="float: right; padding-right: 5px;">
                               <a href="javascript:void(0);" id="show-standard-settings">Show Standard Settings</a>
                               </div>
                            </span>

                            <div class="form-inline standard" style="float: right; padding-bottom:3px">
                                <div class="checkbox" style="padding-top: 0; float: right; ">
                                    <label for="hide-standard-settings"  style="padding-right: 5px; ">
                                        <a href="javascript:void(0);" id="hide-standard-settings">Hide Standard Settings</a>
                                    </label>
                                </div>
                            </div>

                            <!-- Repeat Box -->
                            <div class="panel panel-info repeat-box col-sm-12" style="margin-top: 8px; margin-bottom: 8px;">


                                <?php include(SERVER_HTML_INCLUDE_DIR .'unlock-massage.html.php') ?>
                                <img src="<?php echo BASE_URL?>images/event-repeat-demo.png" />


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
                                    <label for="select-calendar" class="col-sm-3 control-label">Calendars</label>
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
                                                <option data-color="<?php echo $v['color']?>" <?php echo $active?> value="<?php echo $v['id']?>" data-content='<span class="multiple-select-option-label"><?php echo htmlspecialchars($v['name'], ENT_QUOTES);?></span>'><?php echo htmlspecialchars($v['name'], ENT_QUOTES);?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="backgroundColor" class="col-sm-3 control-label">Event Color</label>
                                    <div class="col-sm-9">
                                        <div class="form-control" style="padding-bottom: 2px;;white-space:nowrap">
                                            <span style="background-color: #3a87ad" class="color-box color-box-selected" data-color="#3a87ad" id="cid_3a87ad">&nbsp;✔</span>
                                            <span style="background-color: #eaff00" class="color-box" data-color="#eaff00" id="cid_eaff00">&nbsp;</span>
                                            <span style="background-color: #f903a5" class="color-box" data-color="#f903a5" id="cid_f903a5">&nbsp;</span>
                                            <span style="background-color: #1a9b05" class="color-box" data-color="#1a9b05" id="cid_1a9b05">&nbsp;</span>
                                            <span style="background-color: #0c2ddd" class="color-box" data-color="#0c2ddd" id="cid_0c2ddd">&nbsp;</span>
                                            <span style="background-color: #ff4206" class="color-box" data-color="#ff4206" id="cid_ff4206">&nbsp;</span>
                                            <span style="background-color: #17cccc" class="color-box" data-color="#17cccc" id="cid_17cccc">&nbsp;</span>
                                            <span style="background-color: #0a0003" class="color-box" data-color="#0a0003" id="cid_0a0003">&nbsp;</span>
                                            <span style="background-color: #a8a8a8" class="color-box" data-color="#a8a8a8" id="cid_a8a8a8">&nbsp;</span>
                                        </div>
                                        <input type="hidden" name="backgroundColor" id="backgroundColor" value="#3a87ad" />
                                    </div>
                                </div>

                                <div class="form-group" style="display:none">
                                    <label for="eventImage" class="col-sm-3 control-label">Image</label>
                                    <div class="col-sm-6">
                                        <input type="file" name="eventImage" id="eventImage" class="" />
                                        <!--a href="javascript:jQuery('#eventImage').uploadify('cancel')" id="cancel-file">Cancel File</a-->
                                        <input type="hidden" id="imageName" name="imageName" value="" />
                                    </div>
                                    <div class="col-sm-3">
                                        <img id="img-preview" src="" width="100px" style="display: none" alt="No Image">
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


                        <div class="well well-sm" style="margin-top: 10px; min-height: 40px;">
                            <span class="basic-remind">
                               <div class="show-link-remind" style="float: right; padding-right: 5px;">
                                   <a href="javascript:void(0);" id="show-reminder-settings">Show Reminder Settings</a>
                               </div>
                            </span>

                            <div class="form-inline reminder" style="float: right; padding-bottom:3px">
                                <div class="checkbox" style="padding-top: 0; float: right; ">
                                    <label for="hide-reminder-settings-settings"  style="padding-right: 5px; ">
                                        <a href="javascript:void(0);" id="hide-reminder-settings">Hide Reminder Settings</a>
                                    </label>
                                </div>
                            </div>

                            <div class="reminder col-sm-12" style="margin-top: 8px">

                                <?php include(SERVER_HTML_INCLUDE_DIR .'unlock-massage.html.php') ?>
                                <img src="<?php echo BASE_URL?>images/event-reminder-demo.png" />


                            </div>

                            <!-- Standard Settings Ends -->


                        </div>



                    </fieldset>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="-1" name="update-event" id="update-event" />
                    <input type="hidden" value="" name="currentView" id="currentView" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="create-event">Create Event</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->