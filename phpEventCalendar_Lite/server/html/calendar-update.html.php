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

    .cal-color-box:hover {
        border: 0 solid;
    }

    .cal-color-box:active {
        border-radius: 0;
    }

    .color-box-selected {
        border-radius: 0;
    }

    .list-group-item-manage {
        position: relative;
        display: block;
        padding: 10px 15px;
        margin-bottom: -1px;
        background-color: #ffffff;
        border: 1px solid #dddddd;
    }

    .public {
        cursor: pointer;
        width: 100px;
        /*border-radius: 0px 5px 5px 0px;*/
        text-align: center;
        float: right;
        color: #FFFFFF;
    }

    .editCal {
        width: 50px;
        cursor: pointer;
        text-align: center;
        float: right;
        color: #FFFFFF;
    }

    .deleteCal {
        width: 60px;
        cursor: pointer;
        text-align: center;
        float: right;
        color: #FFFFFF;
    }

    .exportCal {
        width: 60px;
        cursor: pointer;
        text-align: center;
        float: right;
        color: #FFFFFF;
    }

    .cactionss div {
        margin-right: 3px;
    }

    #shareCalendar {
        margin-top: 10px;
    }

</style>
<div class="modal fade" id="myModalCalendarManage" tabindex="-1" role="dialog"
     aria-labelledby="myModalCalendarManageLabel" aria-hidden="true" style="text-align:left;">
    <div class="modal-dialog">
        <div class="modal-content">

            <?php include(SERVER_HTML_INCLUDE_DIR .'unlock-massage.html.php') ?>
            <img src="<?php echo BASE_URL?>images/manage-calendar-demo.png" />

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->