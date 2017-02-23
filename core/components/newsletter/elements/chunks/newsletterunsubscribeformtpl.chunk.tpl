<p class="info">[[%site.fields_required? &topic=`forms`&namespace=`site`]]</p>
<form novalidate action="[[+form.action]]" method="[[+form.method]]" name="[[+form.handler]]" class="form [[+form.state:notempty=`form-active`]]">
    [[+form.error]]
	<div class="form-element required [[+form.error.email:notempty=`error`]]">
		<label for="email">[[%site.form_email? &topic=`forms`&namespace=`site`]]</label>
		<div class="form-element-container">
			<input type="email" name="email" id="email" value="[[+form.email]]" />[[+form.error.email]]
		</div>
	</div>
	<div class="form-element">
		<div class="form-element-container">
			<button type="submit" name="[[+form.handler]]" title="[[%site.form_unsubscribe? &topic=`site`&namespace=`site`]]">[[%site.form_unsubscribe? &topic=`site`&namespace=`site`]]</button>
		</div>
	</div>
</form>