<p class="info">[[%site.fields_required? &topic=`site`&namespace=`site`]]</p>
<form action="[[+form.url]]" method="[[+form.method]]" name="[[+form.submit]]" class="form [[+form.submitted:notempty=`form-active`]]">
	[[+form.error]]
	<div class="form-element required [[+form.error.name:notempty=`error`]]">
		<label for="name">[[%site.form_name? &topic=`site`&namespace=`site`]]</label>
		<div class="form-element-container">
			<input type="name" name="name" id="name" value="[[+form.name]]" />[[+form.error.name]]
		</div>
	</div>
	<div class="form-element required [[+form.error.email:notempty=`error`]]">
		<label for="email">[[%site.form_email? &topic=`site`&namespace=`site`]]</label>
		<div class="form-element-container">
			<input type="email" name="email" id="email" value="[[+form.email]]" />[[+form.error.email]]
		</div>
	</div>
	<div class="form-element">
		<div class="form-element-container">
			<button type="submit" name="[[+form.submit]]" title="[[%site.form_subscribe? &topic=`site`&namespace=`site`]]">[[%site.form_subscribe? &topic=`site`&namespace=`site`]]</button>
		</div>
	</div>
</form>