<!--
|   ====================================================================================================================
|   JS FOR USER
|   ====================================================================================================================
-->
<script type="text/javascript">
    $(document).ready(function (){
        $('#login').click(function (){
            var l = Ladda.create(this);
            l.start();

            var formData = $('#loginForm').serialize();

            var jqxhr = $.ajax({
                type: "POST",
                url: "<?php echo ABS_PATH?>/server/ajax/user_manager.php",
                data: formData
            })
            .done(function(message) {
                var m = message.split('#');
                $(".alert").html(m[1]);
                if(m[0].trim() == 'success'){
                    $(".alert").removeClass('alert-danger');
                    $(".alert").addClass('alert-success');
                    location.href = '<?php echo ABS_PATH?>/calendar.php';
                }
                if(m[0].trim() == 'error'){
                    $(".alert").removeClass('alert-success');
                    $(".alert").addClass('alert-danger');
                }
                $(".alert").fadeIn();
                l.stop();
            })
            .fail(function() {
                $(".alert").html('Something went wrong, please try later. Thanks');
                $(".alert").removeClass('alert-success');
                $(".alert").addClass('alert-danger');
                l.stop();
            })
            return false;
        });

    });
</script>

