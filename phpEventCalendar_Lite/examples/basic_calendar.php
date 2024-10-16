<?php
require_once("../conf.php");
?>
<?php
$pec = new C_PhpEventCal();

//==== Setting Properties
$pec->header();
//$pec->firstDay(2);
//$pec->weekends();
//$pec->weekMode('liquid');
//$pec->weekNumbers(true);
$pec->height(580);

//$pec->contentHeight(400);
//$pec->slotMinutes(50);
//$pec->defaultView('month'); //month,basicWeek,agendaWeek,basicDay,agendaDay
$pec->buttonText(array('prev'=>'Prev','next'=>'Next'));

//===Each Event as a form of Array
$events = array(
    array('id'=>100,'title'=>'My Event 1','start'=>'2013-11-15'),
    array('id'=>101,'title'=>'My Event 2','start'=>'2013-11-05','end'=>'2010-01-01'),
    array('id'=>102,'title'=>'My Event 3','start'=>'2013-11-19 12:30:00','allDay'=>false)
);
$pec->events($events);


/*
$moreEvents = array(
    array('title'=>'event6','start'=>'2013-11-17'),
    array('title'=>'event7','start'=>'2013-11-04','end'=>'2010-01-01'),
    array('title'=>'event8','start'=>'2013-11-20 12:30:00','allDay'=>false)
);

//==============================================
//TODO:Event Source is not working at the moment
$pec->eventSources(
    array('events'=>$moreEvents,'color'=>'red','textColor'=>'green','backgroundColor'=>'gray')
);
*/
//====================================================
//TODO: Google Event Feed is not working at the moment
//$pec->events('http://www.google.com/calendar/feeds/developer-calendar@google.com/public/full?alt=json-in-script','json');

$pec->editable(true);

$pec->dragOpacity(.2);
//$pec->allDaySlot(true);
//$pec->fcFunction('viewRender',array());
//$pec->handleWindowResize(true);
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php echo $pec->display('head');?>
</head>
<body>
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


<?php
//=====display
$pec->display();

?>



</body>
</html>
