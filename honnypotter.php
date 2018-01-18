<?php
/**
 * @package HonnyPotter
 */
/*
Plugin Name: HonnyPotter
Plugin URI: https://github.com/MartinIngesen/HonnyPotter
Description: Log all failed login-attempts.
Version: 1.2
Author: Martin ingesen
Author URI: http://martin.ingesen.no
License: GPLv2 or later
Text Domain: honnypotter
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Martin Ingesen.
*/

define('WP_DEBUG', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);

define( 'HONNYPOTTER__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


require_once ( HONNYPOTTER__PLUGIN_DIR . 'class.gelf.php' );

require_once ( HONNYPOTTER__PLUGIN_DIR . 'class.honnypotter.php' );
register_activation_hook(__FILE__, array( 'HonnyPotter', 'options_init' ) );
add_action( 'init', array( 'HonnyPotter', 'init' ) );

if( is_admin() )
{
	require_once( HONNYPOTTER__PLUGIN_DIR . 'class.honnypotter-admin.php' );
	add_action( 'init', array( 'HonnyPotter_Admin', 'init' ) );
}

function send_to_graylog($username, $password){
	$gelf = new Gelf();
	$gelf->host = get_home_url();
	$gelf->short_message = "Authentication failed for username and password combination: " . $username . ":" . $password;
	$date = new DateTime();
	$gelf->timestamp = $date->getTimestamp();
	$json = json_encode($gelf);

	$options = get_option('honnypotter');

	$ch = curl_init($options['graylog_server']);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json')
	);

	curl_exec($ch);
}

function hp_log($username, $password){


	$options = get_option('honnypotter');

	if( $options['graylog_server'] ){
		send_to_graylog($username, $password);
	}else{
		$logname = $options['log_name'];
		$dateformat = $options['date_format'];
		$logfile = fopen(plugin_dir_path(__FILE__) . $logname, 'a') or die('could not open/create file');
		fwrite($logfile, sprintf("%s - %s:%s\n", date($dateformat) , $username, $password));
		fclose($logfile);
	}
}

if (!function_exists('wp_authenticate')) {
	$options = get_option('honnypotter');
	$options['wp_authenticate_override'] = true;
	update_option('honnypotter', $options);


	function wp_authenticate($username, $password)
	{
		$username = sanitize_user($username);
		$password = trim($password);

		$user = apply_filters('authenticate', null, $username, $password);
		if ($user == null) {
			$user = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.'));
		}

		$ignore_codes = array(
			'empty_username',
			'empty_password'
		);
		if (is_wp_error($user) && !in_array($user->get_error_code() , $ignore_codes)) {
			// Fires after a user login has failed.

			hp_log($username, $password);

			do_action('wp_login_failed', $username);
		}

		return $user;
	}
}else{
	$options = get_option('honnypotter');
	$options['wp_authenticate_override'] = false;
	update_option('honnypotter', $options);
}
