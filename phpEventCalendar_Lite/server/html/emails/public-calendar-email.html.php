<?php
ob_start();
?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>
    <div>

        <p>
            Your friend,
            <a target="_blank" href="#">sadmin@fullcalendar.com</a>, has sent you the following Event Calendar and included this message:
        </p>
        <p> <?php echo $message; ?> </p>
        <p>
            <a target="_blank" href="<?php echo $link; ?>">View calendar</a>
        </p>
    </div>
</body>
</html>
<?php
$publicCalendarEmail = ob_get_clean();
?>