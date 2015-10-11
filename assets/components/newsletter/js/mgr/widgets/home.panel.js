Newsletter.panel.Home = function(config) {
	config = config || {};
	
    Ext.apply(config, {
		id			: 'newsletter-panel-home',
		cls			: 'container',
		defaults	: {
			collapsible	: false,
			autoHeight	: true,
			autoWidth	: true,
			border		: false
		},
		items		: [{
			html		: '<h2>'+_('newsletter')+'</h2>',
			id			: 'newsletter-header',
			cls			: 'modx-page-header'
		}, {
			xtype		: 'modx-tabs',
			items		: [{
				layout		: 'form',
				title		: _('newsletter.newsletters'),
				defaults	: {
					autoHeight	: true,
					autoWidth	: true,
					border		: false
				},
				items		: [{
					html			: '<p>'+_('newsletter.newsletters_desc')+'</p>',
					bodyCssClass	: 'panel-desc'
				}, {
		            html			: Newsletter.config.admin && 0 == parseInt(MODx.config.newsletter_cronjob) ? '<p>' + _('newsletter.newsletter_cronjob_desc') + '</p>' : '',
					bodyCssClass	: Newsletter.config.admin && 0 == parseInt(MODx.config.newsletter_cronjob) ? 'modx-config-error' : ''
	            }, {
					xtype			: 'newsletter-grid-newsletters',
					cls				: 'main-wrapper',
					preventRender	: true
				}]
			}, {
				layout		: 'form',
				title		: _('newsletter.subscriptions'),
				defaults	: {
					autoHeight	: true,
					autoWidth	: true,
					border		: false
				},
				items		: [{
					html			: '<p>'+_('newsletter.subscriptions_desc')+'</p>',
					bodyCssClass	: 'panel-desc'
				}, {
					xtype			: 'newsletter-grid-subscriptions',
					cls				: 'main-wrapper',
					preventRender	: true
				}]
			}, {
				layout		: 'form',
				title		: _('newsletter.lists'),
				defaults	: {
					autoHeight	: true,
					autoWidth	: true,
					border		: false
				},
				items		: [{
					html			: '<p>'+_('newsletter.lists_desc')+'</p>',
					bodyCssClass	: 'panel-desc'
				}, {
					xtype			: 'newsletter-grid-lists',
					cls				: 'main-wrapper',
					preventRender	: true
				}]
			}]
		}]
	});

	Newsletter.panel.Home.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.panel.Home, MODx.FormPanel);

Ext.reg('newsletter-panel-home', Newsletter.panel.Home);