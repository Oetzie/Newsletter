[[!Form?
    &redirect=`20`
    
	&extensions=`newsletterSubscribe,respondEmail`
	&validate=`{"name": ["required"], "email":["email", "required"]}`

	&respondEmailTo=`{"form.email": "form.name"}`
    &respondEmailFrom=`{"[[++newsletter.email]]": "[[++newsletter.name]]"}`
    &respondEmailSubject=`Nieuwsbrief inschrijving [[++site_name]]`
    &respondEmailTpl=`newsletterSubscribeFormEmailTpl`

	&newsletterRedirect=`10`

	&tpl=`newsletterSubscribeFormTpl`
]]