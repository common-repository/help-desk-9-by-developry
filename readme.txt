=== WP Customer Support ===

Contributors: developry
Donate Link: https://developry.com/donate
Tags: help desk, support ticketing, support ticket management, support ticket system
Requires at least: 5.1.0
Tested up to: 5.5
Requires PHP: 7.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Support ticket system built on top of Contact Form 7 and Flamingo plugins.

## Description ##

Support ticket system built on top of Contact Form 7 and Flamingo plugins. 

[youtube https://youtu.be/W9-F-LuvXf4]

**Note:** As long as you have installed and activated Contact Form 7 and Flamingo or you already are using them you can set up the Help Desk 9 plugin in no time and add a simple support ticket system to your site.

### Usage ###

Create a new Contact Form 7 form and add both its shortcode and the WP Customer Support shortcode into the page body.

	[help-desk-9]
	[contact-form-7 id="1" title="Contact Form 7"]

In addition, add the following to the Additional Settings in your Contact Form 7:

	flamingo_email: "[your-email]"
	flamingo_name: "[your-name]"
	flamingo_subject: "[your-subject]"

If you create a support page for a registered user (e.g Subscribers) you may want to have your Contact Form 7 Name and Email fields populated and read-only, e.g.

	[text* your-name default:user_display_name readonly placeholder "Full name*"]
	[email* your-email default:user_email readonly placeholder "Email address*"]

**Note:** This way the user won't need to add them every time they want to create a new support ticket.

**Important:** Currently there is a naming limitation filter for the fields that will show up on the front-end. You need to have the following keywords when you create the Contact Form 7 fields.

	input, title, subject, message, description, content, textarea

For example:

	[text your-subject] OR [text subject]
	[textarea your-message] OR [textarea my-custom-message-as-support-ticket]

**Note:** As long as you have the keyword in the name of the field you should be fine.

### Features ###

You can have your ticketing system working for the following different scenarios:

* **Protected** – visible on a password protected page.
* **Private** – visible only for registered users.
* **Public** – visible to all visitors (this way everyone will be able to see the conversation and post anonymously).

Style and format front-end support page

There are two additional attributes and you can use them if you wish to customize (markup differently) and style the output of the support data table.

	[help-desk-9 output="json" format="raw"]

This is how you get the data in JSON format. (In PHP I would do the following and the loop and use the data to create a template view.)

	// Use print_r( $data ) to view the data structure.
	$data = json_decode( do_shortcode( '[help-desk-9 output="json" format="raw"]' ) ); 

### Detailed Documentaion ###

Additional information with step by step setup, usage, demos, and more video & media help can be found on the Developry [**WP Customer Support**](https://developry.com/help-desk-9-by-developry) page.

### WP Customer Support Pro ###

As of yet this plugin doesn't have a commercial version available.

## Frequently Asked Questions ##

As of right now, none. 

Use the [Support](https://wordpress.org/support/plugin/help-desk-9-by-developry/) tab on this page to post your requests and questions. 

All tickets are usually addressed within 24 hours. 

If your request is add-on feature related we will add it to the plugin wish list and consider implementing it in the next major version.

## Screenshots ##

1. WP Customer Support is dependent on CF7 and Flamingo screenshot-1.(png)
2. Example CF7 form + Additional Settings screenshot-2.(png)
3. Sample support ticket page with CF7 and WP Customer Support shortcodes screenshot-3.(png)
4. Add support new ticket button anchor screenshot-4.(png)
5. Front-end CF7 form with logged-in User screenshot-5.(png)
6. Extended Flamingo Inbound Messages table view with action links for the Administrator screenshot-6.(png)
7. Extended Flamingo Inbound Message single page view with all the replies at the bottom screenshot-7.(png)
8. The modal window for posting a support ticket reply (Administrator view) screenshot-8.(png)
9. The modal window for posting a support ticket reply (User view) screenshot-9.(png)
10. Full support ticket page example screenshot-10.(png)

## Installation ##

Developry plugin installation process is standard and easy to follow, please let us know if you have any difficulties to use our plugins.

= Installation From Within WordPress =

1. Visit **Plugins > Add New**.
2. Search for **WP Customer Support**.
3. Install and activate the **WP Customer Support** plugin.
4. You will be redirected to a Welcome page with startup information.

= Manual Installation =

1. Upload the entire `help-desk-9-by-developry` folder to the `/wp-content/plugins/` directory.
2. Visit **Plugins**.
3. Activate the **WP Customer Support** plugin.
4. You will be redirected to a Welcome page with startup information.

= After Activation =

1. Visit the new **Developry** menu.
2. Click on the sub page for **WP Customer Support**.
3. Follow the additional instructions (in any) in the setup flow.

## Changelog ##

= 1.0.0 = 

Initial release and first commit into the WordPress.org SVN.

## Upgrade Notice ##
