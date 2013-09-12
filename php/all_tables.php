<?php
$table_name = $wpdb->prefix . "websimon_tables";
$result = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");

//create javascript array with table names to prevent tables from having the same name
$jscript = '[';
$i = 0;
foreach ($result as $results) 
{	
	if ($i == 0) 
	{
		$jscript .= '"' . $results->tablename . '"';
		$i++;
	} 
	else 
	{ 
		$jscript .= ', "' . $results->tablename . '"';		
		$i++;
	}
}
$jscript .= ']';

	//javascript to validate form
	echo '
	<script type="text/javascript">
		var deleteTableContent = function() {
			var answer = confirm("Are you really sure that you want to delete all of the content of this table? This is not reversible!")
			if (answer) {
				return true;
			}
			else{
				return false;
			}
		};
		
		
		function deleteTable() {
			var answer = confirm("Are you sure that you want to delete this table? This is not reversible!")
			if (answer) {
				return true;
			}
			else{
				return false;
			}
		}

		function oc(a)
		{
		  var o = {};
		  for(var i=0;i<a.length;i++)
		  {
			o[a[i]]="";
		  }
		  return o;
		}
		
		function checkForm() {
			var rows = document.getElementById("numrows");
			var cols = document.getElementById("numcols");
			var name = document.getElementById("tablename");
			if ( (rows.value == "") || (cols.value == "") || (name.value == "") ) {
				alert("You must enter tablename, number of rows and columns");
				return false;
			} 
			if (name.value in oc(' . $jscript . ')) {
				alert("This name already exists, please choose a unique name");
				return false;
			} else {
				var numericExpression = /^[0-9]+$/;
				if ( (rows.value.match(numericExpression)) && (cols.value.match(numericExpression)) ) {
					return true;
				} else {
					alert("Number of rows and columns must be numbers!");
					return false;
				}
			}
		}
	</script>
	';
		
		echo '
		<div class="wrap">
			<div id="icon-tools" class="icon32">
				<br>
			</div>
			
		<h2>Websimon Tables</h2>';	
echo '
<form method="post" action="' . $_SERVER['REQUEST_URI'] . '" onsubmit="return checkForm()">';
//nonce
if ( function_exists('wp_nonce_field') ) {
	wp_nonce_field('new_table', 'nonce_all_tables');
}
echo '
	<div class="metabox-holder">
		<div class="postbox open" style="width:100%;">
			<div class="handlediv" title="Click to toggle"><br /></div>
			<h3 class="hndle"><span>Choose file</span></h3>
			<div class="inside">
				<p>
					<a href="?page=websimon_tables&action=ws_import_table" class="button-secondary">Import table via .csv file</a> (in Beta)
				</p>
				<p>
					Table Name:<input type="text" id="tablename" name="table_name" class="reg-text-ws" style="width:300px;" />
				</p>
				<p>
					Number of Rows:<input type="text" id="numrows" name="num_rows" style="width: 80px;"/>
				</p>
				<p>
					Number of Columns:<input type="text" id="numcols" name="num_cols" style="width: 80px;"/>
				</p>
				<p class="submit">
					<input type="submit" value="Add Table" class="button-secondary" style="width: 100px;" />
					<input type="hidden" name="create_hidden" />
				</p>
			
			</div>
		</div>
	</div>
</form>
';
echo '<h3 class="admin-above-wide-table">All Tables</h3>	
		<table class="widefat">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Rows &#215;	 Cols</th>
					<th>Shortcode</th>
					<th>PHP code</th>
					<th width="400px">Edit</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Rows &#215;	 Cols</th>
					<th>Shortcode</th>
					<th>PHP code</th>
					<th width="400px">Edit</th>
				</tr>
			</tfoot>
			<tbody>';
	
	$odd_row_counter = 1; 
	$i = 1;
	$ii = 100000;
	echo '<script type="text/javascript">
			function SelectAll(id)
			{
				document.getElementById(id).focus();
				document.getElementById(id).select();
			}
			</script>';
	
	foreach ($result as $results) {
		
		if (isset($_GET['renamed']) && $_GET['renamed'] == $results->id) { 
			$highlighter = 'style="background: #FFFFDB"'; 
			$highlight_txt = '<br /><i>Renamed</i>'; 
		} else { 
			$highlighter = ''; 
			$highlight_txt = ''; 
		}
	
		if ($odd_row_counter%2) { echo '<tr class="alternate" ' . $highlighter . ' colspan="5"> '; } else { echo '<tr  ' . $highlighter . ' colspan="5">'; } 
		$odd_row_counter++;
		
		echo '<td>' . $results->id . '</td>';
		echo '<td>' . $results->tablename . ' | <a href="?page=websimon_tables&action=rename_table&id=' . $results->id . '">Rename table</a>' . $highlight_txt . '</td>';
		echo '<td>' . $results->rows . '&#215;' . $results->cols . '</td>';
		echo '<td><input type="text" id="' . $i . '" onClick="SelectAll(' . $i . ');" value="' . htmlentities($results->shortcode) . '" style="width:170px;" /></td>';
		$short = htmlentities("<?php echo do_shortcode('" . $results->shortcode . "'); ?>");
		echo '<td><input type="text" id="' . $ii . '" onClick="SelectAll(' . $ii . ');" value="' . $short . '" style="width:270px;" /></td>';
		echo '
		<td><a href="?page=websimon_tables&action=edit_table&id=' . $results->id . '">Edit table Content</a> | 
		<a href="?page=websimon_tables&action=edit_style&id=' . $results->id . '">Edit Structure and Style</a> |';
		$nonce= wp_create_nonce('duplicate-table');
		echo '
		<a href="?page=websimon_tables&action=duplicate_table&duplicate_id=' . $results->id . '&_wpnonce=' . $nonce . '">Copy this table</a> | 
		';
		$nonce= wp_create_nonce('delete-table');
		echo '
		<a onclick="return deleteTable()" href="?page=websimon_tables&action=delete_table&delete_id=' . $results->id . '&_wpnonce=' . $nonce . '">Delete</a> |
		';
		$nonce= wp_create_nonce('delete-table-content');
		echo '
		<a onclick="return deleteTableContent()" href="?page=websimon_tables&action=delete_table_content&delete_id=' . $results->id . '&_wpnonce=' . $nonce . '">Delete Content</a> |
		';
		$nonce= wp_create_nonce('export-table-content');
		echo '
		<a href="?page=websimon_tables&action=export_table_content&export_id=' . $results->id . '&exports_name=' . $results->tablename . '&_wpnonce=' . $nonce . '">Export Content (.csv)</a></td>
		';
		echo '</td></tr>';
		$i++;
		$ii--;
	}	

echo '</tbody></table>
<p>
Plugin version: ' . get_site_option('websimon_tables_version') . '
</p>
</div>';
?>