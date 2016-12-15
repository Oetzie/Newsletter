Newsletter.grid.SubscriptionsExtras = function(config) {
    config = config || {};

	config.tbar = [{
        text	: _('newsletter.subscription_extra_create'),
        cls		:'primary-button',
        handler	: this.createExtra,
        scope	: this
	}, '->', {
        xtype		: 'textfield',
        name 		: 'newsletter-filter-search-subscriptions-extras',
        id			: 'newsletter-filter-search-subscriptions-extras',
        emptyText	: _('search')+'...',
        listeners	: {
	        'change'	: {
	        	fn			: this.filterSearch,
	        	scope		: this
	        },
	        'render'	: {
		        fn			: function(cmp) {
			        new Ext.KeyMap(cmp.getEl(), {
				        key		: Ext.EventObject.ENTER,
			        	fn		: this.blur,
				        scope	: cmp
			        });
		        },
		        scope		: this
	        }
        }
    }, {
    	xtype		: 'button',
    	cls			: 'x-form-filter-clear',
    	id			: 'newsletter-filter-clear-subscriptions-extras',
    	text		: _('filter_clear'),
    	listeners	: {
        	'click'		: {
        		fn			: this.clearFilter,
        		scope		: this
        	}
        }
    }];

    columns = new Ext.grid.ColumnModel({
        columns: [{
            header		: _('newsletter.label_extra_key'),
            dataIndex	: 'key',
            sortable	: true,
            editable	: false,
            width		: 200,
            fixed 		: true
        }, {
            header		: _('newsletter.label_extra_content'),
            dataIndex	: 'content',
            sortable	: true,
            editable	: false,
            width		: 200,
            fixed		: false
        }]
    });
    
    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'newsletter-grid-subscriptions-extras',
        url			: Newsletter.config.connector_url,
        baseParams	: {
        	action		: 'mgr/subscriptions/extras/getlist',
        	id			: config.record.id
        },
        fields		: ['id', 'subscription_id', 'key', 'content', 'editedon'],
        paging		: true,
        pageSize	: 5,
        sortBy		: 'key'
    });
    
    Newsletter.grid.SubscriptionsExtras.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.grid.SubscriptionsExtras, MODx.grid.Grid, {
	filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
        
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
	    this.getStore().baseParams.query = '';
	    
	    Ext.getCmp('newsletter-filter-search-subscriptions-extras').reset();
	    
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
		return [{
	    	text	: _('newsletter.subscription_extra_update'),
			handler	: this.updateExtra,
			scope	: this
		}, '-', {
	    	text	: _('newsletter.subscription_extra_remove'),
			handler	: this.removeExtra,
			scope	: this
		}];
    },
    createExtra: function(btn, e) {
        if (this.createExteaWindow) {
	        this.createExteaWindow.destroy();
        }

        this.createExteaWindow = MODx.load({
	        modal 		: true,
	        xtype		: 'newsletter-window-subscription-extra-create',
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
        
        this.createExteaWindow.setValues({
	        subscription_id	: this.config.record.id
        });
        this.createExteaWindow.show(e.target);
    },
    updateExtra: function(btn, e) {
        if (this.updateExtraWindow) {
	        this.updateExtraWindow.destroy();
        }
        
        this.updateExtraWindow = MODx.load({
	        modal 		: true,
	        xtype		: 'newsletter-window-subscription-extra-update',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
            		fn			: this.refresh,
		        	scope		: this
		        }
	         }
        });
        
        this.updateExtraWindow.setValues(this.menu.record);
        this.updateExtraWindow.show(e.target);
    },
    removeExtra: function(btn, e) {
    	MODx.msg.confirm({
        	title 		: _('newsletter.subscription_extra_remove'),
        	text		: _('newsletter.subscription_extra_remove_confirm'),
        	url			: Newsletter.config.connector_url,
        	params		: {
            	action		: 'mgr/subscriptions/extras/remove',
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
    renderBoolean: function(d, c) {
    	c.css = 1 == parseInt(d) || d ? 'green' : 'red';
    	
    	return 1 == parseInt(d) || d ? _('yes') : _('no');
    },
    renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }

        return a;
    }
});

Ext.reg('newsletter-grid-subscriptions-extras', Newsletter.grid.SubscriptionsExtras);

Newsletter.window.CreateExtra = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscription_extra_create'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/extras/create'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'subscription_id'
        }, {
	        xtype		: 'textfield',
            fieldLabel	: _('newsletter.label_extra_key'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_extra_key_desc'),
            name		: 'key',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_extra_key_desc'),
            cls			: 'desc-under'
        }, {
	        xtype		: 'textarea',
            fieldLabel	: _('newsletter.label_extra_content'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_extra_content_desc'),
            name		: 'content',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_extra_content_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Newsletter.window.CreateExtra.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.CreateExtra, MODx.Window);

Ext.reg('newsletter-window-subscription-extra-create', Newsletter.window.CreateExtra);

Newsletter.window.UpdateExtra = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscription_extra_update'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/extras/update'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            xtype		: 'hidden',
            name		: 'subscription_id'
        }, {
	        xtype		: 'textfield',
            fieldLabel	: _('newsletter.label_extra_key'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_extra_key_desc'),
            name		: 'key',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_extra_key_desc'),
            cls			: 'desc-under'
        }, {
	        xtype		: 'textarea',
            fieldLabel	: _('newsletter.label_extra_content'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_extra_content_desc'),
            name		: 'content',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_extra_content_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Newsletter.window.UpdateExtra.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateExtra, MODx.Window);

Ext.reg('newsletter-window-subscription-extra-update', Newsletter.window.UpdateExtra);