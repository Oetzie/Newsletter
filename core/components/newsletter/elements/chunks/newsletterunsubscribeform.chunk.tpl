[[!form?
	&extensions=`newsletterUnSubscribe`
	&validate=`nospam:blank,email:email:required`

	&newsletterRedirect=`29`

	&redirect=`29`
]]
				[[+form.error]]
				<p class="info">Velden gemarkeerd met een sterretje zijn verplicht.</p>
				<form action="[[~[[*id]]]]" method="post" name="newsletter-unsubscribe">
					<input type="hidden" name="nospam" id="nospam" value="[[+form.nospam]]" />
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