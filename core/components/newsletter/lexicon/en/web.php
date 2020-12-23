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

$_lang['newsletter.name']                                   = 'Your name';
$_lang['newsletter.email']                                  = 'Your emailadares';
$_lang['newsletter.subscribe']                              = 'Subscribe';
$_lang['newsletter.unsubscribe']                            = 'Unsubscribe';

$_lang['newsletter.subscribe.error']                        = 'There is an error occurred during the subscribe process for the newsletter, please try again.';
$_lang['newsletter.subscribe_confirm.error']                = 'There is an error occurred during the subscribe process for the newsletter, please try again.';
$_lang['newsletter.unsubscribe.error']                      = 'There is an error occurred during the unsubscribe process for the newsletter, please try again.';
$_lang['newsletter.unsubscribe_confirm.error']              = 'There is an error occurred during the unsubscribe process for the newsletter, please try again.';

$_lang['newsletter.subscribe_email.title']                  = 'Newsletter subscription completed';
$_lang['newsletter.subscribe_email.content']                = '<p>>Thank you for your subscription for the newsletter of <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a>. Your newsletter subscription is completed, you will receive the next newsletter in your inbox.</p>';
$_lang['newsletter.subscribe_email.regards']                = 'Kind regards,';

$_lang['newsletter.subscribe_confirm_email.title']          = 'Newsletter subscription confirmation';
$_lang['newsletter.subscribe_confirm_email.content']        = '<p>Thank you for your subscription for the newsletter of <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a>. To confirm your subscription you need to click on the following link. The moment you did this your subscription will be completed.<p><p><a href="[[+newsletter.subscribe_url]]" title="Click here to confirm your subscription">Click here to confirm your subscription</a></p><p>Or copy-paste the following URL in the address bar of your browser: <a href="[[+newsletter.subscribe_url]]">[[+newsletter.subscribe_url]]</a></p>';
$_lang['newsletter.subscribe_confirm_email.regards']        = 'Kind regards,';

$_lang['newsletter.unsubscribe_email.title']                = 'Newsletter unsubscription completed';
$_lang['newsletter.unsubscribe_email.content']              = '<p>Thank you for your unsubscription for the newsletter of <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a>.Your newsletter unsubscription is completed and you wont receive any newsletters any more in your inbox.</p>';
$_lang['newsletter.unsubscribe_email.regards']              = 'Kind regards,';
