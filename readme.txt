=== Plugin Name ===
Contributors: Mrtn9
Tags: honeypot
Requires at least: 4.3.1
Tested up to: 4.4
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A honeypot for logging all failed login-attempts.

== Description ==

A honeypot for logging all failed login-attempts.

# Note(!!!)
This will currently make all failed login attempts globally accessible. Use at your own risk. Clear the logs immediately if you for some reason type in your wrong (but almost correct) password. This might change in a future version.

== Installation ==

1. Create a `HonnyPotter` directory in `/wp-content/plugins/` and upload all the files there.
1. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to the settings panel under ``Settings > HonnyPotter``
4. Here you can alternatively change the log name. We autogenerate a random name on plugin installation.
5. All failed login attempts will be logged to the log file.

== Frequently Asked Questions ==

= Will my actual username and password be saved? =

No. Only failed attempts get recorded in the logs.

= How do I clear the logs? =

FTP or access the WordPress installation, navigate to the HonnyPotter folder and delete the log.

== Changelog ==

= 1.2 =
* Fixed issue `[Fatal error :( #1](https://github.com/MartinIngesen/HonnyPotter/issues/1)` by rewriting the code for randomizing logfile-names.

= 1.1 =
* First version.
