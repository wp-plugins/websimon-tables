<?php
/*
Displays the form that controls basic settings
*/
$this_id = $_GET['id']; //this table id
$table_name = $wpdb->prefix . "websimon_tables";
$result = $wpdb->get_results("SELECT tablename FROM $table_name WHERE id='$this_id'");

//get database informatione for this table
foreach ($result as $results) {
	$name = $results->tablename;
}
//create javascript array with table names to prevent tables from having the same name
$result = $wpdb->get_results("SELECT tablename FROM $table_name");
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
//Javascript to validate form correctly
echo '
<script type="text/javascript">
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
			var name = document.getElementById("tablename");
			if ( name.value === "" ) {
				alert("You must enter tablename");
				return false;
			} else if (name.value in oc(' . $jscript . ')) {
				alert("This name already exists, please choose a unique name");
				return false;
			} else {
				return true;
			}
		}
</script>
';	
echo '
<div class="wrap">
<h2>Rename ' . $name . '</h2>
<div class="metabox-holder">
	<div class="postbox ópen" style="width:100%;">
		<div class="handlediv" title="Click to toggle"><br /></div>
		<h3 class="hndle"><span>Rename</span>
		</h3>
		<div class="inside">
			<form method="post" action="" onSubmit="return checkForm()">';
			//nonce
			if ( function_exists('wp_nonce_field') ) {
				wp_nonce_field('rename_table', 'nonce_rename_table');
			}
			echo '
			<label for="tablename">New Name:</label>
			<input type="text" name="rename_table_name" id="tablename" value="' . $name . '" style="min-width: 300px;" />
			<p class="submit">
				<input type="submit" value="Rename" class="button-secondary" />
				<input type="hidden" name="rename_table_hidden" />
				<input type="hidden" name="rename_table_id" value="' . $this_id . '" />
			</p>
			</form>
		</div>
	</div>
';

echo '</div></div>';

?>