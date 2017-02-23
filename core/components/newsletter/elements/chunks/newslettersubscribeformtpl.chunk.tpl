<p class="info">[[%site.fields_required? &topic=`forms`&namespace=`site`]]</p>
<form novalidate action="[[+form.action]]" method="[[+form.method]]" name="[[+form.handler]]" class="form [[+form.state:notempty=`form-active`]]">
    [[+form.error]]
	<div class="form-element required [[+form.error.name:notempty=`error`]]">
		<label for="name">[[%site.form_name? &topic=`forms`&namespace=`site`]]</label>
		<div class="form-element-container">
			<input type="name" name="name" id="name" value="[[+form.name]]" />[[+form.error.name]]
		</div>
	</div>
	<div class="form-element required [[+form.error.email:notempty=`error`]]">
		<label for="email">[[%site.form_email? &topic=`forms`&namespace=`site`]]</label>
		<div class="form-element-container">
			<input type="email" name="email" id="email" value="[[+form.email]]" />[[+form.error.email]]
		</div>
	</div>
	<div class="form-element">
		<div class="form-element-container">
			<button type="submit" name="[[+form.handler]]" title="[[%site.form_subscribe? &topic=`site`&namespace=`site`]]">[[%site.form_subscribe? &topic=`site`&namespace=`site`]]</button>
		</div>
	</div>
</form>