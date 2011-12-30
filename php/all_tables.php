<?php
$table_name = $wpdb->prefix . "websimon_tables";
$result = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");

//create javascript array with table names to prevent tables from having the same name
$jscript = '[';
$i = 0;
foreach ($result as $results) {
	
	if ($i == 0) {
		$jscript .= '"' . $results->tablename . '"';
		$i++;
	} else { 
		$jscript .= ', "' . $results->tablename . '"';		
		$i++;
	}
}
$jscript .= ']';

	//javascript to validate form
	echo '
	<script type="text/javascript">
		
		function deleteTable() {
			var answer = confirm("Are you sure that you want to delete this table?")
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

<form method="post" action="' . $_SERVER['REQUEST_URI'] . '" onsubmit="return checkForm()">';
//nonce
if ( function_exists('wp_nonce_field') ) {
	wp_nonce_field('new_table', 'nonce_all_tables');
}
echo '
	<div id="admin-panel-max-600"> 
		<div id="poststuff">
			<div id="namediv" class="stuffbox">
				<h3>Add a new Table</h3>
				<div class="inside">
				Table Name:<input type="text" id="tablename" name="table_name" class="reg-text-ws" />
					<p>
						Number of Rows:<input type="text" id="numrows" name="num_rows" style="width: 80px;" value="' . $numrow . '"/>
					</p>
					<p>
						Number of Columns:<input type="text" id="numcols" name="num_cols" style="width: 80px;" value="' . $numcol . '"/>
					</p>
				</div>
			</div>
		</div>
			<p class="submit">
				<input type="submit" value="Add Table" class="button-secondary" />
				<input type="hidden" name="create_hidden" />
			</p>
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

				if ($odd_row_counter%2) { echo '<tr class="alternate" colspan="5"> '; } else { echo '<tr colspan="5">'; } 
				$odd_row_counter++;
				
				echo '<td>' . $results->id . '</td>';
				echo '<td>' . $results->tablename . '</td>';
				echo '<td>' . $results->rows . '&#215;' . $results->cols . '</td>';
				echo '<td><input type="text" id="' . $i . '" onClick="SelectAll(' . $i . ');" value="' . htmlentities($results->shortcode) . '" style="width:270px;" /></td>';
				$short = htmlentities("<?php echo do_shortcode('" . $results->shortcode . "'); ?>");
				echo '<td><input type="text" id="' . $ii . '" onClick="SelectAll(' . $ii . ');" value="' . $short . '" style="width:270px;" /></td>';
				echo '
				<td><a href="?page=websimon_tables&action=edit_table&id=' . $results->id . '">Edit table Content</a> | 
				<a href="?page=websimon_tables&action=edit_style&id=' . $results->id . '">Edit Structure and Style</a> |';
				$nonce= wp_create_nonce('delete-table');
				echo '
				<a onclick="return deleteTable()" href="?page=websimon_tables&action=delete_table&delete_id=' . $results->id . '&_wpnonce=' . $nonce . '">Delete</a></td>
				';
				echo '</tr>';
				$i++;
				$ii--;
			}	
		
		echo '</tbody></table>
		</div>';
?>