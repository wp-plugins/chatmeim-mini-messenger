<?php
/*
Plugin Name: ChatMe Mini Messenger
Plugin URI: http://www.chatme.im/
Description: This plugin add the javascript code for Chatme.im Mini Messenger a Jabber/XMPP chat for your WordPress.
Version: 3.3.8
Author: camaran
Author URI: http://www.chatme.im
*/

	//converse installation
add_action('wp_head', 'get_chatme_messenger_head');
add_action('wp_footer', 'get_chatme_messenger_footer');
add_action('admin_menu', 'chatme_messenger_menu');
add_action('admin_init', 'register_messenger_mysettings' );

add_action( 'init', 'my_plugin_init' );

function my_plugin_init() {
      $plugin_dir = basename(dirname(__FILE__));
      load_plugin_textdomain( 'chatmeim-mini-messenger', null, $plugin_dir . '/languages/' );
}

function get_chatme_messenger_head() {
		
	echo "\n".'<link rel="stylesheet" type="text/css" href="'.plugins_url( '/core/css/converse.min.css' , __FILE__ ).'">';
	echo "\n".'<script type="text/javascript" src="'.plugins_url( '/core/converse.min.js' , __FILE__ ).'"></script>';
}

function get_chatme_messenger_footer() {

	$lng = (get_option('language') == '') ? "en" : get_option('language');
	$url = (get_option('hosted') == '1') ? "http://api.webchat.domains/http-bind/" : "http://api.chatme.im/http-bind/";

	//if(get_option('language') == '')
	//	$lng = "en";
	//else
	//	$lng = get_option('language');
		
	//if(get_option('hosted') == '1')
	//	$url = "http://api.webchat.domains/http-bind/";
	//else
	//	$url = "http://api.chatme.im/http-bind/";

echo "\n".'<!-- Messenger -->
	<script>
		require([\'converse\'], function (converse) {
		    converse.initialize({
		        auto_list_rooms: false,
		        auto_subscribe: false,
		        bosh_service_url: \''.$url.'\',
		        hide_muc_server: false,
		        i18n: locales.'.$lng.',
		        prebind: false,
		        show_controlbox_by_default: true,
		        xhr_user_search: false
		    });
		});
	</script>';
}

function chatme_messenger_menu() {
  add_options_page('ChatMe Mini Messenger Options', 'ChatMe Mini Messenger', 'manage_options', 'my-messenger-identifier', 'mini_messenger_options');
}

function register_messenger_mysettings() {
	//register our settings
	register_setting('messenger_chat_msn', 'language');
}

function mini_messenger_options() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.', 'chatmeim-mini-messenger') );
  }
 ?>
 <div class="wrap">
<h2>ChatMe Mini Messenger</h2>
<p><?php _e("For more information visit <a href='http://www.chatme.im' target='_blank'>www.chatme.im</a>", 'chatmeim-mini-messenger'); ?> - <a href="https://webchat.chatme.im/?r=support" target="_blank">Support Chat Room</a></p>
<p><?php _e("For subscribe your account visit <a href='http://api.chatme.im/register_web' target='_blank'>http://api.chatme.im/register_web</a>", 'chatmini'); ?></p> 

<form method="post" action="options.php">
    <?php settings_fields( 'messenger_chat_msn' ); ?>
    <table class="form-table">
    
        <tr valign="top">
        	<th scope="row"><?php _e("Mini Messenger language", 'chatmeim-mini-messenger'); ?></th>
        <td>
        	<select id="language" name="language">
        		<option value="de" <?php selected('de', get_option('language')); ?>>Deutsch</option>
        		<option value="en" <?php selected('en', get_option('language')); ?>>English</option>
        		<option value="es" <?php selected('es', get_option('language')); ?>>Español</option>
        		<option value="fr" <?php selected('fr', get_option('language')); ?>>Français</option>
        		<option value="it" <?php selected('it', get_option('language')); ?>>Italiano</option>
        		<option value="ja" <?php selected('ja', get_option('language')); ?>>日本語</option>
        		<option value="nl" <?php selected('nl', get_option('language')); ?>>Nederlands</option>
        		<option value="ru" <?php selected('ru', get_option('language')); ?>>Русский</option>
        		</select><br />
                <input type="checkbox" value="1" name="hosted" <?php if (get_option('hosted')=="1") { echo 'checked=""'; } ?> /> <?php _e("Hosted Domain", 'chatmeim-mini-messenger'); ?><br /><?php _e("For Active an hosted domain with XMPP service <a href='http://chatme.im' target='_blank'>visit www.chatme.im</>", 'chatmeim-mini-messenger'); ?>
        </td>
        </tr>

    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'chatmeim-mini-messenger') ?>" />
    </p>
    <p>For Ever request you can use our <a href="http://chatme.im/forums" target="_blank">forum</a></p>

</form>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="8CTUY8YDK5SEL">
<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
</form>

<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://chatme.im" data-text="Visita chatme.im" data-via="chatmeim" data-lang="it">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

</div>
<?php } ?>