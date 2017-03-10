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
	 
	$_lang['newsletter.form_subscribe_error']					= 'Er is een fout opgetreden tijdens het inschrijven, probeer het nog een keer.';
	$_lang['newsletter.form_subscribe_error_confirm']			= 'Er is een fout opgetreden tijdens het inschrijven, probeer het nog een keer.';
	$_lang['newsletter.form_unsubscribe_error']					= 'Er is een fout opgetreden tijdens het uitschrijven, probeer het nog een keer.';
	$_lang['newsletter.form_unsubscribe_error_confirm']			= 'Er is een fout opgetreden tijdens het uitschrijven, probeer het nog een keer.';
	
	$_lang['newsletter.subscribe_confirm_desc']					= 'Je nieuwsbrief inschrijving is bijna voltooid. Om je e-mailadres te bevestigen dien je op de link te klikken die zojuist is toegestuurd naar je e-mail.';
	$_lang['newsletter.email_subscribe_confirm_title']			= 'Nieuwsbrief inschrijving | [[++site_name]]';
	$_lang['newsletter.email_subscribe_confirm_content']		= '<p>Bedankt voor je inschrijving voor de nieuwsbrief van <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a>. Om je inschrijving te bevestigen dien je op onderstaande link te klikken. Op het moment dat je dit gedaan hebt is je inschrijving voltooid.<p><p><a href="[[!+newsletter.subscribe_url]]" title="Klik hier om je inschrijving te bevestigen">Klik hier om je inschrijving te bevestigen</a></p><p>Of copy-paste de volgende URL in de adresbalk van uw browser: <a href="[[!+newsletter.subscribe_url]]">[[!+newsletter.subscribe_url]]</a></p>';
	
	$_lang['newsletter.subscribe_confirmed_desc']				= 'Je nieuwsbrief inschrijving is voltooid. Je ontvangt de eerst volgende nieuwsbrief in je inbox.';
	$_lang['newsletter.email_subscribe_confirmed_title']		= 'Nieuwsbrief inschrijving | [[++site_name]]';
	$_lang['newsletter.email_subscribe_confirmed_content']		= '<p>Bedankt voor je inschrijving voor de nieuwsbrief van <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a>. Je nieuwsbrief inschrijving is voltooid, je ontvangt de eerst volgende nieuwsbrief in je inbox.</p>';
	
	$_lang['newsletter.unsubscribe_confirmed_desc']				= 'Je nieuwsbrief uitschrijving is voltooid. Je zult geen nieuwsbrieven meer ontvangen in je inbox.';
	
	$_lang['newsletter.newsletter_online_version']				= 'Als u dit bericht niet kunt lezen, klikt u dan alstublieft <a href="[[+newsletter.url]]" target="_blank" title="hier">hier</a>.';
	$_lang['newsletter.newsletter_anti_spam']					= 'Voeg <a href="mailto:[[++newsletter.email]]" target="_blank" title="[[++newsletter.email]]">[[++newsletter.email]]</a> toe aan uw contacten.';
	$_lang['newsletter.newsletter_footer'] 						= 'Deze email werd verzonden naar <a href="mailto:[[+subscribe.email]]" target="_blank" title="[[+subscribe.email]]">[[+subscribe.email]]</a> - Als u zich wilt uitschrijven voor onze promotionele e-mails klik dan alstublieft <a href="[[~[[++newsletter.page_unsubscribe]]? &scheme=`full`]]&email=[[+subscribe.email]]" target="_blank" title="hier">hier</a>.';
	$_lang['newsletter.view_website']							= 'Bezoek onze website voor meer informatie';
	
	/* Custom */
	
	$_lang['newsletter.list_test'] 								= 'Test';
	$_lang['newsletter.list_test_desc'] 						= 'De mailinglijst waar alle test inschrijvingen in staan om nieuwsbrieven te testen.';
	$_lang['newsletter.list_default'] 							= 'Standaard';
	$_lang['newsletter.list_default_desc'] 						= 'De mailinglijst waar standaard alle inschrijvingen in geplaatst worden tijdens het inschrijven op bijvoorbeeld de website.';
	
?>