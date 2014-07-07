<?php 
// Get the path to the root.
$full_path = __FILE__;

$path_bits = explode( 'wp-content', $full_path );

$url = $path_bits[0];

// Require WordPress bootstrap.
require_once( $url . '/wp-load.php' );

$themedy_framework_path = dirname(__FILE__) .  '/../../';

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>
<div id="themedy-dialog">

<div id="themedy-options-buttons" class="clear">
	<div class="alignleft">
	
	    <input type="button" id="themedy-btn-cancel" class="button" name="cancel" value="Cancel" accesskey="C" />
	    
	</div>
	<div class="alignright">
	
	    <input type="button" id="themedy-btn-preview" class="button" name="preview" value="Preview" accesskey="P" />
	    <input type="button" id="themedy-btn-insert" class="button-primary" name="insert" value="Insert" accesskey="I" />
	    
	</div>
	<div class="clear"></div><!--/.clear-->
</div><!--/#themedy-options-buttons .clear-->

<div id="themedy-options" class="alignleft">
    <h3><?php echo __( 'Customize the Shortcode', 'themedy' ); ?></h3>
    
	<table id="themedy-options-table">
	</table>

</div>

<div id="themedy-preview" class="alignleft">

    <h3><?php echo __( 'Preview', 'themedy' ); ?></h3>

    <iframe id="themedy-preview-iframe" frameborder="0" style="width:100%;height:250px" scrolling="no"></iframe>   
    
</div>
<div class="clear"></div>


<script type="text/javascript" src="../wp-content/plugins/themedy-visual-designer/tinymce/js/column-control.js"></script>
<script type="text/javascript" src="../wp-content/plugins/themedy-visual-designer/tinymce/js/dialog-js.php"></script>

</div>

</body>
</html>