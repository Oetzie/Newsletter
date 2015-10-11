<?php

	$output = '';
	
	$translations = array(
		'nl'		=> array(
			'admin_groups'		=> 'Admin gebruikersgroepen',
			'admin_groups_desc'	=> 'De gebruikersgroepen die toegang hebben tot de admin gedeelte van de nieuwsbrieven. Meerdere gebruikersgroepen scheiden met een komma.',
			'email'				=> 'Nieuwsbrief afzender',
			'email_desc'		=> 'Het e-mail adres waarmee de nieuwsbrief verstuurd wordt.',
			'name'				=> 'Nieuwsbrief afzender naam',
			'name_desc'			=> 'De naam waarmee de nieuwsbrief verstuurd wordt.',
			'token'				=> 'Cronjob token',
			'token_desc'		=> 'Deze token dient met de cronjob mee gestuurd te worden zodat de nieuwsbrief niet zomaar verstuurd kan worden door willekeurige personen. Zonder deze token werkt het automatisch versturen van de nieuwsbrieven niet.'
		),
		'en'		=> array(
			'admin_groups'		=> 'Admin usergroups',
			'admin_groups_desc'	=> 'The usergroups that has access to the admin part of the newsletters, to separate usergroups use a comma.',
			'email'				=> 'Newsletter sender',
			'email_desc'		=> 'The e-mail address where the newsletter is send from.',
			'name'				=> 'Newsletter sender name',
			'name_desc'			=> 'The name where the newsletter is send from.',
			'token'				=> 'Cronjob token',
			'token_desc'		=> 'This token needs to be send along with the cronjob so that the newsletter can not be send by random people. Without this token automatically send newsletters is not working.'
		)
	);
	
	$translations = $modx->getOption($modx->getOption('manager_language'), $translations, $translations['en']);
	
	$settings = array(
		'newsletter_admin_groups'	=> 'Administrator',
		'newsletter_email'			=> '',
		'newsletter_name'			=> '',
		'newsletter_token'			=> sha1('newsletter'.strtotime(date('d-m-Y H:i:s')))
	);

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
   		case xPDOTransport::ACTION_INSTALL:
   		case xPDOTransport::ACTION_UPGRADE:
   			foreach (array_keys($settings) as $key => $value) {
	   			if (null !== ($setting = $modx->getObject('modSystemSetting', $value))) {
		   			$settings[$value] = $setting->get('value');
	   			}
   			}
   			
   			if (null !== ($setting = $modx->getObject('modSystemSetting', 'site_name'))) {
		   		$settings['newsletter_email'] = $setting->get('value');
	   		}
	   		
	   		if (null !== ($setting = $modx->getObject('modSystemSetting', 'emailsender'))) {
		   		$settings['newsletter_name'] = $setting->get('value');
	   		}

        	$output = '<div class="x-form-item">
				<label for="ext-comp-newsletter1" class="x-form-item-label" style="width: 150px;">'.$modx->getOption('admin_groups', $translations).'</label>
				<div class="x-form-element" style="padding-left: 155px">
					<input type="text" name="newsletter_admin_groups" id="ext-comp-newsletter1" value="'.$modx->getOption('newsletter_admin_groups', $settings).'" class="x-form-text x-form-field" msgtarget="under" autocomplete="on" size="20" style="width: 350px;">
				</div>
				<div class="x-form-clear-left"></div>
			</div>
			<label class="desc-under" style="font-weight: normal;">'.$modx->getOption('admin_groups_desc', $translations).'</label>
			<div class="x-form-item">
				<label for="ext-comp-newsletter2" class="x-form-item-label" style="width: 150px;">'.$modx->getOption('email', $translations).'</label>
				<div class="x-form-element" style="padding-left: 155px">
					<input type="text" name="newsletter_email" id="ext-comp-newsletter2" value="'.$modx->getOption('newsletter_email', $settings).'" class="x-form-text x-form-field" msgtarget="under" autocomplete="on" size="20" style="width: 350px;">
				</div>
				<div class="x-form-clear-left"></div>
			</div>
			<label class="desc-under" style="font-weight: normal;">'.$modx->getOption('email_desc', $translations).'</label>
			<div class="x-form-item">
				<label for="ext-comp-newsletter3" class="x-form-item-label" style="width: 150px;">'.$modx->getOption('name', $translations).'</label>
				<div class="x-form-element" style="padding-left: 155px">
					<input type="text" name="newsletter_name" id="ext-comp-newsletter3" value="'.$modx->getOption('newsletter_name', $settings).'" class="x-form-text x-form-field" msgtarget="under" autocomplete="on" size="20" style="width: 350px;">
				</div>
				<div class="x-form-clear-left"></div>
			</div>
			<label class="desc-under" style="font-weight: normal;">'.$modx->getOption('name_desc', $translations).'</label>
			<div class="x-form-item">
				<label for="ext-comp-newsletter4" class="x-form-item-label" style="width: 150px;">'.$modx->getOption('token', $translations).'</label>
				<div class="x-form-element" style="padding-left: 155px">
					<input type="text" name="newsletter_token" id="ext-comp-newsletter4" value="'.$modx->getOption('newsletter_token', $settings).'" class="x-form-text x-form-field" msgtarget="under" autocomplete="on" size="20" style="width: 350px;">
				</div>
				<div class="x-form-clear-left"></div>
			</div>
			<label class="desc-under" style="font-weight: normal;">'.$modx->getOption('token_desc', $translations).'</label>';
					
       		break;
	   	case xPDOTransport::ACTION_UNINSTALL:
        	break;
	}

	return $output;
	
?>