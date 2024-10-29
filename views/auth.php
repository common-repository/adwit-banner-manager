<style type="text/css">
    .adwit-login-error {color:red}
</style>

<div style="margin-top: 10px;margin-left: 30px;">
    <form method="post" action="">
	<input type="hidden" value="user_set" name="adwit_action"/>
	<div class="adwit-login-error">
	    <?php
	    if (isset($adwit_recovery) && $adwit_recovery =='B'){
		echo __('Invalid email or password!', 'adwit');
	    } else {
		echo __('You already got an adwit-express account, please logged in','adwit');
	    }?>
        </div>
	<div>&nbsp;</div>
        <div>
	    <?php
		echo __('Login to Adwit-Express platform', 'adwit');
	    ?>
        </div>
	<div>&nbsp;</div>
	<div>
	    <?php
		echo __('Email', 'adwit');
	    ?>
	</div>

	<div>
	    <input type="text" size="30" name="adwit_email" value="<?php echo $current_user->user_email;?>">
	</div>

	<div>
	    <?php
		echo __('Password', 'adwit');
	    ?>
	</div>

	<div>
	    <input type="password" size="30" name="adwit_pass" value=""/>
	</div>

        <div>
	    <br>
	    <a href="<?php echo ADWIT_HOME_URL; ?>/public/lost_password?user[email]=<?php echo $current_user->user_email;?>" target="_blank">
		<?php
		echo __('Forgot password?', 'adwit');
		?>
            </a>
        </div>
	<div>&nbsp;</div>
	<div>
            <input type="submit" value="Login" name="<?php echo __('Login', 'adwit');?>"/>
        </div>

    </form>
</div>