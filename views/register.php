<div class="wrap">
  <div class="icon32" id="icon-edit"><br></div>
  <h2><?php echo __('Activate your adwit-express account', 'adwit');?></h2>
  <h3><?php echo __('Adwit express slogan', 'adwit');?></h3>
  <a href="<?php echo get_bloginfo('wpurl')?>/wp-admin/admin.php?page=adwit&out=login"><?php echo __('Already go an account ?', 'adwit');?></a>
</div>
<div style="width: 400px">
  <div class="form-wrap">
    <form id="post" method="post" name="post">
      <input type="hidden" name="page" value="adwit">
      <?php 
        if (isset($error['global'])) {echo '<p style="color: #ff0000">'.$error['global'].'</p>';}
      ?>
      <div class="form-field form-required">
        <label for="tag-email">Email</label>
        <input type="text" aria-required="true" size="40" value="<?php
  if (isset($_REQUEST['tag-email'])) {
    echo $_REQUEST['tag-email'];
  } else {
    echo $current_user->data->user_email;
  }
?>" id="tag-email" name="tag-email" gtbfieldid="102">
      <p>Note: Email will not be publicly displayed.</p>
    </div>
    <div class="form-field form-required">
      <label for="tag-password"><?php echo __('Choice a password', 'adwit');?></label>
      <input type="password" aria-required="true" size="40" value="" id="tag-password" name="tag-password" gtbfieldid="102">
      <?php if (isset($error['password'])) {echo '<p style="color: #ff0000">'.$error['password'].'</p>';}?>
    </div>
    <div class="form-field form-required">
      <label for="tag-password-confirmation"><?php echo __('Confirm your password', 'adwit');?></label>
      <input type="password" aria-required="true" size="40" value="" id="tag-password-confirmation" name="tag-password-confirmation" gtbfieldid="102">
    </div>
    <div>
      <input type="submit" value="<?php echo __('Create an account', 'adwit');?>" accesskey="p" tabindex="5" id="publish" class="button-primary" name="publish">
    </div>
  </form>
</div>