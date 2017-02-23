Ext.onReady(function() {
	MODx.load({xtype: 'newsletter-page-home'});
});

Newsletter.page.Home = function(config) {
	config = config || {};
	
	config.buttons = [{
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
    }, {
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

Ext.extend(Newsletter.page.Home, MODx.Component, {
	filterContext: function(tf) {
		var request = MODx.request || {};
		
        Ext.apply(request, {
	    	'context' : tf.getValue()  
	    });
	    
        MODx.loadPage('?' + Ext.urlEncode(request));
	}
});

Ext.reg('newsletter-page-home', Newsletter.page.Home);