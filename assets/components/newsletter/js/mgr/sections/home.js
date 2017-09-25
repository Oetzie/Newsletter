Ext.onReady(function() {
	MODx.load({xtype: 'newsletter-page-home'});
});

Newsletter.page.Home = function(config) {
	config = config || {};
	
	config.buttons = [];
	
	if (Newsletter.config.branding_url) {
		config.buttons.push({
			text 		: 'Newsletter ' + Newsletter.config.version,
			cls			: 'x-btn-branding',
			handler		: this.loadBranding
		});
	}
	
	config.buttons.push({
    	xtype		: 'modx-combo-context',
    	hidden		: Newsletter.config.context,
        value 		: MODx.request.context || MODx.config.default_context,
		name		: 'clientsettings-filter-context',
        emptyText	: _('clientsettings.filter_context'),
        listeners	: {
        	'select'	: {
            	fn			: this.filterContext,
            	scope		: this   
		    }
		},
		baseParams	: {
			action		: 'context/getlist',
			exclude		: 'mgr'
		}
    });
    
    if (Newsletter.config.branding_url_help) {
        config.buttons.push({
            text   : _('help_ex'),
            handler: MODx.loadHelpPane,
            scope  : this
        });
    }
	
	Ext.applyIf(config, {
		components	: [{
			xtype		: 'newsletter-panel-home',
			renderTo	: 'newsletter-panel-home-div'
		}]
	});
	
	Newsletter.page.Home.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.page.Home, MODx.Component, {
	loadBranding: function(btn) {
		window.open(Newsletter.config.branding_url);
	},
	filterContext: function(tf) {
		var request = MODx.request || {};
		
        Ext.apply(request, {
	    	'context' : tf.getValue()  
	    });
	    
        MODx.loadPage('?' + Ext.urlEncode(request));
	}
});

Ext.reg('newsletter-page-home', Newsletter.page.Home);