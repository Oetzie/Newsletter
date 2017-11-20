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
	
	$_lang['newsletter.subscribe_confirm_content']				= 'Uw nieuwsbrief inschrijving is bijna voltooid. Om uw e-mailadres te bevestigen dient u op de link te klikken die zojuist is toegestuurd naar uw e-mail.';
	$_lang['newsletter.email_subscribe_confirm_title']			= 'Nieuwsbrief inschrijving | [[++site_name]]';
	$_lang['newsletter.email_subscribe_confirm_content']		= '<p>Bedankt voor uw inschrijving voor de nieuwsbrief van <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a>. Om uw inschrijving te bevestigen dient u op onderstaande link te klikken. Op het moment dat u dit gedaan heeft is uw inschrijving voltooid.<p><p><a href="[[!+newsletter.subscribe_url]]" title="Klik hier om uw inschrijving te bevestigen">Klik hier om uw inschrijving te bevestigen</a></p><p>Of copy-paste de volgende URL in de adresbalk van uw browser: <a href="[[!+newsletter.subscribe_url]]">[[!+newsletter.subscribe_url]]</a></p>';
	
	$_lang['newsletter.subscribe_confirmed_content']			= 'Uw nieuwsbrief inschrijving is voltooid. U ontvangt de eerst volgende nieuwsbrief in uw inbox.';
	$_lang['newsletter.email_subscribe_confirmed_title']		= 'Nieuwsbrief inschrijving voltooid | [[++site_name]]';
	$_lang['newsletter.email_subscribe_confirmed_content']		= '<p>Bedankt voor uw inschrijving voor de nieuwsbrief van <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a>. Uw nieuwsbrief inschrijving is voltooid, u ontvangt de eerst volgende nieuwsbrief in uw inbox.</p>';
	
	$_lang['newsletter.unsubscribe_confirmed_content']			= 'Uw nieuwsbrief uitschrijving is voltooid. U zult geen nieuwsbrieven meer ontvangen in uw inbox.';
	
	$_lang['newsletter.newsletter_online_version']				= 'Als u dit bericht niet kunt lezen, klikt u dan alstublieft <a href="[[+newsletter.url]]" target="_blank" title="hier">hier</a>.';
	$_lang['newsletter.newsletter_anti_spam']					= 'Voeg <a href="mailto:[[++newsletter.email]]" target="_blank" title="[[++newsletter.email]]">[[++newsletter.email]]</a> toe aan uw contacten.';
	$_lang['newsletter.newsletter_footer'] 						= 'Deze email werd verzonden naar <a href="mailto:[[+subscribe.email]]" target="_blank" title="[[+subscribe.email]]">[[+subscribe.email]]</a> - Als u zich wilt uitschrijven voor onze promotionele e-mails klik dan alstublieft <a href="[[~[[++newsletter.page_unsubscribe]]? &scheme=`full`&email=[[+subscribe.email]]]]" target="_blank" title="hier">hier</a>.';
	$_lang['newsletter.view_website']							= 'Bezoek onze website voor meer informatie';
	
?>