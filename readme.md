# How to install

1. Copy all the files into a new directory in ``$WP_ROOT/wp-content/plugins/``
2. Activate the plugin via the WordPress administration panel. (``/wp-admin/plugins.php``)
3. Navigate to the settings panel under ``Settings > HonnyPotter``
4. Here you can alternatively change the log name. We autogenerate a random name on plugin installation.
5. All failed login attempts will be logged to the log file.

# Note(!!!)

This will currently make all failed login attempts globally accessible. Use at your own risk. Clear the logs immediately if you for some reason type in your wrong (but almost correct) password. This might change in a future version.

# Credits

[Created because Per Thorsheim wanted it.](https://twitter.com/thorsheim/status/656828775850725376)
