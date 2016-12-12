[[!Form?
    &submit=`subscribe`
	&success=`20`
    
	&extensions=`NewsletterSubscribe,RespondEmail`
	&validate=`{"name": ["required"], "email":["email", "required"]}`

	&respondEmailTo=`{"form.email": "form.name"}`
	&respondEmailFrom=`{"[[++newsletter.email]]": "[[++newsletter.name]]"}`
	&respondEmailSubject=`[[%newsletter.email_subscribe_title? &topic=`site`&namespace=`newsletter`]]`
	&respondEmailTpl=`newsletterSubscribeFormEmailTpl`

	&newsletterSuccess=`10`

	&tpl=`newsletterSubscribeFormTpl`
]]