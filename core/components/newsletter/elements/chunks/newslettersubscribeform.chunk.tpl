[[!Form?
    &redirect=`REDIRECT`
    
	&extensions=`NewsletterSubscribe,RespondEmail`
	&validate=`{"name": ["required"], "email":["email", "required"]}`

	&respondEmailTo=`{"form.email": "form.name"}`
    &respondEmailFrom=`{"[[++newsletter.email]]": "[[++newsletter.name]]"}`
    &respondEmailSubject=`Nieuwsbrief inschrijving [[++site_name]]`
    &respondEmailTpl=`newsletterSubscribeFormEmailTpl`

	&newsletterRedirect=`REDIRECT`

	&tpl=`newsletterSubscribeFormTpl`
]]