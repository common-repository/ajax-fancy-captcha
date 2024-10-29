<?php
/*
Plugin Name: Ajax Fancy Captcha
Plugin URI: http://www.webdesignbeach.com/beachbar/ajax-fancy-captcha-jquery-plugin
Description: Protects your blog from spammers.
Author: Web Design Beach
Version: 1.0.0
License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


function afc_activate() {
	$options[afc_bc] = "#555";
	$options[afc_style] = "margin-top: 50px; padding: 0px;";
	update_option( 'afc_options', $options );
}

function afc_wp_head() {
$options = get_option( 'afc_options' );
?>
<script type='text/javascript' src='<?=bloginfo('url')?>/wp-content/plugins/AjaxFancyCaptcha/latest-jquery/jquery-1.3.1.js'></script>
<script type='text/javascript' src='<?=bloginfo('url')?>/wp-content/plugins/AjaxFancyCaptcha/latest-jquery-ui/jquery.ui.all.js'></script>
<script type='text/javascript' src='<?=bloginfo('url')?>/wp-content/plugins/AjaxFancyCaptcha/jquery.captcha.js'></script>
<link href="<?=bloginfo('url')?>/wp-content/plugins/AjaxFancyCaptcha/captcha.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8">
	var borderColor = "<?=$options[afc_bc]?>"; /* border color hex or left it null if you don't want to change border color*/
	var captchaDir = "<?=bloginfo('url')?>/wp-content/plugins/AjaxFancyCaptcha/" /* path to captcha files (if you use domain www.example.com path should present all subfolders after that, start with "/") */
	var url = captchaDir + "captcha.php" /* this is name of form action */
	var formId = "commentform" /* id of your form */
	var items = Array("pencil", "scissors", "clock", "heart", "note"); 
	var style = '<?=$options[afc_style]?>';

	$(function() {

		$("p:has('textarea')").append('<div class="ajax-fc-container" style="' + style + '"></div>');
		$(".ajax-fc-container").captcha(); /* in this line note that ajax-fc-container is a class and we reference it with .(dot), if you want to change class to id, don't forget to replace the dot with # sign and also to describe it in css file. */

	});
</script>
<?
}


function afc_form() {
$options = get_option( 'afc_options' );

}


function afc_admin_menu() {
add_submenu_page('plugins.php', __('WordPress.com AFC Plugin'), __('AF Captcha'), 'manage_options', 'afc', 'afc_admin_page');

}

function afc_preprocess_comment($comment) {

	if(!session_id())session_start();
	if($_POST[captcha] && $_POST[captcha] == $_SESSION[captcha]){
		unset($_SESSION[captcha]);
		return($comment);
	}	
	else wp_die( __("Error: please drag the captcha."));		
}

function afc_admin_page() {
		global $plugin_page;
		$options = get_option( 'afc_options' );
		if($_POST[afc_bc] && $_POST[afc_style])
			{
					$options[afc_bc] = $_POST[afc_bc];
					$options[afc_style] = $_POST[afc_style];
					update_option( 'afc_options', $options );
					echo "<p>Saved!</p>";
			}
?>
	<div class="wrap">
		<h2><?php _e('Ajax Fancy Captcha'); ?></h2>
		<div class="narrow">
			<form action="plugins.php?page=<?php echo $plugin_page; ?>" method="post">
				<?php wp_nonce_field('stats'); ?>
				<p><?php _e('Ajax Fancy Captcha is a jQuery plugin that helps you protect your web pages from boys and spammers. We are introducing you to a new, intuitive way of completing “verify humanity” tasks. In order to do that you are asked to drag and drop specified item into a circle.'); ?></p>
				<label for="afc_bc"><?php _e('Captcha border-color (hex):'); ?> <input type="text" name="afc_bc" id="afc_bc" value="<?php echo $options[afc_bc]; ?>" /></label><br />
				<label for="afc_style"><?php _e('Additional Css for Captcha:'); ?> <input type="text" name="afc_style" id="afc_style" value="<?php echo $options[afc_style]; ?>" /></label>
				<p class="submit"><input type="submit" value="<?php _e('Save &raquo;'); ?>" /></p>
			</form>
		</div>
	</div>
<?
}

register_activation_hook(__FILE__, 'afc_activate');
add_action('comment_form', 'afc_form');
add_action('admin_menu', 'afc_admin_menu');
add_action('wp_head', 'afc_wp_head');
add_action('preprocess_comment', 'afc_preprocess_comment');
?>
