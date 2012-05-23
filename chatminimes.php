<?php
/*
Plugin Name: Chatme.im Mini Messenger
Plugin URI: http://www.chatme.im/
Description: This plugin add the javascript code for Chatme.im Mini Messenger a Jabber/XMPP chat for your WordPress.
Version: 0.1 beta 1
Author: Thomas Camaran
Author URI: http://www.chatme.im
*/

/*  Copyright 2012  Thomas Camaran  (email : camaran@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Custom Variables (YOU NOT EDIT)
$GLOBALS['$jappix_url'] = "https://webchat.chatme.im"; 	//jappix installation
$GLOBALS['conference'] = "conference.chatme.im"; 		//server of conference
$GLOBALS['anonymous'] = "anonymous.chatme.im"; 			//Server for anonymous chat
$GLOBALS['resource'] = $_SERVER['SERVER_NAME']; 		//resource for chat
$GLOBALS['default_room'] = "piazza, support"; 					//default room

add_action('wp_head', 'get_chatme_mini_head');
add_action('wp_footer', 'get_chatme_mini_footer');
add_action('admin_menu', 'chatme_mini_menu');
add_action('admin_init', 'register_mysettings' );

add_action( 'init', 'my_plugin_init' );

function my_plugin_init() {
      $plugin_dir = basename(dirname(__FILE__));
      load_plugin_textdomain( 'chatmini', null, $plugin_dir . '/languages/' );
      wp_register_script( 'barra-script', plugins_url('/script/barra.js', __FILE__) );
      wp_register_style( 'barra-style', plugins_url('/script/barra.css', __FILE__) );
}

function get_chatme_mini_head() {
	if(get_option('all') == 1 || get_option('all') == '')
		$all = true;
	else
		$all = false;
if ($all || is_user_logged_in()) {
	if(get_option('auto_login') == 1)
		$auto_login = "true";
	else
		$auto_login = "false";
	if(get_option('animate') == 1)
		$animate = "true";
	else
		$animate = "false";
	if(get_option('auto_show') == 1)
		$auto_show = "true";
	else
		$auto_show = "false";
	if(get_option('yet_jquery') != 1)
		$jquery = "&amp;f=jquery.js";
	if(get_option('join_groupchats') == '')
		$join_groupchats = $GLOBALS['default_room'];
	else
		$join_groupchats = get_option('join_groupchats');
	$groups = explode(',', $join_groupchats);
	foreach ($groups as $value) {
		$group .= '"'.trim($value) . '@' . $GLOBALS['conference'] .'", '; 
	}
	$group = substr ($group, 0, -2);
	$lng = get_option('language');
	$nickname = get_userdata(get_current_user_id())->user_login;
	wp_enqueue_script( 'barra-script' );
	wp_enqueue_style( 'barra-style' );
	echo "\n".'<script type="text/javascript" src="'.$GLOBALS['$jappix_url'].'/php/get.php?l='.$lng.'&amp;t=js&amp;g=mini.xml'.$jquery.'"></script>';
}
}

function get_chatme_mini_footer() {
echo "\n".'<!-- Messenger -->
	<div class="bar">
		<div class="account">
			<form id="login_account" action="#" method="post">
				Login to your XMPP account
				<input type="text" name="xid" placeholder="username@chatme.im" />
				<input class="password_false" type="text" placeholder="password" />
				<input class="password_real" style="display: none;" type="password" name="pwd" />
				<input type="submit" value="Log in" class="botton" />
				<a class="register" href="'. $GLOBALS['$jappix_url'] .'" target="_blank" title="Register for a free account on chatme.im!">Don\'t have an account?</a>
			</form>
		</div>
		<div class="anonymous">
			<form id="login_anonymous" action="#" method="post">
			Use an anonymous account
				<input type="text" name="nick" placeholder="nickname" />
				<input type="text" name="room" value="piazza@'. $GLOBALS['conference'] .'" placeholder="piazza@'. $GLOBALS['conference'] .'" />
				<input type="submit" value="Join" class="botton" />
			</form>
		</div>		
	</div>';
}

function chatme_mini_menu() {
  add_options_page('Chatme.im Mini Messenger Options', 'Chatme.im Mini Messenger', 'manage_options', 'my-unique-identifier', 'mini_jappix_options');
}

function register_mysettings() {
	//register our settings
	register_setting('mini_chat', 'yet_jquery');
	register_setting('mini_chat', 'language');
	register_setting('mini_chat', 'auto_login');
	register_setting('mini_chat', 'auto_show');
	register_setting('mini_chat', 'animate');
	register_setting('mini_chat', 'join_groupchats');
	register_setting('mini_chat', 'all');
}

function mini_jappix_options() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.', 'minijappix') );
  }
 ?>
 <div class="wrap">
<h2>Chatme.im Mini Messenger</h2>
<p><?php _e("For more information visit <a href='http://www.chatme.im' target='_blank'>www.chatme.im</a>", 'chatmini'); ?></p>

<form method="post" action="options.php">
    <?php settings_fields( 'mini_chat' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e("Auto login to the account", 'chatmini'); ?></th>
        <td><input type="checkbox" name="auto_login" value="1" <?php checked('1', get_option('auto_login')); ?> /></td>
        </tr>
		
		<tr valign="top">
        <th scope="row"><?php _e("Auto show the opened chat", 'chatmini'); ?></th>
        <td><input type="checkbox" name="auto_show" value="1" <?php checked('1', get_option('auto_show')); ?> /></td>
        </tr>

		<tr valign="top">
        <th scope="row"><?php _e("Display an animated image when the user is not connected", 'chatmini'); ?></th>
        <td><input type="checkbox" name="animate" value="1" <?php checked('1', get_option('animate')); ?> /></td>
        </tr>
		
		<tr valign="top">
        <th scope="row"><?php _e("Chat rooms to join (if any)", 'chatmini'); ?></th>
        <td><input type="text" name="join_groupchats" value="<?php echo get_option('join_groupchats'); ?>" /> <?php echo $GLOBALS['conference']; ?><br/><?php _e("For more use comma separator (example: piazza, scuola)", 'chatmini'); ?></td>
        </tr>
		
		<tr valign="top">
        <th scope="row"><?php _e("jQuery is yet included", 'chatmini'); ?></th>
        <td><input type="checkbox" name="yet_jquery" value="1" <?php checked('1', get_option('yet_jquery')); ?> /></td>
        </tr>

		<tr valign="top">
        <th scope="row"><?php _e("Available only for logged users", 'chatmini'); ?></th>
        <td><input type="checkbox" name="all" value="0" <?php checked('0', get_option('all')) ?> /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e("Mini Jappix language", 'chatmini'); ?></th>
        <td>
        <select id="language" name="language">
        <option value="de" <?php selected('de', get_option('language')); ?>>Deutsch</option>
        <option value="en" <?php selected('en', get_option('language')); ?>>English</option>
        <option value="eo" <?php selected('eo', get_option('language')); ?>>Esperanto</option>
        <option value="es" <?php selected('es', get_option('language')); ?>>Español</option>
        <option value="fr" <?php selected('fr', get_option('language')); ?>>Français</option>
        <option value="it" <?php selected('it', get_option('language')); ?>>Italiano</option>
        <option value="ja" <?php selected('ja', get_option('language')); ?>>日本語</option>
        <option value="nl" <?php selected('nl', get_option('language')); ?>>Nederlands</option>
        <option value="pl" <?php selected('pl', get_option('language')); ?>>Polski</option>
        <option value="ru" <?php selected('ru', get_option('language')); ?>>Русский</option>
        <option value="sv" <?php selected('sv', get_option('language')); ?>>Svenska</option>
        <option value="hu" <?php selected('hu', get_option('language')); ?>>Hungarian</option>
        </select>
        </td>
        </tr>

    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'chatmini') ?>" />
    </p>

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
