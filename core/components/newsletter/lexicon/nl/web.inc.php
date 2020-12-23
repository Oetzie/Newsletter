<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$_lang['newsletter.name']                                   = 'Uw naam';
$_lang['newsletter.email']                                  = 'Uw e-mailadres';
$_lang['newsletter.subscribe']                              = 'Inschrijven';
$_lang['newsletter.unsubscribe']                            = 'Uitschrijven';

$_lang['newsletter.subscribe.error']                        = 'Er is een fout opgetreden tijdens het inschrijven voor de nieuwsbrief, probeer het nog een keer.';
$_lang['newsletter.subscribe_confirm.error']                = 'Er is een fout opgetreden tijdens het inschrijven voor de nieuwsbrief, probeer het nog een keer.';
$_lang['newsletter.unsubscribe.error']                      = 'Er is een fout opgetreden tijdens het uitschrijven voor de nieuwsbrief, probeer het nog een keer.';
$_lang['newsletter.unsubscribe_confirm.error']              = 'Er is een fout opgetreden tijdens het uitschrijven voor de nieuwsbrief, probeer het nog een keer.';

$_lang['newsletter.subscribe_email.title']                  = 'Nieuwsbrief inschrijving voltooid';
$_lang['newsletter.subscribe_email.content']                = '<p>Bedankt voor uw inschrijving voor de nieuwsbrief van <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a>. Uw nieuwsbrief inschrijving is voltooid en u ontvangt de eerst volgende nieuwsbrief in uw inbox.</p>';
$_lang['newsletter.subscribe_email.regards']                = 'Met vriendelijke groet,';

$_lang['newsletter.subscribe_confirm_email.title']          = 'Nieuwsbrief inschrijving bevestigen';
$_lang['newsletter.subscribe_confirm_email.content']        = '<p>Bedankt voor uw inschrijving voor de nieuwsbrief van <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a>. Om uw inschrijving te bevestigen dient u op onderstaande link te klikken. Op het moment dat u dit gedaan heeft is uw inschrijving voltooid.<p><p><a href="[[+newsletter.subscribe_url]]" title="Klik hier om uw inschrijving te bevestigen">Klik hier om uw inschrijving te bevestigen</a></p><p>Of copy-paste de volgende URL in de adresbalk van uw browser: <a href="[[+newsletter.subscribe_url]]">[[+newsletter.subscribe_url]]</a></p>';
$_lang['newsletter.subscribe_confirm_email.regards']        = 'Met vriendelijke groet,';

$_lang['newsletter.unsubscribe_email.title']                = 'Nieuwsbrief uitschrijving voltooid';
$_lang['newsletter.unsubscribe_email.content']              = '<p>Bedankt voor uw uitschrijving voor de nieuwsbrief van <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a>. Uw nieuwsbrief uitschrijving is voltooid en u ontvangt geen nieuwsbrieven meer in uw inbox.</p>';
$_lang['newsletter.unsubscribe_email.regards']              = 'Met vriendelijke groet,';
