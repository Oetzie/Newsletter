Newsletter.grid.SubscriptionsInfo = function(config) {
    config = config || {};

	config.tbar = [{
        text	: _('newsletter.subscription_info_create'),
        cls		:'primary-button',
        handler	: this.createSubscriptionInfo,
        scope	: this
	}];

    columns = new Ext.grid.ColumnModel({
        columns: [{
            header		: _('newsletter.label_info_key'),
            dataIndex	: 'key',
            sortable	: true,
            editable	: true,
            width		: 150,
            fixed 		: true,
            editor		: {
            	xtype		: 'textfield'
            }
        }, {
            header		: _('newsletter.label_info_content'),
            dataIndex	: 'content',
            sortable	: true,
            editable	: true,
            width		: 200,
            fixed		: false,
            editor		: {
            	xtype		: 'textfield'
            }
        }]
    });
    
    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'newsletter-grid-subscriptions-info',
        url			: Newsletter.config.connector_url,
        baseParams	: {
        	action		: 'mgr/subscriptions/info/getList',
        	id			: config.record.id
        },
        autosave	: true,
        save_action	: 'mgr/subscriptions/info/updateFromGrid',
        fields		: ['id', 'subscription_id', 'key', 'content', 'editedon'],
        paging		: false,
        sortBy		: 'key'
    });
    
    Newsletter.grid.SubscriptionsInfo.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.grid.SubscriptionsInfo, MODx.grid.Grid, {
    getMenu: function() {
		return [{
	    	text	: _('newsletter.subscription_info_update'),
			handler	: this.updateSubscriptionInfo,
			scope	: this
		}, '-', {
	    	text	: _('newsletter.subscription_info_remove'),
			handler	: this.removeSubscriptionInfo,
			scope	: this
		}];
    },
    createSubscriptionInfo: function(btn, e) {
        if (this.createSubscriptionInfoWindow) {
	        this.createSubscriptionInfoWindow.destroy();
        }
        
        var record = {
	        subscription_id	: this.config.record.id
        };

        this.createSubscriptionInfoWindow = MODx.load({
	        xtype		: 'newsletter-window-subscription-create-info',
	        record 		: record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
            		fn		: function() {
            			this.refresh();
            		},
		        	scope		:this
		        }
	         }
        });
        
        this.createSubscriptionInfoWindow.setValues(record);
        this.createSubscriptionInfoWindow.show(e.target);
    },
    updateSubscriptionInfo: function(btn, e) {
        if (this.updateSubscriptionInfoWindow) {
	        this.updateSubscriptionInfoWindow.destroy();
        }
        
        this.updateSubscriptionInfoWindow = MODx.load({
	        xtype		: 'newsletter-window-subscription-update-info',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
            		fn		: function() {
	            		this.refresh();
	           		},
		        	scope		:this
		        }
	         }
        });
        
        this.updateSubscriptionInfoWindow.setValues(this.menu.record);
        this.updateSubscriptionInfoWindow.show(e.target);
    },
    removeSubscriptionInfo: function(btn, e) {
    	MODx.msg.confirm({
        	title 	: _('newsletter.subscription_info_remove'),
        	text	: _('newsletter.subscription_info_remove_confirm'),
        	url		: Newsletter.config.connector_url,
        	params	: {
            	action	: 'mgr/subscriptions/info/remove',
            	id		: this.menu.record.id
            },
            listeners: {
            	'success': {
            		fn		: function() {
	            		this.refresh();
            		},
            		scope	: this
            	}
            }
    	});
    }
});

Ext.reg('newsletter-grid-subscriptions-info', Newsletter.grid.SubscriptionsInfo);

Newsletter.window.CreateInfoSubscription = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscription_info_create'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/info/create'
        },
        defauls		: {
	        labelAlign	: 'top',
            border		: false
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'subscription_id'
        }, {
	        xtype		: 'textfield',
            fieldLabel	: _('newsletter.label_info_key'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_info_key_desc'),
            name		: 'key',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_info_key_desc'),
            cls			: 'desc-under'
        }, {
	        xtype		: 'textfield',
            fieldLabel	: _('newsletter.label_info_content'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_info_content_desc'),
            name		: 'content',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_info_content_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Newsletter.window.CreateInfoSubscription.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.CreateInfoSubscription, MODx.Window);

Ext.reg('newsletter-window-subscription-create-info', Newsletter.window.CreateInfoSubscription);

Newsletter.window.UpdateInfoSubscription = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscription_info_update'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/info/update'
        },
        defauls		: {
	        labelAlign	: 'top',
            border		: false
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            xtype		: 'hidden',
            name		: 'subscription_id'
        }, {
	        xtype		: 'textfield',
            fieldLabel	: _('newsletter.label_info_key'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_info_key_desc'),
            name		: 'key',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_info_key_desc'),
            cls			: 'desc-under'
        }, {
	        xtype		: 'textfield',
            fieldLabel	: _('newsletter.label_info_content'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_info_content_desc'),
            name		: 'content',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_info_content_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Newsletter.window.UpdateInfoSubscription.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateInfoSubscription, MODx.Window);

Ext.reg('newsletter-window-subscription-update-info', Newsletter.window.UpdateInfoSubscription);