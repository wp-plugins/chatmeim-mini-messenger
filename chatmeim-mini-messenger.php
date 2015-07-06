<?php
/*
Plugin Name: ChatMe Mini Messenger
Plugin URI: http://www.chatme.im/
Description: This plugin add the javascript code for Chatme.im Mini Messenger a Jabber/XMPP chat for your WordPress.
Version: 4.2.0
Author: camaran
Author URI: http://www.chatme.im
Text Domain: chatmeim-mini-messenger
Domain Path: /languages/
*/

class ChatMe_Messenger {

private $default	= array(
				'languages' 				=> '/languages/', 
				'placeholder' 				=> ' e.g. chatme.im',	
				'language'				=> 'en',
				'webchat' 				=> 'https://bind.chatme.im/',
				'providers_link'			=> 'http://chatme.im/servizi/domini-disponibili/',
				'auto_list_rooms'			=> 'false',
				'auto_subscribe'			=> 'false',
				'hide_muc_server'			=> 'false',
				'message_carbons'			=> 'true',
				'prebind'				=> 'false',
				'show_controlbox_by_default'		=> 'true',
				'xhr_user_search'			=> 'false',
				'conver'				=> '0.9.3',				
				);

	function __construct() {
		add_action('wp_enqueue_scripts', 		array( $this, 'get_chatme_messenger_head') );
		add_action('wp_footer', 			array( $this, 'get_chatme_messenger_footer') );
		add_action('admin_menu', 			array( $this, 'chatme_messenger_menu') );
		add_action('admin_init', 			array( $this, 'register_messenger_mysettings') );
		add_action( 'init', 				array( $this, 'my_plugin_init') );
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'add_action_messenger_links') );
	}

	function my_plugin_init() {
      	$plugin_dir = basename(dirname(__FILE__));
      	load_plugin_textdomain( 'chatmeim-mini-messenger', null, $plugin_dir . $this->default['languages'] );
	}
	
	function add_action_messenger_links ( $links ) {
      	$mylinks = array( '<a href="' . admin_url( 'options-general.php?page=chatme-mini-messenger' ) . '">' . __( 'Settings', 'chatmeim-mini-messenger' ) . '</a>', );
      	return array_merge( $links, $mylinks );
    }

      	function chatme_messenger_add_help_tab () {
          	$screen = get_current_screen();

          	$screen->add_help_tab( array(
              	      	'id'		=> 'chatme_messenger_help_tab',
              	      	'title'		=> __('Hosted Domain', 'chatmeim-mini-messenger'),
              	      	'content'	=> '<p>' . __( 'Select this option if you have a domain hosted in ChatMe XMPP Server', 'chatmeim-mini-messenger' ) . '</p>',
          	      	) );

          	$screen->set_help_sidebar(
                              __('<p><strong>Other Resources</strong></p><p><a href="http://xmpp.net" target="_blank">XMPP.net</a></p><p><a href="http://chatme.im" target="_blank">ChatMe Site</a></p>', 'chatmeim-mini-messenger')
                             );
      	      	}

	function get_chatme_messenger_head() {
		
		wp_register_style( 'ConverseJS', plugins_url( '/core/css/converse.min.css', __FILE__ ), array(), $this->default['conver'] );
		wp_enqueue_style( 'ConverseJS' );
		wp_register_script( 'ConverseJS', plugins_url( '/core/converse.min.js', __FILE__ ), array(), $this->default['conver'], false );
		wp_enqueue_script( 'ConverseJS' );
	}

	function get_chatme_messenger_footer() {

		$setting	= array(
					'language'	=> esc_html(get_option('language')),			
						);
						
		foreach( $setting as $k => $settings )
			if( false == $settings )
				unset( $setting[$k]);
						
		$actual = wp_parse_args( $setting, $this->default );	

	printf( '
		
		<!-- Messenger -->
		<script defer>
			require([\'converse\'], function (converse) {
		    	converse.initialize({
		        	auto_list_rooms: %s,
		        	auto_subscribe: %s,
		        	bosh_service_url: "%s",
				domain_placeholder: "%s",
				providers_link: "%s",
		        	hide_muc_server: %s,
		        	i18n: locales.%s,
				message_carbons: %s,
		        	prebind: %s,
		        	show_controlbox_by_default: %s,
		        	xhr_user_search: %s
		    	});
			});
		</script>',
			$actual['auto_list_rooms'],
			$actual['auto_subscribe'], 
			$actual['webchat'],
			$actual['placeholder'],
			$actual['providers_link'],
			$actual['hide_muc_server'],
			$actual['language'],
			$actual['message_carbons'],
			$actual['prebind'],
			$actual['show_controlbox_by_default'],
			$actual['xhr_user_search'] 
			);
	}

	function chatme_messenger_menu() {
  		$my_admin_page = add_options_page('ChatMe Mini Messenger Options', 'ChatMe Mini Messenger', 'manage_options', 'chatme-mini-messenger', array($this, 'mini_messenger_options') );
		add_action('load-'.$my_admin_page, array( $this, 'chatme_messenger_add_help_tab') );
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
	<p><?php _e("For more information visit <a href='http://www.chatme.im' target='_blank'>www.chatme.im</a>", 'chatmeim-mini-messenger'); ?> - <?php _e('<a href="https://webchat.chatme.im/?r=support" target="_blank">Support Chat Room</a></p>', 'chatmeim-mini-messenger'); ?>
	<p><?php _e("For subscribe your account visit <a href='http://chatme.im/servizi/domini-disponibili/' target='_blank'>http://webchat.chatme.im/register_web</a>", 'chatmeim-mini-messenger'); ?></p> 

	<form method="post" action="options.php">
    	<?php settings_fields( 'messenger_chat_msn' ); ?>
    	<table class="form-table">
    
        	<tr valign="top">
        		<th scope="row"><?php _e("Mini Messenger language", 'chatmeim-mini-messenger'); ?></th>
        	<td>
        	<select id="language" name="language">
        		<option value="de" <?php selected('de', get_option('language')); ?>>Deutsch</option>
        		<option value="en" <?php selected('en', get_option('language')); ?>>English</option>
        		<option value="es" <?php selected('es', get_option('language')); ?>>Espanol</option>
        		<option value="fr" <?php selected('fr', get_option('language')); ?>>Français</option>
        		<option value="it" <?php selected('it', get_option('language')); ?>>Italiano</option>
        		<option value="ja" <?php selected('ja', get_option('language')); ?>>Ja</option>
        		<option value="nl" <?php selected('nl', get_option('language')); ?>>Nederlands</option>
        		<option value="ru" <?php selected('ru', get_option('language')); ?>>Ru</option>
        	</select>
        	</td>
        	</tr>
    	</table>
    
    	<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes', 'chatmeim-mini-messenger') ?>" /></p>
    	<p><?php _e('For Ever request you can use our <a href="http://chatme.im/forums" target="_blank">forum</a>',  'chatmeim-mini-messenger'); ?></p>

	</form>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="8CTUY8YDK5SEL">
		<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal ? The safer, easier way to pay online.">
		<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
	</form>

	<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://chatme.im" data-text="Visita chatme.im" data-via="chatmeim" data-lang="it">Tweet</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

	</div>
<?php 
	}
} 
new ChatMe_Messenger;
?>