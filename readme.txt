=== WithinWeb PHP-KeyCodes ===
Contributors: paulvgibbs
Donate link:  http://www.withinweb.com/wordpresskeycodes/
Tags: key codes, pin codes, sell pin codes with PayPal
Requires at least: 3.0.1
Tested up to: 3.9.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Sell licence key codes or pin numbers automatically using PayPal.


== Description ==

This Plugin enables you to sell license key codes or pin numbers automatically when someone makes a PayPal purchase.

The pin numbers are listed in the database one entry per line and when a purchase is made, PayPal sends an IPN notification to the plugin which then extracts the first pin number sends it to the purchaser and then removes that pin number from the list.

The email sent to the purchaser contains the pin number, and you should receive a copy of the email.

The sales history listing also identifies which pin number has been sold to each purchaser.

A local test system is included which allows you to test without connecting to PayPal.

Setting a value in the Lower Limit entry box causes an email to be sent to the administrator when a minimum of key codes has been reached.


== Installation ==

This section describes how to install the plugin and get it working.

1. Upload 'withinweb_wp_keycodes' to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. In the 'Settings' of the KeyCodes menu, you must enter in your PayPal email address for payments, and an admin email address.
4. Create an item and enter in the key codes in the key codes field one line at a time.
5. You may test the system using a local test without connecting to PayPal.
6. In 'Settings' make sure you have selected the PayPal enviromment that you want to use, as either local test, PayPal live or PayPal sandbox.
7. To display the PayPal button on your WordPress page, use the short code [keycodesbutton recid='x'] where x is the record id of the product item.
You can get the record id of the product by going to 'Item List'.


PayPal activation.

Make sure that you have enabled IPN in your PayPal account.  You may also have to enter in the IPN Call Back URL which you can get from the 'Settings' menu of the plugin.

The call back url is acutally sent to PayPal as part of the button submission, which means that url entered in PayPal setup can be different to the url needed for this plugin.


== Changelog ==

= 1.0 =
First Version

== Upgrade Notice ==
No upgrades at present



