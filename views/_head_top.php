<?php function adwit_head_top($adwit_ads, $adwit_ban_list, $page) { ?>
  <div id="head_top" style="position: relative; top: 0px; width: 240px; background-color: #ffffff;text-align: center; float: left;min-height: 20px">
    <div id="ht3" style="float: right;<?php echo adwit_get_width_and_height_for_div($adwit_ban_list, $adwit_ads[$page.'_head_right']); ?>" onclick="clear_my_obj(this);"><?php
      if (isset($adwit_ads[$page.'_head_right'])){
        echo '<img src="' .ADWIT_BANNER_IMAGES .'/' .$adwit_ads[$page.'_head_right']['img_name'].'" alt=""/>';
      }?></div>
    <div id="ht1" style="float: left;<?php echo adwit_get_width_and_height_for_div($adwit_ban_list, $adwit_ads[$page.'_head_left']); ?>" onclick="clear_my_obj(this);"><?php
      if (isset($adwit_ads[$page.'_head_left'])){
        echo '<img src="' .ADWIT_BANNER_IMAGES .'/' .$adwit_ads[$page.'_head_left']['img_name'].'" alt=""/>';
      }?></div>
    <div id="ht2" style="margin:0 auto;<?php echo adwit_get_width_and_height_for_div($adwit_ban_list, $adwit_ads[$page.'_head_center']); ?>" onclick="clear_my_obj(this);"><?php
      if (isset($adwit_ads[$page.'_head_center'])){
        echo '<img src="' .ADWIT_BANNER_IMAGES .'/' .$adwit_ads[$page.'_head_center']['img_name'].'" alt=""/>';
      }?></div>
    </div>
<script type="text/javascript">
    Droppables.add('head_top', {
        accept: 'adwit_banner',
        onDrop: function(dragged, dropped, event) {
            if ( $('head_top').offsetLeft +(dragged.offsetWidth/2) > dragged.offsetLeft) {
                obj_updated = 'ht1';
                $(obj_updated).style.marginRight ='5px';
                pos_h = 'left';
            } else if ((2*(dragged.offsetLeft+dragged.offsetWidth-$('head_top').offsetLeft) > $('head_top').offsetWidth) &
                (2*(dragged.offsetLeft-$('head_top').offsetLeft) < $('head_top').offsetWidth)) {
                obj_updated = 'ht2';
                pos_h = 'center';
            } else {
                obj_updated = 'ht3';
                $(obj_updated).style.marginLeft ='5px';
                pos_h = 'right';
            }
            copy_style_element(obj_updated, dragged);
            $(obj_updated).style.border="0px dashed #666666";
            $('head_top').highlight();
            wp_add_adwit_banner(ptype, 'head', pos_h, dragged.id)
        }
    });
</script>
<?php 
  } 
?>