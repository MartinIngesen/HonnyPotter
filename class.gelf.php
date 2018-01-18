<?php

/*
"version": "1.1",
  "host": "example.org",
  "short_message": "A short message that helps you identify what is going on",
  "full_message": "Backtrace here\n\nmore stuff",
  "timestamp": 1385053862.3072,
  "level": 1,
  "_user_id": 9001,
  "_some_info": "foo",
  "_some_env_var": "bar"

*/

class Gelf
{
  public $version = "1.1";
  public $host;
  public $short_message;
  public $timestamp;
  public $level = 1;
}

?>
