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

	$_lang['newsletter'] 											= 'Nieuwsbrief';
	$_lang['newsletter.desc'] 										= 'Wijzig of maak site-brede nieuwsbrieven.';
	
	$_lang['area_newsletter']										= 'Nieuwsbrief';
	
	$_lang['setting_newsletter.cronjob']							= 'Cronjob herinnering';
	$_lang['setting_newsletter.cronjob_desc']						= 'Zet deze instelling op "Ja" als je een cronjob hebt ingesteld voor de nieuwsbrief, door deze instelling op "Ja" te zetten word de cronjob waarschuwing niet meer getoond in de nieuwsbrieven component.';
	$_lang['setting_newsletter.token']								= 'Cronjob token';
	$_lang['setting_newsletter.token_desc']							= 'Deze token dient met de cronjob mee gestuurd te worden zodat de nieuwsbrief niet zomaar verstuurd kan worden door willekeurige personen. Zonder deze token werkt het automatisch versturen van de nieuwsbrieven niet.';
	$_lang['setting_newsletter.email']								= 'Nieuwsbrief afzender';
	$_lang['setting_newsletter.email_desc']							= 'Het e-mail adres waarmee de nieuwsbrief verstuurd wordt.';
	$_lang['setting_newsletter.name']								= 'Nieuwsbrief afzender naam';
	$_lang['setting_newsletter.name_desc']							= 'De naam waarmee de nieuwsbrief verstuurd wordt.';
	$_lang['setting_newsletter.template']							= 'Nieuwsbrief template';
	$_lang['setting_newsletter.template_desc']						= 'De ID van de template die als nieuwsbrief word gebruikt. Meerdere templates scheiden met een komma.';
	$_lang['setting_newsletter.admin_groups']						= 'Admin gebruikersgroepen';
	$_lang['setting_newsletter.admin_groups_desc']					= 'De gebruikersgroepen die toegang hebben tot de admin gedeelte van de nieuwsbrieven. Meerdere gebruikersgroepen scheiden met een komma.';
	
	$_lang['newslettersubscribe_snippet_param_desc']				= 'De URL parameter voor de bevestiging. Standaard is "token".';
	$_lang['newslettersubscribe_snippet_confirm_desc']				= 'Indien "Ja" zal er een bevestigingsmail gestuurd worden die de inschrijver dient te bevestigen.';
	$_lang['newsletterunsubscribe_snippet_param_desc']				= 'De URL parameter voor de bevestiging. Standaard is "token".';
	
	$_lang['newsletter.newsletter']									= 'Nieuwsbrief';
	$_lang['newsletter.newsletters']								= 'Nieuwsbrieven';
	$_lang['newsletter.newsletters_desc']							= 'Hier kun je alle nieuwsbrief beheren, een nieuwsbrief is eigenlijk een pagina die verstuurd word via de e-mail naar alle ingeschreven personen. <strong>Let op:</strong> Controleer de nieuwsbrief goed voordat je hem verstuurd, na het versturen kun je hem niet meer wijzigen en verschijnt hij bij alle ingeschreven personen in de e-mail inbox met eventuele fouten. Nieuwsbrieven worden iedere nacht om 01:00 automatisch verstuurd in verband met het zware proces voor de server van het versturen van meerdere e-mails.';
	$_lang['newsletter.newsletter_cronjob_desc']					= '<strong>Herinnering:</strong> Om nieuwsbrieven automatisch te kunnen versturen dien je gebruik maken van een cronjob, indien je een cronjob voor de nieuwsbrieven hebt ingesteld kun je deze melding uitzetten via systeeminstellingen.';
	$_lang['newsletter.newsletter_create']							= 'Nieuwe nieuwsbrief';
	$_lang['newsletter.newsletter_update']							= 'Nieuwsbrief wijzigen';
	$_lang['newsletter.newsletter_remove']							= 'Nieuwsbrief verwijderen';
	$_lang['newsletter.newsletter_remove_confirm']					= 'Weet je zeker dat je deze nieuwsbrief wilt verwijderen?';
	$_lang['newsletter.newsletter_preview']							= 'Nieuwsbrief bekijken';
	$_lang['newsletter.newsletter_send']							= 'Nieuwsbrief versturen';
	$_lang['newsletter.newsletter_cancel']							= 'Nieuwsbrief annuleren';
	$_lang['newsletter.newsletter_cancel_confirm']					= 'Weet je zeker dat je deze nieuwsbrief wilt annuleren?';
	$_lang['newsletter.newsletter_create_desc']						= 'Om een nieuwe nieuwsbrief te maken, maak je eerst een normale pagina aan die je hier vervolgens selecteert om als nieuwsbrief te dienen. Zorg er wel voor dat je een goede nieuwsbrief template selecteert.';

	$_lang['newsletter.subscription']								= 'Inschrijving';
	$_lang['newsletter.subscriptions']								= 'Inschrijvingen';
	$_lang['newsletter.subscriptions_desc']							= 'Hier kun je alle inschrijvingen instellen voor de nieuwsbrieven, deze inschrijvingen schrijven zich over het algemeen in via de website maar kunnen ook geïmporteerd of geëxporteerd worden vanuit andere systemen.';
	$_lang['newsletter.subscription_create']						= 'Nieuwe inschrijving';
	$_lang['newsletter.subscription_update']						= 'Inschrijving wijzigen';
	$_lang['newsletter.subscription_remove']						= 'Inschrijving verwijderen';
	$_lang['newsletter.subscription_remove_confirm']				= 'Weet je zeker dat je deze inschrijving wilt verwijderen?';
	$_lang['newsletter.subscription_remove_selected']				= 'Geselecteerde inschrijvingen verwijderen';
	$_lang['newsletter.subscription_remove_selected_confirm']		= 'Weet je zeker dat je de geselecteerde inschrijvingen wilt verwijderen?';
	$_lang['newsletter.subscription_activate_selected']				= 'Geselecteerde inschrijvingen bevestigen';
	$_lang['newsletter.subscription_activate_selected_confirm']		= 'Weet je zeker dat je de geselecteerde inschrijvingen wilt bevestigen?';
	$_lang['newsletter.subscription_deactivate_selected']			= 'Geselecteerde inschrijvingen de-bevestigen';
	$_lang['newsletter.subscription_deactivate_selected_confirm']	= 'Weet je zeker dat je de geselecteerde inschrijvingen wilt de-bevestigen?';
	$_lang['newsletter.subscription_move_selected']					= 'Geselecteerde inschrijvingen verplaatsen';
	$_lang['newsletter.subscription_move_selected_desc']			= 'Selecteer de mailinglijst(en) waar de inschrijvingen aan toegevoegd of uit verwijderd moeten worden.';
	
	$_lang['newsletter.subscription_info']							= 'Inschrijving info';
	$_lang['newsletter.subscription_info_desc']						= 'Hier kun je de inschrijving info wijzigen, inschrijving info zijn extra waardes die aan een inschrijving gekoppeld zijn die gebruikt kunnen worden in de nieuwsbrieven.';
	$_lang['newsletter.subscription_info_create']					= 'Nieuwe inschrijving info';
	$_lang['newsletter.subscription_info_update']					= 'Inschrijving info wijzigen';
	$_lang['newsletter.subscription_info_remove']					= 'Inschrijving info verwijderen';
	$_lang['newsletter.subscription_info_remove_confirm']			= 'Weet je zeker dat je deze inschrijving info wilt verwijderen?';
	
	$_lang['newsletter.list']										= 'Mailinglijst';
	$_lang['newsletter.lists']										= 'Mailinglijsten';
	$_lang['newsletter.lists_desc']									= 'Hier kun je alle mailinglijsten voor de inschrijvingen instellen voor het versturen van nieuwsbrieven. Mailinglijsten kunnen ingesteld zijn als een primaire mailinglijst. Een primaire mailinglijst is een mailinglijst waar standaard alle inschrijvingen in geplaatst worden, deze primaire mailinglijst kan dan ook niet verwijderd worden.';
	$_lang['newsletter.list_create']								= 'Nieuwe mailinglijst';
	$_lang['newsletter.list_update']								= 'Mailinglijst wijzigen';
	$_lang['newsletter.list_remove']								= 'Mailinglijst verwijderen';
	$_lang['newsletter.list_remove_confirm']						= 'Weet je zeker dat je deze mailinglijst wilt verwijderen?';
	$_lang['newsletter.list_remove_selected']						= 'Geselecteerde mailinglijsten verwijderen';
	$_lang['newsletter.list_remove_selected_confirm']				= 'Weet je zeker dat je de geselecteerde mailinglijsten wilt verwijderen?';
	$_lang['newsletter.list_import']								= 'Inschrijvingen importeren';
	$_lang['newsletter.list_import_desc']							= 'Selecteer een CSV bestand om inschrijvingen in mailinglijsten te importeren. Het moet een geldig CSV formaat zijn.';
	$_lang['newsletter.list_export']								= 'Inschrijvingen exporteren';
	
	$_lang['newsletter.label_resource']								= 'Pagina';
	$_lang['newsletter.label_resource_desc']						= 'Selecteer de pagina die als nieuwsbrief dient.';
	$_lang['newsletter.label_hidden_newsletter']					= 'Verborgen nieuwsbrief';
	$_lang['newsletter.label_hidden_newsletter_desc']				= 'Verborgen nieuwsbrief, deze nieuwsbrief verbergen voor de niet \'Admin gebruikersgroepen\'.';
	$_lang['newsletter.label_email']								= 'E-mailadres';
	$_lang['newsletter.label_email_desc']							= 'Het e-mailadres van de inschrijving.';
	$_lang['newsletter.label_name']									= 'Naam';
	$_lang['newsletter.label_name_desc']							= 'De naam van de inschrijving.';
	$_lang['newsletter.label_context']								= 'Context';
	$_lang['newsletter.label_context_desc']							= 'De context van de inschrijving.';
	$_lang['newsletter.label_primary_list']							= 'Primaire mailinglijst';
	$_lang['newsletter.label_primary_list_desc']					= 'Primaire mailinglijst, in deze mailinglijst worden standaard alle inschrijvingen in geplaatst.';
	$_lang['newsletter.label_hidden_list']							= 'Verborgen mailinglijst';
	$_lang['newsletter.label_hidden_list_desc']						= 'Verborgen mailinglijst, deze mailinglijst verbergen voor de niet \'Admin gebruikersgroepen\'.';
	$_lang['newsletter.label_list_name']							= 'Naam';
	$_lang['newsletter.label_list_name_desc']						= 'De naam van de mailinglijst.';
	$_lang['newsletter.label_list_description']						= 'Beschrijving';
	$_lang['newsletter.label_list_description_desc']				= 'Een korte beschrijving van de mailinglijst.';
	$_lang['newsletter.label_active']								= 'Actief';
	$_lang['newsletter.label_active_desc']							= '';
	$_lang['newsletter.label_confirmed']							= 'Bevestigd';
	$_lang['newsletter.label_confirmed_desc']						= '';
	$_lang['newsletter.label_subscriptions']						= 'Inschrijvingen';
	$_lang['newsletter.label_subscriptions_desc']					= '';
	$_lang['newsletter.label_lists']								= 'Mailinglijst(en)';
	$_lang['newsletter.label_lists_desc']							= 'De mailinglijst(en) van de inschrijving.';
	$_lang['newsletter.label_lists_subscriptions']					= 'Mailinglijst(en)';
	$_lang['newsletter.label_lists_subscriptions_desc']				= 'De mailinglijst(en) van de inschrijving(en).';
	$_lang['newsletter.label_published']							= 'Gepubliceerd';
	$_lang['newsletter.label_published_desc']						= '';
	$_lang['newsletter.label_send']									= 'Verstuurd';
	$_lang['newsletter.label_send_desc']							= '';
	$_lang['newsletter.label_send_status']							= 'Status';
	$_lang['newsletter.label_send_status_desc']						= '';
	$_lang['newsletter.label_send_at']								= 'Nieuwsbrief versturen op';
	$_lang['newsletter.label_send_at_desc']							= 'Kies een moment waarop je de nieuwsbrief wilt versturen.';
	$_lang['newsletter.label_send_date']							= 'Versturen vanaf';
	$_lang['newsletter.label_send_date_desc']						= 'Selecteer een datum vanaf wanneer de nieuwsbrief verstuurd moet worden, vanaf die datum word de nieuwsbrief om 01:00 automatisch verstuurd.';
	$_lang['newsletter.label_send_repeat']							= 'Aantal keer versturen';
	$_lang['newsletter.label_send_repeat_desc']						= 'Het aantal keer dat de nieuwsbrief verstuurd moet worden, voor oneindig gebruik "0".';
	$_lang['newsletter.label_send_interval']						= 'Om de zoveel dagen versturen';
	$_lang['newsletter.label_send_interval_desc']					= 'Het aantal dagen wat er tussen het versturen van de nieuwsbrief moet zitten vanaf de eerste verzend datum. 7 dagen is elke week, 14 dagen is elke twee week etc.';
	$_lang['newsletter.label_send_to_lists']						= 'Versturen naar de mailinglijst(en)';
	$_lang['newsletter.label_send_to_lists_desc']					= 'De mailinglijst(en) waar de nieuwsbrief naar gestuurd moet worden.';
	$_lang['newsletter.label_send_to_emails']						= 'Versturen naar de e-mailadres(sen)';
	$_lang['newsletter.label_send_to_emails_desc']					= 'De e-mailadres(sen) waar de nieuwsbrief naar gestuurd moet worden, e-mailadressen scheiden met een komma.';	
	$_lang['newsletter.label_import_file']							= 'Bestand';
	$_lang['newsletter.label_import_file_desc']						= 'Selecteer een geldig CSV bestand.';
	$_lang['newsletter.label_delimiter']							= 'Scheidingsteken';
	$_lang['newsletter.label_delimiter_desc']						= 'Het scheidingsteken waarmee kolommen gescheiden worden. Standaard is ";".';
	$_lang['newsletter.label_headers']								= 'Eerste rij kolom titels.';
	$_lang['newsletter.label_headers_desc']							= '';
	$_lang['newsletter.label_move']									= 'Verplaatsingstype';
	$_lang['newsletter.label_move_desc']							= 'De type van de verplaatsing, dit kan toevoegen of verwijderen zijn.';
	$_lang['newsletter.label_info_key']								= 'Sleutel';
	$_lang['newsletter.label_info_key_desc']						= 'De sleutel voor de inschrijving info. De inschrijving info zal beschikbaar zijn via de [[+subscribe_sleutel]] tags.';
	$_lang['newsletter.label_info_content']							= 'Waarde';
	$_lang['newsletter.label_info_content_desc']					= 'De waarde voor de inschrijving info.';
	$_lang['newsletter.filter_context']								= 'Filter op context...';
	$_lang['newsletter.filter_confirm']								= 'Filter op bevestiging...';
	$_lang['newsletter.extra_settings']								= 'Extra instellingen';
	$_lang['newsletter.send_immediately']							= 'Dit moment';
	$_lang['newsletter.send_timestamp']								= 'Een ander moment kiezen';
	$_lang['newsletter.newsletter_status_pending']					= 'In wachtrij';
	$_lang['newsletter.newsletter_status_send']						= 'Verstuurd';
	$_lang['newsletter.newsletter_status_notsend']					= 'Niet verstuurd';
	$_lang['newsletter.newsletter_send_detail']						= 'Verstuurd op <strong>{timestamp}</strong>.';
	$_lang['newsletter.confirmed']									= 'Bevestigd';
	$_lang['newsletter.notconfirmed']								= 'Niet bevestigd';
	$_lang['newsletter.resource_does_not_exists']					= 'De pagina die als nieuwsbrief dient bestaat niet of is verwijderd.';
	$_lang['newsletter.resource_template']							= 'De pagina die als nieuwsbrief dient heeft niet de juiste template.';
	$_lang['newsletter.newsletter_send_succes']						= 'Succes!';
	$_lang['newsletter.newsletter_send_succes_desc']				= 'De nieuwsbrief is succesvol verstuurd.';
	$_lang['newsletter.newsletter_send_failed_desc']				= 'De nieuwsbrief kon niet verstuurd worden, probeer het nog een keer.';
	$_lang['newsletter.newsletter_send_failed_resource_desc']		= 'De nieuwsbrief kon niet verstuurd worden, omdat de pagina die als nieuwsbrief dient niet bestaat of verwijderd is.';
	$_lang['newsletter.newsletter_send_failed_template_desc']		= 'De nieuwsbrief kon niet verstuurd worden, omdat de pagina die als nieuwsbrief dient niet de juiste template heeft.';
	$_lang['newsletter.lists_import_dir_failed']					= 'Er is een fout opgetreden tijdens het importeren van de inschrijvingen, de import folder kon niet aangemaakt worden.';
	$_lang['newsletter.lists_import_valid_failed']					= 'Selecteer een geldig CSV bestand.';
	$_lang['newsletter.lists_import_upload_failed']					= 'Er is een fout opgetreden tijdens het importeren van de inschrijvingen, het CSV bestand kon niet geüpload worden.';
	$_lang['newsletter.lists_import_read_failed']					= 'Er is een fout opgetreden tijdens het importeren van de inschrijvingen, het CSV bestand kon niet gelezen worden.';
	$_lang['newsletter.lists_export_failed']						= 'Het exporteren van de inschrijvingen is mislukt, probeer het nog eens.';
	$_lang['newsletter.lists_export_dir_failed']					= 'Er is een fout opgetreden tijdens het exporteren van de inschrijvingen, de export folder kon niet aangemaakt worden.';
	$_lang['newsletter.lists_remove_primary_list']					= 'Dit is de primaire mailinglijst en kan niet verwijderd worden';
	$_lang['newsletter.activate_selected']							= 'Geselecteerden activeren';
	$_lang['newsletter.deactivate_selected']						= 'Geselecteerden deactiveren';
	$_lang['newsletter.remove_selected']							= 'Geselecteerden verwijderen';
	$_lang['newsletter.confirm_selected']							= 'Geselecteerden bevestigen';
	$_lang['newsletter.deconfirm_selected']							= 'Geselecteerden de-bevestigen';
	$_lang['newsletter.move_selected']								= 'Geselecteerden verplaatsen';
	$_lang['newsletter.add']										= 'Toevoegen';
	$_lang['newsletter.remove']										= 'Verwijderen';
	$_lang['newsletter.info_key_error_character']					= 'Inschrijving info sleutel bevat niet toegestane tekens. Definieer een andere sleutelnaam.';
	$_lang['newsletter.info_key_error_exists']						= 'Inschrijving info met deze sleutel bestaat reeds. Definieer een andere sleutelnaam';

	$_lang['newsletter.subscribe_error']							= 'Er is een fout opgetreden tijdens het inschrijven, probeer het nog een keer.';
	$_lang['newsletter.subscribe_error_confirm']					= 'Er is een fout opgetreden tijdens het inschrijven, probeer het nog een keer.';
	$_lang['newsletter.unsubscribe_error']							= 'Er is een fout opgetreden tijdens het uitschrijven, probeer het nog een keer.';
	$_lang['newsletter.unsubscribe_error_confirm']					= 'Er is een fout opgetreden tijdens het uitschrijven, probeer het nog een keer.';
	
?>