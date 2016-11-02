Newsletter.grid.SubscriptionsValues = function(config) {
    config = config || {};

	config.tbar = [{
        text	: _('newsletter.subscription_value_create'),
        cls		:'primary-button',
        handler	: this.createSubscriptionValue,
        scope	: this
	}];

    columns = new Ext.grid.ColumnModel({
        columns: [{
            header		: _('newsletter.label_subscription_value_key'),
            dataIndex	: 'key',
            sortable	: true,
            editable	: true,
            width		: 150,
            fixed 		: true,
            editor		: {
            	xtype		: 'textfield'
            }
        }, {
            header		: _('newsletter.label_subscription_value_content'),
            dataIndex	: 'content',
            sortable	: true,
            editable	: true,
            width		: 200,
            fixed		: false,
            editor		: {
            	xtype		: 'textfield'
            }
        }, {
            header		: _('last_modified'),
            dataIndex	: 'editedon',
            sortable	: true,
            editable	: false,
            fixed		: true,
			width		: 200,
			renderer	: this.renderDate
        }]
    });
    
    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'newsletter-grid-subscriptions-values',
        url			: Newsletter.config.connector_url,
        baseParams	: {
        	action		: 'mgr/subscriptions/values/getlist',
        	id			: config.record.id
        },
        autosave	: true,
        save_action	: 'mgr/subscriptions/values/updatefromgrid',
        fields		: ['id', 'subscription_id', 'key', 'content', 'editedon'],
        paging		: true,
        pageSize	: 5,
        sortBy		: 'key'
    });
    
    Newsletter.grid.SubscriptionsValues.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.grid.SubscriptionsValues, MODx.grid.Grid, {
    getMenu: function() {
		return [{
	    	text	: _('newsletter.subscription_value_update'),
			handler	: this.updateSubscriptionValue,
			scope	: this
		}, '-', {
	    	text	: _('newsletter.subscription_value_remove'),
			handler	: this.removeSubscriptionValue,
			scope	: this
		}];
    },
    createSubscriptionValue: function(btn, e) {
        if (this.createSubscriptionValueWindow) {
	        this.createSubscriptionValueWindow.destroy();
        }

        this.createSubscriptionValueWindow = MODx.load({
	        modal 		: true,
	        xtype		: 'newsletter-window-subscription-create-value',
	        record 		: {
		        subscription_id	: this.config.record.id
	        },
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
            		fn			: this.refresh,
		        	scope		: this
		        }
	         }
        });
        
        this.createSubscriptionValueWindow.setValues({
	        subscription_id	: this.config.record.id
        });
        this.createSubscriptionValueWindow.show(e.target);
    },
    updateSubscriptionValue: function(btn, e) {
        if (this.updateSubscriptionValueWindow) {
	        this.updateSubscriptionValueWindow.destroy();
        }
        
        this.updateSubscriptionValueWindow = MODx.load({
	        modal 		: true,
	        xtype		: 'newsletter-window-subscription-update-value',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
            		fn			: this.refresh,
		        	scope		: this
		        }
	         }
        });
        
        this.updateSubscriptionValueWindow.setValues(this.menu.record);
        this.updateSubscriptionValueWindow.show(e.target);
    },
    removeSubscriptionValue: function(btn, e) {
    	MODx.msg.confirm({
        	title 		: _('newsletter.subscription_value_remove'),
        	text		: _('newsletter.subscription_value_remove_confirm'),
        	url			: Newsletter.config.connector_url,
        	params		: {
            	action		: 'mgr/subscriptions/values/remove',
            	id			: this.menu.record.id
            },
            listeners	: {
            	'success'	: {
            		fn			: this.refresh,
            		scope		: this
            	}
            }
    	});
    },
    renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }

        return a;
    }
});

Ext.reg('newsletter-grid-subscriptions-values', Newsletter.grid.SubscriptionsValues);

Newsletter.window.CreateSubscriptionValue = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscription_value_create'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/values/create'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'subscription_id'
        }, {
	        xtype		: 'textfield',
            fieldLabel	: _('newsletter.label_subscription_value_key'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_subscription_value_key_desc'),
            name		: 'key',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_subscription_value_key_desc'),
            cls			: 'desc-under'
        }, {
	        xtype		: 'textarea',
            fieldLabel	: _('newsletter.label_subscription_value_content'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_subscription_value_content_desc'),
            name		: 'content',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_subscription_value_content_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Newsletter.window.CreateSubscriptionValue.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.CreateSubscriptionValue, MODx.Window);

Ext.reg('newsletter-window-subscription-create-value', Newsletter.window.CreateSubscriptionValue);

Newsletter.window.UpdateSubscriptionValue = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscription_value_update'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/values/update'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            xtype		: 'hidden',
            name		: 'subscription_id'
        }, {
	        xtype		: 'textfield',
            fieldLabel	: _('newsletter.label_subscription_value_key'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_subscription_value_key_desc'),
            name		: 'key',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_subscription_value_key_desc'),
            cls			: 'desc-under'
        }, {
	        xtype		: 'textarea',
            fieldLabel	: _('newsletter.label_subscription_value_content'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_subscription_value_content_desc'),
            name		: 'content',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_subscription_value_content_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Newsletter.window.UpdateSubscriptionValue.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateSubscriptionValue, MODx.Window);

Ext.reg('newsletter-window-subscription-update-value', Newsletter.window.UpdateSubscriptionValue);