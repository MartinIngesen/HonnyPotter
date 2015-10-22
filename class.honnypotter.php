<?php

class HonnyPotter
{
  function options_init()
  {
  	$random_log_file_name = bin2hex(mcrypt_create_iv(7, MCRYPT_DEV_URANDOM)) . '.log';
  	$array['log_name'] = $random_log_file_name;
  	update_option('honnypotter', $array);
  }
}
