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
	
	$_lang['newsletter.subscribe_confirm_desc']					= 'Your newsletter subscription is almost completed. To confirm your subscription click on the link that is sent to your e-mail address.';
	$_lang['newsletter.email_subscribe_confirm_title']			= 'Newsletter subscription | [[++site_name]]';
	$_lang['newsletter.email_subscribe_confirm_content']		= '<p>Thank you for your subscription for the newsletter of <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a>. To confirm your subscription you need to click on the following link. The moment you did this your subscription will be completed.<p><p><a href="[[!+newsletter.subscribe_url]]" title="Click here to confirm your subscription">Click here to confirm your subscription</a></p><p>Or copy-paste the following URL in your de volgende URL in de address bar of your browser: <a href="[[!+newsletter.subscribe_url]]">[[!+newsletter.subscribe_url]]</a></p>';
	
	$_lang['newsletter.subscribe_confirmed_desc']				= 'Your newsletter subscription is completed. You will receive the next newsletter in your inbox.';
	$_lang['newsletter.email_subscribe_confirmed_title']		= 'Newsletter subscription | [[++site_name]]';
	$_lang['newsletter.email_subscribe_confirmed_content']		= '<p>Thank you for your subscription for the newsletter of <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a>. Your newsletter subscription is completed, you will receive the next newsletter in your inbox.</p>';
	
	$_lang['newsletter.unsubscribe_confirmed_desc']				= 'Your newsletter unsubscription is completed. You will not receive any newsletters more in your inbox.';
	
	$_lang['newsletter.newsletter_online_version']				= 'If you cant read this message, then please click <a href="[[+newsletter.url]]" target="_blank" title="here">here</a>.';
	$_lang['newsletter.newsletter_anti_spam']					= 'Add <a href="mailto:[[++newsletter.email]]" target="_blank" title="[[++newsletter.email]]">[[++newsletter.email]]</a> to your contacts.';
	$_lang['newsletter.newsletter_footer'] 						= 'This email was sent to <a href="mailto:[[+subscribe.email]]" target="_blank" title="[[+subscribe.email]]">[[+subscribe.email]]</a> - If you want to unsubscribe you for our emails please click <a href="[[~[[++newsletter.page_unsubscribe]]? &scheme=`full`]]&email=[[+subscribe.email]]" target="_blank" title="here">here</a>.';
	$_lang['newsletter.view_website']							= 'Visit our website for more information.';
	
?>