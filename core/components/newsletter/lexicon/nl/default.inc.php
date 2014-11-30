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

	$_lang['newsletter'] 											= 'Nieuwsbrief';
	$_lang['newsletter.desc'] 										= 'Wijzig of maak site-brede nieuwsbrieven.';
	
	$_lang['area_newsletter']										= 'Nieuwsbrief';
	
	$_lang['setting_newsletter_cronjob']							= 'Cronjob herinnering';
	$_lang['setting_newsletter_cronjob_desc']						= 'Zet deze instelling op "Ja" als je een cronjob hebt ingesteld voor de nieuwsbrief, door deze instelling op "Ja" te zetten word de cronjob waarschuwing niet meer getoond in de nieuwsbrieven component.';
	$_lang['setting_newsletter_email']								= 'Nieuwsbrief afzender';
	$_lang['setting_newsletter_email_desc']							= 'Het e-mail adres waarmee de nieuwsbrief verstuurd wordt.';
	$_lang['setting_newsletter_name']								= 'Nieuwsbrief afzender naam';
	$_lang['setting_newsletter_name_desc']							= 'De naam waarmee de nieuwsbrief verstuurd wordt.';
	
	$_lang['newslettercronjob_snippet_hash_desc']					= 'Deze hash word met de cronjob mee gestuurd zodat de nieuwsbrief niet zomaar verstuurd kan worden door willekeurige personen. Zonder deze hash werkt het automatisch versturen van de nieuwsbrieven niet.';
	$_lang['newslettersubscribe_snippet_confirmkey_desc']			= 'De parameter key voor de bevestiging. Standaard is "confirm".';
	$_lang['newsletterunsubscribe_snippet_confirmkey_desc']			= 'De parameter key voor de bevestiging. Standaard is "confirm".';
	
	$_lang['newsletter.newsletter']									= 'Nieuwsbrief';
	$_lang['newsletter.newsletters']								= 'Nieuwsbrieven';
	$_lang['newsletter.newsletters_desc']							= 'Hier kun je alle nieuwsbrief beheren van jouw MODX website. Controleer de nieuwsbrief goed voordat je hem verstuurd, na het versturen kun je alleen nog de webversie wijzigen en verschijnt hij bij alle ingeschreven leden in de e-mail inbox met eventuele fouten. Definitieve nieuwsbrieven worden iedere nacht om 00:00 automatisch verstuurd in verband met het zware proces van het versturen van 100en e-mails.';
	$_lang['newsletter.newsletter_cronjob_desc']					= 'Om nieuwsbrieven automatisch te kunnen versturen kun je gebruik maken van een cronjob, indien je een cronjob voor de nieuwsbrieven hebt ingesteld of geen gebruik wilt maken van een cronjob kun je deze melding uitzetten via systeeminstellingen.';
	$_lang['newsletter.newsletter_create']							= 'Maak nieuwe nieuwsbrief';
	$_lang['newsletter.newsletter_update']							= 'Nieuwsbrief updaten';
	$_lang['newsletter.newsletter_remove']							= 'Nieuwsbrief verwijderen';
	$_lang['newsletter.newsletter_remove_confirm']					= 'Weet je zeker dat je deze nieuwsbrief wilt verwijderen?';
	$_lang['newsletter.newsletter_preview']							= 'Nieuwsbrief bekijken';
	$_lang['newsletter.newsletter_send']							= 'Nieuwsbrief versturen';
	$_lang['newsletter.newsletter_cancel']							= 'Nieuwsbrief annuleren';
	$_lang['newsletter.newsletter_cancel_confirm']					= 'Weet je zeker dat je deze nieuwsbrief wilt annuleren?';
	
	$_lang['newsletter.subscription']								= 'Inschrijving';
	$_lang['newsletter.subscriptions']								= 'Inschrijvingen';
	$_lang['newsletter.subscriptions_desc']							= 'Hier kun je alle nieuwsbrief inschrijvingen beheren van de nieuwsbrieven.';
	$_lang['newsletter.subscription_create']						= 'Maak nieuwe inschrijving';
	$_lang['newsletter.subscription_update']						= 'Inschrijving updaten';
	$_lang['newsletter.subscription_remove']						= 'Inschrijving verwijderen';
	$_lang['newsletter.subscription_remove_confirm']				= 'Weet je zeker dat je deze inschrijving wilt verwijderen?';
	$_lang['newsletter.subscription_remove_selected']				= 'Geselecteerde inschrijvingen verwijderen';
	$_lang['newsletter.subscription_remove_selected_confirm']		= 'Weet je zeker dat je de geselecteerde inschrijvingen wilt verwijderen?';
	$_lang['newsletter.subscription_activate_selected']				= 'Geselecteerde inschrijvingen activeren';
	$_lang['newsletter.subscription_activate_selected_confirm']		= 'Weet je zeker dat je de geselecteerde inschrijvingen wilt activeren?';
	$_lang['newsletter.subscription_deactivate_selected']			= 'Geselecteerde inschrijvingen deactiveren';
	$_lang['newsletter.subscription_deactivate_selected_confirm']	= 'Weet je zeker dat je de geselecteerde inschrijvingen wilt deactiveren?';
	$_lang['newsletter.subscription_export']						= 'Exporteer';
	
	$_lang['newsletter.group']										= 'Groep';
	$_lang['newsletter.groups']										= 'Groepen';
	$_lang['newsletter.groups_desc']								= 'Hier kun je alle groepen voor de inschrijvingen instellen van de nieuwsbrieven.';
	$_lang['newsletter.group_create']								= 'Maak nieuwe groep';
	$_lang['newsletter.group_update']								= 'Groep updaten';
	$_lang['newsletter.group_remove']								= 'Groep verwijderen';
	$_lang['newsletter.group_remove_confirm']						= 'Weet je zeker dat je deze groep wilt verwijderen? Dit verwijderd ook alle inschrijvingen van deze groep.';
	$_lang['newsletter.group_remove_selected']						= 'Geselecteerde groepen verwijderen';
	$_lang['newsletter.group_remove_selected_confirm']				= 'Weet je zeker dat je de geselecteerde groepen wilt verwijderen?';
	$_lang['newsletter.group_activate_selected']					= 'Geselecteerde groepen activeren';
	$_lang['newsletter.group_activate_selected_confirm']			= 'Weet je zeker dat je de geselecteerde groepen wilt activeren?';
	$_lang['newsletter.group_deactivate_selected']					= 'Geselecteerde groepen deactiveren';
	$_lang['newsletter.group_deactivate_selected_confirm']			= 'Weet je zeker dat je de geselecteerde groepen wilt deactiveren?';
	
	$_lang['newsletter.label_resource']								= 'Document';
	$_lang['newsletter.label_resource_desc']						= 'Selecteer het document die als nieuwsbrief dient.';
	$_lang['newsletter.label_email']								= 'E-mailadres';
	$_lang['newsletter.label_email_desc']							= 'Het e-mailadres van de inschrijving.';
	$_lang['newsletter.label_name']									= 'Naam';
	$_lang['newsletter.label_name_desc']							= 'De naam van de inschrijving.';
	$_lang['newsletter.label_name_group_desc']						= 'De naam van de groep.';
	$_lang['newsletter.label_context']								= 'Context';
	$_lang['newsletter.label_context_desc']							= 'De context van de inschrijving.';
	$_lang['newsletter.label_description']							= 'Beschrijving';
	$_lang['newsletter.label_description_desc']						= 'Een korte beschrijving van de groep.';
	$_lang['newsletter.label_active']								= 'Actief';
	$_lang['newsletter.label_active_desc']							= '';
	$_lang['newsletter.label_groups']								= 'Groep(en)';
	$_lang['newsletter.label_groups_desc']							= 'De groep(en) van de inschrijving.';
	$_lang['newsletter.label_published']							= 'Gepubliceerd';
	$_lang['newsletter.label_published_desc']						= '';
	$_lang['newsletter.label_send']									= 'Verstuurd';
	$_lang['newsletter.label_send_desc']							= '';
	$_lang['newsletter.label_send_to_groups']						= 'Versturen naar de groep(en)';
	$_lang['newsletter.label_send_to_groups_desc']					= 'De groep(en) waar de nieuwsbrief naar gestuurd moet worden.';
	$_lang['newsletter.label_send_to_emails']						= 'Versturen naar de e-mailadres(sen)';
	$_lang['newsletter.label_send_to_emails_desc']					= 'De e-mailadressen(en) waar de nieuwsbrief naar gestuurd moet worden, e-mailadressen scheiden met een komma.';
	$_lang['newsletter.label_send_as']								= 'Versturen als';
	$_lang['newsletter.label_send_as_desc']							= 'Een test nieuwsbrief wordt gelijk verstuurd, een definitieve nieuwsbrief word op een ander tijdstip verstuurd.';
	$_lang['newsletter.label_send_date']							= 'Versturen op';
	$_lang['newsletter.label_send_date_desc']						= 'Selecteer een datum wanneer de nieuwsbrief verstuurd moet worden, als er geen datum word geselecteerd word hij vannacht om 00:00 automatisch verstuurd.';

	$_lang['newsletter.filter_context']								= 'Filter op context...';
	$_lang['newsletter.pending']									= 'In afwachting';
	$_lang['newsletter.test']										= 'Test nieuwsbrief';
	$_lang['newsletter.permanent']									= 'Definitieve nieuwsbrief';
	$_lang['newsletter.resource_does_not_exists']					= 'Dit document bestaat niet of is verwijderd.';
	$_lang['newsletter.newsletter_send_succes']						= 'Succes!';
	$_lang['newsletter.newsletter_send_succes_desc']				= 'De nieuwsbrief is succesvol verstuurd en opgeslagen.';
	$_lang['newsletter.activate_selected']							= 'Activeer geselecteerden';
	$_lang['newsletter.deactivate_selected']						= 'Deactiveer geselecteerden';
	$_lang['newsletter.remove_selected']							= 'Verwijder geselecteerden';
	
?>