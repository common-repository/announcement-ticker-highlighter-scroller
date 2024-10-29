<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('g_aths_title');
delete_option('g_aths_title_display');
delete_option('g_aths_width');
delete_option('g_aths_height');
delete_option('g_aths_css');
delete_option('g_aths_delay');
delete_option('g_aths_speed');
delete_option('g_aths_highlightcolor');
delete_option('g_aths_textcolor');
 
// for site options in Multisite
delete_site_option('g_aths_title');
delete_site_option('g_aths_title_display');
delete_site_option('g_aths_width');
delete_site_option('g_aths_height');
delete_site_option('g_aths_css');
delete_site_option('g_aths_delay');
delete_site_option('g_aths_speed');
delete_site_option('g_aths_highlightcolor');
delete_site_option('g_aths_textcolor');

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}g_aths_plugin");