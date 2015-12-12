<?php

class HonnyPotter_Admin
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

    add_action('admin_menu', array( 'HonnyPotter_Admin', 'plugin_admin_add_page' ) );
    add_action('admin_init', array( 'HonnyPotter_Admin', 'plugin_admin_init') );
  }

  public static function plugin_admin_add_page()
  {
  	add_options_page('HonnyPotter', 'HonnyPotter', 'manage_options', 'honnypotter', array( 'HonnyPotter_Admin', 'plugin_options_page') );
  }

  public static function plugin_admin_init()
  {
  	register_setting('honnypotter_options', 'honnypotter', array( 'HonnyPotter_Admin', 'honnypotter_validate' ) );
  }

  public static function honnypotter_validate($input)
  {
  	return $input;
  }

  public static function plugin_options_page()
  {
    $options = get_option('honnypotter');
  	$logpath = plugin_dir_url(__FILE__) . $options['log_name'];
  ?>
  <div class="wrap">
    <?php if(!$options['wp_authenticate_override']){ ?>
    <h1>Warning: You have other plugins trying to override the same functions as we use, this plugin may or may not work.</h1>
    <?php } ?>
  <h2>HonnyPotter</h2>

  <p>
    Your log-file is currently accessible via <a href="<?php echo $logpath; ?>"><code><?php echo $logpath; ?></code></a>.
  </p>

  <form action="options.php" method="post">
    <?php
  	settings_fields('honnypotter_options'); ?>
    <h3>Edit log name</h3>
    <input type="text" size="50" name="honnypotter[log_name]" value="<?php echo $options['log_name']; ?>">
    <br />
    <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
  </form>
  <br>
  <br>
  <hr />
  <p>Created by Martin Ingesen.</p>

  <p>
    Please report any issues either via my <a href="https://twitter.com/Mrtn9">Twitter</a>, <a href="mailto:martin@ingesen.no">E-Mail</a> or via <a href="https://github.com/MartinIngesen/HonnyPotter">GitHub</a>.
  </p>
  <p>Read my blog at <a href="http://martin.ingesen.no">martin.ingesen.no</a>. Source code available at <a href="https://github.com/MartinIngesen/HonnyPotter">GitHub</a>.</p>


  </div>

  <?php
  }
}
