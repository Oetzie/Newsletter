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

	$_lang['newsletter'] 											= 'Newsletter';
	$_lang['newsletter.desc'] 										= 'Change or create site-wide newsletters.';
	
	$_lang['area_newsletter']										= 'Newsletter';
	$_lang['area_default']											= 'Default';
	
	$_lang['setting_newsletter.cronjob']							= 'Cronjob reminder';
	$_lang['setting_newsletter.cronjob_desc']						= 'Set to "Yes" if you have set up for the newsletter, by setting this to "Yes" the cronjob notification is no longer displayed in the component newsletters.';
	$_lang['setting_newsletter.token']								= 'Cronjob token';
	$_lang['setting_newsletter.token_desc']							= 'This token needs to be send along with the cronjob so that the newsletter can not be send by random people. Without this token automatically send newsletters is not working.';
	$_lang['setting_newsletter.email']								= 'Newsletter sender';
	$_lang['setting_newsletter.email_desc']							= 'The e-mail address where the newsletter is send from.';
	$_lang['setting_newsletter.name']								= 'Newsletter sender name';
	$_lang['setting_newsletter.name_desc']							= 'The name where the newsletter is send from.';
	$_lang['setting_newsletter.template']							= 'Newsletter template';
	$_lang['setting_newsletter.template_desc']						= 'The ID of the template that get used as a newsletter, to separate templates use a comma.';
	$_lang['setting_newsletter.admin_groups']						= 'Admin usergroups';
	$_lang['setting_newsletter.admin_groups_desc']					= 'The usergroups that has access to the admin part of the newsletters, to separate usergroups use a comma.';
	
	$_lang['setting_newsletter.page_subscribe']						= 'Page "newsletter subscribe"';
	$_lang['setting_newsletter.page_subscribe_desc']				= 'The ID of the page that is the "newsletter subscribe" page.';
	$_lang['setting_newsletter.page_unsubscribe']					= 'Page "newsletter unsubscribe"';
	$_lang['setting_newsletter.page_unsubscribe_desc']				= 'The ID of the page that is the "newsletter unsubscribe" page.';

	$_lang['newsletter.newsletter']									= 'Newsletter';
	$_lang['newsletter.newsletters']								= 'Newsletters';
	$_lang['newsletter.newsletters_desc']							= 'Here you can manage all newsletter, a newsletter is actually a resource that get sent via e-mail to all registered persons. <strong>Note:</strong> Check the newsletter before you sent him, after sending you can not change him any more and he appears in alle the email inboxes of the subsciptions with any errors. Newsletters will be sent every night automatically at 01:00 due to the heavy process for the server sending multiple e-mails.';
	$_lang['newsletter.newsletter_cronjob_notice_desc']				= '<strong>Notice:</strong> To send newsletters automatically, you need to use a cronjob, if you set up a cronjob for newsletters, you can turn off this notification via the systemsettings.';
	$_lang['newsletter.newsletter_site_status_notice_desc']			= '<strong>Notice:</strong> The site status is offline, you can\'t send any newsletters because of this. You can set the site status at "online" via the systemsettings.';
	$_lang['newsletter.newsletter_create']							= 'Create new newsletter';
	$_lang['newsletter.newsletter_create_desc']						= 'To create a new newsletter, create first a normale resource and select him below to use as a newsletter. Make sure that you select a newsletter template.';
	$_lang['newsletter.newsletter_update']							= 'Update newsletter';
	$_lang['newsletter.newsletter_remove']							= 'Delete newsletter';
	$_lang['newsletter.newsletter_remove_confirm']					= 'Are you sure you want to delete this newsletter?';
	$_lang['newsletter.newsletter_preview']							= 'Nieuwsbrief example';
	$_lang['newsletter.newsletter_stats']							= 'Nieuwsbrief statistics';
	$_lang['newsletter.newsletter_stats_desc']						= 'The newsletter is send in total [[+total]] times.';
	$_lang['newsletter.newsletter_send_live']						= 'Send newsletter';
	$_lang['newsletter.newsletter_send_test']						= 'Send test newsletter';
	$_lang['newsletter.newsletter_cancel']							= 'Cancel newsletter';
	$_lang['newsletter.newsletter_cancel_confirm']					= 'Are you sure you want to cancel this newsletter?';

	$_lang['newsletter.subscription']								= 'Subscription';
	$_lang['newsletter.subscriptions']								= 'Subscriptions';
	$_lang['newsletter.subscriptions_desc']							= 'Here you can manage all subscriptions for the newsletters, the most subscriptions are subscribed by the website, but can also be imported or exported from other systems.';
	$_lang['newsletter.subscription_create']						= 'Create new subscription';
	$_lang['newsletter.subscription_update']						= 'Update subscription';
	$_lang['newsletter.subscription_remove']						= 'Delete subscription';
	$_lang['newsletter.subscription_remove_confirm']				= 'Are you sure you want to delete this subscription?';
	$_lang['newsletter.subscriptions_remove_selected']				= 'Delete selected subscriptions';
	$_lang['newsletter.subscriptions_remove_selected_confirm']		= 'Are you sure you want to delete the selected subscriptions?';
	$_lang['newsletter.subscriptions_confirm_selected']				= 'Confirm selected subscriptions';
	$_lang['newsletter.subscriptions_confirm_selected_confirm']		= 'Are you sure you want to confirm the selected subscriptions?';
	$_lang['newsletter.subscriptions_deconfirm_selected']			= 'De-confirm selected subscriptions';
	$_lang['newsletter.subscriptions_deconfirm_selected_confirm']	= 'Are you sure you want to de-confirm the selected subscriptions?';
	$_lang['newsletter.subscriptions_move_selected']				= 'Move selected subscriptions';
	$_lang['newsletter.subscriptions_move_selected_desc']			= 'Select the mailing list(s) to add or delete the subscriptions from.';
	$_lang['newsletter.subscriptions_import']						= 'Export subscriptions';
	$_lang['newsletter.subscriptions_import_desc']					= 'Select a CSV file to import subscriptions. It must be a valid CSV format';
	$_lang['newsletter.subscriptions_export']						= 'Import subscriptions';
	
	$_lang['newsletter.subscription_extra_create']					= 'Create extra field';
	$_lang['newsletter.subscription_extra_update']					= 'Update extra field';
	$_lang['newsletter.subscription_extra_remove']					= 'Delete extra field';
	$_lang['newsletter.subscription_extra_remove_confirm']			= 'Are you sure you want to delete this extra field?';
	
	$_lang['newsletter.list']										= 'List';
	$_lang['newsletter.lists']										= 'Lists';
	$_lang['newsletter.lists_desc']									= 'Here you can set all the lists for the subscriptions for sending newsletters. Lists can be set as a primary list. A primary list is a list where all subscriptions are placed in by default, the primary list can not be removed.';
	$_lang['newsletter.list_create']								= 'Create new list';
	$_lang['newsletter.list_update']								= 'Update list';
	$_lang['newsletter.list_remove']								= 'Delete list';
	$_lang['newsletter.list_remove_confirm']						= 'Are you sure you want to delete this list?';
	$_lang['newsletter.lists_remove_selected']						= 'Delete selected lists';
	$_lang['newsletter.lists_remove_selected_confirm']				= 'Are you sure you want to delete the selected lists?';
	$_lang['newsletter.list_import']								= 'Import list subscriptions';
	$_lang['newsletter.list_import_desc']							= 'Select a CSFV file to import the subscriptions to the list. It must be a valid CSV format.';
	$_lang['newsletter.list_export']								= 'Export list subscriptions';
	
	$_lang['newsletter.label_newsletter_resource']					= 'Resource';
	$_lang['newsletter.label_newsletter_resource_desc']				= 'Select a resource for the newsletter.';
	$_lang['newsletter.label_newsletter_hidden']					= 'Hidden newsletter';
	$_lang['newsletter.label_newsletter_hidden_desc']				= 'Hidden newsletter, hide this newsletter for the not \'Admin usergroups\'.';
	$_lang['newsletter.label_newsletter_send_date']					= 'Date';
	$_lang['newsletter.label_newsletter_send_date_desc']			= 'Select a date from when the newsletter needs to be sent.';
	$_lang['newsletter.label_newsletter_send_time']					= 'Time';
	$_lang['newsletter.label_newsletter_send_time_desc']			= 'Select a time when the newsletter needs to be sent.';
	$_lang['newsletter.label_newsletter_send_repeat']				= 'Repeat';
	$_lang['newsletter.label_newsletter_send_repeat_desc']			= 'The number of times that the newsletter needs to be sent, use "-1" for unlimited.';
	$_lang['newsletter.label_newsletter_send_days']					= 'Days';
	$_lang['newsletter.label_newsletter_send_days_desc']			= 'The days when the newsletter needs to be sent.';
	$_lang['newsletter.label_newsletter_send_lists']				= 'Send to list(s)';
	$_lang['newsletter.label_newsletter_send_lists_desc']			= 'The list(s) where the newsletter needs to be send to.';
	$_lang['newsletter.label_newsletter_send_emails']				= 'Send to e-mail address(es)';
	$_lang['newsletter.label_newsletter_send_emails_desc']			= 'The e-mail address(es) where the newsletter needs to send to, to separate e-mail addresses use a comma.';	
	$_lang['newsletter.label_newsletter_published']					= 'Published';
	$_lang['newsletter.label_newsletter_published_desc']			= '';
	$_lang['newsletter.label_newsletter_send_status']				= 'Status';
	$_lang['newsletter.label_newsletter_send_status_desc']			= '';
	$_lang['newsletter.label_newsletter_stats_newsletter']			= 'Newsletter [[+current]]';
	$_lang['newsletter.label_newsletter_stats_newsletter_desc']		= '';
	$_lang['newsletter.label_newsletter_stats_date']				= 'Date';
	$_lang['newsletter.label_newsletter_stats_date_desc']			= '';
	$_lang['newsletter.label_newsletter_stats_lists']				= 'List(s)';
	$_lang['newsletter.label_newsletter_stats_lists_desc']			= '';
	$_lang['newsletter.label_newsletter_stats_emails']				= 'E-mail address (total: [[+total]])';
	$_lang['newsletter.label_newsletter_stats_emails_desc']			= '';
	
	$_lang['newsletter.label_subscription_email']					= 'E-mail address';
	$_lang['newsletter.label_subscription_email_desc']				= 'The e-mail address of the subscription.';
	$_lang['newsletter.label_subscription_name']					= 'Name';
	$_lang['newsletter.label_subscription_name_desc']				= 'The name of the subscription.';
	$_lang['newsletter.label_subscription_context']					= 'Context';
	$_lang['newsletter.label_subscription_context_desc']			= 'The context of the subscription.';
	$_lang['newsletter.label_subscription_confirmed']				= 'Confirmed';
	$_lang['newsletter.label_subscription_confirmed_desc']			= '';
	$_lang['newsletter.label_subscription_lists']					= 'List(s)';
	$_lang['newsletter.label_subscription_lists_desc']				= 'The list(s) of the subscription.';
	$_lang['newsletter.label_subscriptions_lists']					= 'List(s)';
	$_lang['newsletter.label_subscriptions_lists_desc']				= 'The list(en) of the subscription(s).';
	$_lang['newsletter.label_subscription_move']					= 'Movingstype';
	$_lang['newsletter.label_subscription_move_desc']				= 'The type of the movement, this can be "add" or "remove".';
	
	$_lang['newsletter.label_extra_key']							= 'Key';
	$_lang['newsletter.label_extra_desc']							= 'The key of the extra field. The subscription info will available by the [+subscribe.key]] tags.';
	$_lang['newsletter.label_extra_content']						= 'Value';
	$_lang['newsletter.label_extra_content_desc']					= 'The value of the extra field.';
	
	$_lang['newsletter.label_list_name']							= 'Name';
	$_lang['newsletter.label_list_name_desc']						= 'The name of the list, this can be a lexicon key.';
	$_lang['newsletter.label_list_description']						= 'Description';
	$_lang['newsletter.label_list_description_desc']				= 'A short description of the list, this can be a lexicon key.';
	$_lang['newsletter.label_list_primary']							= 'Primary list';
	$_lang['newsletter.label_list_primary_desc']					= 'Primary list, in this list the subscriptions will be placed in by default.';
	$_lang['newsletter.label_list_hidden']							= 'Hidden list';
	$_lang['newsletter.label_list_hidden_desc']						= 'Hidden list, this list is hidden for the not \'Admin usergroups\'.';
	$_lang['newsletter.label_list_active']							= 'Active';
	$_lang['newsletter.label_list_active_desc']						= '';
	$_lang['newsletter.label_list_subscriptions']					= 'Subscriptions';
	$_lang['newsletter.label_list_subscriptions_desc']				= '';
	
	$_lang['newsletter.label_import_file']							= 'File';
	$_lang['newsletter.label_import_file_desc']						= 'Select a valid CSV file.';
	$_lang['newsletter.label_import_delimiter']						= 'Delimiter';
	$_lang['newsletter.label_import_delimiter_desc']				= 'The delimiter to separate the columns. Default is ";".';
	$_lang['newsletter.label_import_headers']						= 'First row are columns.';
	$_lang['newsletter.label_import_headers_desc']					= '';
	$_lang['newsletter.label_import_reset']							= 'Delete all current subscriptions.';
	$_lang['newsletter.label_import_reset_desc']					= '';
	$_lang['newsletter.label_import_list']							= 'List';
	$_lang['newsletter.label_import_list_desc']						= 'The list where all subscriptions needs to be import to.';
	$_lang['newsletter.label_export_list']							= 'List';
	$_lang['newsletter.label_export_list_desc']						= 'The list where all subscriptions needs to be export from.';

	$_lang['newsletter.filter_context']								= 'Filter at context...';
	$_lang['newsletter.filter_confirm']								= 'Filter at confirmation...';
	$_lang['newsletter.auto_refresh_grid']							= 'Refresh automatically';
	$_lang['newsletter.send']										= 'Send';
	$_lang['newsletter.monday']										= 'Mon';
	$_lang['newsletter.tuesday']									= 'Tue';
	$_lang['newsletter.wednesday']									= 'Wed';
	$_lang['newsletter.thursday']									= 'Thu';
	$_lang['newsletter.friday']										= 'Fri';
	$_lang['newsletter.saturday']									= 'Sat';
	$_lang['newsletter.sunday']										= 'Sun';
	$_lang['newsletter.newsletter_type_1']							= 'Test newsletter';
	$_lang['newsletter.newsletter_type_2']							= 'Definitive newsletter';
	$_lang['newsletter.newsletter_type_confirm']					= 'Are you sure you want to send this newsletter now? This is a pretty tough process, depending on the number of entries this may take awhile.';
	$_lang['newsletter.newsletter_status_0']						= 'Not send';
	$_lang['newsletter.newsletter_status_1']						= 'In queue';
	$_lang['newsletter.newsletter_status_2']						= 'Send';
	$_lang['newsletter.newsletter_send_detail']						= 'Send at <strong>{timestamp}</strong>.';
	$_lang['newsletter.newsletter_error_resource_id']				= 'The page that serves as a newsletter does not exist or has been removed.';
	$_lang['newsletter.newsletter_error_resource_template']			= 'The page that serves as a newsletter does not have the correct template.';
	$_lang['newsletter.newsletter_error_date']						= 'The send date can not be in the past. Choose another date or time.';
	$_lang['newsletter.newsletter_send_save']						= 'Success!';
	$_lang['newsletter.newsletter_send_save_desc']					= 'The newsletter is put in the queue to be sent.';
	$_lang['newsletter.newsletter_send_succes']						= 'Success!';
	$_lang['newsletter.newsletter_send_succes_desc']				= 'The newsletter is sent successfully.';
	$_lang['newsletter.newsletter_send_email_success']				= '[[+current]] of [[+total]]: [[+email]], sent.';
	$_lang['newsletter.newsletter_send_email_error']				= '[[+current]] of [[+total]]: [[+email]], not sent because an e-mail error.';
	$_lang['newsletter.newsletter_send_email_duplicate']			= '[[+current]] of [[+total]]: [[+email]], not sent because a double e-mail address.';
	$_lang['newsletter.newsletter_send_to_emails']					= 'Sending to individual email addresses.';
	$_lang['newsletter.newsletter_send_to_list']					= 'Sending to list "[[+name]]".';
	$_lang['newsletter.newsletter_send_error_desc']					= 'The newsletter could not be sent, try again.';
	$_lang['newsletter.newsletter_send_error_site_status_desc']		= 'The newsletter could not be sent, because the site status is "offline".';
	$_lang['newsletter.newsletter_send_error_resource_desc']		= 'The newsletter could not be sent, because the page that serves as a newsletter does not exist or has been removed.';
	$_lang['newsletter.newsletter_send_error_template_desc']		= 'The newsletter could not be sent, because the page that serves as a newsletter does not have the correct template..';
	$_lang['newsletter.newsletter_send_error_status_desc']			= 'The newsletter could not be sent, because the send status is not correct.';
	$_lang['newsletter.newsletter_send_error_date_desc']			= 'The newsletter could not be sent, because the send date has not arrived yet.';
	$_lang['newsletter.newsletter_send_error_repeat_desc']			= 'The newsletter could not be sent, because the newsletter has been sent to the permitted times.';
	$_lang['newsletter.newsletter_send_feedback']					= 'Newsletter "[[+pagetitle]]" sent to [[+total]] e-mail addresses.';
	$_lang['newsletter.subscription_general']						= 'General';
	$_lang['newsletter.subscription_general_desc']					= 'Here you can manage the subscription.';
	$_lang['newsletter.subscription_extra']							= 'Extra fields';
	$_lang['newsletter.subscription_extra_desc']					= 'Here you can manage the extra fields of the subscription.';
	$_lang['newsletter.subscription_confirmed']						= 'Confirmed';
	$_lang['newsletter.subscription_not_confirmed']					= 'Not confirmed';
	$_lang['newsletter.subscription_unsubscribed']					= 'Unsubscribed';
	$_lang['newsletter.subscription_add_list']						= 'Add to list(s)';
	$_lang['newsletter.subscription_remove_list']					= 'Delete from list(s)';
	$_lang['newsletter.subscription_extra_key_error_character']		= 'Entry detail key contains illegal characters. Define another key name.';
	$_lang['newsletter.subscription_extra_key_error_exists']		= 'Entry detail with this key already exists. Define another key name.';
	$_lang['newsletter.lists_remove_primary_list']					= 'This is a primary list and can\'t be deleted.';
	$_lang['newsletter.import_dir_failed']							= 'An error occurred while importing the subscriptions, the import folder could not be created.';
	$_lang['newsletter.import_valid_failed']						= 'Select a valid CSV file.';
	$_lang['newsletter.import_upload_failed']						= 'An error occurred while importing the subscriptions, the CSV file could not be uploaded.';
	$_lang['newsletter.import_read_failed']							= 'An error occurred while importing the subscriptions, the CSV file could not be read.';
	$_lang['newsletter.export_failed']								= 'An error occurred while exporting the subscriptions, try again.';
	$_lang['newsletter.export_dir_failed']							= 'An error occurred while exporting the subscriptions, the export folder could not be created.';
	
?>