<?php
/*
Plugin Name: HonnyPotter
Plugin URI: https://github.com/MartinIngesen/HonnyPotter
Description: Log all failed login-attempts.
Version: 1.1
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

require_once (plugin_dir_path(__FILE__) . 'options.php');

register_activation_hook(__FILE__, 'options_init');

function options_init()
{
	$random_log_file_name = bin2hex(mcrypt_create_iv(7, MCRYPT_DEV_URANDOM)) . '.txt';
	$array['log_name'] = $random_log_file_name;
	update_option('honnypotter', $array);
}

function log_data($username, $password)
{
	$logname = get_option('honnypotter') ['log_name'];
	$logfile = fopen(plugin_dir_path(__FILE__) . $logname, 'a');
	fwrite($logfile, sprintf("%s - %s:%s\n", date('Y-m-d H:i:s') , $username, $password));
	fclose($logfile);
}

if (!function_exists('wp_authenticate')) {
	function wp_authenticate($username, $password)
	{
		$username = sanitize_user($username);
		$password = trim($password);
		/**
		 * Filter the user to authenticate.
		 *
		 * If a non-null value is passed, the filter will effectively short-circuit
		 * authentication, returning an error instead.
		 *
		 * @since 2.8.0
		 *
		 * @param null|WP_User $user     User to authenticate.
		 * @param string       $username User login.
		 * @param string       $password User password
		 */
		$user = apply_filters('authenticate', null, $username, $password);
		if ($user == null) {

			// TODO what should the error message be? (Or would these even happen?)
			// Only needed if all authentication handlers fail to return anything.

			$user = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.'));
		}

		$ignore_codes = array(
			'empty_username',
			'empty_password'
		);
		if (is_wp_error($user) && !in_array($user->get_error_code() , $ignore_codes)) {
			/**
			 * Fires after a user login has failed.
			 *
			 * @since 2.5.0
			 *
			 * @param string $username User login.
			 */
			log_data($username, $password);
			do_action('wp_login_failed', $username);
		}

		return $user;
	}
}
