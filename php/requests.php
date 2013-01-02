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
	function roundFive($numcol) 
	{
		$div = floor(100/$numcol);
		$rest = $div % 5;
		if ($rest >= 3) 
		{
			return ($div + (5-$rest));
		} 
		elseif ($rest < 3) 
		{
			return ($div - $rest);
		} 
		else 
		{
			return $div;
		}
	}
	
	
	$startwidths = roundFive($cols);
	$i = 1;
	$colwidths = '';
	while ($i < $cols) 
	{
		$colwidths .= $startwidths . '%:';
		$i++;
	}
	
	$colwidths .= $startwidths . '%';

	$rows_affected = $wpdb->insert( $table_name, array( 
										'tablename' => $name , 'rows' => $rows , 'cols' => $cols, 'style' => 'minimalistwhite' , 
										'design' => 'center;on;on;;on;;1.5;1.0;' . $colwidths . ';;;1.2;2.0;center;top;',
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
if (isset($_GET['action']) && $_GET['action'] == 'delete_table') {
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
Delete Table Content
*/
if (isset($_GET['action']) && $_GET['action'] == 'delete_table_content') {
$nonce = $_GET['_wpnonce'];
if ( empty($_GET) || !wp_verify_nonce($nonce, 'delete-table-content' ) )
{
   print 'Sorry, you cannot process data this way.';
   exit;	
} else {
	global $wpdb;
	$table_name = $wpdb->prefix . "websimon_tables";
	$id = $_GET['delete_id'];
	
	$result = $wpdb->get_results("SELECT rows,cols,tablename FROM $table_name WHERE id='$id'");
	foreach ($result as $results) {
		$numrow = $results->rows;
		$numcol = $results->cols;
	}

	//row and column counters
	$row_counter = 1; 
	$col_counter = 1;
	
	
	while ($col_counter <= $numcol) { //concatenate the headlines to variable $headline
		$headlines .= '[-|-]';
		$col_counter++;
	}
	
	$numcells = 1;
		
	while ($row_counter <= $numrow) { //concatenate the cells to variable $content
		$col_counter = 1;
		   
		while ($col_counter <= $numcol) {
			$content .= '[-%cell%-]';
			$col_counter++;
		}
		if ($row_counter < $numrow) {
			$content .= '[-%row%-]';
		} 		
		$row_counter++;
	}
	$wpdb->update( $table_name, array( 'headlines' => $headlines, 'content' => $content ), array( 'id' => $id ) );		
	header('Location: ' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&updated=true');
	exit;	
}
}

/*
Copy entire Table
*/
if (isset($_GET['action']) && $_GET['action'] == 'duplicate_table') {
$nonce = $_GET['_wpnonce'];
if ( empty($_GET) || !wp_verify_nonce($nonce, 'duplicate-table' ) )
{
   print 'Sorry, you cannot process data this way.';
   exit;	
} else {
	$copy = get_option('websimon_tables_copy');
	global $wpdb;
	$table_name = $wpdb->prefix . "websimon_tables";
	$duplicate_table = $_GET['duplicate_id'];
	$result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$duplicate_table'");

	foreach ($result as $results) {
		$tablename = $results->tablename . $copy;
		$rows = $results->rows;
		$cols = $results->cols;
		$style = $results->style;
		$design = $results->design;
		$advanced = $results->advanced;
		$headlines = $results->headlines;
		$content = $results->content;
	}
	$rows_affected = $wpdb->insert( $table_name, array( 
										'tablename' => $tablename,
										'rows' => $rows,
										'cols' => $cols,
										'style' => $style,										
										'design' => $design,
										'advanced' => $advanced,
										'headlines' => $headlines,
										'content' => $content
										));
	
	$result = $wpdb->get_results("SELECT * FROM $table_name WHERE tablename='$tablename'");
	foreach ($result as $results) {
		$id = $results->id;
		$shortcode = '[ws_table id="' . $id . '"]';
	}
	$copy = $copy+1;
	update_option("websimon_tables_copy", $copy);
	$wpdb->update( $table_name, array( 'shortcode' => $shortcode ), array( 'id' => $id ) );	
	
	header('Location: ' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&updated=true');
	exit;	
}
}

/*
Rename table
*/
if (isset($_POST['rename_table_hidden'])) { 
	
if ( empty($_POST) || !wp_verify_nonce($_POST['nonce_rename_table'],'rename_table') )
{
   print 'Sorry, you cannot process data this way.';
   exit;
}
	else
{
	global $wpdb;
	$table_name = $wpdb->prefix . "websimon_tables";
	$id = $_POST['rename_table_id'];
	$new_name = $_POST['rename_table_name'];
	$wpdb->update( $table_name, array( 'tablename' => $new_name ), array( 'id' => $id ) );	
	header('Location: ' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&renamed=' . $id);
	exit;	
	
}
}

/*
* Import table via .csv
*/
if (isset($_POST['import_table_hidden'])) { 
	
if ( empty($_POST) || !wp_verify_nonce($_POST['nonce_import_table'],'import_table') )
{
   print 'Sorry, you cannot process data this way.';
   exit;
}
	else
{
	global $wpdb;
	$table_name = $wpdb->prefix . "websimon_tables";
	$heads = false;
	$headsdone = false;
	
	$csvurl = $_POST['import_table_file'];
	if (isset($_POST['import_table_first_row_head']) && $_POST['import_table_first_row_head'] == 'on' )
	{
		$heads = true;
	}
	
	$content = '';
	$headlines = '';
	$nr_of_rows = 0;
	$nr_of_cols = 1;
	$col = 1;
	if ($handle = @fopen($csvurl, "r") !== FALSE)
	{
		$handle = fopen($csvurl, "r");	
		
		/*
		* read csv file
		* max rows is set to 100 000
		*/
		$i = 0; 
		while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) 
		{
			$nr_of_rows++;
			$num = count($data);

			if ($heads && !$headsdone)
			{
				for ($c=0; $c < $num; $c++) 
				{
					$dataArray = explode(';', utf8_encode( $data[$c]) );
					$headcount = 0;
					foreach ($dataArray as $cell)
					{
						if ($headcount == 0)
						{
							$headlines .= $cell;
						}
						else
						{
							$headlines .= '[-|-]' . $cell;
						}
						$headcount++;
					}
				}
				$headsdone = true;
			}
			else
			{
				for ($c=0; $c < $num; $c++) 
				{
					$dataArray = explode(';', utf8_encode( $data[$c]) );
					
					if ( $nr_of_rows == 1 || ($headsdone && $nr_of_rows == 2) )
					{
						$cellcount = 0;
						foreach ($dataArray as $cell)
						{
							if ($cellcount == 0)
							{
								$content .= $cell;
							}
							else
							{
								$content .= '[-%cell%-]' . $cell;
							}
							$cellcount++;
						}
					}
					else
					{
						$content .= '[-%row%-]';
						$cellcount = 0;
						foreach ($dataArray as $cell)
						{
							if ($cellcount == 0)
							{
								$content .= $cell;
							}
							else
							{
								$content .= '[-%cell%-]' . $cell;
							}
							$cellcount++;
						}
					}
					
					//keep track of max columns
					if ( count($dataArray) > $nr_of_cols) 
					{
						$nr_of_cols = count($dataArray);
					}
				}
			}
		}
		fclose($handle);
		
		
		/* 
		* prepare table
		*/
		$name = $_POST['import_table_name'];
		if ($heads)
		{
			$rows = $nr_of_rows-1;
			$cols = $nr_of_cols-1;
		}
		else 
		{
			$rows = $nr_of_rows;
			$cols = $nr_of_cols;
		}
		
		/*
		Function to round up or down to five.
		Used to create the inital widths for columns.
		*/
		function importRoundFive($numcol) 
		{
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
		
		$startwidths = importRoundFive($cols);
		$r = 1;
		$colwidths = '';
		while ($r < $cols) 
		{
			$colwidths .= $startwidths . '%:';
			$r++;
		}
		
		$colwidths .= $startwidths . '%';
		
		
		/*
		* Save imported table
		*/
		$rows_affected = $wpdb->insert( 
										$table_name, 
										array( 
											'tablename' => $name,
											'rows' => $rows, 
											'cols' => $cols, 
											'style' => 'minimalistwhite', 
											'design' => 'center;on;on;;on;;1.5;1.0;' . $colwidths . ';;;1.2;2.0;center;top;',
											'advanced' => ';;;FFFFFF;EEEEEE;FFFFFF;EEEEEE;FFFFFF;FCFCFC;000000;000000;000000;FFFFFF;CCCCCC', 
											'headlines' => $headlines,
											'content' => $content
											)); 
		
		$get_id = $wpdb->get_results("SELECT id FROM $table_name WHERE tablename='$name'");	
		
		foreach ($get_id as $id) {
			$shortcode = '[ws_table id="' . $id->id . '"]';
		}
		$wpdb->update( $table_name, array( 'shortcode' => $shortcode ), array( 'tablename' => $name ) );
		
		header('Location: ?page=websimon_tables');
		exit;
	}
	elseif ($handle = @fopen($csvurl, "r") === FALSE) //could not find file
	{
		$errormessage = '0';		
		header('Location: ?page=websimon_tables&action=ws_import_table&error=' . $errormessage . '');
		exit;
	}

}
}

/*
Export table to .csv file
*/

if (isset($_GET['action']) && $_GET['action'] == 'export_table_content') {
	$nonce = $_GET['_wpnonce'];
	if ( empty($_GET) || !wp_verify_nonce($nonce, 'export-table-content' ) )
	{
	   print 'Sorry, you cannot process data this way.';
	   exit;	
	} 
	else 
	{
		$output = '';
		$exportArray = array();
		$id = $_GET['export_id'];
		$name = $_GET['exports_name'];
		global $wpdb;
		$table_name = $wpdb->prefix . "websimon_tables";
		$results = $wpdb->get_results("SELECT headlines, content FROM $table_name WHERE id='$id'");
		
		foreach ($results as $result)
		{
			
			//add headlines
			$headArray = explode('[-|-]', $result->headlines);
			$count = 0; 
			foreach ($headArray as $elem)
			{
				if ($count == 0)
				{
					$output .= utf8_decode($elem);
				}
				else
				{
					$output .= ';' . utf8_decode($elem);
				}
				$count++;
			}
			$output .= "\r\n";
			
			//add content
			$bodyArray = explode('[-%row%-]' , $result->content);
			foreach ($bodyArray as $elem)
			{
				$count = 0; 
				$rowArray = explode('[-%cell%-]', $elem);
				foreach ($rowArray as $cell)
				{
					if ($count == 0)
					{
						$output .= utf8_decode($cell);
					}
					else
					{
						$output .= ';' . utf8_decode($cell);
					}
					$count++;
				}
			$output .= "\r\n";
			}
		}
		
		$sitename = get_bloginfo( 'url' );
		$filename = 'exports-table-' . $name . '.csv';
		
		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );
		echo $output;
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
Organize table content 
- Delete content
- add or delete columns 
- add or delete rows
- Move rows or columns
*/
if (isset($_GET['action']) && $_GET['action'] == 'ws_organize_table_content') {
$nonce = $_GET['_wpnonce'];
if ( empty($_GET) || !wp_verify_nonce($nonce, 'organize_table' ) )
{
   print 'Sorry, you cannot process data this way.';
   exit;	
} else {
	
	//define
	$new_heads = '';
	$new_content = '';



	global $wpdb;
	$table_name = $wpdb->prefix . "websimon_tables";
	$id = $_GET['table_id'];
	$organize = $_GET['organize'];
	$result = $wpdb->get_results("SELECT headlines, content, rows, cols FROM $table_name WHERE id='$id'");
	foreach ($result as $results) {
		$old_content = $results->content;
		$numrow = $results->rows;
		$numcol = $results->cols;
		$headlines = $results->headlines;
	}
	
	//insert a new row
	if ($organize == 'new_row') { 
		$after_row = $_GET['after'];
		$row_counter = 1;
		$col_counter = 1;
		$row_array = explode('[-%row%-]', $old_content);
		
		while ($row_counter <= $numrow) {
			
			if ($after_row == $row_counter) {
				if ($row_counter == 1) { $new_content .= $row_array[$row_counter-1]; } else { $new_content .= '[-%row%-]' . $row_array[$row_counter-1]; }
				$new_content .= '[-%row%-]';
				while ($col_counter <= $numcol) {
					$new_content .= '[-%cell%-]';
					$col_counter++;
				}
			} else {
				if ($row_counter == 1) { $new_content .= $row_array[$row_counter-1]; } else { $new_content .= '[-%row%-]' . $row_array[$row_counter-1]; }
			}
						
			$row_counter++;
		}
		$column_message = '&column_change=1';
		$wpdb->update( $table_name, array( 'rows' => $numrow+1, 'content' => $new_content ), array( 'id' => $id ) );	
	
	//insert a row at the top
	} elseif ($organize == 'new_row_top') { 
		$col_counter = 1;
		while ($col_counter <= $numcol) {
			$new_row .= '[-%cell%-]';
			$col_counter++;
		}
		$new_row .= '[-%row%-]';
		
		$new_content = $new_row . $old_content;
		$wpdb->update( $table_name, array( 'rows' => $numrow+1, 'content' => $new_content ), array( 'id' => $id ) );	
		
	//delete a row	
	} elseif ($organize == 'delete_row') { 
		$delete_row = $_GET['row_number'];
		$row_counter = 1;
		$col_counter = 1;
		$row_array = explode('[-%row%-]', $old_content);
		unset($row_array[$delete_row-1]);
		$row_array = array_values($row_array);
	
		$i = 1;
		foreach ($row_array as $row) {
			if ($i == 1) {
				$new_content .= $row_array[$i-1];
			} else {
				$new_content .= '[-%row%-]' . $row_array[$i-1];
			}
			$i++;
		}
		$column_message = '&column_change=1';
		$wpdb->update( $table_name, array( 'rows' => $numrow-1, 'content' => $new_content ), array( 'id' => $id ) );	
	
	//Move a row upwards one step
	} elseif ($organize == 'move_row_up') {
		$move_row = $_GET['row_number'];
		$row_array = explode('[-%row%-]', $old_content);
		$row_counter = 1;
		while ($row_counter <= $numrow) {
			if ( ($row_counter + 1) == $move_row) {
				$new_content .= $row_array[$row_counter] . '[-%row%-]' . $row_array[$row_counter-1];
				if ($row_counter+1 != $numrow) {
					$new_content .= '[-%row%-]';
				}
				$row_counter = $row_counter+2;
			} else {
				if ($row_counter == 1) {
					$new_content .= $row_array[$row_counter-1] . '[-%row%-]'; 
				} else {
					if ($row_counter != $numrow) {
						$new_content .= $row_array[$row_counter-1] . '[-%row%-]';
					} else {
						$new_content .= $row_array[$row_counter-1];
					}
					
				}
				$row_counter++;
			}
		}
		$column_message = '&column_change=1';
		$wpdb->update( $table_name, array( 'content' => $new_content ), array( 'id' => $id ) );		
	
	//Move a row one step down
	} elseif ($organize == 'move_row_down') {
		$move_row = $_GET['row_number'];
		$row_array = explode('[-%row%-]', $old_content);
		$row_counter = 1;
		while ($row_counter <= $numrow) {
			if ( $row_counter == $move_row) {
				$new_content .= $row_array[$row_counter] . '[-%row%-]' . $row_array[$row_counter-1];
				if ($row_counter != $numrow) {
					$new_content .= '[-%row%-]';
				}
				$row_counter = $row_counter+2;
			} else {
				if ($row_counter == 1) {
					$new_content .= $row_array[$row_counter-1] . '[-%row%-]'; 
				} else {
					if ($row_counter != $numrow) {
						$new_content .= $row_array[$row_counter-1] . '[-%row%-]';
					} else {
						$new_content .= $row_array[$row_counter-1];
					}
					
				}
				$row_counter++;
			}
		}
		$column_message = '&column_change=1';
		$wpdb->update( $table_name, array( 'content' => $new_content ), array( 'id' => $id ) );		
	
	//insert a column to the left
	} elseif ($organize == 'new_col_left') { //new column to the left
		$insert_before = $_GET['before'];
		$headlines_counter = 1;
		
		$headlines_array = explode('[-|-]', $headlines);
		foreach ($headlines_array as $head) {
			if ($headlines_counter == 1) {	
				if ($insert_before == $headlines_counter) { 
					$new_heads .= '[-|-]' . $head;
				} else {
					$new_heads .= $head;
				}
			} else {
				if ($insert_before == $headlines_counter) { 
					$new_heads .= '[-|-][-|-]' . $head;
				} else {
					$new_heads .= '[-|-]' . $head;
				}
			}
			$headlines_counter++;
		}
		
		$row_array = explode('[-%row%-]', $old_content);
		$row_counter = 1;		
		foreach ($row_array as $row) { 
			$col_counter = 1;
			$col_array = explode('[-%cell%-]',$row);
			foreach ($col_array as $col) {
				if ($col_counter == 1) {	
					if ($insert_before == $col_counter) { 
						$new_content .= '[-%cell%-]' . $col;
					} else {
						$new_content .= $col;
					}
				} else {
					if ($insert_before == $col_counter) { 
						$new_content .= '[-%cell%-][-%cell%-]' . $col;
					} else {
						$new_content .= '[-%cell%-]' . $col;
					}
				}
				$col_counter++;
			}
			
			if ($row_counter < $numrow) { $new_content .= '[-%row%-]'; }
			$row_counter++;
		}
		$column_message = '&column_change=1';
		$wpdb->update( $table_name, array( 'cols' => $numcol+1, 'headlines' => $new_heads, 'content' => $new_content ), array( 'id' => $id ) );
		
	} elseif ($organize == 'new_col_last') {
		$row_counter = 1;		
		$new_heads .= $headlines . '[-|-]';
		$row_array = explode('[-%row%-]', $old_content);
	
		foreach ($row_array as $row) { 
			$new_content .= $row . '[-%cell%-]';
			if ($row_counter < $numrow) { $new_content .= '[-%row%-]'; }
			$row_counter++;
		}
		$column_message = '&column_change=1';
		$wpdb->update( $table_name, array( 'cols' => $numcol+1, 'headlines' => $new_heads, 'content' => $new_content ), array( 'id' => $id ) );
	
	//delete a column
	} elseif ($organize == 'delete_col') {

		$del_column = $_GET['column'];
		$headlines_counter = 1;
		
		$headlines_array = explode('[-|-]', $headlines);
		foreach ($headlines_array as $head) {
			if ($headlines_counter == 1) {	
				if ($del_column == $headlines_counter) { 
					//do nothing
				} else {
					$new_heads .= $head;
				}
			} else {
				if ($del_column == $headlines_counter) { 
					//do nothing
				} else {
					if ($del_column == 1 && $headlines_counter == 2) { 
						$new_heads .= $head; 
					} else { 
						$new_heads .= '[-|-]' . $head; 
					}
				}
			}
			$headlines_counter++;
		}
	
		$row_array = explode('[-%row%-]', $old_content);
		$row_counter = 1;		
		foreach ($row_array as $row) { 
			$col_counter = 1;
			$col_array = explode('[-%cell%-]',$row);
			foreach ($col_array as $col) {
				if ($col_counter == 1) {	
					if ($del_column == $col_counter) { 
						//do nothing
					} else {
						$new_content .= $col;
					}
				} else {
					if ($del_column == $col_counter) { 
						//do nothing
					} else {
						if ($del_column == 1 && $col_counter == 2) { 
							$new_content .= $col;
						} else { 
							$new_content .= '[-%cell%-]' . $col;
						}
					}
				}
				$col_counter++;
			}
			if ($row_counter < $numrow) { $new_content .= '[-%row%-]'; }
			$row_counter++;
		}
		$column_message = '&column_change=1';
		$wpdb->update( $table_name, array( 'cols' => $numcol-1, 'headlines' => $new_heads, 'content' => $new_content ), array( 'id' => $id ) );
	
	//move column one step left
	} elseif ($organize == 'move_col_left') {
		$move_column = $_GET['column'];
		$headlines_counter = 1;
		
		$headlines_array = explode('[-|-]', $headlines);
		while ($headlines_counter <= $numcol) {
			if ($headlines_counter == 1) {
				if ($headlines_counter+1 == $move_column) {
					$new_heads .= $headlines_array[$headlines_counter] . '[-|-]' . $headlines_array[$headlines_counter-1];
					$headlines_counter = $headlines_counter+2;
				} else {
					$new_heads .= $headlines_array[$headlines_counter-1];
					$headlines_counter++;
				}
			} else {
				if ($headlines_counter+1 == $move_column) {
					$new_heads .= '[-|-]' . $headlines_array[$headlines_counter] . '[-|-]' . $headlines_array[$headlines_counter-1];
					$headlines_counter = $headlines_counter+2;
				} else {
					$new_heads .= '[-|-]' .$headlines_array[$headlines_counter-1];
					$headlines_counter++;
				}
			}
		}		
	
		$row_array = explode('[-%row%-]', $old_content);
		$row_counter = 1;		
		foreach ($row_array as $row) { 
			$col_counter = 1;
			$col_array = explode('[-%cell%-]',$row);
			while ($col_counter <= $numcol) {
				if ($col_counter == 1) {
					if ($col_counter+1 == $move_column) {
						$new_content .= $col_array[$col_counter] . '[-%cell%-]' . $col_array[$col_counter-1];
						$col_counter = $col_counter+2;
					} else {
						$new_content .= $col_array[$col_counter-1];
						$col_counter++;
					}
				} else {
					if ($col_counter+1 == $move_column) {
						$new_content .= '[-%cell%-]' . $col_array[$col_counter] . '[-%cell%-]' . $col_array[$col_counter-1];
						$col_counter = $col_counter+2;
					} else {
						$new_content .= '[-%cell%-]' .$col_array[$col_counter-1];
						$col_counter++;
					}
				}
			}		
			if ($row_counter < $numrow) { $new_content .= '[-%row%-]'; }
			$row_counter++;
		}
		$column_message = '&column_change=1';
		$wpdb->update( $table_name, array( 'headlines' => $new_heads, 'content' => $new_content ), array( 'id' => $id ) );
		
	//move column one step right
	} elseif ($organize == 'move_col_right') {
		$move_column = $_GET['column'];
		$headlines_counter = 1;
		
		$headlines_array = explode('[-|-]', $headlines);
		while ($headlines_counter <= $numcol) {
			if ($headlines_counter == 1) {
				if ($headlines_counter == $move_column) {
					$new_heads .= $headlines_array[$headlines_counter] . '[-|-]' . $headlines_array[$headlines_counter-1];
					$headlines_counter = $headlines_counter+2;
				} else {
					$new_heads .= $headlines_array[$headlines_counter-1];
					$headlines_counter++;
				}
			} else {
				if ($headlines_counter == $move_column) {
					$new_heads .= '[-|-]' . $headlines_array[$headlines_counter] . '[-|-]' . $headlines_array[$headlines_counter-1];
					$headlines_counter = $headlines_counter+2;
				} else {
					$new_heads .= '[-|-]' .$headlines_array[$headlines_counter-1];
					$headlines_counter++;
				}
			}
		}		

		$row_array = explode('[-%row%-]', $old_content);
		$row_counter = 1;		
		foreach ($row_array as $row) { 
			$col_counter = 1;
			$col_array = explode('[-%cell%-]',$row);
			while ($col_counter <= $numcol) {
				if ($col_counter == 1) {
					if ($col_counter == $move_column) {
						$new_content .= $col_array[$col_counter] . '[-%cell%-]' . $col_array[$col_counter-1];
						$col_counter = $col_counter+2;
					} else {
						$new_content .= $col_array[$col_counter-1];
						$col_counter++;
					}
				} else {
					if ($col_counter == $move_column) {
						$new_content .= '[-%cell%-]' . $col_array[$col_counter] . '[-%cell%-]' . $col_array[$col_counter-1];
						$col_counter = $col_counter+2;
					} else {
						$new_content .= '[-%cell%-]' .$col_array[$col_counter-1];
						$col_counter++;
					}
				}
			}		
			if ($row_counter < $numrow) { $new_content .= '[-%row%-]'; }
			$row_counter++;
		}
		$column_message = '&column_change=1';
		$wpdb->update( $table_name, array( 'headlines' => $new_heads, 'content' => $new_content ), array( 'id' => $id ) );
	}
	header('Location: ?page=websimon_tables&action=edit_table&id=' . $id . $column_message);
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
	if (isset($_POST['table_style'])) { $style = $_POST['table_style']; } else { $style = ''; }
	if (isset($_POST['num_rows'])) { $rows = $_POST['num_rows']; } else { $rows = ''; }
	if (isset($_POST['num_cols'])) { $cols = $_POST['num_cols']; } else { $cols = ''; }
	if (isset($_POST['edit_hidden_settings_id'])) { $id = $_POST['edit_hidden_settings_id']; } else { $id = ''; }
	if (isset($_POST['table_text_align'])) { $text_align = $_POST['table_text_align']; } else { $text_align = ''; }
	if (isset($_POST['fixed_width'])) { $fixed_width = $_POST['fixed_width'];  } else { $fixed_width = ''; }
	if (isset($_POST['cell_padding'])) { $cell_padding = $_POST['cell_padding']; } else { $cell_padding = ''; }
	if (isset($_POST['table_footer'])) { $table_footer = $_POST['table_footer']; } else { $table_footer = ''; }
	if (isset($_POST['table_header'])) { $table_header = $_POST['table_header']; } else { $table_header = ''; }
	if (isset($_POST['table_h_borders'])) { $table_h_borders = $_POST['table_h_borders']; } else { $table_h_borders = ''; }
	if (isset($_POST['table_v_borders'])) { $table_v_borders = $_POST['table_v_borders']; } else { $table_v_borders = ''; }
	if (isset($_POST['shadow_effect'])) { $shadow_effect = $_POST['shadow_effect']; } else { $shadow_effect = ''; }
	if (isset($_POST['hover_effect'])) { $hover_effect = $_POST['hover_effect']; } else { $hover_effect = ''; }
	if (isset($_POST['font_size_h'])) { $font_size_h = $_POST['font_size_h']; } else { $font_size_h = ''; }
	if (isset($_POST['font_size_b'])) { $font_size_b = $_POST['font_size_b']; } else { $font_size_b = ''; }
	if (isset($_POST['line_height_b'])) { $line_height_b = $_POST['line_height_b']; } else { $line_height_b = ''; }
	if (isset($_POST['line_height_h'])) { $line_height_h = $_POST['line_height_h']; } else { $line_height_h = ''; }
	if (isset($_POST['table_text_align_h'])) { $table_text_align_h = $_POST['table_text_align_h']; } else { $table_text_align_h = ''; }
	if (isset($_POST['v_text_align'])) { $v_text_align = $_POST['v_text_align']; } else { $v_text_align = ''; }
	
	
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
	. ';' . $line_height_h . ';' . $table_text_align_h . ';' . $v_text_align . ';' . $table_header;

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
	
	if (isset($_POST['edit_hidden_settings_id'])) { $id = $_POST['edit_hidden_settings_id']; } else { $id = ''; }
	if (isset($_POST['fixed_width'])) { $fixed_width = $_POST['fixed_width']; } else { $fixed_width = ''; }
	if (isset($_POST['cell_padding'])) { $cell_padding = $_POST['cell_padding']; } else { $cell_padding = ''; }
	if (isset($_POST['custom_settings'])) { $style = $_POST['custom_settings']; } else { $style = ''; }
	if (isset($_POST['custom_settings'])) { $custom_settings = $_POST['custom_settings']; } else { $custom_settings = ''; }
	if (isset($_POST['start_color_h'])) { $start_color_h = $_POST['start_color_h']; } else { $start_color_h = ''; }
	if (isset($_POST['end_color_h'])) { $end_color_h = $_POST['end_color_h']; } else { $end_color_h = ''; }
	if (isset($_POST['start_color_f'])) { $start_color_f = $_POST['start_color_f']; } else { $start_color_f = ''; }
	if (isset($_POST['end_color_f'])) { $end_color_f = $_POST['end_color_f']; } else { $end_color_f = ''; }
	if (isset($_POST['row_color'])) { $row_color = $_POST['row_color']; } else { $row_color = ''; }
	if (isset($_POST['alt_row_color'])) { $alt_row_color = $_POST['alt_row_color']; } else { $alt_row_color = ''; }
	if (isset($_POST['h_font_color'])) { $h_font_color = $_POST['h_font_color']; } else { $h_font_color = ''; }
	if (isset($_POST['f_font_color'])) { $f_font_color = $_POST['f_font_color']; } else { $f_font_color = ''; }
	if (isset($_POST['c_font_color'])) { $c_font_color = $_POST['c_font_color']; } else { $c_font_color = ''; }
	if (isset($_POST['v_border_color'])) { $v_border_color = $_POST['v_border_color']; } else { $v_border_color = ''; }
	if (isset($_POST['h_border_color'])) { $h_border_color = $_POST['h_border_color']; } else { $h_border_color = ''; }
	
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