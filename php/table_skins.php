<?php
$table_name = $wpdb->prefix . "websimon_tables";
$result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$table_id'");

foreach ($result as $results) {
$id = 't' . $results->id;
$style = $results->style;
$design = $results->design;
$numcol = $results->cols;
$adv = $results->advanced;
$advanced = explode(';', $adv);

//skin design
$design_elements = explode(';', $design);
$text_align = $design_elements[0];
$h_borders = $design_elements[2];
$v_borders = $design_elements[3];
$shadow_effect = $design_elements[4];
$hover_effect = $design_elements[5];
$font_size_h = $design_elements[6];
$font_size_b = $design_elements[7];
$colwidths = explode(':' , $design_elements[8]);
$fixed_width = $design_elements[9];
$cell_padding = $design_elements[10];
$line_height_b = $design_elements[11];
$line_height_h = $design_elements[12];
$table_text_align_h = $design_elements[13];
$v_text_align = $design_elements[14];

$css = ''; //return string

/*
*	Custom style
*/

if ($style == 'custom') {

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

$css .= '
table#' . $id . ' {
    border-collapse: collapse;
	border-width: 0px;
	border-style: outset;
	line-height: 2.0em;
    text-align: ' . $text_align . ';
    vertical-align: top;';
	if ($fixed_width != '' ) { $css .= 'width: ' . $fixed_width . 'px;'; } else { $css .= 'width: 100%;';} 
	if ($h_borders != '' ) { $css .= 'border-top: 1px solid #' . $h_border_color . ';'; }
	if ($v_borders != '' ) { $css .= 'border-right: 1px solid #' . $v_border_color . ';'; }
	if ($shadow_effect != '' ) { $css .= 'box-shadow: 0 0px 5px rgba(0, 0, 0, 0.4);'; } $css .= '
	
}
table#' . $id . ' thead tr {

}
table#' . $id . ' thead tr th.' . $id . ' {
color: #' . $h_font_color . ';
background: #' . $startcol . ';
background: -moz-linear-gradient(top,  #' . $startcol . ' 0%, #' . $endcol . ' 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#' . $startcol . '), color-stop(100%,#' . $endcol . ')); 
background: -webkit-linear-gradient(top,  #' . $startcol . ' 0%,#' . $endcol . ' 100%);
background: -o-linear-gradient(top,  #' . $startcol . ' 0%,#' . $endcol . ' 100%);
background: -ms-linear-gradient(top,  #' . $startcol . ' 0%,#' . $endcol . ' 100%);
background: linear-gradient(to bottom,  #' . $startcol . ' 0%,#' . $endcol . ' 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#' . $startcol . '", endColorstr="#' . $endcol . '",GradientType=0 );
font-size: ' . $font_size_h . 'em;
letter-spacing: 0;
line-height: ' . $line_height_h . ';
padding: 4px;
text-transform: none;
text-align: ' . $table_text_align_h . ';';
if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #' . $h_border_color . ';'; }
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #' . $v_border_color . ';'; } $css .= '
}

table#' . $id . ' thead tr th#' . $id . '.start {

}
table#' . $id . ' thead tr th#' . $id . '.end {

}
table#' . $id . ' tbody tr {
    background: none repeat scroll 0 0 #' . $row_color . ';
}
table#' . $id . ' tbody tr.table-alternate {
    background: none repeat scroll 0 0 #' . $alt_row_color . ';
}
table#' . $id . ' tbody tr td {
	color: #' . $c_font_color . ';
    padding: 5px;
	border-width: 0px;
	line-height: ' . $line_height_b . ';
	font-size: ' . $font_size_b . 'em;
	border-top: medium none;';
	if ($cell_padding != '' ) { $css .= 'padding: ' . $cell_padding . 'px;'; }	
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #' . $h_border_color . ';'; }
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #' . $v_border_color . ';'; } $css .= '
    text-align: ' . $text_align . ';
	vertical-align: ' . $v_text_align . ';' . '
}
';
$i = 1;
while ($i <= $numcol) {
	$css .= 'table#' . $id . ' tbody tr td#n' . $i . ' {
	width: ' . $colwidths[$i-1] . ';
	}';
	$i++;
}
$css .= '
table#' . $id . ' tbody tr:hover td {';
	if ($hover_effect != '' ) { $css .= 'background: none repeat scroll 0 0 #EEEEEE;'; } $css .= '
}
table#' . $id . ' tfoot tr {
}

table#' . $id . ' tfoot tr td {
color: #' . $f_font_color . ';
background: #' . $startcolf . ';
background: -moz-linear-gradient(top,  #' . $startcolf . ' 0%, #' . $endcolf . ' 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#' . $startcol . '), color-stop(100%,#' . $endcolf . ')); 
background: -webkit-linear-gradient(top,  #' . $startcolf . ' 0%,#' . $endcolf . ' 100%);
background: -o-linear-gradient(top,  #' . $startcolf . ' 0%,#' . $endcolf . ' 100%);
background: -ms-linear-gradient(top,  #' . $startcolf . ' 0%,#' . $endcolf . ' 100%);
background: linear-gradient(to bottom,  #' . $startcolf . ' 0%,#' . $endcolf . ' 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#' . $startcolf . '", endColorstr="#' . $endcolf . '",GradientType=0 );
padding: 4px;
border-width: 0px;
font-size: ' . $font_size_b . 'em;
border-top: medium none;
text-align: ' . $table_text_align_h . ';';
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #' . $v_border_color . ';'; } $css .= '
}
';
}


/*
*	Minimalist White skin
*/
if ($style == 'minimalistwhite') {
$css .= '

table#' . $id . ' {
border-collapse: collapse;	
border-width: 0px;
border-style: outset;
margin: 20px 0;
line-height: 2.0em;
text-align: ' . $text_align . ';
vertical-align: top;';
if ($fixed_width != '' ) { $css .= 'width: ' . $fixed_width . 'px;'; } else { $css .= 'width: 100%;';} 
if ($h_borders != '' ) { $css .= 'border-top: 1px solid #EEEEEE;'; }
if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #EEEEEE;'; }
if ($v_borders != '' ) { $css .= 'border-right: 1px solid #EEEEEE;'; }
if ($shadow_effect != '' ) { $css .= 'box-shadow: 0 2px 3px rgba(0, 0, 0, 0.4);'; } $css .= '
}
table#' . $id . ' thead tr {

}
table#' . $id . ' thead tr th.' . $id . ' {
color: #333333;
background: none repeat scroll 0 0 #FFFFFF;
font-size: ' . $font_size_h . 'em;
letter-spacing: 0;
line-height: ' . $line_height_h . ';
padding: 4px;
text-transform: none;
text-align: ' . $table_text_align_h . ';';
if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #EEEEEE;'; }
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #EEEEEE;'; } $css .= '
}
';

$css .= '
table#' . $id . ' thead tr th#' . $id . '.start {

}
table#' . $id . ' thead tr th#' . $id . '.end {

}
table#' . $id . ' tbody tr {
    background: none repeat scroll 0 0 #FFFFFF;
}
table#' . $id . ' tbody tr.table-alternate {
    background: none repeat scroll 0 0 #FDFDFD;
}
';
$i = 1;
while ($i <= $numcol) {
	$css .= 'table#' . $id . ' tbody tr td#n' . $i . ' {
	width: ' . $colwidths[$i-1] . ';
	}';
	$i++;
}
$css .= '

table#' . $id . ' tbody tr td {
    padding: 5px;
	border-width: 0px;
	font-size: ' . $font_size_b . 'em;
	border-top: medium none;';
	if ($cell_padding != '' ) { $css .= 'padding: ' . $cell_padding . 'px;'; }	
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #EEEEEE;'; }
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #EEEEEE;'; } $css .= '
    text-align: ' . $text_align . ';
	line-height: ' . $line_height_b . ';
	vertical-align: ' . $v_text_align . ';' . '
}';
if ($hover_effect != '' ) { $css .= '
	table#' . $id . ' tbody tr:hover td {
	background: none repeat scroll 0 0 #F2F2F2;
	color: #000000;'; } $css .= '
}
table#' . $id . ' tfoot tr {
}

table#' . $id . ' tfoot tr td {
    background: none repeat scroll 0 0 #FCFCFC;	
	padding: 4px;
	border-width: 0px;
	color: #7a7a7a;
	font-size: ' . $font_size_b . 'em;
	border-top: medium none;
    text-align: ' . $table_text_align_h . ';';
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #EEEEEE;'; } $css .= '
}
';
}

/*
*	Black and white skin
*/

if ($style == 'blackwhite') {
$css .= '

table#' . $id . ' {
    border-collapse: collapse;
	border-width: 0px;
	border-style: outset;
    margin: 20px 0;
	line-height: 1.2em;
    text-align: ' . $text_align . ';
    vertical-align: top;';
	if ($fixed_width != '' ) { $css .= 'width: ' . $fixed_width . 'px;'; } else { $css .= 'width: 100%;';} 
	if ($h_borders != '' ) { $css .= 'border-top: 1px solid #AFAFAF;'; }
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #AFAFAF;'; }
	if ($v_borders != '' ) { $css .= 'border-right: 1px solid #AFAFAF;'; }
	if ($shadow_effect != '' ) { $css .= 'box-shadow: 0 2px 3px rgba(0, 0, 0, 0.4);'; } $css .= '
	
}
table#' . $id . ' thead tr {

}
table#' . $id . ' thead tr th.' . $id . ' {
color: #EEEEEE;
background: #1D1D1D;
background: -moz-linear-gradient(top,  #1D1D1D 0%, #303030 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#1D1D1D), color-stop(100%,#303030)); 
background: -webkit-linear-gradient(top,  #1D1D1D 0%,#303030 100%);
background: -o-linear-gradient(top,  #1D1D1D 0%,#303030 100%);
background: -ms-linear-gradient(top,  #1D1D1D 0%,#303030 100%);
background: linear-gradient(to bottom,  #1D1D1D 0%,#303030 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#1D1D1D", endColorstr="#303030",GradientType=0 );
font-size: ' . $font_size_h . 'em;
letter-spacing: 0;
line-height: ' . $line_height_h . ';
text-align: ' . $table_text_align_h . ';
padding: 4px;
text-transform: none;';
if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #AFAFAF;'; }
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #AFAFAF;'; } $css .= '
}';

$css .= '
table#' . $id . ' thead tr th#' . $id . '.start {

}
table#' . $id . ' thead tr th#' . $id . '.end {

}
table#' . $id . ' tbody tr {
    background: none repeat scroll 0 0 #FFFFFF;
}
table#' . $id . ' tbody tr.table-alternate {
    background: none repeat scroll 0 0 #FDFDFD;
}

';
$i = 1;
while ($i <= $numcol) {
	$css .= 'table#' . $id . ' tbody tr td#n' . $i . ' {
	width: ' . $colwidths[$i-1] . ';
	}';
	$i++;
}
$css .= '

table#' . $id . ' tbody tr td {
    padding: 5px;
	border-width: 0px;
	font-size: ' . $font_size_b . 'em;
	border-top: medium none;';
	if ($cell_padding != '' ) { $css .= 'padding: ' . $cell_padding . 'px;'; }	
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #AFAFAF;'; }
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #AFAFAF;'; } $css .= '
    text-align: ' . $text_align . ';
	line-height: ' . $line_height_b . ';
	vertical-align: ' . $v_text_align . ';' . '
}';
if ($hover_effect != '' ) { $css .= '
	table#' . $id . ' tbody tr:hover td {
	background: none repeat scroll 0 0 #F2F2F2;
	color: #000000;'; } $css .= '
}
table#' . $id . ' tfoot tr {
}

table#' . $id . ' tfoot tr td {
background: #1D1D1D;
background: -moz-linear-gradient(top,  #1D1D1D 0%, #303030 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#1D1D1D), color-stop(100%,#303030)); 
background: -webkit-linear-gradient(top,  #1D1D1D 0%,#303030 100%);
background: -o-linear-gradient(top,  #1D1D1D 0%,#303030 100%);
background: -ms-linear-gradient(top,  #1D1D1D 0%,#303030 100%);
background: linear-gradient(to bottom,  #1D1D1D 0%,#303030 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#1D1D1D", endColorstr="#303030",GradientType=0 );
padding: 4px;
border-width: 0px;
font-size: ' . $font_size_b . 'em;
color: #CCCCCC;
border-top: medium none;
text-align: ' . $table_text_align_h . ';';
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #AFAFAF;'; } $css .= '
}
';
}


/*
Lightblue skin
*/
if ($style == 'lightblue') {
$css .= '

table#' . $id . ' {
    border-collapse: collapse;
	border-width: 0px;
	border-style: outset;
    margin: 20px 0;
	line-height: 2.0em;
    text-align: ' . $text_align . ';
    vertical-align: top;';
	if ($fixed_width != '' ) { $css .= 'width: ' . $fixed_width . 'px;'; } else { $css .= 'width: 100%;';} 
	if ($h_borders != '' ) { $css .= 'border-top: 1px solid #94DBFF;'; }
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #94DBFF;'; }
	if ($v_borders != '' ) { $css .= 'border-right: 1px solid #94DBFF;'; }
	if ($shadow_effect != '' ) { $css .= 'box-shadow: 0 2px 3px rgba(0, 0, 0, 0.4);'; } $css .= '
	
}
table#' . $id . ' thead tr {

}
table#' . $id . ' thead tr th.' . $id . ' {
    color: #333333;
	background: none repeat scroll 0 0 #D1F0FF;
    font-size: 1em;
    letter-spacing: 0;
    line-height: ' . $line_height_h . ';
	font-size: ' . $font_size_h . 'em;
    padding: 4px;
    text-transform: none;
    text-align: ' . $table_text_align_h . ';';
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #94DBFF;'; }
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #94DBFF;'; } $css .= '
}
';
$css .= '
table#' . $id . ' thead tr th#' . $id . '.start {

}
table#' . $id . ' thead tr th#' . $id . '.end {

}
table#' . $id . ' tbody tr {
    background: none repeat scroll 0 0 #F0FAFF;
}
table#' . $id . ' tbody tr.table-alternate {
    background: none repeat scroll 0 0 #FCFEFF;
}
table#' . $id . ' tbody tr td {
    padding: 5px;
	border-width: 0px;
	font-size: ' . $font_size_b . 'em;
	border-top: medium none;';
	if ($cell_padding != '' ) { $css .= 'padding: ' . $cell_padding . 'px;'; }	
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #94DBFF;'; }
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #94DBFF;'; } $css .= '
    text-align: ' . $text_align . ';
	line-height: ' . $line_height_b . ';
	vertical-align: ' . $v_text_align . ';' . '
}
';
$i = 1;
while ($i <= $numcol) {
	$css .= 'table#' . $id . ' tbody tr td#n' . $i . ' {
	width: ' . $colwidths[$i-1] . ';
	}';
	$i++;
}
$css .= '
table#' . $id . ' tbody tr:hover td {';
	if ($hover_effect != '' ) { $css .= 'background: none repeat scroll 0 0 #D1F0FF;'; } $css .= '
}
table#' . $id . ' tfoot tr {
}

table#' . $id . ' tfoot tr td {
    background: none repeat scroll 0 0 #D1F0FF;	
	padding: 4px;
	border-width: 0px;
	font-size: ' . $font_size_b . 'em;
	border-top: medium none;
    text-align: ' . $table_text_align_h . ';';
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #94DBFF;'; } $css .= '
}
';
}

/*
Kind of blue
*/
if ($style == 'blue') {
$css .= '

table#' . $id . ' {
    border-collapse: collapse;
	border-width: 0px;
	border-style: outset;
    margin: 20px 0;
	line-height: 2.0em;
    text-align: ' . $text_align . ';
    vertical-align: top;';
	if ($fixed_width != '' ) { $css .= 'width: ' . $fixed_width . 'px;'; } else { $css .= 'width: 100%;';} 
	if ($h_borders != '' ) { $css .= 'border-top: 1px solid #1D5791;'; }
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #1D5791;'; }
	if ($v_borders != '' ) { $css .= 'border-right: 1px solid #1D5791;'; }
	if ($shadow_effect != '' ) { $css .= 'box-shadow: 0 2px 3px rgba(0, 0, 0, 0.4);'; } $css .= '
	
}
table#' . $id . ' thead tr {

}
table#' . $id . ' thead tr th.' . $id . ' {
    color: #FFFFFF;
	background: none repeat scroll 0 0 #297CCF;
	font-size: ' . $font_size_h . 'em;
    letter-spacing: 0;
    line-height: ' . $line_height_h . ';
    padding: 4px;
    text-transform: none;
    text-align: ' . $table_text_align_h . ';';
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #1D5791;'; }
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #1D5791;'; } $css .= '
}

table#' . $id . ' thead tr th#' . $id . '.start {

}
table#' . $id . ' thead tr th#' . $id . '.end {

}
table#' . $id . ' tbody tr {
    background: none repeat scroll 0 0 #DFECF8;
}
table#' . $id . ' tbody tr.table-alternate {
    background: none repeat scroll 0 0 #BFD8F1;
}
';
$i = 1;
while ($i <= $numcol) {
	$css .= 'table#' . $id . ' tbody tr td#n' . $i . ' {
	width: ' . $colwidths[$i-1] . ';
	}';
	$i++;
}
$css .= '

table#' . $id . ' tbody tr td {
    padding: 5px;
	border-width: 0px;
	font-size: ' . $font_size_b . 'em;
	border-top: medium none;';
	if ($cell_padding != '' ) { $css .= 'padding: ' . $cell_padding . 'px;'; }	
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #1D5791;'; }
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #1D5791;'; } $css .= '
    text-align: ' . $text_align . ';
	line-height: ' . $line_height_b . ';
	vertical-align: ' . $v_text_align . ';' . '
}';
if ($hover_effect != '' ) { $css .= '
	table#' . $id . ' tbody tr:hover td {
	background: none repeat scroll 0 0 #2163A6;
	color: #FFFFFF;'; } $css .= '
}
table#' . $id . ' tfoot tr {
}

table#' . $id . ' tfoot tr td {
    background: none repeat scroll 0 0 #297CCF;	
	padding: 4px;
	border-width: 0px;
	color: #FFFFFF;
	border-top: medium none;
	font-size: ' . $font_size_b . 'em;
    text-align: ' . $table_text_align_h . ';';	
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #1D5791;'; } $css .= '
}
';
}

/*
Dark blue skin
*/

if ($style == 'darkblue') {
$css .= '

table#' . $id . ' {
    border-collapse: collapse;
	border-width: 0px;
	border-style: outset;
    margin: 20px 0;
	line-height: 2.0em;
    text-align: ' . $text_align . ';
    vertical-align: top;';
	if ($fixed_width != '' ) { $css .= 'width: ' . $fixed_width . 'px;'; } else { $css .= 'width: 100%;';} 
	if ($h_borders != '' ) { $css .= 'border-top: 1px solid #24476B;'; }
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #24476B;'; }
	if ($v_borders != '' ) { $css .= 'border-right: 1px solid #24476B;'; }
	if ($shadow_effect != '' ) { $css .= 'box-shadow: 0 2px 3px rgba(0, 0, 0, 0.4);'; } $css .= '
	
}
table#' . $id . ' thead tr {

}
table#' . $id . ' thead tr th.' . $id . ' {
color: #EEF0F3;
background: #24476B;
background: -moz-linear-gradient(top,  #24476B 0%, #506C89 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#24476B), color-stop(100%,#506C89)); 
background: -webkit-linear-gradient(top,  #24476B 0%,#506C89 100%);
background: -o-linear-gradient(top,  #24476B 0%,#506C89 100%);
background: -ms-linear-gradient(top,  #24476B 0%,#506C89 100%);
background: linear-gradient(to bottom,  #24476B 0%,#506C89 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#24476B", endColorstr="#506C89",GradientType=0 );
font-size: ' . $font_size_h . 'em;
letter-spacing: 0;
line-height: ' . $line_height_h . ';
padding: 4px;
text-transform: none;
text-align: ' . $table_text_align_h . ';';
if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #24476B;'; }
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #24476B;'; } $css .= '
}

table#' . $id . ' thead tr th#' . $id . '.start {

}
table#' . $id . ' thead tr th#' . $id . '.end {

}
table#' . $id . ' tbody tr {
    background: none repeat scroll 0 0 #3A597A;
}
table#' . $id . ' tbody tr.table-alternate {
    background: none repeat scroll 0 0 #627B95;
}
';
$i = 1;
while ($i <= $numcol) {
	$css .= 'table#' . $id . ' tbody tr td#n' . $i . ' {
	width: ' . $colwidths[$i-1] . ';
	}';
	$i++;
}
$css .= '
table#' . $id . ' tbody tr td {
    color: #EEF0F3;
	padding: 5px;
	border-width: 0px;
	font-size: ' . $font_size_b . 'em;
	border-top: medium none;';
	if ($cell_padding != '' ) { $css .= 'padding: ' . $cell_padding . 'px;'; }	
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #24476B;'; }
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #24476B;'; } $css .= '
    text-align: ' . $text_align . ';
	line-height: ' . $line_height_b . ';
	vertical-align: ' . $v_text_align . ';' . '
}';
if ($hover_effect != '' ) { $css .= '
	table#' . $id . ' tbody tr:hover td {
	background: none repeat scroll 0 0 #48627C;
	color: #EEF0F3;'; } $css .= '
}
table#' . $id . ' tfoot tr {
}

table#' . $id . ' tfoot tr td {
color: #EEF0F3;
background: #24476B;
background: -moz-linear-gradient(top,  #24476B 0%, #506C89 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#24476B), color-stop(100%,#506C89)); 
background: -webkit-linear-gradient(top,  #24476B 0%,#506C89 100%);
background: -o-linear-gradient(top,  #24476B 0%,#506C89 100%);
background: -ms-linear-gradient(top,  #24476B 0%,#506C89 100%);
background: linear-gradient(to bottom,  #24476B 0%,#506C89 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#24476B", endColorstr="#506C89",GradientType=0 );
padding: 4px;
font-size: ' . $font_size_b . 'em;
border-width: 0px;
border-top: medium none;
text-align: ' . $table_text_align_h . ';';
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #24476B;'; } $css .= '
}
';
}

/*
*	Lightred skin
*/
if ($style == 'lightred') {
$css .= '

table#' . $id . ' {
    border-collapse: collapse;
	border-width: 0px;
	border-style: outset;
    margin: 20px 0;
	line-height: 2.0em;
    text-align: ' . $text_align . ';
    vertical-align: top;';
	if ($fixed_width != '' ) { $css .= 'width: ' . $fixed_width . 'px;'; } else { $css .= 'width: 100%;';} 
	if ($h_borders != '' ) { $css .= 'border-top: 1px solid #FFE6E6;'; }
	if ($v_borders != '' ) { $css .= 'border-right: 1px solid #FFE6E6;'; }
	if ($shadow_effect != '' ) { $css .= 'box-shadow: 0 2px 3px rgba(0, 0, 0, 0.4);'; } $css .= '
	
}
table#' . $id . ' thead tr {

}
table#' . $id . ' thead tr th.' . $id . ' {
color: #333333;
background: #FFCCCC;
background: -moz-linear-gradient(top,  #FFCCCC 0%, #FFB2B2 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FFCCCC), color-stop(100%,#FFB2B2)); 
background: -webkit-linear-gradient(top,  #FFCCCC 0%,#FFB2B2 100%);
background: -o-linear-gradient(top,  #FFCCCC 0%,#FFB2B2 100%);
background: -ms-linear-gradient(top,  #FFCCCC 0%,#FFB2B2 100%);
background: linear-gradient(to bottom,  #FFCCCC 0%,#FFB2B2 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#FFCCCC", endColorstr="#FFB2B2",GradientType=0 );
font-size: ' . $font_size_h . 'em;
letter-spacing: 0;
line-height: ' . $line_height_h . ';
padding: 4px;
text-transform: none;
text-align: ' . $table_text_align_h . ';';
if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #FFE6E6;'; }
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #FFE6E6;'; } $css .= '
}

table#' . $id . ' thead tr th#' . $id . '.start {

}
table#' . $id . ' thead tr th#' . $id . '.end {

}
table#' . $id . ' tbody tr {
    background: none repeat scroll 0 0 #FFFFFF;
}
table#' . $id . ' tbody tr.table-alternate {
    background: none repeat scroll 0 0 #FFF0F0;
}
';
$i = 1;
while ($i <= $numcol) {
	$css .= 'table#' . $id . ' tbody tr td#n' . $i . ' {
	width: ' . $colwidths[$i-1] . ';
	}';
	$i++;
}
$css .= '
table#' . $id . ' tbody tr td {
    padding: 5px;
	border-width: 0px;
	font-size: ' . $font_size_b . 'em;
	border-top: medium none;';
	if ($cell_padding != '' ) { $css .= 'padding: ' . $cell_padding . 'px;'; }	
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #FFE6E6;'; }
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #FFE6E6;'; } $css .= '
    text-align: ' . $text_align . ';
	line-height: ' . $line_height_b . ';
	vertical-align: ' . $v_text_align . ';' . '
}
table#' . $id . ' tbody tr:hover td {';
	if ($hover_effect != '' ) { $css .= 'background: none repeat scroll 0 0 #FFDCDC;'; } $css .= '
}
table#' . $id . ' tfoot tr {
}

table#' . $id . ' tfoot tr td {
    background: none repeat scroll 0 0 #FFCCCC;	
	padding: 4px;
	border-width: 0px;
	border-top: medium none;
	font-size: ' . $font_size_b . 'em;
    text-align: ' . $table_text_align_h . ';';
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #FFE6E6;'; } $css .= '
}
';
}

/*
*	Darkred skin
*/
if ($style == 'darkred') {
$css .= '

table#' . $id . ' {
    border-collapse: collapse;
	border-width: 0px;
	border-style: outset;
    margin: 20px 0;
	line-height: 2.0em;
    text-align: ' . $text_align . ';
    vertical-align: top;';
	if ($fixed_width != '' ) { $css .= 'width: ' . $fixed_width . 'px;'; } else { $css .= 'width: 100%;';} 
	if ($h_borders != '' ) { $css .= 'border-top: 1px solid #CCCCCC;'; }
	if ($v_borders != '' ) { $css .= 'border-right: 1px solid #CCCCCC;'; }
	if ($shadow_effect != '' ) { $css .= 'box-shadow: 0 2px 3px rgba(0, 0, 0, 0.4);'; } $css .= '
	
}
table#' . $id . ' thead tr {

}
table#' . $id . ' thead tr th.' . $id . ' {
color: #EEEEEE;
background: #660000;
background: -moz-linear-gradient(top,  #660000 0%, #8F0000 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#660000), color-stop(100%,#8F0000)); 
background: -webkit-linear-gradient(top,  #660000 0%,#8F0000 100%);
background: -o-linear-gradient(top,  #660000 0%,#8F0000 100%);
background: -ms-linear-gradient(top,  #660000 0%,#8F0000 100%);
background: linear-gradient(to bottom,  #660000 0%,#8F0000 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#660000", endColorstr="#8F0000",GradientType=0 );
font-size: ' . $font_size_h . 'em;
letter-spacing: 0;
line-height: ' . $line_height_h . ';
padding: 4px;
text-transform: none;
text-align: ' . $table_text_align_h . ';';
if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #CCCCCC;'; }
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #CCCCCC;'; } $css .= '
}

table#' . $id . ' thead tr th#' . $id . '.start {

}
table#' . $id . ' thead tr th#' . $id . '.end {

}
table#' . $id . ' tbody tr {
    background: none repeat scroll 0 0 #FFFFFF;
}
table#' . $id . ' tbody tr.table-alternate {
    background: none repeat scroll 0 0 #FDFDFD;
}
';
$i = 1;
while ($i <= $numcol) {
	$css .= 'table#' . $id . ' tbody tr td#n' . $i . ' {
	width: ' . $colwidths[$i-1] . ';
	}';
	$i++;
}
$css .= '
table#' . $id . ' tbody tr td {
    padding: 5px;
	border-width: 0px;
	font-size: ' . $font_size_b . 'em;
	border-top: medium none;';
	if ($cell_padding != '' ) { $css .= 'padding: ' . $cell_padding . 'px;'; }	
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #CCCCCC;'; }
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #CCCCCC;'; } $css .= '
    text-align: ' . $text_align . ';
	line-height: ' . $line_height_b . ';
	vertical-align: ' . $v_text_align . ';' . '
}
table#' . $id . ' tbody tr:hover td {';
	if ($hover_effect != '' ) { $css .= 'background: none repeat scroll 0 0 #EEEEEE;'; } $css .= '
}
table#' . $id . ' tfoot tr {
}

table#' . $id . ' tfoot tr td {
color: #EEEEEE;	
background: #660000;
background: -moz-linear-gradient(top,  #660000 0%, #8F0000 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#660000), color-stop(100%,#8F0000)); 
background: -webkit-linear-gradient(top,  #660000 0%,#8F0000 100%);
background: -o-linear-gradient(top,  #660000 0%,#8F0000 100%);
background: -ms-linear-gradient(top,  #660000 0%,#8F0000 100%);
background: linear-gradient(to bottom,  #660000 0%,#8F0000 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#660000", endColorstr="#8F0000",GradientType=0 );
padding: 4px;
border-width: 0px;
font-size: ' . $font_size_b . 'em;
border-top: medium none;
text-align: ' . $table_text_align_h . ';';
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #CCCCCC;'; } $css .= '
}
';
}

/* 
Coffee skin
*/

if ($style == 'coffee') {
$css .= '

table#' . $id . ' {
    border-collapse: collapse;
	border-width: 0px;
	border-style: outset;
    margin: 20px 0;
	line-height: 2.0em;
    text-align: ' . $text_align . ';
    vertical-align: top;';
	if ($fixed_width != '' ) { $css .= 'width: ' . $fixed_width . 'px;'; } else { $css .= 'width: 100%;';} 
	if ($h_borders != '' ) { $css .= 'border-top: 1px solid #CCCCCC;'; }
	if ($v_borders != '' ) { $css .= 'border-right: 1px solid #CCCCCC;'; }
	if ($shadow_effect != '' ) { $css .= 'box-shadow: 0 2px 3px rgba(0, 0, 0, 0.4);'; } $css .= '
	
}
table#' . $id . ' thead tr {

}
table#' . $id . ' thead tr th.' . $id . ' {
color: #EEEEEE;
background: #472400;
background: -moz-linear-gradient(top,  #472400 0%, #5C2E00 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#472400), color-stop(100%,#5C2E00)); 
background: -webkit-linear-gradient(top,  #472400 0%,#5C2E00 100%);
background: -o-linear-gradient(top,  #472400 0%,#5C2E00 100%);
background: -ms-linear-gradient(top,  #472400 0%,#5C2E00 100%);
background: linear-gradient(to bottom,  #472400 0%,#5C2E00 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#472400", endColorstr="#5C2E00",GradientType=0 );
font-size: ' . $font_size_h . 'em;
letter-spacing: 0;
line-height: ' . $line_height_h . ';
padding: 4px;
text-transform: none;
text-align: ' . $table_text_align_h . ';';
if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #CCCCCC;'; }
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #CCCCCC;'; } $css .= '
}

table#' . $id . ' thead tr th#' . $id . '.start {

}
table#' . $id . ' thead tr th#' . $id . '.end {

}
table#' . $id . ' tbody tr {
    background: none repeat scroll 0 0 #FFFFFF;
}
table#' . $id . ' tbody tr.table-alternate {
    background: none repeat scroll 0 0 #F0EBE6;
}
';
$i = 1;
while ($i <= $numcol) {
	$css .= 'table#' . $id . ' tbody tr td#n' . $i . ' {
	width: ' . $colwidths[$i-1] . ';
	}';
	$i++;
}
$css .= '
table#' . $id . ' tbody tr td {
    padding: 5px;
	border-width: 0px;
	font-size: ' . $font_size_b . 'em;
	border-top: medium none;';
	if ($cell_padding != '' ) { $css .= 'padding: ' . $cell_padding . 'px;'; }	
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #CCCCCC;'; }
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #CCCCCC;'; } $css .= '
    text-align: ' . $text_align . ';
	line-height: ' . $line_height_b . ';
	vertical-align: ' . $v_text_align . ';' . '
}
table#' . $id . ' tbody tr:hover td {';
	if ($hover_effect != '' ) { $css .= 'background: none repeat scroll 0 0 #C2AD99;'; } $css .= '
}
table#' . $id . ' tfoot tr {
}

table#' . $id . ' tfoot tr td {
color: #EEEEEE;
background: #472400;
background: -moz-linear-gradient(top,  #472400 0%, #5C2E00 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#472400), color-stop(100%,#5C2E00)); 
background: -webkit-linear-gradient(top,  #472400 0%,#5C2E00 100%);
background: -o-linear-gradient(top,  #472400 0%,#5C2E00 100%);
background: -ms-linear-gradient(top,  #472400 0%,#5C2E00 100%);
background: linear-gradient(to bottom,  #472400 0%,#5C2E00 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#472400", endColorstr="#5C2E00",GradientType=0 );
padding: 4px;
font-size: ' . $font_size_b . 'em;
border-width: 0px;
border-top: medium none;
text-align: ' . $text_align . ';';
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #CCCCCC;'; } $css .= '
}
';
}

/*
Green skin
*/
if ($style == 'green') {
$css .= '

table#' . $id . ' {
    border-collapse: collapse;
	border-width: 0px;

    margin: 20px 0;
	line-height: 2.0em;
    text-align: ' . $text_align . ';
    vertical-align: top;';
	if ($fixed_width != '' ) { $css .= 'width: ' . $fixed_width . 'px;'; } else { $css .= 'width: 100%;';} 
	if ($h_borders != '' ) { $css .= 'border-top: 1px solid #CCEBCC;'; }
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #CCEBCC;'; }
	if ($v_borders != '' ) { $css .= 'border-right: 1px solid #CCEBCC;'; }
	if ($shadow_effect != '' ) { $css .= 'box-shadow: 0 2px 3px rgba(0, 0, 0, 0.4);'; } $css .= '
	
}
table#' . $id . ' thead tr {

}
table#' . $id . ' thead tr th.' . $id . ' {
color: #EEF0F3;
background: #006B00;
background: -moz-linear-gradient(top,  #006B00 0%, #008A00 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#006B00), color-stop(100%,#008A00)); 
background: -webkit-linear-gradient(top,  #006B00 0%,#008A00 100%);
background: -o-linear-gradient(top,  #006B00 0%,#008A00 100%);
background: -ms-linear-gradient(top,  #006B00 0%,#008A00 100%);
background: linear-gradient(to bottom,  #006B00 0%,#008A00 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#006B00", endColorstr="#008A00",GradientType=0 );    
font-size: ' . $font_size_h . 'em;
letter-spacing: 0;
line-height: ' . $line_height_h . ';
padding: 4px;
text-transform: none;
text-align: ' . $table_text_align_h . ';';
if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #CCEBCC;'; }
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #006B00;'; } $css .= '
}

table#' . $id . ' thead tr th#' . $id . '.start {

}
table#' . $id . ' thead tr th#' . $id . '.end {

}
table#' . $id . ' tbody tr {
    background: none repeat scroll 0 0 #FFFFFF;
}
table#' . $id . ' tbody tr.table-alternate {
    background: none repeat scroll 0 0 #F0F5E6;
}
';
$i = 1;
while ($i <= $numcol) {
	$css .= 'table#' . $id . ' tbody tr td#n' . $i . ' {
	width: ' . $colwidths[$i-1] . ';
	}';
	$i++;
}
$css .= '
table#' . $id . ' tbody tr td {
    color: #003D00;
	padding: 5px;
	border-width: 0px;
	font-size: ' . $font_size_b . 'em;
	border-top: medium none;';
	if ($cell_padding != '' ) { $css .= 'padding: ' . $cell_padding . 'px;'; }	
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #CCEBCC;'; }
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #CCEBCC;'; } $css .= '
    text-align: ' . $text_align . ';
	line-height: ' . $line_height_b . ';
	vertical-align: ' . $v_text_align . ';' . '
}';
if ($hover_effect != '' ) { $css .= '
table#' . $id . ' tbody tr:hover td {
background: none repeat scroll 0 0 #D8DCCF;
color: #003D00;'; } 
$css .= '
}

table#' . $id . ' tfoot tr {
}

table#' . $id . ' tfoot tr td {
color: #EEF0F3;
background: #006B00;
background: -moz-linear-gradient(top,  #006B00 0%, #008A00 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#006B00), color-stop(100%,#008A00)); 
background: -webkit-linear-gradient(top,  #006B00 0%,#008A00 100%);
background: -o-linear-gradient(top,  #006B00 0%,#008A00 100%);
background: -ms-linear-gradient(top,  #006B00 0%,#008A00 100%);
background: linear-gradient(to bottom,  #006B00 0%,#008A00 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#006B00", endColorstr="#008A00",GradientType=0 );  
padding: 4px;
font-size: ' . $font_size_b . 'em;
border-width: 0px;
border-top: medium none;
text-align: ' . $table_text_align_h . ';';
if ($v_borders != '' ) { $css .= 'border-left: 1px solid #006B00;'; } $css .= '
}
';
}

/*
Blue newpaper e.g. financial
*/
if ($style == 'bluenewspaper') { 
$css .= '

table#' . $id . ' {
    border-collapse: collapse;
	border-width: 0px;
	border-style: outset;
    margin: 20px 0;
	border: 1px solid #6699CC;
	line-height: 2.0em;
    text-align: ' . $text_align . ';
    vertical-align: top;';
	if ($fixed_width != '' ) { $css .= 'width: ' . $fixed_width . 'px;'; } else { $css .= 'width: 100%;';} 
	if ($shadow_effect != '' ) { $css .= 'box-shadow: 0 2px 3px rgba(0, 0, 0, 0.4);'; } $css .= '
	
}
table#' . $id . ' thead tr {

}
table#' . $id . ' thead tr th.' . $id . ' {
    color: #6699CC;
    letter-spacing: 0;
    line-height: ' . $line_height_h . ';
	font-size: ' . $font_size_h . 'em;
    padding: 4px;
    text-transform: none;
	border-bottom: 1px dashed #6699CC;
    text-align: ' . $table_text_align_h . ';
	
}

table#' . $id . ' thead tr th#' . $id . '.start {

}
table#' . $id . ' thead tr th#' . $id . '.end {

}
table#' . $id . ' tbody tr {
    background: none repeat scroll 0 0 #FFFFFF;
}
table#' . $id . ' tbody tr.table-alternate {
    background: none repeat scroll 0 0 #FFFFFF;
}

';
$i = 1;
while ($i <= $numcol) {
	$css .= 'table#' . $id . ' tbody tr td#n' . $i . ' {
	width: ' . $colwidths[$i-1] . ';
	}';
	$i++;
}
$css .= '
table#' . $id . ' tbody tr td {
    padding: 5px;
	border-width: 0px;
	color: #666699;
	font-size: ' . $font_size_b . 'em;
	border-top: medium none;';
	if ($cell_padding != '' ) { $css .= 'padding: ' . $cell_padding . 'px;'; }	
	if ($h_borders != '' ) { $css .= 'border-bottom: 1px solid #C2D6EB;'; }
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #C2D6EB;'; } $css .= '
    text-align: ' . $text_align . ';
	line-height: ' . $line_height_b . ';
	vertical-align: ' . $v_text_align . ';' . '
}
table#' . $id . ' tbody tr td.start {
	border-left: medium none;
}

table#' . $id . ' tbody tr:hover td {';
	if ($hover_effect != '' ) { $css .= 'background: none repeat scroll 0 0 #EDF3F9;'; } $css .= '
}
table#' . $id . ' tfoot tr {
}

table#' . $id . ' tfoot tr td {
	color: #6699CC;
	padding: 4px;
	border-width: 0px;
	border-top: medium none;
	font-size: ' . $font_size_b . 'em;
    text-align: ' . $table_text_align_h . ';';
	if ($v_borders != '' ) { $css .= 'border-left: 1px solid #C2D6EB;'; } $css .= '
}
table#' . $id . ' tfoot tr td.start {
	border-left: medium none;
}

';
}
}
?>

