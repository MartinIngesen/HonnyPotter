<?php

class HonnyPotter
{
  private static $initiated = false;

  public static function init()
  {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

  private static function init_hooks()
  {
    self::$initiated = true;

    register_activation_hook(__FILE__, array( 'HonnyPotter', 'options_init' ) );
  }

  function options_init()
  {
  	$random_log_file_name = bin2hex(mcrypt_create_iv(7, MCRYPT_DEV_URANDOM)) . '.log';
  	$array['log_name'] = $random_log_file_name;
  	update_option('honnypotter', $array);
  }
}
