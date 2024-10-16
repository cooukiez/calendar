<style>

    body {
        margin-top: 40px;
        text-align: center;
        font-size: 14px;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    }
    /*
            #calendar {
                width: 900px;
                margin: 0 auto;
            }
    */
</style>

<div id="pec_toolbar">

    <div class="input-group col-md-3"  style="padding-left: 0; margin-bottom: 10px; padding-right: 2px;">
        <input id="search-event-input" type="search" class="form-control" placeholder="Search" name="search" />
        <span class="input-group-addon btn-info ladda-button" data-style="expand-right" id="search-btn" style="cursor: pointer; cursor: hand;"><span class="ladda-label"><span class="glyphicon glyphicon-search"> </span></span></span>
    </div>


    <ul class="nav nav-pills" style="float: right">
        <li><a href="javascript:void(0);" id="cal-settings-link">Settings</a></li>
        <li><a href="javascript: window.print();">Print</a></li>
    </ul>

    <?php require_once ('includes/calendar-search.html.php'); ?>

</div>

<div style="clear: both"></div>
<div id="calendar"></div>
