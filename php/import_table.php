<?php

//create javascript array with table names to prevent tables from having the same name
$table_name = $wpdb->prefix . "websimon_tables";
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
			var file = document.getElementById("fileurl");
			if ( name.value === "" ) {
				alert("You must enter tablename");
				return false;
			} else if (file.value === "") {
				alert("You must enter file url");
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
<h2>Import Table</h2>';

if (isset($_GET['error']) && $_GET['error'] == '0') 
{
	echo '
	<div id="websimon-tables-message">
		Could not read the file, please make sure you have entered a valid file url.
	</div>';
}	
	
echo '
<div class="metabox-holder">
	<div class="postbox open" style="width:100%;">
		<div class="handlediv" title="Click to toggle"><br /></div>
		<h3 class="hndle"><span>Choose file</span>
		</h3>
		<div class="inside">
			<form method="post" action="" onSubmit="return checkForm()">';
			//nonce
			if ( function_exists('wp_nonce_field') ) {
				wp_nonce_field('import_table', 'nonce_import_table');
			}
			echo '
			
			<p>
				<a href="?page=websimon_tables" class="button-secondary"><- Back to all tables</a>
			</p>
			<h4>Instructions</h4>
			<p>
				Please note that this feature is in beta, please use with caution. 
			</p>
			<ol>
				<li>First upload your .csv file via your <a target="_blank" href="' . get_bloginfo( 'url' ) . '/wp-admin/media-new.php">wordpress media uploader</a>.</li>
				<li>Then paste the file url into "File url:" input field below.</li>
				<li>Choose an unique table name.</li>
				<li>If first row is headlines, check the option for that</li>
				<li>Click import.</li>
			</ol>
						
			<p>
				<label for="fileurl">File url:</label>
				<input type="text" name="import_table_file" id="fileurl" style="min-width: 400px;" />
			</p>
			<p>
				<label for="fileurl">Table name:</label>
				<input type="text" name="import_table_name" id="tablename" style="min-width: 400px;" />
			</p>
			<p>
				<label for="firsthead">First row is headlines:</label>
				<input type="checkbox" name="import_table_first_row_head" id="firsthead" />
			</p>

			<p class="submit">
				<input type="submit" value="Import file into table" class="button-secondary" />
				<input type="hidden" name="import_table_hidden" />
			</p>
			</form>
		</div>
	</div>
';

echo '</div></div>';

?>