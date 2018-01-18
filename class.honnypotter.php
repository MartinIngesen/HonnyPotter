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
  public static function init_hooks()
  {
    self::$initiated = true;
  }

  public static function options_init()
  {
  	$random_log_file_name = $rand = substr(md5(microtime()),rand(0,17),14) . '.log';
  	$array['log_name'] = $random_log_file_name;
    $array['date_format'] = "Y-m-d H:i:s";
    $array['graylog_server'] = "";
  	$array['wp_authenticate_override'] = false;
  	update_option('honnypotter', $array);
  }
}
