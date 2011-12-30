<?php
	$this_id = $_GET['id'];
	$table_name = $wpdb->prefix . "websimon_tables";
	$result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$this_id'");
	
	foreach ($result as $results) {
		$numrow = $results->rows;
		$numcol = $results->cols;
		$style = $results->style;
		$name = $results->tablename;
		$shortcode = $results->shortcode;
		$headlines = $results->headlines;
		$content = $results->content;
		$adv = $results->advanced;
	}
	
	$advanced = explode(';', $adv);
	$custom_settings = $advanced[0];
	if ($advanced[0] == 'on') { $active_custom = 'checked'; };
	$fixed_width = $advanced[1];
	$cell_padding = $advanced[2];
	$startcol = $advanced[3];
	$endcol = $advanced[4];
	$startcolf = $advanced[5];
	$endcolf = $advanced[6];
	$row_color = $advanced[7];
	$alt_row_color = $advanced[8];
	$h_font_color = $advanced[9];
	$f_font_color = $advanced[10];
	$c_font_color = $advanced[11];
	$v_border_color = $advanced[12];
	$h_border_color = $advanced[13];
	
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
		<a class="nav-tab" href="' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&action=edit_style&id=' . $this_id . '&settings=basic">Basic Settings</a>
		<a class="nav-tab nav-tab-active" href="' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&action=edit_style&id=' . $this_id . '&settings=advanced">Custom skin colors</a>
	</h3>
		<script type="text/javascript">
		function SelectAll(id)
			{
			document.getElementById(id).focus();
			document.getElementById(id).select();
			}
	</script>
	
	Shortcode: <input type="text" id="1" onClick="SelectAll(1);" value="' . htmlentities($results->shortcode) . '" style="width:270px;" />
	<div id="single-table-wrapper">
		<form method="post" action="' . $_SERVER['REQUEST_URI'] . '">';
		//nonce security check
		wp_nonce_field('advanced-settings', 'nonce_advanced_settings');
				
		echo '
			<div id="admin-panel-max-800"> 
				<div id="poststuff">
					<div id="namediv" class="stuffbox">
						<h3>Table settings</h3>
						<div class="inside">';
						if ($style != 'custom') {
							echo '
							<p class="admin-activate-advanced">
								Important: You must activate custom skin first in <a href="' . get_bloginfo('wpurl') . '/wp-admin/tools.php?page=websimon_tables&action=edit_style&id=' . $this_id . '&settings=basic">basic settings</a>
							</p>'; 
						} else {
						echo '
						<div id="admin-left">
							
							<p>
								<script type="text/javascript" src="' . get_option('siteurl') . '/wp-content/plugins/websimon_tables/js/jscolor/jscolor.js"></script>
								<h4>Gradient Colors header:</h4>
								Start color: <input name="start_color_h" class="color" id="admin-colorpicker" value="' . $startcol . '" /><br />
								End color: <input name="end_color_h" class="color" id="admin-colorpicker" value="' . $endcol . '" /><br />
								<h4>Gradient Colors footer:</h4>
								Start color: <input name="start_color_f" class="color" id="admin-colorpicker" value="' . $startcolf . '" /><br />
								End color: <input name="end_color_f" class="color" id="admin-colorpicker" value="' . $endcolf . '" /><br />
							</p>
						</div>
						
						<div id="admin-right">
							<p>
								<h4>Font colors:</h4>
								Header font color: <input name="h_font_color" class="color" id="admin-colorpicker" value="' . $h_font_color . '" />
								Content font color: <input name="c_font_color" class="color" id="admin-colorpicker" value="' . $c_font_color . '" />
								Footer font color: <input name="f_font_color" class="color" id="admin-colorpicker" value="' . $f_font_color . '" />
								<h4>Border colors:</h4>
								Vertical border color: <input name="v_border_color" class="color" id="admin-colorpicker" value="' . $v_border_color . '" /><br />
								Horizontal row color: <input name="h_border_color" class="color" id="admin-colorpicker" value="' . $h_border_color . '" />
							</p>

						</div>
						<div id="admin-right">
							<h4>Table content colors:</h4>
								Row background color: <input name="row_color" class="color" id="admin-colorpicker" value="' . $row_color . '" /><br />
								Alternative background row color: <input name="alt_row_color" class="color" id="admin-colorpicker" value="' . $alt_row_color . '" />
						</div>
						</div>
					</div>
				</div>
				</div>
					<p class="submit">
						<input type="submit" value="Save Table Settings" class="button-secondary" />
						<input type="hidden" name="edit_advanced_settings"/>
						<input type="hidden" name="edit_hidden_settings_id" value="' . $this_id . '"/>
					</p>

			
	</div>
</div></form>

';

echo '<p>preview (May look different on your site)';
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
}
?>