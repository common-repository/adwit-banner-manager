<?php
  /*
   Plugin Name: Adwit Banner Manager
   Plugin URI: http://www.adwitserver.com/adwitserver/wordpress_plugin
   Description: The Adwit Banner Manager is still the easiest and more powerful ad manager to use on Wordpress. This version includes a Multi Widgets optimization system and a faster uploader of image ads. When using Adwit Banner Manager you agree to the terms of conditions of the Adwit Banner Manager <a href="http://www.adwit-express.com/images/adwit-banner-manager/User_agreement.pdf">User Agreement</a>.
   Version: 2.2.3
   Author: Adwitserver
   Author URI: http://www.adwitserver.com
   Revision Date: Mar,29 2011
   */
  
  /*  Copyright 2011  Etineria Inc  (email : info@adwitserver.com)
   
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License, version 2, as
   published by the Free Software Foundation.
   
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   
   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
   */
  //error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
  /*  error_reporting(-1);*/
  if (file_exists(WP_PLUGIN_DIR.'/adwit-dev')) {
    define("ADWIT_HOME_URL", "http://www.adwit-express-.com:3000");
  }else{
    define("ADWIT_HOME_URL", "http://www.adwit-express.com");
  }
  
  define("ADWIT_BANNER_MANAGER_VERSION", "2.2.3");
  define('ADWIT_BANNER_MANAGER_PATH', WP_PLUGIN_DIR . '/adwit-banner-manager');
  define("ADWIT_BANNER_IMAGES", get_bloginfo('wpurl').'/wp-content/plugins/adwit-banner-manager/images');
  load_plugin_textdomain('adwit', false, 'adwit-banner-manager/lang');
  
  if (file_exists(dirname( __FILE__ ) . '/../logger.class.php')) {
    require_once dirname( __FILE__ ) . '/../logger.class.php';
  }
  if(!function_exists('str_split')) {
    function str_split($string,$string_length=1) {
      if(strlen($string)>$string_length || !$string_length) {
        do {
          $c = strlen($string);
          $parts[] = substr($string,0,$string_length);
          $string = substr($string,$string_length);
        } while($string !== false);
      } else {
        $parts = array($string);
      }
      return $parts;
    }
  }
  require_once ADWIT_BANNER_MANAGER_PATH . '/adwit-banner-manager-admin.php';
  require_once ADWIT_BANNER_MANAGER_PATH . '/adwit-banner-widget.php';
  
  function adwit_banner_manager_dashboard() {
    add_object_page('adwit', 'Adwit-Banner', 'manage_options', 'adwit', 'adwit_banner_manager_ads');
    add_submenu_page('adwit', 'adwit > Manage', __('Manage Ads', 'adwit'), 'manage_options', 'adwit', 'adwit_banner_manager_ads');
    add_submenu_page('adwit', 'adwit > Zones', __('Zones', 'adwit'), 'manage_options', 'adwit_zones', 'adwit_banner_manager_zones');
    add_submenu_page('adwit', 'adwit > Setup Adwit Ads', __('Setup Adwit Ads', 'adwit'), 'manage_options', 'adwit_setup', 'adwit_banner_manager_setup');
  }
  
  class adwit_banner_manager {
    var $user_email;
    var $private_key;
    var $token;
    var $adwit_data;
    var $publisher_id;
    
    /* for php 4 compatibily only */
    function adwit_banner_manager($data) {
      $this->__construct($data);
    }
    
    function adwit_banner_manager_setup() {
      $vp_type = 'page';
      
      if (isset($_POST['post_type']) && !empty($_POST['post_type'])) {
        $vp_type = $_POST['post_type'];
      }
      
      switch ($vp_type) {
        case 'post':
          $view_page = 'post';
          $adwit_ads = Adwit_Banner_Manager_Admin::_get_adwit_post_type_data($this->adwit_data['ads'], 'post');
          break;
        case 'home':
          $view_page = 'home';
          $adwit_ads = Adwit_Banner_Manager_Admin::_get_adwit_post_type_data($this->adwit_data['ads'], 'home');
          break;
        case 'search':
          $view_page = 'search';
          $adwit_ads = Adwit_Banner_Manager_Admin::_get_adwit_post_type_data($this->adwit_data['ads'], 'search');
          break;
        default:
          $view_page = 'page';
          $adwit_ads = Adwit_Banner_Manager_Admin::_get_adwit_post_type_data($this->adwit_data['ads'], 'page');
          break;
      }
      include ADWIT_BANNER_MANAGER_PATH . '/views/main.php';
    }
    
    function __construct($data){  
      global $error;
      if (!isset($error)) {
        $error=array();
      }
      $this->adwit_data = get_option('adwit_options');
      if (!is_array($this->adwit_data)) {
        $this->adwit_data = array();
      }
      if ($this->adwit_data['adwit_express_key']=='') {
        if ($_POST['tag-email']) {          
          $wp_url = Adwit_Banner_Manager_Admin::_get_wp_url();
          $adwit_wp_email = $_POST['tag-email'];
          $adwit_wp_password = $_POST['tag-password'];
          $adwit_regie_id = 5;
          $adwit_wp_url = $wp_url;
          $adwit_wp_lang = 'EN';
          if (!isset($_POST['out']) and $_REQUEST['tag-password']==$_REQUEST['tag-password-confirmation']) {
            if ($this->create_new_account($adwit_wp_email, $adwit_wp_password, $adwit_regie_id, $adwit_wp_url, $adwit_wp_lang)==2) {            
              $this->adwit_banner_manager_setup();
              return ;
            } else {
              $error['global'] = __('Email already exist', 'adwit');
            }
          } elseif (isset($_POST['out']) and $_POST['out']=='login') {
            if ($this->login_to_account($adwit_wp_email, $adwit_wp_password, $adwit_regie_id, $adwit_wp_url, $adwit_wp_lang)==2) {            
              $this->adwit_banner_manager_setup();
              return ;
            } else {
              $error['global'] = __('Bad password', 'adwit');
            } 
          }                   
        } 
        if ($_REQUEST['out']=='login') {
          $this->login_form();
        }else {
          $this->register_form();
        }
        return false;
      } 
      
      if ($data=='ads') {        
        $res = Adwit_Banner_Manager_Admin::adwit_find_or_create_customer($this->adwit_data);	
        /*        if ($res==2) {
         echo "<span style=\"color:#ff0000;font-size: 1.2em;\">" . __('You should set at least one zone', 'adwit') . "</span>";
         $this->adwit_banner_manager_setup();
         } else if($res==1) {        */
        $data = array();
        $data['date'] = Adwit_Banner_Manager_Admin::_get_date_now_adwit_format();
        $data['publisher_id'] = $this->adwit_data['adwit_express_publisher_id'];          
        $url = ADWIT_HOME_URL . '/' . Adwit_Banner_Manager_Admin::adwit_generate_token('campaign_ads/banners', $data, $this->adwit_data['adwit_express_key']);
        echo "<iframe width='100%' border='0' frameborder='no' height='600' src='" . $url . "'></iframe>";
        
        /*        }*/
      } elseif ($data=='zones') {
        $data = array();
        $data['date'] = Adwit_Banner_Manager_Admin::_get_date_now_adwit_format();
        $data['publisher_id'] = $this->adwit_data['adwit_express_publisher_id'];          
        $url = ADWIT_HOME_URL . '/' . Adwit_Banner_Manager_Admin::adwit_generate_token('campaign_ads/zones', $data, $this->adwit_data['adwit_express_key']);
        echo "<iframe width='100%' border='0' frameborder='no'  height='600' src='" . $url . "'></iframe>";
      } elseif ($data=='setup') {
        $this->adwit_banner_manager_setup();
      }
    }
    
    function login_to_account($adwit_wp_email, $adwit_wp_password, $adwit_regie_id, $adwit_wp_url, $adwit_wp_lang) {
      $url = ADWIT_HOME_URL . "/campaign_ads/user_set?email=" .urlencode($adwit_wp_email)
      . "&password=" . urlencode($adwit_wp_password)
      . "&regie_id=" . urlencode($adwit_regie_id)
      . "&url=" . urlencode($adwit_wp_url)
      . "&lang=" . urlencode($adwit_wp_lang);
      $get_account = wp_remote_fopen($url);
      if (empty($get_account) or $get_account == 'D') {
        return $get_account;
      }
      
      $cust_arr = explode(';', $get_account);   
      if (!empty($get_account) && is_array($cust_arr) && !empty($cust_arr[1])) {
        $this->adwit_data['adwit_express_email'] = $cust_arr[0];
        $this->adwit_data['adwit_express_key'] = $cust_arr[1];
        $this->adwit_data['adwit_express_publisher_id'] = $cust_arr[2];
        $this->adwit_data['adwit_express_ads_enabled'] = 0;
        update_option('adwit_options', $this->adwit_data);
        $result = 2; //has account, 0 zones
      }
      return $result;
    }
    
    
    function create_new_account($adwit_wp_email, $adwit_wp_password, $adwit_regie_id, $adwit_wp_url, $adwit_wp_lang) {
      $url = ADWIT_HOME_URL . "/campaign_ads/user_add_with_password?email=" .urlencode($adwit_wp_email)
      . "&password=" . urlencode($adwit_wp_password)
      . "&regie_id=" . urlencode($adwit_regie_id)
      . "&url=" . urlencode($adwit_wp_url)
      . "&lang=" . urlencode($adwit_wp_lang);
      $get_account = wp_remote_fopen($url);
      
      if (empty($get_account) or $get_account == 'D') {
        return $get_account;
      }
      
      $cust_arr = explode(';', $get_account);   
      if (!empty($get_account) && is_array($cust_arr) && !empty($cust_arr[1])) {
        $this->adwit_data['adwit_express_email'] = $cust_arr[0];
        $this->adwit_data['adwit_express_key'] = $cust_arr[1];
        $this->adwit_data['adwit_express_publisher_id'] = $cust_arr[2];
        $this->adwit_data['adwit_express_ads_enabled'] = 0;
        update_option('adwit_options', $this->adwit_data);
        $result = 2; //has account, 0 zones
      }
      return $result;
    }
    
    function __destruct() {
      update_option('adwit_options', $this->adwit_data);
    }
    
    function register_form() {      
      global $current_user, $error;
      require_once ADWIT_BANNER_MANAGER_PATH . '/views/register.php';
    } 
    
    function login_form() {
      global $current_user, $error;
      require_once ADWIT_BANNER_MANAGER_PATH . '/views/login.php';
    }
  }
  
  function adwit_banner_manager_ads() {    
    $adwit_banner_manager = new adwit_banner_manager('ads');
  }
  
  function adwit_banner_manager_zones() {    
    $adwit_banner_manager = new adwit_banner_manager('zones');
  }
  
  function adwit_banner_manager_setup() {
    $adwit_banner_manager = new adwit_banner_manager('setup');
  }
  
  function adwit_banner_manager_widget_init() {
    register_widget('Widget_Adwit_Banner');
  }
  
  function adwit_banner_manager_inject_hook($content) {   
    $content = preg_replace("/(\[ABM[ ]+id=\"([0-9]+)\"[ ]+width=\"([0-9]+)\"[ ]+height=\"([0-9]+)\"\])/iu", '<script type="text/javascript" id="scawx-\\2">'."\n".'<!--'."\n".'adwitServer_client = "awx-\\2";'."\n".'adwitServer_client_width = \\3;'."\n".'adwitServer_client_height = \\4;'."\n".'adwitServer_output = \'div\';'."\n".'//-->'."\n".'</script>'."\n".'<script type="text/javascript" src="http://ads.adwitserver.com/script/show_ads.js"></script>', $content);
    #                 '<script type="text/javascript" id="scawx-\\1"><!--adwitServer_client = "awx-\\1";adwitServer_client_width = \\2;adwitServer_client_height = \\3;adwitServer_output = \'div\';//--></script><script type="text/javascript" src="http://ads.adwitserver.com/script/show_ads.js"></script>',  
    #    
    $plist_to_bypass = array('10000','10001','63');
    if ((is_home() || is_archive() || is_search() || is_page() || is_single() && !empty($content)) && !is_page($plist_to_bypass)) {
      
      $adwit_ads = Adwit_Banner_Manager_Admin::_get_adwit_ads_data();
      if (empty($adwit_ads)) {
        return $content;
      }      
      $content = preg_replace('(\n)', ' ', $content);
      
      $paragraphes = preg_match_all("/(<p(.*?)<\/p>)/", $content, $result);
      # print_r($result);
      if (count($result[0]) > 1 and (!is_search() )) {
        $two_array = array_chunk($result[0], ceil(count($result[0]) / 2));
        $script_top = Adwit_Banner_Manager_Admin::_get_adwit_ads('top');
        if (!is_search()) {
          echo $script_top;
          $two_array[0][0] = $two_array[0][0];
        } else {
          $two_array[0][0] = $script_top.$two_array[0][0];
        }
        $script_middle = Adwit_Banner_Manager_Admin::_get_adwit_ads('middle');
        $script_bottom = Adwit_Banner_Manager_Admin::_get_adwit_ads('bottom');
        $two_array[1][2] = $two_array[1][2];
        
        $res = implode(array_merge($two_array[0], array($script_middle), $two_array[1]));
        
        $content = $res . $script_bottom;
      } else {
        $script_top = Adwit_Banner_Manager_Admin::_get_adwit_ads('top');
        if (!empty($script_top)) {
          if (!is_search()) {
            echo $script_top;
            $content = $content;
          } else {
            $content = $script_top.$content;
          }
        }
        
        $script_bottom = Adwit_Banner_Manager_Admin::_get_adwit_ads('bottom');
        if (!empty($script_bottom)) {
          $content .=  $script_bottom;
        }
        return $content;
      }
    }
    return $content;
  }
  
  function adwit_banner_manager_load_scripts() {
    wp_enqueue_script('scriptaculous');
  }
  
  add_action('admin_menu', 'adwit_banner_manager_dashboard');
  add_action('admin_init', 'adwit_banner_manager_load_scripts');
  add_filter('the_content', 'adwit_banner_manager_inject_hook');
  add_action('widgets_init', 'adwit_banner_manager_widget_init');
  
  //Add/Update
  if ($_POST && isset($_POST['adwit_action'])) {
    Adwit_Banner_Manager_Admin::adwit_update($_POST);
  }
  
  function adwit_widget_options($reset=false) {
    global $widget_adwit_banner_data;
    if (empty($widget_adwit_banner_data) || $reset !== false) {
      $widget_adwit_banner_data = get_option('widget_adwit_banner');
    }
    return $widget_adwit_banner_data;
  }
  
  function adwit_options($reset=false) {
    global $adwit_data;
    if (empty($adwit_data) || $reset !== false) {
      $adwit_data = get_option('adwit_options');
    }
    return $adwit_data;
  }
  
  function adwit_banner_manager_banner($key_id, $width, $height) {
    echo '<script type="text/javascript" id="scawx-'.$key_id.'"><!--
    adwitServer_client = "awx-'.$key_id.'";
    adwitServer_client_width = '.$width.';
    adwitServer_client_height = '.$height.';
    adwitServer_output = \'div\';
    //-->
    </script>
    <script type="text/javascript"
    src="http://ads.adwitserver.com/script/show_ads.js">
    </script>';    
  } 
  
  function show_presentation() {
    echo __("Adwit Banner Manager is a banner manager to help you managing all of your ad. More powerful than a banner rotator it's a real adserver (ad manager).", 'adwit');
    echo __("It allow you to publish all affiliate advertisement adbrite, adgridwork, adify, adpinion, adroll, adsense, chitika, cj, commission junction, crispads, google, random, shoppingads, yahoo, ypn and more", 'adwit');
  }
  
  ?>