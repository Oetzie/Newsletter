[[+form.error]]
<p class="info">Velden gemarkeerd met een sterretje zijn verplicht.</p>
<form action="[[~[[*id]]]]" method="post" name="newsletter-unsubscribe" class="form [[+form.submit:notempty=`form-active`]]">
	<div class="form-element required [[+form.error.email:notempty=`error`]]">
		<label for="email">Uw e-mailadres</label>
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