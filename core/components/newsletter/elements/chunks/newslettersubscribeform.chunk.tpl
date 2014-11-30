[[!form?
	&extensions=`newsletterSubscribe,autoRespondEmail`
	&validate=`nospam:blank,name:required,email:email:required`

	&autoRespondEmailTpl=`newsletterEmailSubscribeForm`
	&autoRespondEmailTo=`form.email=form.name`
	&autoRespondEmailFrom=`[[++newsletter_email]]=[[++newsletter_name]]`
	&autoRespondEmailSubject=`Nieuwsbrief inschrijving [[++site_name]]`

	&newsletterGroups=`2`
	&newsletterRedirect=`10`

	&redirect=`28`
]]
				[[+form.error]]
				<p class="info">Velden gemarkeerd met een sterretje zijn verplicht.</p>
				<form action="[[~[[*id]]]]" method="post" name="newsletter">
					<input type="hidden" name="nospam" id="nospam" value="[[+form.nospam]]" />
					<div class="form-element [[+fi.error.name:notempty=`error`]]">
						<label for="name">Naam *</label>
						<div class="form-element-container">
							<input type="name" name="name" id="name" value="[[+form.name]]" />[[+form.error.name]]
						</div>
					</div>
					<div class="form-element [[+form.error.email:notempty=`error`]]">
						<label for="email">E-mailadres *</label>
						<div class="form-element-container">
							<input type="email" name="email" id="email" value="[[+form.email]]" />[[+form.error.email]]
						</div>
					</div>
					<div class="form-element">
						<div class="form-element-container">
							<button type="submit" name="submit" title="Verzenden">Verzenden</button>
						</div>
					</div>
				</form>