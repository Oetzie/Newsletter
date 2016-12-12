<?php

	/**
	 * Newsletter
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <info@oetzie.nl>
	 *
	 * This file is part of Newsletter, a real estate property listings component
	 * for MODX Revolution.
	 *
	 * Newsletter is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Newsletter is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Newsletter; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */
	 
	$_lang['newsletter.form_subscribe_error']					= 'There is an error occurred during the subscribe process, please try again.';
	$_lang['newsletter.form_subscribe_error_confirm']			= 'There is an error occurred during the subscribe process, please try again.';
	$_lang['newsletter.form_unsubscribe_error']					= 'There is an error occurred during the unsubscribe process, please try again..';
	$_lang['newsletter.form_unsubscribe_error_confirm']			= 'There is an error occurred during the unsubscribe process, please try again.';
	
	$_lang['newsletter.email_subscribe_title']					= 'Newsletter subscription [[++site_name]]';
	$_lang['newsletter.email_subscribe_content']				= '<p>Thank you for your subscription for the newsletter of <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a>. To confirm your subscription click on the following. From that moment your subscription will be done.<p><p><a href="[[+newsletter_confirm_url]]" title="Click here to confirm your subscription">Click here to confirm your subscription</a></p><p>or copy-paste the next URL in you browser: <a href="[[+newsletter_confirm_url]]">[[+newsletter_confirm_url]]</a></p>';
	
	$_lang['newsletter.newsletter_online_version']				= 'If you cant read this message, then please click <a href="[[+newsletter.url]]" target="_blank" title="here">here</a>.';
	$_lang['newsletter.newsletter_anti_spam']					= 'Add <a href="mailto:[[++newsletter.email]]" target="_blank" title="[[++newsletter.email]]">[[++newsletter.email]]</a> to your contacts.';
	$_lang['newsletter.newsletter_footer'] 						= 'This email was sent to <a href="mailto:[[+subscribe.email]]" target="_blank" title="[[+subscribe.email]]">[[+subscribe.email]]</a> - If you want to unsubscribe you for Als u zich wilt uitschrijven voor onze advertising emails please click <a href="[[~[[++page.newsletter_unsubscribe]]? &scheme=`full`]]&email=[[+subscribe.email]]" target="_blank" title="here">here</a>.';
	$_lang['newsletter.view_website']							= 'Visit our website for more information.';
	
	/* Custom */
	
	$_lang['newsletter.list_test'] 								= 'Test';
	$_lang['newsletter.list_test_desc'] 						= 'The mailinglist with all the test subscriptions to test the newsletters.';
	$_lang['newsletter.list_default'] 							= 'Default';
	$_lang['newsletter.list_default_desc'] 						= 'The mailinglist with all the subscriptions from the website.';
	
?>