Ext.onReady(function() {
	MODx.load({xtype: 'newsletter-page-home'});
});

Newsletter.page.Home = function(config) {
	config = config || {};
	
	config.buttons = [{
		text		: _('help_ex'),
		handler		: MODx.loadHelpPane,
		scope		: this
	}];
	
	Ext.applyIf(config, {
		components	: [{
			xtype		: 'newsletter-panel-home',
			renderTo	: 'newsletter-panel-home-div'
		}]
	});
	
	Newsletter.page.Home.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.page.Home, MODx.Component);

Ext.reg('newsletter-page-home', Newsletter.page.Home);