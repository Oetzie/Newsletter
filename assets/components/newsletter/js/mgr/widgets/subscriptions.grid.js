Newsletter.grid.Subscriptions = function(config) {
    config = config || {};

	config.tbar = [{
        text	: _('newsletter.subscription_create'),
        handler	: this.createSubscription
   }, '->', {
        xtype		: 'textfield',
        name 		: 'newsletter-filter-search-subscriptions',
        id			: 'newsletter-filter-search-subscriptions',
        emptyText	: _('search')+'...',
        listeners	: {
	        'change'	: {
	        	fn			: this.filterSearch,
	        	scope		: this
	        },
	        'render'		: {
		        fn			: function(cmp) {
			        new Ext.KeyMap(cmp.getEl(), {
				        key		: Ext.EventObject.ENTER,
			        	fn		: this.blur,
				        scope	: cmp
			        });
		        },
		        scope	: this
	        }
        }
    }, {
    	xtype	: 'button',
    	id		: 'newsletter-filter-clear-subscriptions',
    	text	: _('filter_clear'),
    	listeners: {
        	'click': {
        		fn		: this.clearFilter,
        		scope	: this
        	}
        }
    }];

    columns = new Ext.grid.ColumnModel({
        columns: [{
            header		: _('newsletter.label_email'),
            dataIndex	: 'email',
            sortable	: true,
            editable	: true,
            width		: 150,
            editor		: {
            	xtype		: 'textfield'
            }
        }, {
            header		: _('newsletter.label_name'),
            dataIndex	: 'name',
            sortable	: true,
            editable	: true,
            width		: 150,
            fixed		: true,
            editor		: {
            	xtype		: 'textfield'
            }
        }, {
            header		: _('newsletter.label_groups'),
            dataIndex	: 'group_names',
            sortable	: true,
            editable	: false,
            width		: 150,
            fixed		: true
        }, {
            header		: _('newsletter.label_active'),
            dataIndex	: 'active',
            sortable	: true,
            editable	: true,
            width		: 100,
            fixed		: true,
			renderer	: this.renderActive,
			editor		: {
            	xtype		: 'modx-combo-boolean'
            }
        }, {
            header		: _('last_modified'),
            dataIndex	: 'editedon',
            sortable	: true,
            editable	: false,
            fixed		: true,
			width		: 200
        }]
    });
    
    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'newsletter-grid-subscriptions',
        url			: Newsletter.config.connectorUrl,
        baseParams	: {
        	action		: 'mgr/subscriptions/getList'
        },
        autosave	: true,
        save_action	: 'mgr/subscriptions/updateFromGrid',
        fields		: ['id', 'name', 'email', 'groups', 'group_names', 'active', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'email'
    });
    
    Newsletter.grid.Subscriptions.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.grid.Subscriptions, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
	    this.getStore().baseParams.query = '';
	    Ext.getCmp('newsletter-filter-search-subscriptions').reset();
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
	        text	: _('newsletter.subscription_update'),
	        handler	: this.updateSubscription
	    }, '-', {
		    text	: _('newsletter.subscription_remove'),
		    handler	: this.removeSubscription
		 }];
    },
    createSubscription: function(btn, e) {
        if (this.createSubscriptionWindow) {
	        this.createSubscriptionWindow.destroy();
        }
        
        this.createSubscriptionWindow = MODx.load({
	        xtype		: 'newsletter-window-subscription-create',
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
		        	fn			:this.refresh,
		        	scope		:this
		        }
	         }
        });
        
        
        this.createSubscriptionWindow.show(e.target);
    },
    updateSubscription: function(btn, e) {
        if (this.updateSubscriptionWindow) {
	        this.updateSubscriptionWindow.destroy();
        }
        
        this.updateSubscriptionWindow = MODx.load({
	        xtype		: 'newsletter-window-subscription-update',
	        record		: this.menu.record,
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
		        	fn			:this.refresh,
		        	scope		:this
		        }
	         }
        });
        
        this.updateSubscriptionWindow.setValues(this.menu.record);
        this.updateSubscriptionWindow.show(e.target);
    },
    removeSubscription: function() {
    	MODx.msg.confirm({
        	title 	: _('newsletter.subscription_remove'),
        	text	: _('newsletter.subscription_remove_confirm'),
        	url		: this.config.url,
        	params	: {
            	action	: 'mgr/subscriptions/remove',
            	id		: this.menu.record.id
            },
            listeners: {
            	'success': {
            		fn		: this.refresh,
            		scope	: this
            	}
            }
    	});
    },
    renderActive: function(d, c) {
    	c.css = 1 == parseInt(d) || d ? 'green' : 'red';
    	
    	return 1 == parseInt(d) || d ? _('yes') : _('no');
    }
});

Ext.reg('newsletter-grid-subscriptions', Newsletter.grid.Subscriptions);

Newsletter.window.CreateSubscription = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        title 		: _('newsletter.subscription_create'),
        url			: Newsletter.config.connectorUrl,
        baseParams	: {
            action		: 'mgr/subscriptions/create'
        },
        defauls		: {
	        labelAlign	: 'top',
            border		: false
        },
        fields		: [{
        	layout		: 'column',
        	border		: false,
            defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
        	items		: [{
	        	columnWidth	: .9,
	        	items		: [{
			        xtype		: 'textfield',
		            fieldLabel	: _('newsletter.label_name'),
		            description	: MODx.expandHelp ? '' : _('newsletter.label_name_desc'),
		            name		: 'name',
		            anchor		: '100%'
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_name_desc'),
		            cls			: 'desc-under'
		        }]
	        }, {
		        columnWidth	: .1,
		        style		: 'margin-right: 0;',
		        items		: [{
			        xtype		: 'checkbox',
		            fieldLabel	: _('newsletter.label_active'),
		            description	: MODx.expandHelp ? '' : _('newsletter.label_active_desc'),
		            name		: 'active',
		            inputValue	: 1,
		            checked		: true
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_active_desc'),
		            cls			: 'desc-under'
		        }]
	        }]	
	    }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('newsletter.label_email'),
        	description	: MODx.expandHelp ? '' : _('newsletter.label_email_desc'),
        	name		: 'email',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_email_desc'),
            cls			: 'desc-under'
        }, {
	       	xtype		: 'label',
	       	fieldLabel	: _('newsletter.label_groups')
	    }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_groups_desc'),
            cls			: 'desc-under'
        }, {
	        xtype		: 'container',
	        id			: 'newsletter-groups',
	        listeners	: {
		        'render'	: {
		        	fn 			: this.groups,
					scope 		: this
				}
	        }
        }, {
        	xtype		: 'label',
            html		: '&nbsp;',
            cls			: 'desc-under'
        }]
    });
    
    Newsletter.window.CreateSubscription.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.CreateSubscription, MODx.Window, {
	groups : function() {
		Ext.Ajax.request({
			url		: Newsletter.config.connectorUrl,
			params	: {
            	action		: 'mgr/groups/getlist'
			},
			success : function(response, opts) {
				var response = Ext.decode(response.responseText);
				
				Ext.each(response.results, function(record) {
					Ext.getCmp('newsletter-groups').add({
				        xtype		: 'checkbox',
			            boxLabel	: record.name + (0 == parseInt(Newsletter.config.context) ? '' : ' (' + record.context + ')'),
			            description	: MODx.expandHelp ? '' : record.description,
			            name		: 'groups[]',
			            inputValue	: record.id
			        });
				});
			}
		});
	}
});

Ext.reg('newsletter-window-subscription-create', Newsletter.window.CreateSubscription);

Newsletter.window.UpdateSubscription = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        title 		: _('newsletter.subscription_update'),
        url			: Newsletter.config.connectorUrl,
        baseParams	: {
            action		: 'mgr/subscriptions/update'
        },
        defauls		: {
	        labelAlign	: 'top',
            border		: false
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
        	layout		: 'column',
        	border		: false,
            defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
        	items		: [{
	        	columnWidth	: .9,
	        	items		: [{
			        xtype		: 'textfield',
		            fieldLabel	: _('newsletter.label_name'),
		            description	: MODx.expandHelp ? '' : _('newsletter.label_name_desc'),
		            name		: 'name',
		            anchor		: '100%'
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_name_desc'),
		            cls			: 'desc-under'
		        }]
	        }, {
		        columnWidth	: .1,
		        style		: 'margin-right: 0;',
		        items		: [{
			        xtype		: 'checkbox',
		            fieldLabel	: _('newsletter.label_active'),
		            description	: MODx.expandHelp ? '' : _('newsletter.label_active_desc'),
		            name		: 'active',
		            inputValue	: 1
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_active_desc'),
		            cls			: 'desc-under'
		        }]
	        }]	
	    }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('newsletter.label_email'),
        	description	: MODx.expandHelp ? '' : _('newsletter.label_email_desc'),
        	name		: 'email',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_email_desc'),
            cls			: 'desc-under'
        }, {
	       	xtype		: 'label',
	       	fieldLabel	: _('newsletter.label_groups')
	    }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_groups_desc'),
            cls			: 'desc-under'
        }, {
	        xtype		: 'container',
	        id			: 'newsletter-groups',
	        listeners	: {
		        'render'	: {
		        	fn 			: this.groups,
					scope 		: this
				}
	        }
        }, {
        	xtype		: 'label',
            html		: '&nbsp;',
            cls			: 'desc-under'
        }]
    });
    
    Newsletter.window.UpdateSubscription.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateSubscription, MODx.Window, {
	groups : function() {
		var value = this.record.groups;
		
		Ext.Ajax.request({
			url		: Newsletter.config.connectorUrl,
			params	: {
            	action		: 'mgr/groups/getlist'
			},
			success : function(response, opts) {
				var response = Ext.decode(response.responseText);
				
				Ext.each(response.results, function(record) {
					Ext.getCmp('newsletter-groups').add({
				        xtype		: 'checkbox',
			            boxLabel	: record.name + (0 == parseInt(Newsletter.config.context) ? '' : ' (' + record.context + ')'),
			            description	: MODx.expandHelp ? '' : record.description,
			            name		: 'groups[]',
			            inputValue	: record.id,
			            checked		: -1 != value.split(',').indexOf(record.id.toString()) ? true : false
			        });
				});
			}
		});
	}
});

Ext.reg('newsletter-window-subscription-update', Newsletter.window.UpdateSubscription);