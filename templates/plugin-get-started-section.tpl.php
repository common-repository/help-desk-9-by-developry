<?php defined( 'ABSPATH' ) || exit; ?>
<h2>
	<?php echo HD9_NAME; ?>
</h2>
<h3>
	<strong>Usage</strong>
</h3>
<p>
	Create a new Contact Form 7 form and add both its shortcode and the WP Customer Support shortcode into the page body.
</p>
<pre><mark>[help-desk-9]
[contact-form-7 id="1" title="Contact Form 7"]</mark></pre>
<p>
	In addition, add the following to the Additional Settings in your Contact Form 7:
</p>
<pre><mark>flamingo_email: "[your-email]"
flamingo_name: "[your-name]"
flamingo_subject: "[your-subject]"</mark></pre>
<p>
	If you create a support page for a registered user (e.g Subscribers) you may want to have your Contact Form 7 Name and Email fields populated and read-only, e.g.
</p>
<pre><mark>[text* your-name default:user_display_name readonly placeholder "Full name*"]
[email* your-email default:user_email readonly placeholder "Email address*"]</mark></pre>
<p>
	<strong>Important:</strong> Currently there is a naming limitation filter for the fields that will show up on the front-end. You need to have the following keywords when you create the Contact Form 7 fields.
</p>
<pre><mark>input, title, subject, message, description, content, textarea</mark></pre>
<h3>
	<strong>Features</strong>
</h3>
<p>
	You can have your ticketing system working for the following different scenarios:
</p>
<ul>
	<li><strong>Protected</strong> – visible on a password protected page.</li>
	<li><strong>Privated</strong> – visible only for registered users.</li>
	<li><strong>Public</strong> – visible to all visitors (this way everyone will be able to see the conversation and post anonymously).</li>
</ul>
<p>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>wp-admin/admin.php?page=<?php echo HD9_WPORG_SLUG; ?>"  class="button button-secondary">Go to <?php echo HD9_NAME; ?> &rarr;</a>
</p>