<?php
/*
Plugin Name: Announcement ticker highlighter scroller
Plugin URI: http://www.gopiplus.com/work/2010/07/18/announcement-ticker-highlighter-scroller/
Description: This plug-in will display the announcement with highlighter scroller. It gradually reveals each message into view from bottom to top.
Version: 12.1
Author: Gopi Ramasamy
Author URI: http://www.gopiplus.com/work/2010/07/18/announcement-ticker-highlighter-scroller/
Donate link: http://www.gopiplus.com/work/2010/07/18/announcement-ticker-highlighter-scroller/
Text Domain: announcement-ticker-highlighter-scroller
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb, $wp_version;
define("WP_g_aths_TABLE", $wpdb->prefix . "g_aths_plugin");
define('WP_g_aths_FAV', 'http://www.gopiplus.com/work/2010/07/18/announcement-ticker-highlighter-scroller/');

if ( ! defined( 'WP_g_aths_PLUGIN_BASENAME' ) )
	define( 'WP_g_aths_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'WP_g_aths_PLUGIN_NAME' ) )
	define( 'WP_g_aths_PLUGIN_NAME', trim( dirname( WP_g_aths_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'WP_g_aths_PLUGIN_DIR' ) )
	define( 'WP_g_aths_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . WP_g_aths_PLUGIN_NAME );

if ( ! defined( 'WP_g_aths_PLUGIN_URL' ) )
	define( 'WP_g_aths_PLUGIN_URL', plugins_url() . '/' . WP_g_aths_PLUGIN_NAME );
	
if ( ! defined( 'WP_g_aths_ADMIN_URL' ) )
	define( 'WP_g_aths_ADMIN_URL', admin_url() . 'options-general.php?page=announcement-ticker-highlighter-scroller' );


function g_aths_announcement()
{
	echo g_aths_shortcode();
}

function g_aths_shortcode()
{
	//[announcement-ticker group=""]
	global $wpdb;
	$g_aths = "";
	$g_athsStr = "";
	$data = $wpdb->get_results("select g_aths_text from ".WP_g_aths_TABLE." where g_aths_status='YES' ORDER BY g_aths_order");
	if ( ! empty($data) ) 
	{
		$count = 0; 
		foreach ( $data as $data ) 
		{
			$g_athscontent = $data->g_aths_text;
			$g_athsStr = $g_athsStr . "g_aths_contents[$count]='$g_athscontent';";
			$count++;
		}
		
		$g_aths .= '<script type="text/javascript"> ';
			$g_aths .= 'var g_aths_contents=new Array();';
			$g_aths .= $g_athsStr;
			
			$g_aths .= 'var g_aths_width="'.get_option('g_aths_width').'px";';
			$g_aths .= 'var g_aths_height="'.get_option('g_aths_height').'px";';
			$g_aths .= 'var g_aths_css="'.get_option('g_aths_css').'";';
			$g_aths .= 'var g_aths_delay='.get_option('g_aths_delay').';';
			$g_aths .= 'var g_aths_speed='.get_option('g_aths_speed').';';
			$g_aths .= 'var g_aths_highlightcolor="'.get_option('g_aths_highlightcolor').'";';
			$g_aths .= 'var g_aths_textcolor="'.get_option('g_aths_textcolor').'";';
			
			$id1 = '"g_aths_bg"';
			$id2 = '"g_aths_highlighter"';
			$Quote = '"';
			$line1 = "position:relative;left:0px;top:5px; width:'+g_aths_width+'; height:'+g_aths_height+';'+g_aths_css+'";
			$line2 = "position:absolute;left:0;top:0;color:'+g_aths_textcolor+'; width:'+g_aths_width+'; height:'+g_aths_height+';padding: 4px";
			$line3 = "position:absolute;left:0;top:0;clip:rect(auto auto auto 0px); background-color:'+g_aths_highlightcolor+'; width:'+g_aths_width+';height:'+g_aths_height+';padding: 4px";
			
			$g_aths .= "document.write('<style>#g_aths_bg a{color:'+g_aths_textcolor+'}</style>');";
			$g_aths .= "document.write('<div style=" . $Quote . $line1 . $Quote .">');";
			$g_aths .= "document.write('<span id=" . $id1 ." style=" . $Quote . $line2 . $Quote ."></span><span id=" . $id2 . " style=" . $Quote . $line3 . $Quote ."></span>');";
			$g_aths .= "document.write('</div>');";
			$g_aths .= 'g_aths_startcontent();';
		$g_aths .= '</script>';
	}
	else
	{
		$g_aths = 'No records available';
	}
	return $g_aths;
}

function g_aths_deactivate() 
{
	delete_option('g_aths_title');
	delete_option('g_aths_title_display');
	delete_option('g_aths_width');
	delete_option('g_aths_height');
	delete_option('g_aths_css');
	delete_option('g_aths_delay');
	delete_option('g_aths_speed');
	delete_option('g_aths_highlightcolor');
	delete_option('g_aths_textcolor');
}

function g_aths_activation() 
{
	global $wpdb;
	
	if($wpdb->get_var("show tables like '". WP_g_aths_TABLE . "'") != WP_g_aths_TABLE) 
	{
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `". WP_g_aths_TABLE . "` (
			  `g_aths_id` int(11) NOT NULL auto_increment,
			  `g_aths_text` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			  `g_aths_order` int(11) NOT NULL default '0',
			  `g_aths_status` char(3) NOT NULL default 'No',
			  `g_aths_date` datetime NOT NULL default '0000-00-00 00:00:00',
			  PRIMARY KEY  (`g_aths_id`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			");
		$sSql = "INSERT INTO `". WP_g_aths_TABLE . "` (`g_aths_text`, `g_aths_order`, `g_aths_status`, `g_aths_date`)"; 
		$sSql = $sSql . "VALUES ('This is sample text for announcement ticker highlighter scroller by gopiplus.com', '1', 'YES', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
	}
	add_option('g_aths_title', "Announcement");
	add_option('g_aths_title_display', "YES");
	add_option('g_aths_width', "300");
	add_option('g_aths_height', "150");
	add_option('g_aths_css', "font: Verdana; color:black");
	add_option('g_aths_delay', "3000");
	add_option('g_aths_speed', "2");
	add_option('g_aths_highlightcolor', "#F0EFEE");
	add_option('g_aths_textcolor', "#DD4B39");
}

function g_aths_admin_options() 
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/content-management-edit.php');
			break;
		case 'add':
			include('pages/content-management-add.php');
			break;
		case 'set':
			include('pages/widget-setting.php');
			break;
		default:
			include('pages/content-management-show.php');
			break;
	}
}

function g_aths_add_to_menu() 
{
	add_options_page( __('Announcement ticker highlighter scroller', 'announcement-ticker-highlighter-scroller'), 
			__('Announcement ticker', 'announcement-ticker-highlighter-scroller'), 'manage_options', 'announcement-ticker-highlighter-scroller', 'g_aths_admin_options' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'g_aths_add_to_menu');
}

function g_aths_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('g_aths_title');
	echo $after_title;
	g_aths_announcement();
	echo $after_widget;
}

function g_aths_control()
{
	echo '<p><b>';
	_e('Announcement ticker highlighter scroller', 'announcement-ticker-highlighter-scroller');
	echo '.</b> ';
	_e('Check official website for more information', 'announcement-ticker-highlighter-scroller');
	?> <a target="_blank" href="<?php echo WP_g_aths_FAV; ?>"><?php _e('click here', 'announcement-ticker-highlighter-scroller'); ?></a></p><?php
}

function g_aths_widget_init() 
{
	if(function_exists('wp_register_sidebar_widget')) 	
	{
		wp_register_sidebar_widget('Announcement-ticker-highlighter-scroller', 
				__('Announcement ticker highlighter scroller', 'announcement-ticker-highlighter-scroller'), 'g_aths_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 	
	{
		wp_register_widget_control('Announcement-ticker-highlighter-scroller', 
				array( __('Announcement ticker highlighter scroller', 'announcement-ticker-highlighter-scroller'), 'widgets'), 'g_aths_control');
	} 
}

function g_aths_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'announcement-ticker-highlighter-scroller', WP_g_aths_PLUGIN_URL.'/announcement-ticker-highlighter-scroller.js');
	}
}    

function g_aths_textdomain() 
{
	  load_plugin_textdomain( 'announcement-ticker-highlighter-scroller', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function g_aths_adminscripts() 
{
	if( !empty( $_GET['page'] ) ) 
	{
		switch ( $_GET['page'] ) 
		{
			case 'announcement-ticker-highlighter-scroller':
				wp_register_script( 'g_aths-adminscripts', plugins_url( 'pages/setting.js', __FILE__ ), '', '', true );
				wp_enqueue_script( 'g_aths-adminscripts' );
				$g_aths_select_params = array(
					'g_aths_text'  	=> __( 'Please enter the announcement.', 'g_aths-select', 'announcement-ticker-highlighter-scroller' ),
					'g_aths_status' => __( 'Please select the display status.', 'g_aths-select', 'announcement-ticker-highlighter-scroller' ),
					'g_aths_order' 	=> __( 'Please enter the display order, only number.', 'g_aths-select', 'announcement-ticker-highlighter-scroller' ),
					'g_aths_delete'	=> __( 'Do you want to delete this record?', 'g_aths-select', 'announcement-ticker-highlighter-scroller' ),
				);
				wp_localize_script( 'g_aths-adminscripts', 'g_aths_adminscripts', $g_aths_select_params );
				break;
		}
	}
}

add_shortcode( 'announcement-ticker', 'g_aths_shortcode' );
add_action('plugins_loaded', 'g_aths_textdomain');
add_action('init', 'g_aths_add_javascript_files');
add_action("plugins_loaded", "g_aths_widget_init");
register_activation_hook(__FILE__, 'g_aths_activation');
register_deactivation_hook( __FILE__, 'g_aths_deactivate' );
add_action( 'admin_enqueue_scripts', 'g_aths_adminscripts' );
?>