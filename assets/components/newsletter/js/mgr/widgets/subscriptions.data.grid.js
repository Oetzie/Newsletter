Newsletter.grid.SubscriptionsData = function(config) {
    config = config || {};

	config.tbar = [{
        text		: _('newsletter.subscription_data_create'),
        cls			: 'primary-button',
        handler		: this.createData,
        scope		: this
	}, '->', {
        xtype		: 'textfield',
        name 		: 'newsletter-filter-search-subscriptions-data',
        id			: 'newsletter-filter-search-subscriptions-data',
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
    	id			: 'newsletter-filter-clear-subscriptions-data',
    	text		: _('filter_clear'),
    	listeners	: {
        	'click'		: {
        		fn			: this.clearFilter,
        		scope		: this
        	}
        }
    }];
    
    expander = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="desc">{description}</p>'
        )
    });

    columns = new Ext.grid.ColumnModel({
        columns: [expander, {
            header		: _('newsletter.label_data_key'),
            dataIndex	: 'key_formatted',
            sortable	: true,
            editable	: false,
            width		: 200,
            fixed 		: true
        }, {
            header		: _('newsletter.label_data_content'),
            dataIndex	: 'content_formatted',
            sortable	: true,
            editable	: false,
            width		: 200,
            fixed		: false
        }]
    });
    
    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'newsletter-grid-subscriptions-data',
        url			: Newsletter.config.connector_url,
        baseParams	: {
        	action		: 'mgr/subscriptions/data/getlist',
        	id			: config.record.id
        },
        fields		: ['key', 'content', 'key_formatted', 'content_formatted',  'description', 'subscription'],
        paging		: true,
        pageSize	: 5,
        sortBy		: 'key',
        plugins		: expander
    });
    
    Newsletter.grid.SubscriptionsData.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.grid.SubscriptionsData, MODx.grid.Grid, {
	filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
        
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
	    this.getStore().baseParams.query = '';
	    
	    Ext.getCmp('newsletter-filter-search-subscriptions-data').reset();
	    
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
		return [{
	    	text	: _('newsletter.subscription_data_update'),
			handler	: this.updateData,
			scope	: this
		}, '-', {
	    	text	: _('newsletter.subscription_data_remove'),
			handler	: this.removeData,
			scope	: this
		}];
    },
    createData: function(btn, e) {
        if (this.createDataWindow) {
	        this.createDataWindow.destroy();
        }
        
        var record = Ext.apply({}, {
	        id : this.config.record.id
        });

        this.createDataWindow = MODx.load({
	        modal 		: true,
	        xtype		: 'newsletter-window-subscription-data-create',
	        record 		: record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
            		fn			: this.refresh,
		        	scope		: this
		        }
	         }
        });
        
        this.createDataWindow.setValues(record);
        this.createDataWindow.show(e.target);
    },
    updateData: function(btn, e) {
        if (this.updateDataWindow) {
	        this.updateDataWindow.destroy();
        }
        
        var record = Ext.apply(this.menu.record, {
	        id : this.config.record.id
        });
        
        this.updateDataWindow = MODx.load({
	        modal 		: true,
	        xtype		: 'newsletter-window-subscription-data-update',
	        record		: record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
            		fn			: this.refresh,
		        	scope		: this
		        }
	         }
        });
        
        this.updateDataWindow.setValues(record);
        this.updateDataWindow.show(e.target);
    },
    removeData: function(btn, e) {
    	MODx.msg.confirm({
        	title 		: _('newsletter.subscription_data_remove'),
        	text		: _('newsletter.subscription_data_remove_confirm'),
        	url			: Newsletter.config.connector_url,
        	params		: {
            	action		: 'mgr/subscriptions/data/remove',
            	id			: this.config.record.id,
            	key			: this.menu.record.key
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

Ext.reg('newsletter-grid-subscriptions-data', Newsletter.grid.SubscriptionsData);

Newsletter.window.CreateData = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscription_data_create'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/data/create'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
	        xtype		: 'textfield',
            fieldLabel	: _('newsletter.label_data_key'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_data_key_desc'),
            name		: 'key',
            anchor		: '100%',
            allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_data_key_desc'),
            cls			: 'desc-under'
        }, {
	        xtype		: 'textarea',
            fieldLabel	: _('newsletter.label_data_content'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_data_content_desc'),
            name		: 'content',
            anchor		: '100%',
            allowBlank	: true
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_data_content_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Newsletter.window.CreateData.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.CreateData, MODx.Window);

Ext.reg('newsletter-window-subscription-data-create', Newsletter.window.CreateData);

Newsletter.window.UpdateData = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscription_data_update'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/data/update'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
	        xtype		: 'textfield',
            fieldLabel	: _('newsletter.label_data_key'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_data_key_desc'),
            name		: 'key',
            anchor		: '100%',
            allowBlank	: false,
            cls			: 'x-static-text-field x-item-disabled',
            readOnly	: true
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_data_key_desc'),
            cls			: 'desc-under'
        }, {
	        xtype		: 'textarea',
            fieldLabel	: _('newsletter.label_extra_content'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_data_content_desc'),
            name		: 'content',
            anchor		: '100%',
            allowBlank	: true
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_data_content_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Newsletter.window.UpdateData.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateData, MODx.Window);

Ext.reg('newsletter-window-subscription-data-update', Newsletter.window.UpdateData);