<?php
add_action('admin_menu', 'plugin_admin_add_page');

function plugin_admin_add_page()
{
	add_options_page('HonnyPotter', 'HonnyPotter', 'manage_options', 'honnypotter', 'plugin_options_page');
}

add_action('admin_init', 'plugin_admin_init');

function plugin_admin_init()
{
	register_setting('honnypotter_options', 'honnypotter', 'honnypotter_validate');
}

function honnypotter_validate($input)
{
	return $input;
}

function plugin_options_page()
{
	$logpath = plugin_dir_url(__FILE__) . get_option('honnypotter') ['log_name'];
?>
<div class="wrap">
<h2>HonnyPotter</h2>

<p>
  Your log-file is currently accessible via <a href="<?php echo $logpath; ?>"><code><?php echo $logpath; ?></code></a>.
</p>

<form action="options.php" method="post">
  <?php
	settings_fields('honnypotter_options'); ?>
  <h3>Edit log name</h3>
  <input type="text" size="50" name="honnypotter[log_name]" value="<?php echo get_option('honnypotter') ['log_name']; ?>">
  <br />
  <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
</form>
</div>

<?php
}
?>