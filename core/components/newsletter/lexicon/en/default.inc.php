<?php

	/**
	 * Newsletter
	 *
	 * Copyright 2014 by Oene Tjeerd de Bruin <info@oetzie.nl>
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

	$_lang['newsletter'] 									= 'Newsletter';
	$_lang['newsletter.desc'] 								= 'Change or create site-wide newsletters.';
	
	$_lang['area_newsletter']								= 'Newsletter';
	
	$_lang['setting_newsletter_cronjob']					= 'Cron job reminder';
	$_lang['setting_newsletter_cronjob_desc']				= 'Set to "Yes" if you have set up for the newsletter, by setting this to "Yes" the cronjob nofication is no longer displayed in the component newsletters.';
	$_lang['setting_newsletter_hash']						= 'Cron job hash';
	$_lang['setting_newsletter_hash_desc']					= 'This hash get send along with the cron job so that the newsletter cant be send by random people. Without this hash automatically send newsletters is not working.';
	$_lang['setting_newsletter_email']						= 'Newsletter sender';
	$_lang['setting_newsletter_email_desc']					= 'The e-mail address where the newsletter is sent of.';
	$_lang['setting_newsletter_name']						= 'Newsletter sender name';
	$_lang['setting_newsletter_name_desc']					= 'The name where the newsletter is sent of.';
	
	$_lang['newsletter.newsletter']							= 'Newsletter';
	$_lang['newsletter.newsletters']						= 'Newsletters';
	$_lang['newsletter.newsletters_desc']					= 'Here you can manage all newsletters of your MODX website. Check the newsletter well before you sent him, after sending you can only change the web version and will all members receive it with possible errors. Final newsletters are sent automatically every night at 00:00 because of the tough process of sending 100s emails.';
	$_lang['newsletter.newsletter_cronjob_desc']			= 'To send newsletters automatically, you can use a cron job, if you set up a cron job for newsletters or you don\'t want to use a cron job, you can turn off this notification via system settings.';
	$_lang['newsletter.newsletter_create']					= 'Create new newsletter';
	$_lang['newsletter.newsletter_update']					= 'Update newsletter';
	$_lang['newsletter.newsletter_remove']					= 'Delete newsletter';
	$_lang['newsletter.newsletter_remove_confirm']			= 'Are you sure you want to delete this newsletter?';
	$_lang['newsletter.newsletter_preview']					= 'View newsletter';
	$_lang['newsletter.newsletter_send']					= 'Send newsletter';
	$_lang['newsletter.newsletter_send_confirm']			= 'Are you sure you want to delete this newsletter? Check the newsletter well before you sent him, after sending you can only change the web version and will all members receive it with possible errors.';
	
	$_lang['newsletter.subscription']						= 'Subscription';
	$_lang['newsletter.subscriptions']						= 'Subscriptions';
	$_lang['newsletter.subscriptions_desc']					= 'Here you can manage all newsletter subscriptions of the newsletters.';
	$_lang['newsletter.subscription_create']				= 'Create new subscription';
	$_lang['newsletter.subscription_update']				= 'Update subscription';
	$_lang['newsletter.subscription_remove']				= 'Delete subscription';
	$_lang['newsletter.subscription_remove_confirm']		= 'Are you sure you want to delete this subscription?';
	
	$_lang['newsletter.group']								= 'Group';
	$_lang['newsletter.groups']								= 'Groups';
	$_lang['newsletter.groups_desc']						= 'Here you can manage all groups for the newsletter subscriptions of the newsletters.';
	$_lang['newsletter.group_create']						= 'Create new group';
	$_lang['newsletter.group_update']						= 'Update group';
	$_lang['newsletter.group_remove']						= 'Delete group';
	$_lang['newsletter.group_remove_confirm']				= 'Are you sure you want to delete this group? This will also delete all subscriptions of this group.';
	
	$_lang['newsletter.label_resource']						= 'Document ID';
	$_lang['newsletter.label_resource_desc']				= 'Select the ID of the document that serves as a newsletter, these can be found on the left.';
	$_lang['newsletter.label_email']						= 'E-mail address';
	$_lang['newsletter.label_email_desc']					= 'The e-mail address of the subscription.';
	$_lang['newsletter.label_name']							= 'Name';
	$_lang['newsletter.label_name_desc']					= 'The name of the subscription.';
	$_lang['newsletter.label_name_group_desc']				= 'The name of the group.';
	$_lang['newsletter.label_context']						= 'Context';
	$_lang['newsletter.label_context_desc']					= 'The context of the subscription.';
	$_lang['newsletter.label_description']					= 'Description';
	$_lang['newsletter.label_description_desc']				= 'A short description of the group.';
	$_lang['newsletter.label_active']						= 'Active';
	$_lang['newsletter.label_active_desc']					= '';
	$_lang['newsletter.label_groups']						= 'Group(s)';
	$_lang['newsletter.label_groups_desc']					= 'The group(s) of the subscription.';
	$_lang['newsletter.label_send']							= 'Send';
	$_lang['newsletter.label_send_desc']					= '';
	$_lang['newsletter.label_send_to']						= 'Send to';
	$_lang['newsletter.label_send_to_desc']					= 'The group(s) where the newsletter should be send to.';
	$_lang['newsletter.label_timing']						= 'Timing';
	$_lang['newsletter.label_timing_desc']					= 'Sending newsletters is a tough process, this may take tens of seconds or even minutes (depending on the number of subscriptions). Therefore, the newsletters are sent automatically every night at 00:00, check this option to send the newsletter immediately.';
	
	$_lang['newsletter.filter_context']						= 'Filter on context...';
	$_lang['newsletter.pending']							= 'Pending';
	$_lang['newsletter.resource_does_not_exists']			= 'There is no document with this ID.';
	
?>