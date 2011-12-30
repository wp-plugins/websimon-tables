<?php
/*
Create a new table
*/
if (isset($_POST['create_hidden'])) { 
	
if ( empty($_POST) || !wp_verify_nonce($_POST['nonce_all_tables'],'new_table') )
{
   print 'Sorry, you cannot process data this way.';
   exit;
}
	else
{
	global $wpdb;
	$table_name = $wpdb->prefix . "websimon_tables";
	$name = $_POST['table_name'];
	$rows = $_POST['num_rows'];
	$cols = $_POST['num_cols'];
	$avoid_duplicate = $wpdb->get_results("SELECT * FROM $table_name WHERE tablename='$name'");	
	$num_dupl = $wpdb->num_rows;
	
	/*
	Function to round up or down to five.
	Used to create the inital widths for columns.
	*/
	function roundFive($numcol) {
		$div = floor(100/$numcol);
		$rest = $div % 5;
		if ($rest >= 3) {
			return ($div + (5-$rest));
		} elseif ($rest < 3) {
			return ($div - $rest);
		} else {
			return $div;
		}
	}
	
	
	$startwidths = roundFive($cols);
	$i = 1;
	
	while ($i < $cols) {
		$colwidths .= $startwidths . '%:';
		$i++;
	}
	
	$colwidths .= $startwidths . '%';

	$rows_affected = $wpdb->insert( $table_name, array( 
										'tablename' => $name , 'rows' => $rows , 'cols' => $cols, 'style' => 'minimalistwhite' , 
										'design' => 'center;on;on;;on;;1.5;1.0;' . $colwidths . ';;;1.2;2.0;center;top',
										'advanced' => ';;;FFFFFF;EEEEEE;FFFFFF;EEEEEE;FFFFFF;FCFCFC;000000;000000;000000;FFFFFF;CCCCCC' 
										));
	
	$get_id = $wpdb->get_results("SELECT id FROM $table_name WHERE tablename='$name'");	
	foreach ($get_id as $id) {
		$shortcode = '[ws_table id="' . $id->id . '"]';
	}
	$wpdb->update( $table_name, array( 'shortcode' => $shortcode ), array( 'tablename' => $name ) );	

	header('Location: ' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&updated=true');
	exit;
	}
}

/*
Delete entire Table
*/
if ($_GET['action'] == 'delete_table') {
$nonce = $_GET['_wpnonce'];
if ( empty($_GET) || !wp_verify_nonce($nonce, 'delete-table' ) )
{
   print 'Sorry, you cannot process data this way.';
   exit;	
} else {
	global $wpdb;
	$table_name = $wpdb->prefix . "websimon_tables";
	$delete_table = $_GET['delete_id'];
	$wpdb->query("DELETE FROM $table_name WHERE id='$delete_table'");
	header('Location: ' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&updated=true');
	exit;	
}
}
/*
Edit table content
Saves the table content
*/
if (isset($_POST['edit_hidden_content'])) {

if ( empty($_POST) || !wp_verify_nonce($_POST['nonce_table_content'],'table-content') )
{
   print 'Sorry, you cannot process data this way.';
   exit;
}	
	else
{

	$content = ''; //content string to be saved to the database
	$headlines = ''; //headline string to be saved to the database
	$id = $_POST['edit_hidden_content_id'];
	global $wpdb;
	$table_name = $wpdb->prefix . "websimon_tables";
	$result = $wpdb->get_results("SELECT rows,cols,tablename FROM $table_name WHERE id='$id'");
	
	foreach ($result as $results) {
		$numrow = $results->rows;
		$numcol = $results->cols;
		$name = $results->tablename;
	}
	

	//row and column counters
	$row_counter = 1; 
	$col_counter = 1;
	$cell_counter = 1;
	
	
	while ($col_counter <= $numcol) { //concatenate the headlines to variable $headline
		if ($col_counter < $numcol) {
			$headlines .= $_POST['head' . $col_counter] . '[-|-]';
			$col_counter++;
		} elseif($col_counter == $numcol) {
			$headlines .= $_POST['head' . $col_counter];
			$col_counter++;
		}
	}
	
	$numcells = 1;
		
	while ($row_counter <= $numrow) { //concatenate the cells to variable $content
		$col_counter = 1;
		while ($col_counter <= $numcol) {
			
			if ($col_counter < $numcol) {
				$content .= $_POST['cell' . $cell_counter] . '[-%cell%-]';
			} elseif ($col_counter == $numcol) {
				$content .= $_POST['cell' . $cell_counter];
			}
			$col_counter++;
			$cell_counter++;
		}
			
			if ($row_counter < $numrow) {
				$content .= '[-%row%-]';
			} 		
		$row_counter++;

	}
	$wpdb->update( $table_name, array( 'headlines' => $headlines, 'content' => $content ), array( 'id' => $id ) );	
	header('Location: ' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&action=edit_table&id=' . $id . '');
	exit;	
}
}

/*
Edit basic table Design
Saves the settings from "basic settings"
*/
if (isset($_POST['edit_hidden_settings'])) { 

if ( empty($_POST) || !wp_verify_nonce($_POST['nonce_basic_settings'],'basic-settings') )
{
   print 'Sorry, you cannot process data this way.';
   exit;
}
	else
{
	global $wpdb;
	$table_name = $wpdb->prefix . "websimon_tables";
	$style = $_POST['table_style'];
	$rows = $_POST['num_rows'];
	$cols = $_POST['num_cols'];
	$id = $_POST['edit_hidden_settings_id'];
	$text_align = $_POST['table_text_align'];
	$fixed_width = $_POST['fixed_width']; 
	$cell_padding = $_POST['cell_padding'];
	$table_footer = $_POST['table_footer'];
	$table_h_borders = $_POST['table_h_borders'];
	$table_v_borders = $_POST['table_v_borders'];
	$shadow_effect = $_POST['shadow_effect'];
	$hover_effect = $_POST['hover_effect'];
	$font_size_h = $_POST['font_size_h'];
	$font_size_b = $_POST['font_size_b'];
	$line_height_b = $_POST['line_height_b'];
	$line_height_h = $_POST['line_height_h'];
	$table_text_align_h = $_POST['table_text_align_h'];
	$v_text_align = $_POST['v_text_align'];
	
	
	//save column widths
	$numcol = $_POST['numcol'];
	$colwidths = $_POST['column1'];
	$i = 2;
	while ($i <= $numcol) {
		$colwidths .= ':' . $_POST['column' . $i];
		$i++;
	}
	
	
	$design = $text_align . ';' . $table_footer . ';' . $table_h_borders . ';' . $table_v_borders . ';' . $shadow_effect . ';' . $hover_effect
	. ';' . $font_size_h . ';' . $font_size_b . ';' . $colwidths . ';' . $fixed_width . ';' . $cell_padding . ';' . $line_height_b
	. ';' . $line_height_h . ';' . $table_text_align_h . ';' . $v_text_align;

	$wpdb->update( $table_name, array( 'rows' => $rows, 'cols' => $cols, 'style' => $style, 'design' => $design ), array( 'id' => $id ) );	
	header('Location: ' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&action=edit_style&id=' . $id . '');
	exit;	
}
}

/*
Edit custom table Design
Saves the settings from "Custom skin colors"
*/
if (isset($_POST['edit_advanced_settings'])) { 

if ( empty($_POST) || !wp_verify_nonce($_POST['nonce_advanced_settings'],'advanced-settings') )
{
   print 'Sorry, you cannot process data this way.';
   exit;
}	
	else
{
	global $wpdb;
	$table_name = $wpdb->prefix . "websimon_tables";
	
	$id = $_POST['edit_hidden_settings_id'];
	$fixed_width = $_POST['fixed_width'];
	$cell_padding = $_POST['cell_padding'];
	$style = $_POST['custom_settings'];
	$custom_settings = $_POST['custom_settings'];
	$start_color_h = $_POST['start_color_h'];
	$end_color_h = $_POST['end_color_h'];
	$start_color_f = $_POST['start_color_f'];
	$end_color_f = $_POST['end_color_f'];
	$row_color = $_POST['row_color'];
	$alt_row_color = $_POST['alt_row_color'];
	$h_font_color = $_POST['h_font_color'];
	$f_font_color = $_POST['f_font_color'];
	$c_font_color = $_POST['c_font_color'];
	$v_border_color = $_POST['v_border_color'];
	$h_border_color = $_POST['h_border_color'];
	
	//create string to save
	$advanced = $custom_settings . ';' . $fixed_width . ';' . $cell_padding . ';' . $start_color_h . ';' . $end_color_h . ';' . $start_color_f . ';' . $end_color_f . ';' . $row_color
				 . ';' . $alt_row_color . ';' . $h_font_color . ';' . $f_font_color . ';' . $c_font_color . ';' . $v_border_color . ';' . $h_border_color;

	$wpdb->update( $table_name, array( 'advanced' => $advanced ), array( 'id' => $id ) );	

	if ($style == 'on') {
		$wpdb->update( $table_name, array( 'style' => 'custom' ), array( 'id' => $id ) );
	}

	header('Location: ' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&action=edit_style&id=' . $id . '&settings=advanced');
	exit;	
}
}	
?>