<?php

class Widget_Adwit_Banner extends WP_Widget {

    /** constructor */
    function Widget_Adwit_Banner() {
	$widget_ops = array('classname' => 'widget_adwit_banner', 'description' => __('Create and display your adwit-express ads', 'adwit'));
	parent::WP_Widget('adwit_banner', __('Adwit-Banner', 'adwit'), $widget_ops);
    }

    function widget($args, $instance) {
	extract($args);

	$sizeid = trim($instance['sizeid']);
	$key_id = trim($instance['key']);
	
	$title = apply_filters('widget_title', $instance['title']);
	
	if (empty($title))
	    $title = __('Adwit Banner', 'adwit');

	echo "{$before_widget}{$before_title}" . esc_html($title) . "{$after_title}";

	if (!empty($key_id) && $sizeid > 0) {

	    $bann_list = Adwit_Banner_Manager_Admin::_get_bann_list();
	    //Add new banner info into adwit_options (wp)
	    $bsizes = array();

	    foreach ($bann_list as $bk => $bv) {
		if ($bv['id'] == $sizeid) {
		    $bsizes = explode('x', $bk);
		    break;
		}
	    }

	    echo '<div style="clear:left;"><center>';
	    $script_pos = Adwit_Banner_Manager_Admin::_adwit_script_generator($key_id, $bsizes[0], $bsizes[1]);
	    echo $script_pos;
	    echo '</center></div>';
	}
	echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
	$instance = $old_instance;
	if ($new_instance['sizeid'] != '') {
		
	    $adwit_data = adwit_options();

	    $instance['sizeid'] = trim(strip_tags(stripslashes($new_instance['sizeid'])));
	    $instance['title'] = trim(strip_tags(stripslashes($new_instance['title'])));
	    $instance['adwit_zone_name'] = trim(strip_tags(stripslashes(str_replace('-', '_', $new_instance['adwit_zone_name']))));
	    $tmp_sizeid = trim(strip_tags(stripslashes($new_instance['tmp_sizeid'])));
	    $wg_opt_list = $this->_adwit_widget_filter($tmp_sizeid);

	    $bann_list = Adwit_Banner_Manager_Admin::_get_bann_list();

	    $url_data = array();
	    $url_data['page_type'] = $instance['adwit_zone_name'];
	    $url_data['pos_v'] = $instance['sizeid'];
	    $url_data['pos_h'] = 0;
	    $url_data['size_id'] = $instance['sizeid'];
	    $url_data['status'] = 'on';
	    $url_data['publisher_id'] = $adwit_data['adwit_express_publisher_id'];

	    //Get zone key
	    $url_express_create_zone = ADWIT_HOME_URL . '/' . Adwit_Banner_Manager_Admin::adwit_generate_token('campaign_ads/zone_add', $url_data, $adwit_data['adwit_express_key']);
	    $get_zone_key = wp_remote_fopen($url_express_create_zone);

	    if (!empty($tmp_sizeid) && $tmp_sizeid > 0 && $tmp_sizeid != $new_instance['sizeid']) {
		$url_data['status'] = 'off';
		$url_data['pos_v'] = $tmp_sizeid;
		$url_data['size_id'] = $tmp_sizeid;
		$url_express_update_zone = ADWIT_HOME_URL . '/' . Adwit_Banner_Manager_Admin::adwit_generate_token('campaign_ads/zone_add', $url_data, $adwit_data['adwit_express_key']);
		wp_remote_fopen($url_express_update_zone);		
	    }

	    if (empty($tmp_sizeid) && $new_instance['sizeid'] > 0) {
		$adwit_data['adwit_express_ads_enabled'] = 1;
		update_option('adwit_options', $adwit_data);
	    }

	    //Add new banner info into adwit_options (wp)
	    $bsizes = array();
	    $img_bsize = '';

	    foreach ($bann_list as $bk => $bv) {
		if ($bv['id'] == $new_instance['sizeid']) {
		    $img_bsize = $bk;
		    $bsizes = explode('x', $bk);
		    break;
		}
	    }

	    $instance['page_type'] = $instance['adwit_zone_name'];
	    $instance['pos_v'] = $instance['sizeid'];
	    $instance['pos_h'] = 0;
	    $instance['width'] = $bsizes[0];
	    $instance['height'] = $bsizes[1];
	    $instance['bsize'] = $img_bsize;
	    $instance['key'] = $get_zone_key; //zone key
	    $instance['img_name'] = $img_bsize . '.png';
	}

	return $instance;
    }

    function _adwit_widget_filter($tmp_sizeid = '') {
	$bann_list = Adwit_Banner_Manager_Admin::_get_bann_list();
	return $bann_list;
    }

    /** @see WP_Widget::form */
    function form($instance) {
	//widget_adwit_banner
	$instance = wp_parse_args((array) $instance, array('sizeid' => '', 'title' => '', 'adwit_zone_name'=>''));
	
	$sizeid = esc_attr($instance['sizeid']);
	$title = esc_attr($instance['title']);
	$adwit_zone_name = esc_attr($instance['adwit_zone_name']);
	
	$adwit_uid = '';
    if ( empty($instance['sizeid']) && empty($instance['title']) && empty($instance['adwit_zone_name']) || !$instance['adwit_zone_name']){
		$adwit_zone_name = 'widget_'.$this->id;
	}else{
		$adwit_widget_uname =  trim(strip_tags(stripslashes(str_replace('-', '_', $instance['adwit_zone_name']))));
	    $adwit_uid_arr = explode('_', $adwit_widget_uname);
		if (is_array($adwit_uid_arr) && !empty($adwit_uid_arr)){
			$adwit_uid = $adwit_uid_arr[count($adwit_uid_arr)-1];
			if (!empty($title)){
				$adwit_uid = $title .'_'. $adwit_uid;
			}
			echo '<p><label> id: '.$adwit_uid.'</label></p>';			
		}
	}
	
	echo '<p>
		<input class="widefat" id="' . $this->get_field_id('adwit_zone_name') . '" name="' . $this->get_field_name('adwit_zone_name') . '" type="hidden" value="' . $adwit_zone_name . '" />
		<label for="' . $this->get_field_id('title') . '">' . esc_html__('title:').'
		<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" />
		</label></p>';

	$bann_list = $this->_adwit_widget_filter();

	echo '<p><select id="' . $this->get_field_id('sizeid') . '" name="' . $this->get_field_name('sizeid') . '">';
	echo '<option value="">' . __('Select', 'adwit') . '</option>';
	foreach ($bann_list as $key => $value) {
	    echo '<option value="' . $value['id'] . '" ' . ($sizeid == $value['id'] ? ' selected=true' : '') . '>' . $key . '</option>';
	}
	echo '</select> ' . __('Banner Size', 'adwit') . '
                    <input id="' . $this->get_field_id('tmp_sizeid') . '" name="' . $this->get_field_name('tmp_sizeid') . '" type="hidden" value="' . $sizeid . '" />
                    </p>';
    }

}