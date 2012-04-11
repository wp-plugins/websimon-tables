<?php
/*
Displays the form that controls basic settings
*/
$this_id = $_GET['id']; //this table id
$table_name = $wpdb->prefix . "websimon_tables";
$result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$this_id'");

//get database informatione for this table
foreach ($result as $results) {
	$numrow = $results->rows;
	$numcol = $results->cols;
	$style = $results->style;
	$name = $results->tablename;
	$shortcode = $results->shortcode;
	$headlines = $results->headlines;
	$content = $results->content;
	$design = $results->design;
}

//Handle the information from the database
$design_elements = explode(';', $design);
$text_align = $design_elements[0];
if ($design_elements[1] == 'on') { $footer_on = 'checked'; };
if ($design_elements[2] == 'on') { $table_h_borders_on = 'checked'; };
if ($design_elements[3] == 'on') { $table_v_borders_on = 'checked'; };
if ($design_elements[4] == 'on') { $shadow_effect_on = 'checked'; };
if ($design_elements[5] == 'on') { $hover_effect_on = 'checked'; };
$font_size_h = $design_elements[6];
$font_size_b = $design_elements[7];
$colwidths = explode(':' , $design_elements[8]);
$fixed_width = $design_elements[9];
$cell_padding = $design_elements[10];
$line_height_b = $design_elements[11];
$line_height_h = $design_elements[12];
$table_text_align_h = $design_elements[13];
$v_text_align = $design_elements[14];
if ($design_elements[15] == 'on') { $header_on = 'checked'; };

//Javascript to validate form correctly
echo '
<script type="text/javascript">
function checkNumbers() {
	var width = document.getElementById("fixed_width");
	var cell = document.getElementById("cell_padding");
	var rows = document.getElementById("num_rows");
	var cols = document.getElementById("num_cols");
	var numericExpression = /^[0-9]+$/;
	
	
	if ( 
		((width.value.match(numericExpression)) || (width.value == "") ) &&
		((cell.value.match(numericExpression)) || (cell.value == "")) &&
		((rows.value.match(numericExpression)) || (rows.value == "")) &&
		((cols.value.match(numericExpression)) || (cols.value == ""))
		) 
	{
		return true;
	} 
	else 
	{
		alert("Please control that Fixed table width, Custom cell padding, Number of Rows and Number of Columns only contains numbers!");
		return false;
	}
}
</script>
';	/*
		(rows.value.match(numericExpression) || rows.value == "") &&
		(cols.value.match(numericExpression) || cols.value == "") &&
*/
//Add Headlines and navigation 
echo '
	<div class="wrap">
		<div id="icon-tools" class="icon32">
			<br />
		</div>
	
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab" href="' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables">All Tables</a>
			<a class="nav-tab" href="' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&action=edit_table&id=' . $this_id . '">Edit Table Content</a>
			<a class="nav-tab nav-tab-active" href="' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&action=edit_style&id=' . $this_id . '">Edit Table Structure & Style</a>
		</h2>
	
		<h2>Style for ' . $name . '</h2>
		
		<h3 class="nav-tab-wrapper">
			<a class="nav-tab nav-tab-active" href="' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&action=edit_style&id=' . $this_id . '&settings=basic">Basic Settings</a>
			<a class="nav-tab" href="' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&action=edit_style&id=' . $this_id . '&settings=advanced">Custom skin colors</a>
		</h3>
		';
		
		//javascript to highlight shortcode
		echo '
		<script type="text/javascript">
			function SelectAll(id)
				{
				document.getElementById(id).focus();
				document.getElementById(id).select();
				}
		</script>';
		//display shortcode
		echo '
		Shortcode: <input type="text" id="1" onClick="SelectAll(1);" value="' . htmlentities($results->shortcode) . '" style="width:270px;" />
		<div id="single-table-wrapper">';
	
		//display form that edits basic settings
		echo '
			<form method="post" action="' . $_SERVER['REQUEST_URI'] . '" onsubmit="return checkNumbers()">';
			
			//nonce security check
			wp_nonce_field('basic-settings', 'nonce_basic_settings');
			
			echo '
				<div id="admin-panel-max-600"> 
					<div id="poststuff">
						<div id="namediv" class="stuffbox">
							<h3>Table settings</h3>
							<div class="inside">
							<script type="text/javascript">
								function explan_shadow() {
									alert("Adds a shadow around the table. No support in IE8 and lower");
								}
							</script>
							</p>
								<p class="admin-float-right">
								<strong>More styles and effects</strong><br />
								<input name="table_footer" type="checkbox" class="admin-checkbox" ' . $footer_on . ' style="width: 15%;"/>Show table footer<br />
								<input name="table_h_borders" type="checkbox" class="admin-checkbox" ' . $table_h_borders_on . ' style="width: 15%;"/>Show horizontal borders<br />
								<input name="table_v_borders" type="checkbox" class="admin-checkbox" ' . $table_v_borders_on . ' style="width: 15%;"/>Show vertical borders<br />
								<input name="shadow_effect" type="checkbox" class="admin-checkbox" ' . $shadow_effect_on . ' style="width: 15%;"/>CSS3 Shadow effect 
								<img style="cursor:pointer;" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/websimon-tables/images/questionmark.png" onClick="explan_shadow()" title="Adds a shadow around the table. No support in IE8 and lower"><br />
								<input name="hover_effect" type="checkbox" class="admin-checkbox" ' . $hover_effect_on . ' style="width: 15%;"/>Hover effect<br />
								<input name="table_header" type="checkbox" class="admin-checkbox" ' . $header_on . ' style="width: 15%;"/>Remove Header<br />
							</p>
								<p>
								Skin:
								<select name="table_style" class="admin-select-style">
									<option '; if ($style == '') { echo 'selected="selected"'; } echo ' value="0">Choose your table style..</option>
									<option '; if ($style == 'minimalistwhite') { echo 'selected="selected"'; } echo ' value="minimalistwhite">Minimilist White</option>
									<option '; if ($style == 'blackwhite') { echo 'selected="selected"'; } echo ' value="blackwhite">Black and White</option>
									<option '; if ($style == 'lightblue') { echo 'selected="selected"'; } echo ' value="lightblue">Light blue</option>
									<option '; if ($style == 'blue') { echo 'selected="selected"'; } echo ' value="blue">Blue</option>
									<option '; if ($style == 'darkblue') { echo 'selected="selected"'; } echo ' value="darkblue">Dark Blue</option>
									<option '; if ($style == 'lightred') { echo 'selected="selected"'; } echo ' value="lightred">Light red</option>
									<option '; if ($style == 'darkred') { echo 'selected="selected"'; } echo ' value="darkred">Dark red and White</option>
									<option '; if ($style == 'coffee') { echo 'selected="selected"'; } echo ' value="coffee">Coffee</option>
									<option '; if ($style == 'bluenewspaper') { echo 'selected="selected"'; } echo ' value="bluenewspaper">Financial</option>
									<option '; if ($style == 'green') { echo 'selected="selected"'; } echo ' value="green">Green and warm</option>
									<option '; if ($style == 'custom') { echo 'selected="selected"'; } echo ' value="custom">Custom Skin (see custom skin colors)</option>
								</select>
								</p>
								<p>
								Font size Table Header:
								<select name="font_size_h" class="admin-select-style">
									<option '; if ($font_size_h == '0.2') { echo 'selected="selected"'; } echo ' value="0.2">0.2em</option>
									<option '; if ($font_size_h == '0.4') { echo 'selected="selected"'; } echo ' value="0.4">0.4em</option>
									<option '; if ($font_size_h == '0.6') { echo 'selected="selected"'; } echo ' value="0.6">0.6em</option>
									<option '; if ($font_size_h == '0.7') { echo 'selected="selected"'; } echo ' value="0.7">0.7em</option>
									<option '; if ($font_size_h == '0.8') { echo 'selected="selected"'; } echo ' value="0.8">0.8em</option>
									<option '; if ($font_size_h == '0.85') { echo 'selected="selected"'; } echo ' value="0.85">0.85em</option>
									<option '; if ($font_size_h == '0.9') { echo 'selected="selected"'; } echo ' value="0.9">0.9em</option>
									<option '; if ($font_size_h == '0.95') { echo 'selected="selected"'; } echo ' value="0.95">0.95em</option>
									<option '; if ($font_size_h == '1.0') { echo 'selected="selected"'; } echo ' value="1.0">1.0em</option>
									<option '; if ($font_size_h == '1.1') { echo 'selected="selected"'; } echo ' value="1.1">1.1em</option>
									<option '; if ($font_size_h == '1.2') { echo 'selected="selected"'; } echo ' value="1.2">1.2em</option>
									<option '; if ($font_size_h == '1.3') { echo 'selected="selected"'; } echo ' value="1.3">1.3em</option>
									<option '; if ($font_size_h == '1.4') { echo 'selected="selected"'; } echo ' value="1.4">1.4em</option>
									<option '; if ($font_size_h == '1.5') { echo 'selected="selected"'; } echo ' value="1.5">1.5em</option>
									<option '; if ($font_size_h == '1.6') { echo 'selected="selected"'; } echo ' value="1.6">1.6em</option>
									<option '; if ($font_size_h == '1.7') { echo 'selected="selected"'; } echo ' value="1.7">1.7em</option>
									<option '; if ($font_size_h == '1.8') { echo 'selected="selected"'; } echo ' value="1.8">1.8em</option>
									<option '; if ($font_size_h == '1.9') { echo 'selected="selected"'; } echo ' value="1.9">1.9em</option>
									<option '; if ($font_size_h == '2.0') { echo 'selected="selected"'; } echo ' value="2.0">2.0em</option>
									<option '; if ($font_size_h == '2.5') { echo 'selected="selected"'; } echo ' value="2.5">2.5em</option>
									<option '; if ($font_size_h == '3.0') { echo 'selected="selected"'; } echo ' value="3.0">3.0em</option>
									<option '; if ($font_size_h == '4.0') { echo 'selected="selected"'; } echo ' value="4.0">4.0em</option>
								</select>
								</p>
								<p>
								Line height Headlines:
								<select name="line_height_h" class="admin-select-style">
									<option '; if ($line_height_h == '0.2') { echo 'selected="selected"'; } echo ' value="0.2">0.2em</option>
									<option '; if ($line_height_h == '0.4') { echo 'selected="selected"'; } echo ' value="0.4">0.4em</option>
									<option '; if ($line_height_h == '0.6') { echo 'selected="selected"'; } echo ' value="0.6">0.6em</option>
									<option '; if ($line_height_h == '0.7') { echo 'selected="selected"'; } echo ' value="0.7">0.7em</option>
									<option '; if ($line_height_h == '0.8') { echo 'selected="selected"'; } echo ' value="0.8">0.8em</option>
									<option '; if ($line_height_h == '0.85') { echo 'selected="selected"'; } echo ' value="0.85">0.85em</option>
									<option '; if ($line_height_h == '0.9') { echo 'selected="selected"'; } echo ' value="0.9">0.9em</option>
									<option '; if ($line_height_h == '0.95') { echo 'selected="selected"'; } echo ' value="0.95">0.95em</option>
									<option '; if ($line_height_h == '1.0') { echo 'selected="selected"'; } echo ' value="1.0">1.0em</option>
									<option '; if ($line_height_h == '1.1') { echo 'selected="selected"'; } echo ' value="1.1">1.1em</option>
									<option '; if ($line_height_h == '1.2') { echo 'selected="selected"'; } echo ' value="1.2">1.2em</option>
									<option '; if ($line_height_h == '1.3') { echo 'selected="selected"'; } echo ' value="1.3">1.3em</option>
									<option '; if ($line_height_h == '1.4') { echo 'selected="selected"'; } echo ' value="1.4">1.4em</option>
									<option '; if ($line_height_h == '1.5') { echo 'selected="selected"'; } echo ' value="1.5">1.5em</option>
									<option '; if ($line_height_h == '1.6') { echo 'selected="selected"'; } echo ' value="1.6">1.6em</option>
									<option '; if ($line_height_h == '1.7') { echo 'selected="selected"'; } echo ' value="1.7">1.7em</option>
									<option '; if ($line_height_h == '1.8') { echo 'selected="selected"'; } echo ' value="1.8">1.8em</option>
									<option '; if ($line_height_h == '1.9') { echo 'selected="selected"'; } echo ' value="1.9">1.9em</option>
									<option '; if ($line_height_h == '2.0') { echo 'selected="selected"'; } echo ' value="2.0">2.0em</option>
									<option '; if ($line_height_h == '2.5') { echo 'selected="selected"'; } echo ' value="2.5">2.5em</option>
									<option '; if ($line_height_h == '3.0') { echo 'selected="selected"'; } echo ' value="3.0">3.0em</option>
									<option '; if ($line_height_h == '4.0') { echo 'selected="selected"'; } echo ' value="4.0">4.0em</option>
								</select>
								</p>
								<p>
								Text Align Headlines: 
								<select name="table_text_align_h" class="admin-select-style">
									<option '; if ($table_text_align_h == '') { echo 'selected="selected"'; } echo ' value="0">Text Align..</option>
									<option '; if ($table_text_align_h == 'left') { echo 'selected="selected"'; } echo ' value="left">Left</option>
									<option '; if ($table_text_align_h == 'center') { echo 'selected="selected"'; } echo ' value="center">Center</option>
									<option '; if ($table_text_align_h == 'right') { echo 'selected="selected"'; } echo ' value="right">Right</option>
								</select>
								</p>
								<p>
								Font size Table Body:
								<select name="font_size_b" class="admin-select-style">
									<option '; if ($font_size_b == '0.2') { echo 'selected="selected"'; } echo ' value="0.2">0.2em</option>
									<option '; if ($font_size_b == '0.4') { echo 'selected="selected"'; } echo ' value="0.4">0.4em</option>
									<option '; if ($font_size_b == '0.6') { echo 'selected="selected"'; } echo ' value="0.6">0.6em</option>
									<option '; if ($font_size_b == '0.7') { echo 'selected="selected"'; } echo ' value="0.7">0.7em</option>
									<option '; if ($font_size_b == '0.8') { echo 'selected="selected"'; } echo ' value="0.8">0.8em</option>
									<option '; if ($font_size_b == '0.85') { echo 'selected="selected"'; } echo ' value="0.85">0.85em</option>
									<option '; if ($font_size_b == '0.9') { echo 'selected="selected"'; } echo ' value="0.9">0.9em</option>
									<option '; if ($font_size_b == '0.95') { echo 'selected="selected"'; } echo ' value="0.95">0.95em</option>
									<option '; if ($font_size_b == '1.0') { echo 'selected="selected"'; } echo ' value="1.0">1.0em</option>
									<option '; if ($font_size_b == '1.1') { echo 'selected="selected"'; } echo ' value="1.1">1.1em</option>
									<option '; if ($font_size_b == '1.2') { echo 'selected="selected"'; } echo ' value="1.2">1.2em</option>
									<option '; if ($font_size_b == '1.3') { echo 'selected="selected"'; } echo ' value="1.3">1.3em</option>
									<option '; if ($font_size_b == '1.4') { echo 'selected="selected"'; } echo ' value="1.4">1.4em</option>
									<option '; if ($font_size_b == '1.5') { echo 'selected="selected"'; } echo ' value="1.5">1.5em</option>
									<option '; if ($font_size_b == '1.6') { echo 'selected="selected"'; } echo ' value="1.6">1.6em</option>
									<option '; if ($font_size_b == '1.7') { echo 'selected="selected"'; } echo ' value="1.7">1.7em</option>
									<option '; if ($font_size_b == '1.8') { echo 'selected="selected"'; } echo ' value="1.8">1.8em</option>
									<option '; if ($font_size_b == '1.9') { echo 'selected="selected"'; } echo ' value="1.9">1.9em</option>
									<option '; if ($font_size_b == '2.0') { echo 'selected="selected"'; } echo ' value="2.0">2.0em</option>
									<option '; if ($font_size_b == '2.5') { echo 'selected="selected"'; } echo ' value="2.5">2.5em</option>
									<option '; if ($font_size_b == '3.0') { echo 'selected="selected"'; } echo ' value="3.0">3.0em</option>
									<option '; if ($font_size_b == '4.0') { echo 'selected="selected"'; } echo ' value="4.0">4.0em</option>
								</select>
								</p>
								<p>
								Line height body text:
								<select name="line_height_b" class="admin-select-style">
									<option '; if ($line_height_b == '0.2') { echo 'selected="selected"'; } echo ' value="0.2">0.2em</option>
									<option '; if ($line_height_b == '0.4') { echo 'selected="selected"'; } echo ' value="0.4">0.4em</option>
									<option '; if ($line_height_b == '0.6') { echo 'selected="selected"'; } echo ' value="0.6">0.6em</option>
									<option '; if ($line_height_b == '0.7') { echo 'selected="selected"'; } echo ' value="0.7">0.7em</option>
									<option '; if ($line_height_b == '0.8') { echo 'selected="selected"'; } echo ' value="0.8">0.8em</option>
									<option '; if ($line_height_b == '0.85') { echo 'selected="selected"'; } echo ' value="0.85">0.85em</option>
									<option '; if ($line_height_b == '0.9') { echo 'selected="selected"'; } echo ' value="0.9">0.9em</option>
									<option '; if ($line_height_b == '0.95') { echo 'selected="selected"'; } echo ' value="0.95">0.95em</option>
									<option '; if ($line_height_b == '1.0') { echo 'selected="selected"'; } echo ' value="1.0">1.0em</option>
									<option '; if ($line_height_b == '1.1') { echo 'selected="selected"'; } echo ' value="1.1">1.1em</option>
									<option '; if ($line_height_b == '1.2') { echo 'selected="selected"'; } echo ' value="1.2">1.2em</option>
									<option '; if ($line_height_b == '1.3') { echo 'selected="selected"'; } echo ' value="1.3">1.3em</option>
									<option '; if ($line_height_b == '1.4') { echo 'selected="selected"'; } echo ' value="1.4">1.4em</option>
									<option '; if ($line_height_b == '1.5') { echo 'selected="selected"'; } echo ' value="1.5">1.5em</option>
									<option '; if ($line_height_b == '1.6') { echo 'selected="selected"'; } echo ' value="1.6">1.6em</option>
									<option '; if ($line_height_b == '1.7') { echo 'selected="selected"'; } echo ' value="1.7">1.7em</option>
									<option '; if ($line_height_b == '1.8') { echo 'selected="selected"'; } echo ' value="1.8">1.8em</option>
									<option '; if ($line_height_b == '1.9') { echo 'selected="selected"'; } echo ' value="1.9">1.9em</option>
									<option '; if ($line_height_b == '2.0') { echo 'selected="selected"'; } echo ' value="2.0">2.0em</option>
									<option '; if ($line_height_b == '2.5') { echo 'selected="selected"'; } echo ' value="2.5">2.5em</option>
									<option '; if ($line_height_b == '3.0') { echo 'selected="selected"'; } echo ' value="3.0">3.0em</option>
									<option '; if ($line_height_b == '4.0') { echo 'selected="selected"'; } echo ' value="4.0">4.0em</option>
								</select>
								</p>
								
								<p>
								Horizontal text align body: 
								<select name="table_text_align" class="admin-select-style">
									<option '; if ($text_align == '') { echo 'selected="selected"'; } echo ' value="0">Text Align..</option>
									<option '; if ($text_align == 'left') { echo 'selected="selected"'; } echo ' value="left">Left</option>
									<option '; if ($text_align == 'center') { echo 'selected="selected"'; } echo ' value="center">Center</option>
									<option '; if ($text_align == 'right') { echo 'selected="selected"'; } echo ' value="right">Right</option>
								</select>
								</p>
								<p>
								Vertical text align body: 
								<select name="v_text_align" class="admin-select-style">
									<option '; if ($v_text_align == '') { echo 'selected="selected"'; } echo ' value="0">Text Align..</option>
									<option '; if ($v_text_align == 'top') { echo 'selected="selected"'; } echo ' value="top">top</option>
									<option '; if ($v_text_align == 'middle') { echo 'selected="selected"'; } echo ' value="middle">middle</option>
									<option '; if ($v_text_align == 'bottom') { echo 'selected="selected"'; } echo ' value="bottom">bottom</option>
								</select>
								</p>
															<p>
									Fixed table width:<input type="text" name="fixed_width" id="fixed_width" style="width: 80px;" value="' . $fixed_width . '"/>px (else 100% width)
								</p>
								<p>
									Custom cell padding:<input type="text" id="cell_padding" name="cell_padding" style="width: 50px;" value="' . $cell_padding . '"/>px
								</p>
								<p>
									Number of Rows:<input type="text" id="num_rows" name="num_rows" style="width: 80px;" value="' . $numrow . '"/>
								</p>
								<p>
									Number of Columns:<input type="text" id="num_cols" name="num_cols" style="width: 80px;" value="' . $numcol . '"/>
								</p>
								<p>
								Column width:<br />';	
									$i = 1;
									$custom_width = (int) (100/$numcol);
									while ($i <= $numcol){ 
										
										echo 'Col ' . $i . ':<select name="column' . $i . '" class="admin-select-style">
											<option '; if ($colwidths[$i-1] == '10%') { echo 'selected="selected"'; } echo ' value="10%">10%</option>
											<option '; if ($colwidths[$i-1] == '15%') { echo 'selected="selected"'; } echo ' value="15%">15%</option>
											<option '; if ($colwidths[$i-1] == '20%') { echo 'selected="selected"'; } echo ' value="20%">20%</option>
											<option '; if ($colwidths[$i-1] == '25%') { echo 'selected="selected"'; } echo ' value="25%">25%</option>
											<option '; if ($colwidths[$i-1] == '30%') { echo 'selected="selected"'; } echo ' value="30%">30%</option>
											<option '; if ($colwidths[$i-1] == '35%') { echo 'selected="selected"'; } echo ' value="35%">35%</option>
											<option '; if ($colwidths[$i-1] == '40%') { echo 'selected="selected"'; } echo ' value="40%">40%</option>
											<option '; if ($colwidths[$i-1] == '45%') { echo 'selected="selected"'; } echo ' value="45%">45%</option>
											<option '; if ($colwidths[$i-1] == '50%') { echo 'selected="selected"'; } echo ' value="50%">50%</option>
											<option '; if ($colwidths[$i-1] == '55%') { echo 'selected="selected"'; } echo ' value="55%">55%</option>
											<option '; if ($colwidths[$i-1] == '60%') { echo 'selected="selected"'; } echo ' value="60%">60%</option>
											<option '; if ($colwidths[$i-1] == '65%') { echo 'selected="selected"'; } echo ' value="65%">65%</option>
											<option '; if ($colwidths[$i-1] == '70%') { echo 'selected="selected"'; } echo ' value="70%">70%</option>
											<option '; if ($colwidths[$i-1] == '75%') { echo 'selected="selected"'; } echo ' value="75%">75%</option>
											<option '; if ($colwidths[$i-1] == '80%') { echo 'selected="selected"'; } echo ' value="80%">80%</option>
											<option '; if ($colwidths[$i-1] == '85%') { echo 'selected="selected"'; } echo ' value="85%">85%</option>
											<option '; if ($colwidths[$i-1] == '90%') { echo 'selected="selected"'; } echo ' value="90%">90%</option>
											<option '; if ($colwidths[$i-1] == '95%') { echo 'selected="selected"'; } echo ' value="95%">95%</option>
											<option '; if ($colwidths[$i-1] == '100%') { echo 'selected="selected"'; } echo ' value="100%">100%</option>
										</select>';
										$i++;
									}
								
								echo '</p>
							</div>
						</div>
					</div>
						<p class="submit">
							<input type="submit" value="Save Table Settings" class="button-secondary" />
							<input type="hidden" name="edit_hidden_settings"/>
							<input type="hidden" name="numcol" value="' . $numcol . '"/>
							<input type="hidden" name="edit_hidden_settings_id" value="' . $this_id . '"/>
						</p>
					</div>
				
		</div>
</div></form>

';
echo '<p>preview with content width 600px<br />
(May look a bit different on your site)</p>';
echo 
'<div style="max-width:600px;">' . 
do_shortcode($shortcode)
. '</div></p>
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