<style type="text/css">
.adwit_banner {
    font-size:12px;
    margin:5px;
    float: left;
    text-align:center;}
</style>
<script type="text/javascript">
function _adwit_change_post_type(){
    document.awitfrm.submit();
}
</script>
<div class="wrap">
  <div class="icon32" id="icon-edit"><br></div>
  <h2><?php echo __('Adwit Banner Manager - Choice of advertising zones', 'adwit')?>
<p class="search-box">
<a href="http://www.adwit-express.com/public/contact" class = "button add-new-h2" target = 'blank'><?php echo __('Need Help ?') ?></a>
</p>

  </h2> 
<?php echo __('Choose the type of page on which you want to display ads, then drag and drop the ad formats that you want to install on your blog<br/>If you need to add specific size zones, specific locations (ie: header), or because of your blog template you cannot ideally place your advertising zones using the “drag and drop” tool, please <a href=\"http://www.adwit-express.com/help/Adding-customized-zones-from-your-Adwit-Banner-Manager-account.html\">click here</a> to see how to configure customized advertising zones.', 'adwit') ?>
</div>
<div style="margin-top: 10px;margin-left: 30px;width: 240px;">
    <form name="awitfrm" action="<?php echo get_bloginfo('wpurl')?>/wp-admin/admin.php?page=adwit_setup" method="post">
<?php echo __('Post type','adwit') ?>
      <select id="post_type" name="post_type" onchange="_adwit_change_post_type();">
        <option value="page" <?php echo ($_POST['post_type']=='page' ? ' selected=true' : '');?>><?php echo __('Page', 'adwit') ?></option>
        <option value="post" <?php echo ($_POST['post_type']=='post' ? ' selected=true' : '');?>><?php echo __('Post', 'adwit') ?></option>
        <option value="home" <?php echo ($_POST['post_type']=='home' ? ' selected=true' : '');?>><?php echo __('Home', 'adwit') ?></option>
        <option value="search" <?php echo ($_POST['post_type']=='search' ? ' selected=true' : '');?>><?php echo __('search', 'adwit') ?></option>
    </select> 
    </form>
</div>
<div id="view_post_type">
<?php

if (file_exists(ADWIT_BANNER_MANAGER_PATH . '/views/'.$view_page.'.php')) {
    $adwit_ban_list = Adwit_Banner_Manager_Admin::_get_bann_list();
    include(ADWIT_BANNER_MANAGER_PATH . '/views/'.$view_page.'.php');
}
?>
</div>
<?php
  function adwit_get_width_and_height_for_div($adwit_ban_list, $obj) {
    return 'width: '.$adwit_ban_list[$obj['width']."x".$obj['height']]['width'].';height: '.$adwit_ban_list[$obj['width']."x".$obj['height']]['height'];
  }
?>
<div style="float: left; margin-top: 15px;  margin-left: 50px; width:340px;  border: 1px solid #777777;height: 300px">
<?php
  foreach ($adwit_ban_list as $bkey => $bval){
    ?>
<div id="<?php echo 'ban'.$bkey?>"   class="<?php echo $bval['class']?>" style="width: <?php echo $bval['width']?>; height: <?php echo $bval['height']?>; background-color: <?php echo $bval['bgcolor']?>;"><img src="<?php echo ADWIT_BANNER_IMAGES; ?>/<?php echo $bkey;?>.png" alt=""/></div>
<?php
  }?>
<div id="comment" style="float: left;font-size: 10px;line-height: 12px;margin: 5px;">
<?php echo __("* Warning! Vertical banners (160x600, 120x600) may deform your pages, so it is recommended to use them in widgets rather than here in the page content.", adwit)?><br/>
<?php echo __("* Depending of your template, it may be that banners located on your page footer cannot be activated (Some templates prohibiting the use of script)", adwit)?><br/><br/>
<?php echo __("<font color='#ff0000'>* Once you have set the banner, just click on it to disable<br/>", adwit)?></font>
</div>
</div>
<?php include(ADWIT_BANNER_MANAGER_PATH . '/views/_help_square.php');?>

<script type="text/javascript">
    new Draggable('ban468x60', {revert: true });
    new Draggable('ban728x90', { revert: true });
    new Draggable('ban160x600', { revert: true });
    new Draggable('ban120x600', { revert: true });
    new Draggable('ban200x200', { revert: true });
    new Draggable('ban250x250', { revert: true });
    new Draggable('ban300x250', { revert: true });
    new Draggable('ban120x240', { revert: true });
    new Draggable('ban336x280', { revert: true });
    new Draggable('ban180x150', { revert: true });
    new Draggable('ban125x125', { revert: true });
    new Draggable('ban234x60', { revert: true });

    var posttype = document.getElementById('post_type');
    var ptype = posttype.options[posttype.selectedIndex].value;

    function clear_my_obj(obj) {
        obj.innerHTML='';
        obj.style.height='1px';
        obj.style.width='1px';
        obj.style.backgroundColor='#ffffff';
        obj.style.lineHeight='5px';
        //alert(obj.id);
        wp_remove_adwit_banner(ptype, obj.id)
          /* todo appel interne pour desactivé une zone (qui lui appeller adwit) */
    }

    function wp_add_adwit_banner(post_type, pos_v, pos_h, bsize) {
        var url = '<?php echo get_bloginfo('wpurl')?>/wp-admin/admin.php';
        new Ajax.Request(url, {
            method: 'post',
            parameters: { page: 'adwit_setup',
                adwit_action: 'adwit_add_banner',
                post_type: post_type,
                pos_v: pos_v,
                pos_h: pos_h,
                bsize: bsize
            },
            onSuccess: function(transport) {
                //somme proccess
            }
        });
    }

    function wp_remove_adwit_banner(post_type, pos) {
        var url = '<?php echo get_bloginfo('wpurl')?>/wp-admin/admin.php';
        new Ajax.Request(url, {
            method: 'post',
            parameters: { page: 'adwit_setup',
                adwit_action: 'adwit_remove_banner',
                post_type: post_type,
                pos: pos
            },
            onSuccess: function(transport) {
                //somme proccess
            }
        });
    }

    function copy_style_element(source, dest) {
        $(source).style.width=dest.style.width;
        $(source).style.height=dest.style.height;
        $(source).style.backgroundColor=dest.style.backgroundColor;
        $(source).style.lineHeight=dest.style.lineHeight;
        $(source).update(dest.innerHTML);
    }
</script>

<div style="clear:left; height: 30px;"></div>

<div class="tablenav">
	<div class="alignleft actions">
	<?php echo __("Once you have set up display zones, you may add one or more Adwit widgets (\"Widgets\"  on the Wordpress menu), then click on the \"Ad manager\" menu to insert ads", adwit)?>
	<br/>		
	<a target="_blank" href="http://www.youtube.com/user/adwitserver#p/u/2/33t1V-bkt6c">
		<?php echo __("How to easily Setup your display zones", adwit)?></a><br/>
	</div>	
</div>
