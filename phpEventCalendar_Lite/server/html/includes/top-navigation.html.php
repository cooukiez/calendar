<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img src="<?php echo BASE_URL?>images/pec-logo.png" alt="PEC logo" width="70" /></a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="calendar.php">Home</a></li>
                <?php
                    if(!isset($privacy)){
                ?>
                <li><a href="<?php echo BASE_URL?>logout.php">Logout</a></li>
                <?php
                    }
                ?>
                <li>
                    <script>
                        // Include the UserVoice JavaScript SDK (only needed once on a page)
                        UserVoice=window.UserVoice||[];(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/YeozGEz9hA0eZMJyToOKag.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})();

                        //
                        // UserVoice Javascript SDK developer documentation:
                        // https://www.uservoice.com/o/javascript-sdk
                        //

                        // Set colors
                        UserVoice.push(['set', {
                            accent_color: '#448dd6',
                            trigger_color: 'white',
                            trigger_background_color: '#6aba2e'
                        }]);

                        // Identify the user and pass traits
                        // To enable, replace sample data with actual user traits and uncomment the line
                        UserVoice.push(['identify', {
                            //email:      'john.doe@example.com', // User’s email address
                            //name:       'John Doe', // User’s real name
                            //created_at: 1364406966, // Unix timestamp for the date the user signed up
                            //id:         123, // Optional: Unique id of the user (if set, this should not change)
                            //type:       'Owner', // Optional: segment your users by type
                            //account: {
                            //  id:           123, // Optional: associate multiple users with a single account
                            //  name:         'Acme, Co.', // Account name
                            //  created_at:   1364406966, // Unix timestamp for the date the account was created
                            //  monthly_rate: 9.99, // Decimal; monthly rate of the account
                            //  ltv:          1495.00, // Decimal; lifetime value of the account
                            //  plan:         'Enhanced' // Plan name for the account
                            //}
                        }]);

                        // Add default trigger to the bottom-right corner of the window:
                        UserVoice.push(['addTrigger', { mode: 'contact', trigger_position: 'top-right', contact_title: 'Please send us your feedback' }]);

                        // Or, use your own custom trigger:
                        //UserVoice.push(['addTrigger', '#id', { mode: 'contact' }]);

                        // Autoprompt for Satisfaction and SmartVote (only displayed under certai
                        // n conditions)
                        UserVoice.push(['autoprompt', {}]);

                        // Richard: Show the Uservoice feedback widget automatically 5 seconds after page load
                        // setTimeout(function(){UserVoice.show();}, 5000);
                    </script><div id="feedback_link"></div></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
