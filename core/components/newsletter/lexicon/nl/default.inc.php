<?php

	/**
	 * Newsletter
	 *
	 * Copyright 2017 by Oene Tjeerd de Bruin <modx@oetzie.nl>
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
	$_lang['newsletter.desc'] 										= 'Wijzig of maak nieuwsbrieven.';
	
	$_lang['area_newsletter']										= 'Nieuwsbrief';
	
	$_lang['setting_newsletter.branding_url']              			= 'Branding';
	$_lang['setting_newsletter.branding_url_desc']         			= 'De URL waar de branding knop heen verwijst, indien leeg wordt de branding knop niet getoond.';
    $_lang['setting_newsletter.branding_url_help']         			= 'Branding (help)';
    $_lang['setting_newsletter.branding_url_help_desc']    			= 'De URL waar de branding help knop heen verwijst, indien leeg wordt de branding help knop niet getoond.';
	$_lang['setting_newsletter.cronjob']							= 'Cronjob herinnering';
	$_lang['setting_newsletter.cronjob_desc']						= 'Zet deze instelling op "Ja" als er een cronjob is ingesteld voor de nieuwsbrieven, door deze instelling op "Ja" te zetten word er geen cronjob waarschuwing meer getoond.';
	$_lang['setting_newsletter.token']								= 'Cronjob token';
	$_lang['setting_newsletter.token_desc']							= 'Deze token dient met de cronjob mee gestuurd te worden zodat de nieuwsbrief niet zomaar verstuurd kan worden door willekeurige personen. Zonder deze token werkt het automatisch versturen van de nieuwsbrieven niet.';
	$_lang['setting_newsletter.email']								= 'Nieuwsbrief afzender';
	$_lang['setting_newsletter.email_desc']							= 'Het e-mail adres waarmee de nieuwsbrief verstuurd wordt.';
	$_lang['setting_newsletter.name']								= 'Nieuwsbrief afzender naam';
	$_lang['setting_newsletter.name_desc']							= 'De naam waarmee de nieuwsbrief verstuurd wordt.';
	$_lang['setting_newsletter.template']							= 'Nieuwsbrief template';
	$_lang['setting_newsletter.template_desc']						= 'De ID van de template die als nieuwsbrief word gebruikt. Meerdere templates scheiden met een komma.';
	$_lang['setting_newsletter.log_send']							= 'Log versturen';
	$_lang['setting_newsletter.log_send_desc']						= 'Indien ja, het aangemaakte log bestand die automatisch word aangemaakt versturen via e-mail.';
	$_lang['setting_newsletter.log_email']							= 'Log e-mailadres(sen)';
	$_lang['setting_newsletter.log_email_desc']						= 'De e-mailadres(sen) waar het log bestand heen gestuurd dient te worden. Meerdere e-mailadressen scheiden met een komma.';	
	$_lang['setting_newsletter.log_lifetime']						= 'Log levensduur';
	$_lang['setting_newsletter.log_lifetime_desc']					= 'Het aantal dagen dat een log bestand bewaard moet blijven, hierna word het log bestand automatisch verwijderd.';
	$_lang['setting_newsletter.page_subscribe']						= 'Pagina "nieuwsbrief inschrijven"';
	$_lang['setting_newsletter.page_subscribe_desc']				= 'De ID van de pagina die als "nieuwsbrief inschrijven" pagina dient.';
	$_lang['setting_newsletter.page_unsubscribe']					= 'Pagina "nieuwsbrief uitschrijven"';
	$_lang['setting_newsletter.page_unsubscribe_desc']				= 'De ID van de pagina die als "nieuwsbrief uitschrijven" pagina dient.';

	$_lang['newsletter.newsletter']									= 'Nieuwsbrief';
	$_lang['newsletter.newsletters']								= 'Nieuwsbrieven';
	$_lang['newsletter.newsletters_desc']							= 'Hier kun je alle nieuwsbrief beheren, een nieuwsbrief een pagina die verstuurd word via de e-mail naar alle ingeschreven personen. Nieuwsbrieven kunnen elk willekeurig uur automatisch verstuurd worden, maar in verband met het zware proces voor de server van het versturen van meerdere e-mails word het aangeraden om nieuwsbrieven \'s nachts te versturen.';
	$_lang['newsletter.newsletter_cronjob_notice_desc']				= '<strong>Herinnering:</strong> voor nieuwsbrieven moet er een cronjob ingesteld zijn die elk uur alle nieuwsbrieven synchroniseert. Deze herinnering kan uit gezet worden via de systeem instellingen.';
	$_lang['newsletter.newsletter_site_status_notice_desc']			= '<strong>Melding:</strong> De website status staat op "offline", hierdoor kunnen er geen nieuwsbrieven verstuurd worden. De website status kan op "online" gezet worden via de systeem instellingen.';
	$_lang['newsletter.newsletter_create']							= 'Nieuwe nieuwsbrief';
	$_lang['newsletter.newsletter_create_desc']						= 'Om een nieuwe nieuwsbrief te maken, maak je eerst een normale pagina aan die je hier vervolgens selecteert om als nieuwsbrief te dienen. Zorg er wel voor dat je een goede nieuwsbrief template selecteert.';
	$_lang['newsletter.newsletter_update']							= 'Nieuwsbrief wijzigen';
	$_lang['newsletter.newsletter_remove']							= 'Nieuwsbrief verwijderen';
	$_lang['newsletter.newsletter_remove_confirm']					= 'Weet je zeker dat je deze nieuwsbrief wilt verwijderen?';
	$_lang['newsletter.newsletter_preview']							= 'Nieuwsbrief voorbeeld';
	$_lang['newsletter.newsletter_preview_send']					= 'Nieuwsbrief versturen (voorbeeld)';
	$_lang['newsletter.newsletter_stats']							= 'Nieuwsbrief statistieken';
	$_lang['newsletter.newsletter_stats_desc']						= 'De nieuwsbrief is in totaal [[+total]] keer verstuurd.';
	$_lang['newsletter.newsletter_queue']							= 'Nieuwsbrief versturen';
	$_lang['newsletter.newsletter_queue_cancel']					= 'Nieuwsbrief versturen annuleren';
	$_lang['newsletter.newsletter_queue_cancel_confirm']			= 'Weet je zeker dat je deze nieuwsbrief versturen wilt annuleren?';
	
	$_lang['newsletter.subscription']								= 'Inschrijving';
	$_lang['newsletter.subscriptions']								= 'Inschrijvingen';
	$_lang['newsletter.subscriptions_desc']							= 'Hier kun je alle inschrijvingen beheren voor de nieuwsbrieven, deze inschrijvingen schrijven zich over het algemeen in via de website maar kunnen ook geïmporteerd of geëxporteerd worden vanuit andere systemen.';
	$_lang['newsletter.subscription_create']						= 'Nieuwe inschrijving';
	$_lang['newsletter.subscription_update']						= 'Inschrijving wijzigen';
	$_lang['newsletter.subscription_data']							= 'Inschrijvingsvelden';
	$_lang['newsletter.subscription_remove']						= 'Inschrijving verwijderen';
	$_lang['newsletter.subscription_remove_confirm']				= 'Weet je zeker dat je deze inschrijving wilt verwijderen?';
	$_lang['newsletter.subscriptions_remove_selected']				= 'Geselecteerde inschrijvingen verwijderen';
	$_lang['newsletter.subscriptions_remove_selected_confirm']		= 'Weet je zeker dat je de geselecteerde inschrijvingen wilt verwijderen?';
	$_lang['newsletter.subscriptions_confirm_selected']				= 'Geselecteerde inschrijvingen bevestigen';
	$_lang['newsletter.subscriptions_confirm_selected_confirm']		= 'Weet je zeker dat je de geselecteerde inschrijvingen wilt bevestigen?';
	$_lang['newsletter.subscriptions_deconfirm_selected']			= 'Geselecteerde inschrijvingen de-bevestigen';
	$_lang['newsletter.subscriptions_deconfirm_selected_confirm']	= 'Weet je zeker dat je de geselecteerde inschrijvingen wilt de-bevestigen?';
	$_lang['newsletter.subscriptions_move_selected']				= 'Geselecteerde inschrijvingen verplaatsen';
	$_lang['newsletter.subscriptions_move_selected_desc']			= 'Selecteer de mailinglijst(en) waar de inschrijvingen aan toegevoegd of uit verwijderd moeten worden.';
	$_lang['newsletter.subscriptions_import']						= 'Inschrijvingen importeren';
	$_lang['newsletter.subscriptions_import_desc']					= 'Selecteer een CSV bestand om inschrijvingen te importeren. Het moet een geldig CSV formaat zijn.';
	$_lang['newsletter.subscriptions_export']						= 'Inschrijvingen exporteren';
	
	$_lang['newsletter.subscription_data_create']					= 'Nieuw veld';
	$_lang['newsletter.subscription_data_update']					= 'Veld wijzigen';
	$_lang['newsletter.subscription_data_remove']					= 'Veld verwijderen';
	$_lang['newsletter.subscription_data_remove_confirm']			= 'Weet je zeker dat je dit veld wilt verwijderen?';
	
	$_lang['newsletter.list']										= 'Mailinglijst';
	$_lang['newsletter.lists']										= 'Mailinglijsten';
	$_lang['newsletter.lists_desc']									= 'Hier kun je alle mailinglijsten voor de nieuwsbrief inschrijvingen beheren voor het versturen van nieuwsbrieven. Mailinglijsten kunnen ingesteld zijn als een primaire mailinglijst. Een primaire mailinglijst is een mailinglijst waar standaard alle inschrijvingen in geplaatst worden, deze primaire mailinglijst kan dan ook niet verwijderd worden.';
	$_lang['newsletter.list_create']								= 'Nieuwe mailinglijst';
	$_lang['newsletter.list_update']								= 'Mailinglijst wijzigen';
	$_lang['newsletter.list_remove']								= 'Mailinglijst verwijderen';
	$_lang['newsletter.list_remove_confirm']						= 'Weet je zeker dat je deze mailinglijst wilt verwijderen?';
	$_lang['newsletter.lists_remove_selected']						= 'Geselecteerde mailinglijsten verwijderen';
	$_lang['newsletter.lists_remove_selected_confirm']				= 'Weet je zeker dat je de geselecteerde mailinglijsten wilt verwijderen?';
	$_lang['newsletter.list_import']								= 'Mailinglijst inschrijvingen importeren';
	$_lang['newsletter.list_import_desc']							= 'Selecteer een CSV bestand om inschrijvingen in mailinglijsten te importeren. Het moet een geldig CSV formaat zijn.';
	$_lang['newsletter.list_export']								= 'Mailinglijst inschrijvingen exporteren';
	
	$_lang['newsletter.label_newsletter_resource']					= 'Pagina';
	$_lang['newsletter.label_newsletter_resource_desc']				= 'Selecteer de pagina die als nieuwsbrief dient.';
	$_lang['newsletter.label_newsletter_hidden']					= 'Verborgen nieuwsbrief';
	$_lang['newsletter.label_newsletter_hidden_desc']				= 'Verborgen nieuwsbrief, deze nieuwsbrief verbergen voor de niet \'Admin gebruikersgroepen\'.';
	$_lang['newsletter.label_newsletter_send_date']					= 'Datum';
	$_lang['newsletter.label_newsletter_send_date_desc']			= 'Selecteer een datum vanaf wanneer de nieuwsbrief verstuurd moet worden.';
	$_lang['newsletter.label_newsletter_send_time']					= 'Tijdstip';
	$_lang['newsletter.label_newsletter_send_time_desc']			= 'Selecteer een tijdstip wanneer de nieuwsbrief verstuurd moet worden.';
	$_lang['newsletter.label_newsletter_send_repeat']				= 'Herhalen';
	$_lang['newsletter.label_newsletter_send_repeat_desc']			= 'Het aantal keer dat de nieuwsbrief verstuurd moet worden, voor oneindig gebruik "-1".';
	$_lang['newsletter.label_newsletter_send_days']					= 'Dagen';
	$_lang['newsletter.label_newsletter_send_days_desc']			= 'De dagen wanneer de nieuwsbrief verstuurd moet worden.';
	$_lang['newsletter.label_newsletter_send_lists']				= 'Versturen naar de mailinglijst(en)';
	$_lang['newsletter.label_newsletter_send_lists_desc']			= 'De mailinglijst(en) waar de nieuwsbrief naar gestuurd moet worden.';
	$_lang['newsletter.label_newsletter_send_emails']				= 'Versturen naar de e-mailadres(sen)';
	$_lang['newsletter.label_newsletter_send_emails_desc']			= 'De e-mailadres(sen) waar de nieuwsbrief naar gestuurd moet worden, e-mailadressen scheiden met een komma.';	
	$_lang['newsletter.label_newsletter_filter']					= 'Mailinglijst filter';
	$_lang['newsletter.label_newsletter_filter_desc']				= 'De aangepaste filter om de inschrijvingen van de mailinglijst(en) te filteren op bijzonderheden, dit moet de naam van een snippet zijn.';
	$_lang['newsletter.label_newsletter_published']					= 'Gepubliceerd';
	$_lang['newsletter.label_newsletter_published_desc']			= '';
	$_lang['newsletter.label_newsletter_send_status']				= 'Status';
	$_lang['newsletter.label_newsletter_send_status_desc']			= '';
	$_lang['newsletter.label_newsletter_stats_newsletter']			= 'Verstuurd: [[+timestamp]]';
	$_lang['newsletter.label_newsletter_stats_newsletter_desc']		= '';
	$_lang['newsletter.label_newsletter_stats_date']				= 'Datum';
	$_lang['newsletter.label_newsletter_stats_date_desc']			= '';
	$_lang['newsletter.label_newsletter_stats_lists']				= 'Mailinglijst(en)';
	$_lang['newsletter.label_newsletter_stats_lists_desc']			= '';
	$_lang['newsletter.label_newsletter_stats_emails']				= 'E-mailadressen (totaal: [[+total]])';
	$_lang['newsletter.label_newsletter_stats_emails_desc']			= '';
	
	$_lang['newsletter.label_subscription_email']					= 'E-mailadres';
	$_lang['newsletter.label_subscription_email_desc']				= 'Het e-mailadres van de inschrijving.';
	$_lang['newsletter.label_subscription_name']					= 'Naam';
	$_lang['newsletter.label_subscription_name_desc']				= 'De naam van de inschrijving.';
	$_lang['newsletter.label_subscription_context']					= 'Context';
	$_lang['newsletter.label_subscription_context_desc']			= 'De context van de inschrijving.';
	$_lang['newsletter.label_subscription_confirmed']				= 'Status';
	$_lang['newsletter.label_subscription_confirmed_desc']			= 'De status van de inschrijving.';
	$_lang['newsletter.label_subscription_lists']					= 'Mailinglijst(en)';
	$_lang['newsletter.label_subscription_lists_desc']				= 'De mailinglijst(en) van de inschrijving.';
	$_lang['newsletter.label_subscriptions_lists']					= 'Mailinglijst(en)';
	$_lang['newsletter.label_subscriptions_lists_desc']				= 'De mailinglijst(en) van de inschrijving(en).';
	$_lang['newsletter.label_subscription_move']					= 'Verplaatsingstype';
	$_lang['newsletter.label_subscription_move_desc']				= 'De type van de verplaatsing, dit kan "toevoegen" of "verwijderen" zijn.';

	$_lang['newsletter.label_data_key']								= 'Naam';
	$_lang['newsletter.label_data_key_desc']						= 'De naam van het veld. Het veld zal beschikbaar zijn via de [[+subscription.naam]] tags.';
	$_lang['newsletter.label_data_content']							= 'Waarde';
	$_lang['newsletter.label_data_content_desc']					= 'De waarde van het veld.';
	
	$_lang['newsletter.label_list_name']							= 'Naam';
	$_lang['newsletter.label_list_name_desc']						= 'De naam van de mailinglijst.';
	$_lang['newsletter.label_list_description']						= 'Omschrijving';
	$_lang['newsletter.label_list_description_desc']				= 'Een korte omschrijving van de mailinglijst.';
	$_lang['newsletter.label_list_primary']							= 'Primaire mailinglijst';
	$_lang['newsletter.label_list_primary_desc']					= 'Primaire mailinglijst, in deze mailinglijst worden standaard alle inschrijvingen in geplaatst.';
	$_lang['newsletter.label_list_hidden']							= 'Verborgen mailinglijst';
	$_lang['newsletter.label_list_hidden_desc']						= 'Verborgen mailinglijst, deze mailinglijst verbergen voor de niet \'Admin gebruikersgroepen\'.';
	$_lang['newsletter.label_list_active']							= 'Actief';
	$_lang['newsletter.label_list_active_desc']						= '';
	$_lang['newsletter.label_list_subscriptions']					= 'Inschrijvingen';
	$_lang['newsletter.label_list_subscriptions_desc']				= '';
	
	$_lang['newsletter.label_import_file']							= 'Bestand';
	$_lang['newsletter.label_import_file_desc']						= 'Selecteer een geldig CSV bestand.';
	$_lang['newsletter.label_import_delimiter']						= 'Scheidingsteken';
	$_lang['newsletter.label_import_delimiter_desc']				= 'Het scheidingsteken waarmee kolommen gescheiden worden. Standaard is ";".';
	$_lang['newsletter.label_import_headers']						= 'Eerste rij zijn kolommen.';
	$_lang['newsletter.label_import_headers_desc']					= '';
	$_lang['newsletter.label_import_reset']							= 'Verwijder alle huidige inschrijvingen.';
	$_lang['newsletter.label_import_reset_desc']					= '';
	$_lang['newsletter.label_import_list']							= 'Mailinglijst';
	$_lang['newsletter.label_import_list_desc']						= 'De mailinglijst waarvan de inschrijvingen geïmporteerd moet worden.';
	$_lang['newsletter.label_export_list']							= 'Mailinglijst';
	$_lang['newsletter.label_export_list_desc']						= 'De mailinglijst waarvan de inschrijvingen geëxporteerd moet worden.';

	$_lang['newsletter.filter_context']								= 'Filter op context...';
	$_lang['newsletter.filter_confirm']								= 'Filter op bevestiging...';
	$_lang['newsletter.filter_lists']								= 'Filter op mailinglijst...';
	$_lang['newsletter.auto_refresh_grid']							= 'Automatisch vernieuwen';
	$_lang['newsletter.queue']										= 'Versturen';
	$_lang['newsletter.monday']										= 'Maa';
	$_lang['newsletter.tuesday']									= 'Din';
	$_lang['newsletter.wednesday']									= 'Woe';
	$_lang['newsletter.thursday']									= 'Don';
	$_lang['newsletter.friday']										= 'Vri';
	$_lang['newsletter.saturday']									= 'Zat';
	$_lang['newsletter.sunday']										= 'Zon';
	$_lang['newsletter.newsletter_type_1']							= 'Test nieuwsbrief';
	$_lang['newsletter.newsletter_type_2']							= 'Definitieve nieuwsbrief';
	$_lang['newsletter.newsletter_type_confirm']					= 'Weet je zeker dat je de nieuwsbrief wilt versturen als test? Dit is een vrij zwaar proces, afhankelijk van het aantal inschrijvingen kan dit enkele ogenblikken duren.';
	$_lang['newsletter.newsletter_status_0']						= 'Niet verstuurd';
	$_lang['newsletter.newsletter_status_1']						= 'In wachtrij';
	$_lang['newsletter.newsletter_status_2']						= 'Verstuurd';
	$_lang['newsletter.newsletter_send_detail']						= 'Verstuurd op <strong>{timestamp}</strong>.';
	$_lang['newsletter.newsletter_error_resource_id']				= 'De pagina die als nieuwsbrief dient bestaat niet of is verwijderd.';
	$_lang['newsletter.newsletter_error_resource_template']			= 'De pagina die als nieuwsbrief dient heeft niet de juiste template.';
	$_lang['newsletter.newsletter_error_date']						= 'De verstuur datum kan niet in het verleden zijn. Kies een andere datum of tijdstip.';
	$_lang['newsletter.newsletter_queue_save']						= 'Succes!';
	$_lang['newsletter.newsletter_queue_save_desc']					= 'De nieuwsbrief is in de wachtrij gezet om verstuurd te worden.';
	$_lang['newsletter.newsletter_send_succes']						= 'Succes!';
	$_lang['newsletter.newsletter_send_succes_desc']				= 'De nieuwsbrief is succesvol verstuurd.';
	$_lang['newsletter.newsletter_send_email_success']				= '[[+current]] van [[+total]]: [[+email]], verstuurd.';
	$_lang['newsletter.newsletter_send_email_error']				= '[[+current]] van [[+total]]: [[+email]], niet verstuurd vanwege een e-mail error.';
	$_lang['newsletter.newsletter_send_email_duplicate']			= '[[+current]] van [[+total]]: [[+email]], niet verstuurd vanwege een dubbel e-mailadres.';
	$_lang['newsletter.newsletter_send_to_emails']					= 'Bezig met versturen naar losse e-mailadressen.';
	$_lang['newsletter.newsletter_send_to_list']					= 'Bezig met versturen naar mailinglijst "[[+name]]".';
	$_lang['newsletter.newsletter_send_error_desc']					= 'De nieuwsbrief kon niet verstuurd worden, probeer het nog een keer.';
	$_lang['newsletter.newsletter_send_error_site_status_desc']		= 'De nieuwsbrief kon niet verstuurd worden, omdat de website status op "offline" staat.';
	$_lang['newsletter.newsletter_send_error_resource_desc']		= 'De nieuwsbrief kon niet verstuurd worden, omdat de pagina die als nieuwsbrief dient niet bestaat of verwijderd is.';
	$_lang['newsletter.newsletter_send_error_template_desc']		= 'De nieuwsbrief kon niet verstuurd worden, omdat de pagina die als nieuwsbrief dient niet de juiste template heeft.';
	$_lang['newsletter.newsletter_send_error_status_desc']			= 'De nieuwsbrief kon niet verstuurd worden, omdat de verstuur status niet juist is.';
	$_lang['newsletter.newsletter_send_error_date_desc']			= 'De nieuwsbrief kon niet verstuurd worden, omdat de verstuur datum nog niet aangebroken is.';
	$_lang['newsletter.newsletter_send_error_repeat_desc']			= 'De nieuwsbrief kon niet verstuurd worden, omdat de de nieuwsbrief al de toegestane keren verstuurd is.';
	$_lang['newsletter.newsletter_send_feedback']					= 'Nieuwsbrief "[[+pagetitle]]" verstuurd naar [[+total]] e-mailadressen.';
	$_lang['newsletter.subscription_general']						= 'Algemeen';
	$_lang['newsletter.subscription_general_desc']					= 'Hier kun je de algemene gegevens van de inschrijving in zien.';
	$_lang['newsletter.subscription_data']							= 'Inschrijving velden';
	$_lang['newsletter.subscription_data_desc']						= 'Hier kun je de velden van de inschrijving in zien.';
	$_lang['newsletter.subscription_confirmed']						= 'Bevestigd';
	$_lang['newsletter.subscription_not_confirmed']					= 'Niet bevestigd';
	$_lang['newsletter.subscription_unsubscribed']					= 'Uitgeschreven';
	$_lang['newsletter.subscription_add_list']						= 'Toevoegen aan de mailinglijst(en)';
	$_lang['newsletter.subscription_remove_list']					= 'Verwijderen uit de mailinglijst(en)';
	$_lang['newsletter.subscription_data_error']					= 'De inschrijving is niet gevonden.';
	$_lang['newsletter.subscription_data_error_character']			= 'De naam bevat niet toegestane tekens. Definieer een andere naam.';
	$_lang['newsletter.subscription_data_error_exists']				= 'Een veld met deze naam bestaat reeds. Definieer een andere naam.';
	$_lang['newsletter.lists_remove_primary_list']					= 'Dit is de primaire mailinglijst en kan niet verwijderd worden';
	$_lang['newsletter.import_dir_failed']							= 'Er is een fout opgetreden tijdens het importeren van de inschrijvingen, de import folder kon niet aangemaakt worden.';
	$_lang['newsletter.import_valid_failed']						= 'Selecteer een geldig CSV bestand.';
	$_lang['newsletter.import_upload_failed']						= 'Er is een fout opgetreden tijdens het importeren van de inschrijvingen, het CSV bestand kon niet geüpload worden.';
	$_lang['newsletter.import_read_failed']							= 'Er is een fout opgetreden tijdens het importeren van de inschrijvingen, het CSV bestand kon niet gelezen worden.';
	$_lang['newsletter.export_failed']								= 'Het exporteren van de inschrijvingen is mislukt, probeer het nog eens.';
	$_lang['newsletter.export_dir_failed']							= 'Er is een fout opgetreden tijdens het exporteren van de inschrijvingen, de export folder kon niet aangemaakt worden.';

?>