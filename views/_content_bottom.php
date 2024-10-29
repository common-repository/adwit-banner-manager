<?php 
  function adwit_content_bottom($adwit_ads, $adwit_ban_list, $page) { 
  ?>
<div id="content_bottom" style="padding:5px;line-height: 9px;position: relative; top: 0px; width: 230px;background-color: #ffffff;text-align: center; float: left;min-height: 20px;font-size: 7px;">
<div id="cb3" style="float: right;<?php echo adwit_get_width_and_height_for_div($adwit_ban_list, $adwit_ads[$page.'_bottom_right']); ?>" onclick="clear_my_obj(this);"><?php
  if (isset($adwit_ads[$page.'_bottom_right'])){
    echo '<img src="' .ADWIT_BANNER_IMAGES .'/' .$adwit_ads[$page.'_bottom_right']['img_name'].'" alt=""/>';
  }?></div>
<div id="cb1" style="float: left;<?php echo adwit_get_width_and_height_for_div($adwit_ban_list, $adwit_ads[$page.'_bottom_left']); ?>" onclick="clear_my_obj(this);"><?php
  if (isset($adwit_ads[$page.'_bottom_left'])){
    echo '<img src="' .ADWIT_BANNER_IMAGES .'/' .$adwit_ads[$page.'_bottom_left']['img_name'].'" alt=""/>';
  }?></div>
<div id="cb2" style="margin:0 auto;<?php echo adwit_get_width_and_height_for_div($adwit_ban_list, $adwit_ads[$page.'_bottom_center']); ?>" onclick="clear_my_obj(this);"><?php
  if (isset($adwit_ads[$page.'_bottom_center'])){
    echo '<img src="' .ADWIT_BANNER_IMAGES .'/' .$adwit_ads[$page.'_bottom_center']['img_name'].'" alt=""/>';
  }?></div>
</div>
<script type="text/javascript">
    Droppables.add('content_bottom', {
        accept: 'adwit_banner',
        onDrop: function(dragged, dropped, event) {
            if ( $('content_bottom').offsetLeft +(dragged.offsetWidth/2) > dragged.offsetLeft) {
                obj_updated = 'cb1';
                $(obj_updated).style.marginRight ='5px';
                $(obj_updated).style.marginBottom ='5px';
                pos_h = 'left';
            } else if ((2*(dragged.offsetLeft+dragged.offsetWidth-$('content_bottom').offsetLeft) > $('content_bottom').offsetWidth) &
                (2*(dragged.offsetLeft-$('content_bottom').offsetLeft) < $('content_bottom').offsetWidth)){
                obj_updated = 'cb2';
                $(obj_updated).style.marginBottom ='5px';
                pos_h = 'center';
            } else {
                obj_updated = 'cb3';
                $(obj_updated).style.marginLeft ='5px';
                $(obj_updated).style.marginBottom ='5px';
                pos_h = 'right';
            }
            copy_style_element(obj_updated, dragged);
            $('content_bottom').highlight();
            wp_add_adwit_banner(ptype, 'content_bottom', pos_h, dragged.id)
        }
    });
</script>
<?php
  }
  ?>