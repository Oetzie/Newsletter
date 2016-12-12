<p class="info">[[%site.fields_required? &topic=`site`&namespace=`site`]]</p>
<form action="[[+form.url]]" method="[[+form.method]]" name="[[+form.submit]]" class="form [[+form.submitted:notempty=`form-active`]]">
	[[+form.error]]
	<div class="form-element required [[+form.error.email:notempty=`error`]]">
		<label for="email">[[%site.form_email? &topic=`site`&namespace=`site`]]</label>
		<div class="form-element-container">
			<input type="email" name="email" id="email" value="[[+form.email]]" />[[+form.error.email]]
		</div>
	</div>
	<div class="form-element">
		<div class="form-element-container">
			<button type="submit" name="[[+form.submit]]" title="[[%site.form_unsubscribe? &topic=`site`&namespace=`site`]]">[[%site.form_unsubscribe? &topic=`site`&namespace=`site`]]</button>
		</div>
	</div>
</form>