<?php 
  function adwit_content_middle($adwit_ads, $adwit_ban_list, $page) { 
    ?>
<div id="content_middle" style="line-height: 9px;position: relative; top: 0px; width: 240px; background-color: #ffffff;text-align: center; float: left;min-height: 20px;font-size: 7px;overflow: hidden">
<div id="cm3" style="float: right;<?php echo adwit_get_width_and_height_for_div($adwit_ban_list, $adwit_ads[$page.'_middle_right']); ?>" onclick="clear_my_obj(this);"><?php
  if (isset($adwit_ads[$page.'_middle_right'])){
    echo '<img src="' .ADWIT_BANNER_IMAGES .'/' .$adwit_ads[$page.'_middle_right']['img_name'].'" alt=""/>';
  }?>
</div>
<div id="cm1" style="float: left;<?php echo adwit_get_width_and_height_for_div($adwit_ban_list, $adwit_ads[$page.'_middle_left']); ?>" onclick="clear_my_obj(this);"><?php
  if (isset($adwit_ads[$page.'_middle_left'])){
    echo '<img src="' .ADWIT_BANNER_IMAGES .'/' .$adwit_ads[$page.'_middle_left']['img_name'].'" alt=""/>';
  }?>
</div>
<div id="cm2" style="margin:0 auto;<?php echo adwit_get_width_and_height_for_div($adwit_ban_list, $adwit_ads[$page.'_middle_center']); ?>" onclick="clear_my_obj(this);"><?php
  if (isset($adwit_ads[$page.'_middle_center'])){
    echo '<img src="' .ADWIT_BANNER_IMAGES .'/' .$adwit_ads[$page.'_middle_center']['img_name'].'" alt=""/>';
  }?>
</div>
</div>
<div style="padding:5px;line-height: 9px;position: relative; top: 0px; width: 230px;background-color: #ffffff;text-align: justify; float: left;min-height: 20px;font-size: 7px;">Morbi at quam dui, id pharetra enim. Maecenas purus orci, convallis vel faucibus sit amet, sollicitudin nec nulla. Etiam id nisl non mauris semper convallis. Phasellus et nibh lorem. Praesent rhoncus porta lorem vitae feugiat. Morbi aliquet pulvinar vehicula. Suspendisse potenti. Maecenas vitae augue lorem, non dictum nisi. In at nisl at urna viverra congue. Ut rhoncus eleifend elementum. In facilisis sapien sit amet metus suscipit auctor. Sed pulvinar tempus vulputate. Fusce sollicitudin ornare metus, eu consectetur odio tincidunt ac. Donec consequat facilisis leo ultricies convallis. Duis elit leo, porta et varius id, luctus non augue. In massa mauris, posuere eu consectetur ac, faucibus at metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
</div>
<script type="text/javascript">
  Droppables.add('content_middle', {
        accept: 'adwit_banner',
        onDrop: function(dragged, dropped, event) {
            if ( $('content_middle').offsetLeft +(dragged.offsetWidth/2) > dragged.offsetLeft) {
                obj_updated = 'cm1';
                $(obj_updated).style.marginRight ='5px';
                $(obj_updated).style.marginBottom ='5px';
                pos_h = 'left';
            } else if ((2*(dragged.offsetLeft+dragged.offsetWidth-$('content_middle').offsetLeft) > $('content_middle').offsetWidth) &
                (2*(dragged.offsetLeft-$('content_bottom').offsetLeft) < $('content_middle').offsetWidth)){
                obj_updated = 'cm2';
                $(obj_updated).style.marginBottom ='5px';
                pos_h = 'center';
            } else {
                obj_updated = 'cm3';
                $(obj_updated).style.marginLeft ='5px';
                $(obj_updated).style.marginBottom ='5px';
                pos_h = 'right';
            }
            copy_style_element(obj_updated, dragged);
            $('content_middle').highlight();
            wp_add_adwit_banner(ptype, 'content_middle', pos_h, dragged.id)
        }
    });
</script>
<?php
}
?>