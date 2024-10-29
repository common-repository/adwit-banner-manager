<?php

class Adwit_Banner_Manager_Admin {

    function adwit_update($params) {
	global $adwit_data;
	if (isset($params['adwit_action'])) {
	    switch ($params['adwit_action']) {
		case 'adwit_add_banner':
		    $adwit_data = adwit_options();
		    if (empty($adwit_data['adwit_express_key'])) {
			//@TODO, Log message
			return;
		    }

		    $post_v = $params['pos_v'];

		    switch ($params['pos_v']) {
			case 'content_top':
			    $post_v = 'top';
			    break;
			case 'content_middle':
			    $post_v = 'middle';
			    break;
			case 'content_bottom':
			    $post_v = 'bottom';
			    break;
		    }

		    $file_name = stripslashes($params['post_type'] . '_' . $post_v . '_' . $params['pos_h']);
		    $bsizes = explode('x', substr($params['bsize'], 3));

		    $bann_list = Adwit_Banner_Manager_Admin::_get_bann_list();

		    $url_data = array();
		    $url_data['page_type'] = $params['post_type'];
		    $url_data['pos_v'] = $post_v;
		    $url_data['pos_h'] = $params['pos_h'];
		    $url_data['size_id'] = $bann_list[substr($params['bsize'], 3)]['id'];
		    $url_data['status'] = 'on';
		    $url_data['publisher_id'] = $adwit_data['adwit_express_publisher_id'];

		    //Get zone key
		    $url_express_create_zone = ADWIT_HOME_URL . '/' . Adwit_Banner_Manager_Admin::adwit_generate_token('campaign_ads/zone_add', $url_data, $adwit_data['adwit_express_key']);

		    $get_zone_key = wp_remote_fopen($url_express_create_zone);

		    //Add new banner info into adwit_options (wp)
		    $ad = array();
		    $ad['page_type'] = $params['post_type'];
		    $ad['pos_v'] = $post_v;
		    $ad['pos_h'] = $params['pos_h'];
		    $ad['width'] = $bsizes[0];
		    $ad['height'] = $bsizes[1];


		    $ad['bsize'] = $params['bsize'];
		    $ad['key'] = $get_zone_key; //zone key
		    $ad['img_name'] = substr($params['bsize'], 3) . '.png';
		    $adwit_data['ads'][$file_name] = $ad;

		    update_option('adwit_options', $adwit_data);

		    //Check available Ads and set adwit_express_ads_enabled (true/false)
		    Adwit_Banner_Manager_Admin::adwit_check_ads();
		    break;

		case 'adwit_remove_banner':
		    $pos = str_split($params['pos']);
		    $file_name = $params['post_type'];

		    if (is_array($pos) && !empty($pos)) {
			switch ($pos[0]) {
			    case 'h':
				$file_name .= '_head';
				$post_v = 'head';
				break;
			    case 'c':
				switch ($pos[1]) {
				    case 't':
					$file_name .= '_top';
					$post_v = 'top';
					break;

				    case 'm':
					$file_name .= '_middle';
					$post_v = 'middle';
					break;
				    case 'b':
					$file_name .= '_bottom';
					$post_v = 'bottom';
					break;
				}
				break;
			}

			switch ($pos[2]) {
			    case 1:
				$file_name .= '_left';
				$post_h = 'left';
				break;
			    case 2:
				$file_name .= '_center';
				$post_h = 'center';
				break;
			    case 3:
				$file_name .= '_right';
				$post_h = 'right';
				break;
			}

			if (!empty($file_name)) {
			    $adwit_data = adwit_options();

			    $url_data = array();
			    $url_data['page_type'] = $params['post_type'];
			    $url_data['pos_v'] = $post_v;
			    $url_data['pos_h'] = $post_h;
			    $url_data['status'] = 'off';
			    $url_data['publisher_id'] = $adwit_data['adwit_express_publisher_id'];
			    //Get zone key
			    $url_express_update_zone = ADWIT_HOME_URL . '/' . Adwit_Banner_Manager_Admin::adwit_generate_token('campaign_ads/zone_add', $url_data, $adwit_data['adwit_express_key']);
			    wp_remote_fopen($url_express_update_zone);

			    unset($adwit_data['ads'][$file_name]);
			    update_option('adwit_options', $adwit_data);
			    Adwit_Banner_Manager_Admin::adwit_check_ads();
			}
		    }

		    break;

		case 'user_set':
		    $wp_url = Adwit_Banner_Manager_Admin::_get_wp_url();
		    $url_express_user_set = ADWIT_HOME_URL . "/campaign_ads/user_set?email=" . $params['adwit_email'] . "&password=" . $params['adwit_pass'] . "&regie_id=1&url=" . $wp_url . "&lang=EN";
		    $adwit_recovery = wp_remote_fopen($url_express_user_set);
		    $cust_arr = explode(';', $adwit_recovery);

		    if (!empty($adwit_recovery) && is_array($cust_arr) && !empty($cust_arr[1])) {
			$adwit_data['adwit_express_email'] = $cust_arr[0];
			$adwit_data['adwit_express_key'] = $cust_arr[1];
			$adwit_data['adwit_express_publisher_id'] = $cust_arr[2];
			update_option('adwit_options', $adwit_data);
			Adwit_Banner_Manager_Admin::adwit_check_ads();
			$result['status'] = 1;
		    }

		    $result['data'] = $adwit_data;
		    break;
	    }
	}
    }

    function adwit_generate_token($path, $data, $private_key) {
	ksort($data);
	$params_line = '';
	foreach ($data as $key => $value) {
	    if ($params_line != '') {
		$params_line .= '&';
	    }
	    $params_line .= "$key=$value";
	}
	$params_line_key = $params_line . '&key=' . $private_key;
	$token = sha1("$path?$params_line_key");
	return "$path?$params_line&sign=$token";
    }

  function adwit_find_or_create_customer($adwit_data) {
	  if (Adwit_Banner_Manager_Admin::_adwit_user_has_zones($adwit_data)) {
      return 1; //has account and zones
	  }
    return 2; //don't have zone
	}

  function _adwit_user_has_zones($adwit_data){
    if ($adwit_data['ads'] != NULL && !empty($adwit_data['ads'])){
      return true;
    }

	  $adwit_banner_data = adwit_widget_options();
	  $list_av_zones = array();
	  foreach ($adwit_banner_data as $k => $v ){
	  	if (empty($v) || $k['_multiwidget']){
	  		continue;
	  	}
	  	$list_av_zones[] = $v;
	  }
	   
	  if (!empty($list_av_zones)){
			$adwit_data['adwit_express_ads_enabled'] = 1;
			update_option('adwit_options', $adwit_data);
			return true;
    }
    return false;
  }

    function _get_wp_url($getfull=false) {
	$s = empty($_SERVER ["HTTPS"]) ? '' : ($_SERVER ["HTTPS"] == "on") ? "s" : "";

	$protocol = strtolower($_SERVER ["SERVER_PROTOCOL"]);
	$protocol = substr($protocol, 0, strpos($protocol, "/"));
	$protocol .= $s;

	$port = ($_SERVER ["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER ["SERVER_PORT"]);

	if ($getfull == true) {
	    $res = $protocol . "://" . $_SERVER ['SERVER_NAME'] . $port . $_SERVER ['REQUEST_URI'];
	} else {
	    $res = $protocol . "://" . $_SERVER ['SERVER_NAME'] . $port;
	}

	return $res;
    }

    function adwit_check_ads() {
	$adwit_data = adwit_options();
	$count_ads = Adwit_Banner_Manager_Admin::_count_adwit_ads();
	$adwit_data['adwit_express_ads_enabled'] = 0;
	if ($count_ads >= 1) {
	    $adwit_data['adwit_express_ads_enabled'] = 1;
	}

	update_option('adwit_options', $adwit_data);
	get_option('adwit_options');
	return $count_ads;
    }

    function _count_adwit_ads() {
	$adwit_data = adwit_options();
	$res = count($adwit_data['ads']);
	return $res;
    }

    function _get_bann_list() {
	$res = array();
	$res['468x60'] = array('id' => 2, 'class' => "adwit_banner", 'width' => "117px", 'height' => "17px", 'bgcolor' => " #ffcfa0");
	$res['728x90'] = array('id' => 1, 'class' => "adwit_banner", 'width' => "182px", 'height' => "22px", 'bgcolor' => " #ffaf70");
	$res['160x600'] = array('id' => 5, 'class' => "adwit_banner", 'width' => "40px", 'height' => "150px", 'bgcolor' => " #ff8f40");
	$res['120x600'] = array('id' => 4, 'class' => "adwit_banner", 'width' => "30px", 'height' => "150px", 'bgcolor' => " #ff9f90");
	$res['200x200'] = array('id' => 8, 'class' => "adwit_banner", 'width' => "50px", 'height' => "50px", 'bgcolor' => " #ffcf70");
	$res['250x250'] = array('id' => 7, 'class' => "adwit_banner", 'width' => "62px", 'height' => "62px", 'bgcolor' => " #ffaf40");
	$res['300x250'] = array('id' => 6, 'class' => "adwit_banner", 'width' => "75px", 'height' => "62px", 'bgcolor' => " #ff8fa0");
	$res['120x240'] = array('id' => 10, 'class' => "adwit_banner", 'width' => "30px", 'height' => "60px", 'bgcolor' => " #ff6f70");
	$res['336x280'] = array('id' => 12, 'class' => "adwit_banner", 'width' => "84px", 'height' => "70px", 'bgcolor' => " #ffcf40");
	$res['180x150'] = array('id' => 11, 'class' => "adwit_banner", 'width' => "45px", 'height' => "37px", 'bgcolor' => " #ffafa0");
	$res['125x125'] = array('id' => 9, 'class' => "adwit_banner", 'width' => "31px", 'height' => "31px", 'bgcolor' => " #ff8f70");
	$res['234x60'] = array('id' => 3, 'class' => "adwit_banner", 'width' => "58px", 'height' => "17px", 'bgcolor' => " #ff6f40");

	return $res;
    }

    function _get_date_now_adwit_format() {
	return date('Y-m-d') . '_' . date('h') . 'h' . date('i');
    }

    function _get_adwit_post_type_data($ads, $type='page') {
	$res = array();
	if (is_array($ads) && !empty($ads)) {
	    foreach ($ads as $key => $val) {
		if (0 == strcmp($type, substr($key, 0, strlen($type)))) {
		    $res[$key] = $val;
		}
	    }
	}
	return $res;
    }

    function _get_adwit_ads_data() {
	$options = adwit_options();
	$res = array();
	if (is_page ()) {
	    $res = Adwit_Banner_Manager_Admin::_get_adwit_post_type_data($options['ads'], 'page');
	} elseif (is_single() || is_archive()) {
	    $res = Adwit_Banner_Manager_Admin::_get_adwit_post_type_data($options['ads'], 'post');
	} elseif (is_home ()) {
	    $res = Adwit_Banner_Manager_Admin::_get_adwit_post_type_data($options['ads'], 'home');
	} elseif (is_search ()) {
	    $res = Adwit_Banner_Manager_Admin::_get_adwit_post_type_data($options['ads'], 'search');
	}
	return $res;
    }

    function _get_adwit_ads($pos='') {
	if (empty($pos)) {
	    return '';
	}

	$post_type = '';
	if (is_page ()) {
	    $post_type = 'page_' . $pos;
	} elseif (is_single() || is_archive()) {
	    $post_type = 'post_' . $pos;
	} elseif (is_home ()) {
	    $post_type = 'home_' . $pos;
	} elseif (is_search ()) {
	    $post_type = 'search_' . $pos;
	}

	$adwit_ads = Adwit_Banner_Manager_Admin::_get_adwit_ads_data();
	$script_pos = '';
/*      print_r($adwit_ads);*/
	if (isset($adwit_ads[$post_type . '_left'])) {
	    $script_pos .= '<div style="float:left; margin-right: 5px;">' . Adwit_Banner_Manager_Admin::_adwit_script_generator($adwit_ads[$post_type . '_left']['key'], $adwit_ads[$post_type . '_left']['width'], $adwit_ads[$post_type . '_left']['height']) . '</div>';
	}
	if (isset($adwit_ads[$post_type . '_right'])) {
	    $script_pos .= '<div style="float:right; margin-left: 5px;">' . Adwit_Banner_Manager_Admin::_adwit_script_generator($adwit_ads[$post_type . '_right']['key'], $adwit_ads[$post_type . '_right']['width'], $adwit_ads[$post_type . '_right']['height']) . '</div>';
	}
	if (isset($adwit_ads[$post_type . '_center'])) {
	    $script_pos .= '<div style="width: '. $adwit_ads[$post_type . '_center']['width'].'px; margin: 0 auto;">' . Adwit_Banner_Manager_Admin::_adwit_script_generator($adwit_ads[$post_type . '_center']['key'], $adwit_ads[$post_type . '_center']['width'], $adwit_ads[$post_type . '_center']['height']) . '</div>';
	}

	return $script_pos;
    }

    function _adwit_script_generator($key_id='', $w, $h) {
	if (empty($key_id)) {
	    return;
	}

	//$key_id = '1993090646';
	$ad_script = "<script type=\"text/javascript\" id=\"scawx-$key_id\"><!--\n" .
		"adwitServer_client = \"awx-$key_id\";\n" .
		"adwitServer_client_width = $w;\n" .
		"adwitServer_client_height = $h;\n" .
		"adwitServer_output = \"div\";\n" .
		"//-->\n" .
		"</script>\n" .
		"<script type=\"text/javascript\" src=\"http://ads.adwitserver.com/script/show_ads.js\"></script>";

	return $ad_script;
    }

}
