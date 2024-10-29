<div style="margin-top: 15px;margin-left: 30px;width: 240px; border: 1px solid #777777; float: left">
    <?php
    //include(ADWIT_BANNER_MANAGER_PATH . '/views/_head_top.php');
    //adwit_head_top($adwit_ads, $adwit_ban_list, 'home')
    ?>
    <div style="font-weight: bold;font-size: 12px;position: relative; line-height: 15px;height: 15px;top: 0px; width: 240px; background-color: #eeeeee;text-align: left; float: left;">
        Lorem ipsum dolor sit amet
    </div>
    <?php
    include(ADWIT_BANNER_MANAGER_PATH . '/views/_content_top.php');
    adwit_content_top($adwit_ads, $adwit_ban_list, 'home')
    ?>
    <?php
    include(ADWIT_BANNER_MANAGER_PATH . '/views/_content_bottom.php');
    adwit_content_bottom($adwit_ads, $adwit_ban_list, 'home')
    ?>
</div>