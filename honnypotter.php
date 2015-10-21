<?php
/*
Plugin Name: HonnyPotter
*/

function log_data($username, $password){
  $logfile = fopen(plugin_dir_path( __FILE__ ) . 'log.txt', 'a');
  fwrite($logfile,sprintf("%s - %s:%s\n", date('Y-m-d H:i:s'), $username, $password));
  fclose($logfile);
}

if( ! function_exists('wp_authenticate') ) {
  function wp_authenticate($username, $password) {
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
    $user = apply_filters( 'authenticate', null, $username, $password );

    if ( $user == null ) {
      // TODO what should the error message be? (Or would these even happen?)
      // Only needed if all authentication handlers fail to return anything.
      $user = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.'));
    }

    $ignore_codes = array('empty_username', 'empty_password');

    if (is_wp_error($user) && !in_array($user->get_error_code(), $ignore_codes) ) {
      /**
      * Fires after a user login has failed.
      *
      * @since 2.5.0
      *
      * @param string $username User login.
      */
      log_data($username, $password);
      do_action( 'wp_login_failed', $username );
    }

    return $user;
  }
}
