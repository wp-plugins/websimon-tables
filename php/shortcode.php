<?php
	$table = '';
	//select table id from db 
	global $wpdb;
	$table_name = $wpdb->prefix . "websimon_tables";
	$result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$id'");
	
	//extract content from db row
	foreach ($result as $results) 
	{
		$tablename = $results->tablename;
		$numrow = $results->rows;
		$table_id = $results->id;
		$numcol = $results->cols;
		$style = $results->style;
		$design = $results->design;
		$name = $results->tablename;
		$headlines = $results->headlines;
		$content = $results->content;
	}
	
	//dynamic css
	include( 'table_skins.php' ); 
	$table .= '<style>' . $css . '</style>';
	
	$design_elements = explode(';', $design);
	
	//counters
	$row_counter = 1;
	$col_counter = 1;
	$cell_counter = 1;
	$class_counter = 1;

	//the table headlines
	$table .= '<table id="t' . $table_id . '">';
	if ($design_elements[15] != 'on') {
		$table .= '
		<thead>
			<tr>';
				$thead_content = explode('[-|-]' , $headlines); //explode headlines
					while ($col_counter <= $numcol) 
					{ 
						$table .= '<th scope="col" class="t' . $table_id . '" ';
						$table .= 'id="n' . $col_counter . '">';
						if (isset($thead_content[$col_counter-1])) { $table .= stripslashes($thead_content[$col_counter-1]); }
						$table .= '</th>';	
						$col_counter++;
						$class_counter++;
					}
		$table .= '</tr></thead>';
	}
	
	//the table footer
	$col_counter = 1;
	
	if ($design_elements[1] == 'on') 
	{
		
		$table .= '<tfoot><tr>';
		$thead_content = explode('[-|-]' , $headlines); //explode headlines
		while ($col_counter <= $numcol) 
		{ 
			$table .= '<td>';
			if (isset($thead_content[$col_counter-1])) { $table .= stripslashes($thead_content[$col_counter-1]); }
			$table .= '</td>';	
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
			if ($odd_row_counter%2) { $table .= '<tr class="table-alternate row' . $row_counter . '"> '; } else { $table .= '<tr class= "table-noalt row' . $row_counter . '">'; } 
			$odd_row_counter++;
			$col_counter = 1;
			unset($cell);
			if (isset($tbody_content[$row_counter-1])) {
				$cell = explode('[-%cell%-]' , $tbody_content[$row_counter-1]); //explode each cell
			}
			while ($col_counter <= $numcol) {
				if ($col_counter == 1) {
					$table .= '<td id="n' . $col_counter . '" class="start">';
				} else {
					$table .= '<td id="n' . $col_counter . '" >';
				}
				if (isset($cell[$col_counter-1])) { $table .= stripslashes($cell[$col_counter-1]); }
				$table .= '</td>';
				$col_counter++;
				$cell_counter++;
			}
			$table .= '</tr>';
			$row_counter++;
		}

	$table .= '</tbody></table>';
?>