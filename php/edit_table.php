<?php 
	$this_id = $_GET['id'];
	$table_name = $wpdb->prefix . "websimon_tables";
	$result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$this_id'");
	
	foreach ($result as $results) {
		$numrow = $results->rows;
		$numcol = $results->cols;
		$style = $results->style;
		$shortcode = $results->shortcode;
		$name = $results->tablename;
		$headlines = $results->headlines;
		$content = $results->content;
	}
	
$row_counter = 1;
$col_counter = 1;
$cell_counter = 1;
//javascripts to validate actions with table content
echo '
<script type="text/javascript">
	var wsValidateDelCol = function() {
		var answer =  confirm("Are you sure you want to delete this column?");
		if (answer) { return true; } else { return false; }
	}
	var wsValidateDelContent = function() {
		var answer = confirm("Are you sure you want to delete all of this tables content?");
		if (answer) { return true; } else { return false; }
	}
	var wsValidateDelRow = function() {
		var answer = confirm("Are you sure you want to delete this row?");
		if (answer) { return true; } else { return false; }
	}
	var SelectAll = function(id) {
		document.getElementById(id).focus();
		document.getElementById(id).select();
	};
</script>
';

echo '
<div id="icon-tools" class="icon32">
				<br>
			</div>
		
<h2 class="nav-tab-wrapper">
<a class="nav-tab" href="' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables">All Tables</a>
<a class="nav-tab nav-tab-active" href="' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&action=edit_table&id=' . $this_id . '">Edit Table Content</a>
<a class="nav-tab" href="' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&action=edit_style&id=' . $this_id . '">Edit Table Structure & Style</a>
</h2>
		
	<h2>Edit content for ' . $name . '</h2>';
	$selectID = 1;
	echo 'shortcode: <input id="' . $selectID .'" onClick="SelectAll(' . $selectID .');" type="text" value="' . htmlentities($shortcode) . '" />';
	
	//the nonce for table organize
	if ( function_exists('wp_nonce_field') ) {
		$organize_nonce = wp_create_nonce('organize_table');
	}
		
	if ($_GET['column_change'] == '1') {
		echo '
		<div id="websimon-tables-message">
			You have added or deleted a column.<br />Please note that your settings for column widths might need a change in <a href="?page=websimon_tables&action=edit_style&id=' . $this_id . '">Edit table structure and style</a>
		</div>
		';
	}

	echo '<form method="post" action="' . $_SERVER['REQUEST_URI'] . '">	
			<p class="submit">
			<input type="submit" value="Save Table Content" class="button-secondary" />
			</p>
			';

//nonce security check
wp_nonce_field('table-content', 'nonce_table_content');

echo '
<table class="widefat"><thead><tr><th class="empty-cell" width="100px">Row Info</th>';
	
	//number of the column thead
	while ($col_counter <= $numcol) {
		
	
		echo '<th>Column ' . $col_counter . '
		<a href="?page=websimon_tables&action=ws_organize_table_content&table_id=' . $this_id . '&organize=new_col_left&before=' . $col_counter . '&_wpnonce=' . $organize_nonce . '">
			<img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/websimon-tables/images/insert_column.png" title="insert column to the left" style="float:left;margin: 0 10px 0 0"/>
		</a>';
		if ($numcol > 1) {
			echo '
			<a onclick="return wsValidateDelCol()" href="?page=websimon_tables&action=ws_organize_table_content&table_id=' . $this_id . '&organize=delete_col&column=' . $col_counter . '&_wpnonce=' . $organize_nonce . '">
				<img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/websimon-tables/images/delete_column.png" title="delete column" style="float:left;margin: 0 10px 0 0"/>
			</a>';
		}
		if ($col_counter != 1) {
			echo '
			<a href="?page=websimon_tables&action=ws_organize_table_content&table_id=' . $this_id . '&organize=move_col_left&column=' . $col_counter . '&_wpnonce=' . $organize_nonce . '">
				<img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/websimon-tables/images/move_col_left.png" title="Move column one step left" style="float:left;margin: 0 10px 0 0"/>
			</a>';
		}
		if ($col_counter != $numcol) {
			echo '
			<a href="?page=websimon_tables&action=ws_organize_table_content&table_id=' . $this_id . '&organize=move_col_right&column=' . $col_counter . '&_wpnonce=' . $organize_nonce . '">
				<img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/websimon-tables/images/move_col_right.png" title="Move column one step right" style="float:left;margin: 0 10px 0 0"/>
			</a>';
		}
		if ($col_counter == $numcol) {
			echo '<a href="?page=websimon_tables&action=ws_organize_table_content&table_id=' . $this_id . '&organize=new_col_last&after=' . $col_counter . '&_wpnonce=' . $organize_nonce . '">
				<img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/websimon-tables/images/insert_column_right.png" title="insert column to the right" style="float:right;margin: 0 10px 0 0"/>
			</a>';
		}
		
		echo '</th>';
		$col_counter++;
	}
	$col_counter = 1;
	
	//number of the column tfoot
	echo '</tr></thead><tfoot><tr><th class="empty-cell">Row Info</th>';
	while ($col_counter <= $numcol) { 
		echo '<th>Column ' . $col_counter . '</th>';
		$col_counter++;
	}
	$col_counter = 1;
	echo '</tr></tfoot><tbody>';
	
	
//the headlines
$thead_content = explode('[-|-]' , $headlines); //explode headlines
echo '<tr class="table-header"><td class="table-row-explan">Table Headlines<br />
	<a href="?page=websimon_tables&action=ws_organize_table_content&table_id=' . $this_id . '&organize=new_row_top&_wpnonce=' . $organize_nonce . '">
		<img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/websimon-tables/images/new_row.png" title="insert row below" />
	</a></td>';
	while ($col_counter <= $numcol) { 
		echo '<td>
			<textarea rows="1" style="height: 30px;width:100%;" name="head' . $col_counter . '" />' . stripslashes($thead_content[$col_counter-1]) . '</textarea>
		</td>';	
		$col_counter++;
	}
	$col_counter = 1;
echo '</tr>';


//the content
$tbody_content = explode('[-%row%-]' , $content); //explode each row

while ($row_counter <= $numrow) {
	$col_counter = 1;
	echo '<tr><td class="table-row-explan">Row ' . $row_counter;
	//table row controls
	if ($row_counter != 1) {
		echo '
		<br />
		<a href="?page=websimon_tables&action=ws_organize_table_content&table_id=' . $this_id . '&organize=move_row_up&row_number=' . $row_counter . '&_wpnonce=' . $organize_nonce . '">
			<img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/websimon-tables/images/move_row_up.png" title="Move row one step upwards"/>
		</a>'; 
	}
	if ($row_counter != $numrow) {
		echo '
		<br />
		<a href="?page=websimon_tables&action=ws_organize_table_content&table_id=' . $this_id . '&organize=move_row_down&row_number=' . $row_counter . '&_wpnonce=' . $organize_nonce . '">
			<img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/websimon-tables/images/move_row_down.png" title="Move row one step downwards"/>
		</a>';
	}
	if ($numrow > 1) {
		echo '
		<br />
		<a onclick="return wsValidateDelRow()" href="?page=websimon_tables&action=ws_organize_table_content&table_id=' . $this_id . '&organize=delete_row&row_number=' . $row_counter . '&_wpnonce=' . $organize_nonce . '">
			<img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/websimon-tables/images/delete_row.png" title="delete row"/>
		</a>
		';
	}
	echo '<br />
	<a href="?page=websimon_tables&action=ws_organize_table_content&table_id=' . $this_id . '&organize=new_row&after=' . $row_counter . '&_wpnonce=' . $organize_nonce . '">
		<img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/websimon-tables/images/new_row.png" title="new row below" />
	</a>
	';
	echo '
	</td>';
	unset($cell);
	$cell = explode('[-%cell%-]' , $tbody_content[$row_counter-1]); //explode each cell

	while ($col_counter <= $numcol) {
		echo '<td><textarea style="width:100%;min-height:120px;" name="cell' . $cell_counter . '" class="cell-content">' . stripslashes($cell[$col_counter-1]) . '</textarea></td>';
		$col_counter++;
		$cell_counter++;
		
	}
	echo '</tr>';
	$row_counter++;
}
echo '</tbody></table>
	<p class="submit">
		<input type="submit" value="Save Table Content" class="button-secondary" />
		<input type="hidden" name="edit_hidden_content"/>
		<input type="hidden" name="edit_hidden_content_id" value="' . $this_id . '"/>
	</p>
	';
echo '<p>preview (May look different on your site)';
echo 
'<div style="max-width:600px;">' . 
do_shortcode($shortcode)
. '
<p class="submit">
		<input type="submit" value="Save Table Content" class="button-secondary" />

	</p>
</form>

</div></p>
<div id="contribute">
<h4>Please donate 10$</h4>
If you donate 10$ to this projekt you will encourage further develepment and updates for this plugin.
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="QMBY63UZJE4TY">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/sv_SE/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
';
?>