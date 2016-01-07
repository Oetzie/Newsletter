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
	
	$_lang['setting_newsletter_cronjob']							= 'Cronjob reminder';
	$_lang['setting_newsletter_cronjob_desc']						= 'Set to "Yes" if you have set up for the newsletter, by setting this to "Yes" the cronjob notification is no longer displayed in the component newsletters.';
	$_lang['setting_newsletter_token']								= 'Cronjob token';
	$_lang['setting_newsletter_token_desc']							= 'This token needs to be send along with the cronjob so that the newsletter can not be send by random people. Without this token automatically send newsletters is not working.';
	$_lang['setting_newsletter_email']								= 'Newsletter sender';
	$_lang['setting_newsletter_email_desc']							= 'The e-mail address where the newsletter is send from.';
	$_lang['setting_newsletter_name']								= 'Newsletter sender name';
	$_lang['setting_newsletter_name_desc']							= 'The name where the newsletter is send from.';
	$_lang['setting_newsletter_template']							= 'Newsletter template';
	$_lang['setting_newsletter_template_desc']						= 'The ID of the template that get used as a newsletter, to separate templates use a comma.';
	$_lang['setting_newsletter_admin_groups']						= 'Admin usergroups';
	$_lang['setting_newsletter_admin_groups_desc']					= 'The usergroups that has access to the admin part of the newsletters, to separate usergroups use a comma.';
	
	$_lang['newslettersubscribe_snippet_param_desc']				= 'The URL parameter for the confirmation. Default is "token".';
	$_lang['newslettersubscribe_snippet_confirm_desc']				= 'If "Yes" there will be sent a confirmation email which must confirm the subscription.';
	$_lang['newsletterunsubscribe_snippet_param_desc']				= 'The URL parameter for the confirmation. Default is "token".';
	
	$_lang['newsletter.newsletter']									= 'Newsletter';
	$_lang['newsletter.newsletters']								= 'Newsletters';
	$_lang['newsletter.newsletters_desc']							= 'Here you can manage all newsletter, a newsletter is actually a resource that get sent via e-mail to all registered persons. <strong>Note:</strong> Check the newsletter before you sent him, after sending you can not change him any more and he appears in alle the email inboxes of the subsciptions with any errors. Newsletters will be sent every night automatically at 01:00 due to the heavy process for the server sending multiple e-mails.';
	$_lang['newsletter.newsletter_cronjob_desc']					= '<strong>Reminder:</strong> To send newsletters automatically, you need to use a cronjob, if you set up a cronjob for newsletters, you can turn off this notification via systemsettings.';
	$_lang['newsletter.newsletter_create']							= 'Create new newsletter';
	$_lang['newsletter.newsletter_update']							= 'Update newsletter';
	$_lang['newsletter.newsletter_remove']							= 'Delete newsletter';
	$_lang['newsletter.newsletter_remove_confirm']					= 'Are you sure you want to delete this newsletter?';
	$_lang['newsletter.newsletter_preview']							= 'View newsletter';
	$_lang['newsletter.newsletter_send']							= 'Send newsletter';
	$_lang['newsletter.newsletter_cancel']							= 'Cancel newsletter';
	$_lang['newsletter.newsletter_cancel_confirm']					= 'Are you sure you want to cancel this newsletter?';
	$_lang['newsletter.newsletter_create_desc']						= 'To create a new newsletter, create first a normale resource and select him below to use as a newsletter. Make sure that you select a newsletter template.';

	$_lang['newsletter.subscription']								= 'Subscription';
	$_lang['newsletter.subscriptions']								= 'Subscriptions';
	$_lang['newsletter.subscriptions_desc']							= 'Here you can manage all subscriptions for the newsletters, the most subscriptions are subscribed by the website, but can also be imported or exported from other systems.';
	$_lang['newsletter.subscription_create']						= 'Create new subscription';
	$_lang['newsletter.subscription_update']						= 'Update subscription';
	$_lang['newsletter.subscription_remove']						= 'Delete subscription';
	$_lang['newsletter.subscription_remove_confirm']				= 'Are you sure you want to delete this subscription?';
	$_lang['newsletter.subscription_remove_selected']				= 'Delete selected subscriptions';
	$_lang['newsletter.subscription_remove_selected_confirm']		= 'Are you sure you want to delete the selected subscriptions?';
	$_lang['newsletter.subscription_activate_selected']				= 'Confirm selected subscriptions';
	$_lang['newsletter.subscription_activate_selected_confirm']		= 'Are you sure you want to confirm the selected subscriptions?';
	$_lang['newsletter.subscription_deactivate_selected']			= 'De-confirm selected subscriptions';
	$_lang['newsletter.subscription_deactivate_selected_confirm']	= 'Are you sure you want to de-confirm the selected subscriptions?';
	$_lang['newsletter.subscription_move_selected']					= 'Move selected subscriptions';
	$_lang['newsletter.subscription_move_selected_desc']			= 'Select the mailing list(s) to add or delete the subscriptions from.';
	
	$_lang['newsletter.subscription_info']							= 'Subscription info';
	$_lang['newsletter.subscription_info_desc']						= 'Here you can manage all the subscription info, subscription info are extra values that you can assign to a subscription and can be used in a newsletter.';
	$_lang['newsletter.subscription_info_create']					= 'Create subscription info';
	$_lang['newsletter.subscription_info_update']					= 'Update subscription info';
	$_lang['newsletter.subscription_info_remove']					= 'Delete subscription info';
	$_lang['newsletter.subscription_info_remove_confirm']			= 'Are you sure you want to delete this subscription info?';
	
	$_lang['newsletter.list']										= 'Mailing list';
	$_lang['newsletter.lists']										= 'Mailing list';
	$_lang['newsletter.lists_desc']									= 'Here you can set all the mailing lists for the subscriptions for sending newsletters. Mailing lists can be set as a primary mailing list. A primary mailing list is a mailing list where all subscriptions are placed in by default, the primary mailing list can not be removed.';
	$_lang['newsletter.list_create']								= 'Create new mailing list';
	$_lang['newsletter.list_update']								= 'Update mailing list';
	$_lang['newsletter.list_remove']								= 'Delete mailing list';
	$_lang['newsletter.list_remove_confirm']						= 'Are you sure you want to delete this mailing list?';
	$_lang['newsletter.list_remove_selected']						= 'Delete selected mailing lists';
	$_lang['newsletter.list_remove_selected_confirm']				= 'Are you sure you want to delete the selected mailing lists?';
	$_lang['newsletter.list_import']								= 'Import subscriptions';
	$_lang['newsletter.list_import_desc']							= 'Select a CSF file to import the subscriptions to the mailing list. It must be a valid CSV format.';
	$_lang['newsletter.list_export']								= 'Export subscriptions';
	
	$_lang['newsletter.label_resource']								= 'Resource';
	$_lang['newsletter.label_resource_desc']						= 'Select a resource for the newsletter.';
	$_lang['newsletter.label_hidden_newsletter']					= 'Hidden newsletter';
	$_lang['newsletter.label_hidden_newsletter_desc']				= 'Hidden newsletter, hide this newsletter for the not \'Admin usergroups\'.';
	$_lang['newsletter.label_email']								= 'E-mail address';
	$_lang['newsletter.label_email_desc']							= 'The e-mail address of the subscription.';
	$_lang['newsletter.label_name']									= 'Name';
	$_lang['newsletter.label_name_desc']							= 'The name of the subscription.';
	$_lang['newsletter.label_context']								= 'Context';
	$_lang['newsletter.label_context_desc']							= 'The context of the subscription.';
	$_lang['newsletter.label_primary_list']							= 'Primary mailing list';
	$_lang['newsletter.label_primary_list_desc']					= 'Primary mailing list, in this mailing list are all subscriptions placed in by default.';
	$_lang['newsletter.label_hidden_list']							= 'Hidden mailing list';
	$_lang['newsletter.label_hidden_list_desc']						= 'Hidden mailing list, hide this mailing list for the not \'Admin usergroups\'.';
	$_lang['newsletter.label_list_name']							= 'Name';
	$_lang['newsletter.label_list_name_desc']						= 'The name of the mailing list.';
	$_lang['newsletter.label_list_description']						= 'Description';
	$_lang['newsletter.label_list_description_desc']				= 'A short description of the mailing list.';
	$_lang['newsletter.label_active']								= 'Active';
	$_lang['newsletter.label_active_desc']							= '';
	$_lang['newsletter.label_confirmed']							= 'Comfirmed';
	$_lang['newsletter.label_confirmed_desc']						= '';
	$_lang['newsletter.label_subscriptions']						= 'Subscriptions';
	$_lang['newsletter.label_subscriptions_desc']					= '';
	$_lang['newsletter.label_lists']								= 'Mailing list(s)';
	$_lang['newsletter.label_lists_desc']							= 'The mailing list(s) of the subscription.';
	$_lang['newsletter.label_lists_subscriptions']					= 'Mailing list(s)';
	$_lang['newsletter.label_lists_subscriptions_desc']				= 'The mailing list(s) of the subscription(s).';
	$_lang['newsletter.label_published']							= 'Published';
	$_lang['newsletter.label_published_desc']						= '';
	$_lang['newsletter.label_send']									= 'Send';
	$_lang['newsletter.label_send_desc']							= '';
	$_lang['newsletter.label_send_status']							= 'Status';
	$_lang['newsletter.label_send_status_desc']						= '';
	$_lang['newsletter.label_send_at']								= 'Send newsletter at';
	$_lang['newsletter.label_send_at_desc']							= 'Choose a moment when the newsletter needs to be send.';
	$_lang['newsletter.label_send_date']							= 'Send at';
	$_lang['newsletter.label_send_date_desc']						= 'Select a date from when the newsletter needs to be send, from this date the newsletter will be send 01:00 automaticly.';
	$_lang['newsletter.label_send_repeat']							= 'Number of times to send';
	$_lang['newsletter.label_send_repeat_desc']						= 'The number of times to send the newsletter, for unlimited use "0".';
	$_lang['newsletter.label_send_interval']						= 'To send every few days';
	$_lang['newsletter.label_send_interval_desc']					= 'The number of days between sending the newsletter from the first send date. 7 days is every week, 14 days is every to weeks etc.';
	$_lang['newsletter.label_send_to_lists']						= 'Send to mailing list(s)';
	$_lang['newsletter.label_send_to_lists_desc']					= 'The mailing list(s) where the newsletter needs to be send.';
	$_lang['newsletter.label_send_to_emails']						= 'Send to the e-mail address(es)';
	$_lang['newsletter.label_send_to_emails_desc']					= 'The e-mail address(es) where the newsletter needs to send to, to separate e-mail addresses use a comma.';	
	$_lang['newsletter.label_import_file']							= 'File';
	$_lang['newsletter.label_import_file_desc']						= 'Select a valid CSV file.';
	$_lang['newsletter.label_delimiter']							= 'Delimiter';
	$_lang['newsletter.label_delimiter_desc']						= 'The delimiter to separate the columns. Default is ";".';
	$_lang['newsletter.label_headers']								= 'First row column titles.';
	$_lang['newsletter.label_headers_desc']							= '';
	$_lang['newsletter.label_move']									= 'Movement type';
	$_lang['newsletter.label_move_desc']							= 'The type of the movement, this can be add or remove.';
	$_lang['newsletter.label_info_key']								= 'Key';
	$_lang['newsletter.label_info_key_desc']						= 'The key for the subscription info. The subscription info will be avaible by the [[+subscribe_key]] tags.';
	$_lang['newsletter.label_info_content']							= 'Value';
	$_lang['newsletter.label_info_content_desc']					= 'The value for the subscription info.';
	$_lang['newsletter.filter_context']								= 'Filter at context...';
	$_lang['newsletter.filter_confirm']								= 'Filter at confirmation...';
	$_lang['newsletter.extra_settings']								= 'Extra settings';
	$_lang['newsletter.send_immediately']							= 'This moment';
	$_lang['newsletter.send_timestamp']								= 'Another moment';
	$_lang['newsletter.newsletter_status_pending']					= 'In queue';
	$_lang['newsletter.newsletter_status_send']						= 'Send';
	$_lang['newsletter.newsletter_status_notsend']					= 'Not send';
	$_lang['newsletter.newsletter_send_detail']						= 'Send at <strong>{timestamp}</strong>.';
	$_lang['newsletter.confirmed']									= 'Confirmed';
	$_lang['newsletter.notconfirmed']								= 'Not confirmed';
	$_lang['newsletter.resource_does_not_exists']					= 'The resource of the newsletter does not exists or is deleted.';
	$_lang['newsletter.resource_template']							= 'The resource of the newsletter does not have a newsletter template.';
	$_lang['newsletter.newsletter_send_succes']						= 'Success!';
	$_lang['newsletter.newsletter_send_succes_desc']				= 'The newsletter is send successfully.';
	$_lang['newsletter.newsletter_send_failed_desc']				= 'The newsletter could not be send, try again.';
	$_lang['newsletter.newsletter_send_failed_resource_desc']		= 'The newsletter could not be send, because the selected resource does not exists or is removed.';
	$_lang['newsletter.newsletter_send_failed_template_desc']		= 'The newsletter could not be send, because the selected resource does not have a newsletter template.';
	$_lang['newsletter.lists_import_dir_failed']					= 'An error occurred while importing the subscriptions, the import directory could not be created.';
	$_lang['newsletter.lists_import_valid_failed']					= 'Select a valid CSV file..';
	$_lang['newsletter.lists_import_upload_failed']					= 'An error occurred while importing the subscriptions, the CSV could not be uploaded.';
	$_lang['newsletter.lists_import_read_failed']					= 'An error occurred while importing the subscriptions, the CSV could not be not be read.';
	$_lang['newsletter.lists_export_failed']						= 'An error occurred while exporting the subscriptions, try again.';
	$_lang['newsletter.lists_export_dir_failed']					= 'An error occurred while exporting the subscriptions, the import directory could not be created.';
	$_lang['newsletter.lists_remove_primary_list']					= 'This is the primary mailing list and could not be deleted.';
	$_lang['newsletter.activate_selected']							= 'Activate selected';
	$_lang['newsletter.deactivate_selected']						= 'De-activate selected';
	$_lang['newsletter.remove_selected']							= 'Delete selected';
	$_lang['newsletter.confirm_selected']							= 'Confirm selected';
	$_lang['newsletter.deconfirm_selected']							= 'De-confirm selected';
	$_lang['newsletter.move_selected']								= 'Move selected';
	$_lang['newsletter.add']										= 'Add';
	$_lang['newsletter.remove']										= 'Remove';
	$_lang['clientsettings.setting_error_character']				= 'Subscription info contains forbidden characters. Please specify another keyname.';
	$_lang['clientsettings.setting_error_exists']					= 'Subscription info with that key already exists. Please specify another keyname.';

?>