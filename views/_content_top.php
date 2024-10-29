<?php
  function adwit_content_top($adwit_ads, $adwit_ban_list, $page) {?>
  <div id="content_top" style="padding:5px;line-height: 9px;position: relative; top: 0px; width: 230px; background-color: #ffffff;text-align: justify; float: left;min-height: 20px;font-size: 7px;">
        <div id="ct3" style="float: right;<?php if (isset($adwit_ads[$page.'_top_right'])){ echo 'margin-left: 5px;margin-bottom: 5px;';}echo adwit_get_width_and_height_for_div($adwit_ban_list, $adwit_ads[$page.'_top_right']); ?>" onclick="clear_my_obj(this);"><?php
        if (isset($adwit_ads[$page.'_top_right'])){
          echo '<img src="' .ADWIT_BANNER_IMAGES .'/' .$adwit_ads[$page.'_top_right']['img_name'].'" alt=""/>';
        }?></div>
        <div id="ct1" style="float: left;<?php if (isset($adwit_ads[$page.'_top_left'])){ echo 'margin-right: 5px;margin-bottom: 5px;';} echo adwit_get_width_and_height_for_div($adwit_ban_list, $adwit_ads[$page.'_top_left']); ?>" onclick="clear_my_obj(this);"><?php
        if (isset($adwit_ads[$page.'_top_left'])){
          echo '<img src="' .ADWIT_BANNER_IMAGES .'/' .$adwit_ads[$page.'_top_left']['img_name'].'" alt=""/>';
        }?></div>
        <div id="ct2" style="margin:0 auto;<?php echo adwit_get_width_and_height_for_div($adwit_ban_list, $adwit_ads[$page.'_top_center']); ?>" onclick="clear_my_obj(this);"><?php
        if (isset($adwit_ads[$page.'_top_center'])){
          echo '<img src="' .ADWIT_BANNER_IMAGES .'/' .$adwit_ads[$page.'_top_center']['img_name'].'" alt=""/>';
        }?></div>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nibh mauris, tincidunt non dictum eu, egestas eget lectus. Nullam vehicula dignissim sapien nec scelerisque. Suspendisse a sem at sapien venenatis ultrices. Integer vehicula eleifend lorem, euismod iaculis velit mollis feugiat. Suspendisse euismod mauris sit amet tortor suscipit facilisis. Nulla a justo vel nunc aliquam interdum non sit amet enim. Quisque facilisis eleifend tellus sed posuere. Fusce aliquet, mauris non dapibus viverra, ipsum sem porta tortor, et adipiscing magna nibh vel justo. Praesent nisi metus, pretium in tristique vel, sollicitudin non odio. Maecenas et lacus ac massa hendrerit placerat. Cras ipsum est, congue eu elementum eu, pellentesque et quam. Maecenas orci lectus, egestas mattis bibendum tincidunt, commodo a nibh.
    </div>
<script type="text/javascript">
    Droppables.add('content_top', {
        accept: 'adwit_banner',
        onDrop: function(dragged, dropped, event) {
            if ( $('content_top').offsetLeft +(dragged.offsetWidth/2) > dragged.offsetLeft) {
                obj_updated = 'ct1';
                $(obj_updated).style.marginRight ='5px';
                $(obj_updated).style.marginBottom ='5px';
                pos_h = 'left';
            } else if ((2*(dragged.offsetLeft+dragged.offsetWidth-$('content_top').offsetLeft) > $('content_top').offsetWidth) &
                (2*(dragged.offsetLeft-$('content_top').offsetLeft) < $('content_top').offsetWidth)){
                obj_updated = 'ct2';
                $(obj_updated).style.marginBottom ='5px';
                pos_h = 'center';
            } else {
                obj_updated = 'ct3';
                $(obj_updated).style.marginLeft ='5px';
                $(obj_updated).style.marginBottom ='5px';
                pos_h = 'right';
            }
            copy_style_element(obj_updated, dragged);
            $('content_top').highlight();

            wp_add_adwit_banner(ptype, 'content_top', pos_h, dragged.id)

        }
    });
</script>
<?php
}
?>