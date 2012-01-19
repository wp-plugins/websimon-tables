<?php
/*
Plugin Name: Websimon Tables
Plugin URI: http://websimon.se/websimon-tables
Description: Create and style tables for wordpress posts and pages
Version: 1.01
Author: Simon Rybrand
Author URI: http://websimon.se
*/

/*
Actionhooks
*/
add_action('admin_menu', 'websimon_tables_menu_items'); //menu
add_action('init', 'websimon_tables_plugin_requests', 9999); //all db requests
add_action('wp_head', 'websimon_tables_register_scripts'); //on site css
add_action('admin_head', 'websimon_tables_admin_register_head'); //admin css
add_action('plugins_loaded', 'websimon_tables_update_function'); //update
/*
Shortcodes
*/
add_shortcode('ws_table', 'websimon_tables_shortcode'); //shortcode function

/*
register and unregister hooks
*/
register_activation_hook(__FILE__,'websimon_tables_install_plugin');
register_uninstall_hook(__FILE__, 'websimon_tables_uninstall_plugin');

/*
creates the content for the shortcode
returns $table
*/
function websimon_tables_shortcode ($atts){ 
	extract(shortcode_atts(array(
		'id' => 'The correct attribute is missing'
	), $atts));
	
	//select table id from db 
	global $wpdb;
	$table_name = $wpdb->prefix . "websimon_tables";
	$result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$id'");
	
	//extract content from db row
	foreach ($result as $results) {
		$tablename = $results->tablename;
		$numrow = $results->rows;
		$id = $results->id;
		$numcol = $results->cols;
		$style = $results->style;
		$design = $results->design;
		$name = $results->tablename;
		$headlines = $results->headlines;
		$content = $results->content;
	}
	
	
	$design_elements = explode(';', $design);
	
	
	//counters
	$row_counter = 1;
	$col_counter = 1;
	$cell_counter = 1;
	$class_counter = 1;
	
	//the table headlines
	$table = '<table id="t' . $id . '">
	<thead>
		<tr>';
			$thead_content = explode('[-|-]' , $headlines); //explode headlines
				while ($col_counter <= $numcol) { 
					$table .= '<th scope="col" class="t' . $id . '" ';
					$table .= 'id="n' . $col_counter . '">';
					$table .= $thead_content[$col_counter-1] . '</th>';	
					$col_counter++;
					$class_counter++;
				}
	
	//the table footer
	$col_counter = 1;
	$table .= '</tr></thead>';
	if ($design_elements[1] == 'on') {
	$table .= '<tfoot><tr>';
				$thead_content = explode('[-|-]' , $headlines); //explode headlines
						while ($col_counter <= $numcol) { 
							$table .= '<td>' . $thead_content[$col_counter-1] . '</td>';	
							$col_counter++;
						}
						$col_counter = 1;
	$table .= '</tr></tfoot>';
	}
	$table .= '
	<tbody>';
	
	//Odd row counter
	$odd_row_counter = 1; 
			
	//The content
	$tbody_content = explode('[-%row%-]' , $content); //explode each row
		while ($row_counter <= $numrow) {
			if ($odd_row_counter%2) { $table .= '<tr class="table-alternate"> '; } else { $table .= '<tr>'; } 
			$odd_row_counter++;
			$col_counter = 1;
			unset($cell);
			$cell = explode('[-%cell%-]' , $tbody_content[$row_counter-1]); //explode each cell

			while ($col_counter <= $numcol) {
				if ($col_counter == 1) {
					$table .= '<td class="start">';
				} else {
					$table .= '<td>';
				}
				$table .= stripslashes($cell[$col_counter-1]) . '</td>';
				$col_counter++;
				$cell_counter++;
			}
			$table .= '</tr>';
			$row_counter++;
		}

	$table .= '</tbody></table>';
	return $table;
}

//Add a submenu page
function websimon_tables_menu_items() { 
	add_submenu_page( 'tools.php', 'Websimon Tables', 'Websimon Tables', 'manage_options', 'websimon_tables', 'websimon_tables_plugin_page');
} 

/*
function that displays all admin pages
*/
function websimon_tables_plugin_page () { 
	if (!current_user_can('manage_options'))  { 
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	global $wpdb;
	if ($_GET['action'] && $_GET['action'] == 'edit_table') { 
		require_once( 'php/edit_table.php' );
	}
	elseif ($_GET['action'] && $_GET['action'] == 'edit_style') { 
		require_once( 'php/edit_style.php' );
	}
	elseif ($_GET['action'] && $_GET['action'] == 'delete_row_col') { 
	} else {
		require_once( 'php/all_tables.php' );
	}
}

/*
Handles all requests to and from database
*/
function websimon_tables_plugin_requests(){ 
	require_once( 'php/requests.php' );
}

/*
Adds links to stylesheet in admin pages
*/
function websimon_tables_admin_register_head() {
    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/css/websimon_tables.css';
    $preview_url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/css/table_skins.php';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
    echo "<link rel='stylesheet' type='text/css' href='$preview_url' />\n";
}

/*
Adds the link to dynamic css
*/
function websimon_tables_register_scripts () { 
	$plugin_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));
	echo '<link type="text/css" rel="stylesheet" href="' . $plugin_url . '/css/table_skins.php	" />' . "\n";
	if (!is_admin()) {
	
	}
}

/*
Installs the table wp_websimon_tables 
*/
function websimon_tables_install_plugin() {
	$websimon_tables_db_version = "1.0";
	$websimon_tables_version = "1.0";
	global $wpdb;

	$table_name = $wpdb->prefix . "websimon_tables";
	$sql = "CREATE TABLE " . $table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  tablename VARCHAR(150) DEFAULT '' NOT NULL,
	  shortcode VARCHAR(500) DEFAULT '' NOT NULL,
	  rows VARCHAR(500) DEFAULT '' NOT NULL,
	  cols VARCHAR(500) DEFAULT '' NOT NULL,
	  style VARCHAR(500) DEFAULT '' NOT NULL,
	  design TEXT(5000) DEFAULT '' NOT NULL,
	  advanced TEXT(5000) DEFAULT '' NOT NULL,
	  headlines TEXT(5000) DEFAULT '' NOT NULL,
	  content TEXT(100000) DEFAULT '' NOT NULL,
	  UNIQUE KEY id (id)
	) ENGINE=MyISAM DEFAULT CHARSET=UTF8 AUTO_INCREMENT=1 ;";
		
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	   
	add_option("websimon_tables_db_version", $websimon_tables_db_version);
	add_option("websimon_tables_version", $websimon_tables_version);
}

/*
Updates for database and version of plugin in the future 
*/
function websimon_tables_update_function() {

    if (get_site_option('websimon_tables_db_version') != $websimon_tables_db_version) {
		update_option("websimon_tables_db_version", '1.01');
    }
	if (get_site_option('websimon_tables_version') != $websimon_tables_version) {
        update_option("websimon_tables_version", '1.01');
    }
		
}
/*
Removes the the table wp_websimon_tables if plugin is uninstalled
*/
function websimon_tables_uninstall_plugin () {
	global $wpdb;
	$table_name = $wpdb->prefix . "websimon_tables";
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
	delete_option("websimon_tables_db_version");
	delete_option("websimon_tables_version");
}

?>