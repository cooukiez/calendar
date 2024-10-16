<?php
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body style="font-family: verdana">
<table style="border: 1px solid; width: 90%; font-size: 16px; padding: 10px">
    <thead>
    <tr>
        <td colspan="2">
            <p style="float: left; font-size: 20px;"><b><?php echo $eventData['title']?></b></p><p style="float: right;"><a href="#"> More Details >> </a></p>
        </td>
    </tr>

    </thead>
    <tbody>
    <tr>
        <td style="color: #666;">When</td><td><?php echo date('D M d, Y H:i A',$eventData['start_timestamp']) ?> - <?php echo date('D M d, Y H H:i A',$eventData['end_timestamp']) ?> <?php echo date('e',$eventData['start_timestamp']) ?></td>
    </tr>
    <tr>
        <td style="color: #666;">Calendar</td><td>&nbsp;</td>
    </tr>
    <tr>
        <td style="color: #666;">Who</td><td>
            <ul>
                <?php foreach($guests as $k=>$gData) { ?>
                <li><?php echo $gData['email'] ?></li>
                <?php } ?>
            </ul>
        </td>
    </tr>
    </tbody>
    <tfoot style="border-top: 1px solid; background-color: #CCC; color: #333333" >
    <tr>
        <td colspan="2">
            <p>Invitation from PHP Event Calendar</p>
            <p>You are receiving this courtesy email at the account<?php echo $guestData['email'] ?> because you are an attendee of this event. To stop receiving future notification for this event, decline this event. Alternatively you can sign up for a account at http://www.phpeventcal.com and control your notification setting for your entire calendar.</p>
        </td>
    </tr>

    </tfoot>
</table>
</body>
</html>
<?php
    $reminderEmail = ob_get_clean();
?>