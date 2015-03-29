Newsletter.grid.Subscriptions = function(config) {
    config = config || {};

	config.tbar = [{
        text	: _('newsletter.subscription_create'),
        cls		:'primary-button',
        handler	: this.createSubscription,
        scope	: this
	}, {
		text	: _('bulk_actions'),
		menu	: [{
			text	: _('newsletter.remove_selected'),
			handler	: this.removeSelectedSubscription,
			scope	: this
		}, '-', {
			text	: _('newsletter.confirm_selected'),
			name	: 'activate',
			handler	: this.activateSelectedSubscription,
			scope	: this
		}, {
			text	: _('newsletter.deconfirm_selected'),
			name	: 'deactivate',
			handler	: this.activateSelectedSubscription,
			scope	: this
		}, '-', {
       		text	: _('newsletter.subscription_import'),
	   		handler	: this.importSubscriptions,
	   		scope	: this
		}, {
       		text	: _('newsletter.subscription_export'),
	   		handler	: this.exportSubscriptions,
	   		scope	: this
		}]
	}, '->', {
    	xtype		: 'newsletter-combo-confirm',
    	name		: 'newsletter-filter-confirm-subscriptions',
        id			: 'newsletter-filter-confirm-subscriptions',
        emptyText	: _('newsletter.filter_confirm'),
        listeners	: {
        	'select'	: {
	            	fn			: this.filterConfirm,
	            	scope		: this   
		    }
		},
		width: 150
    }, {
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
    	cls		: 'x-form-filter-clear',
    	id		: 'newsletter-filter-clear-subscriptions',
    	text	: _('filter_clear'),
    	listeners: {
        	'click': {
        		fn		: this.clearFilter,
        		scope	: this
        	}
        }
    }];
    
    sm = new Ext.grid.CheckboxSelectionModel();

    columns = new Ext.grid.ColumnModel({
        columns: [sm, {
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
            header		: _('newsletter.label_confirm'),
            dataIndex	: 'active',
            sortable	: true,
            editable	: true,
            width		: 100,
            fixed		: true,
			renderer	: this.renderBoolean,
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
    	sm 			: sm,
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
    filterConfirm: function(tf, nv, ov) {
        this.getStore().baseParams.confirm = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
	    this.getStore().baseParams.confirm = '';
	    this.getStore().baseParams.query = '';
	    Ext.getCmp('newsletter-filter-confirm-subscriptions').reset();
	    Ext.getCmp('newsletter-filter-search-subscriptions').reset();
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
	        text	: _('newsletter.subscription_update'),
	        handler	: this.updateSubscription,
	        scope	: this
	    }, '-', {
		    text	: _('newsletter.subscription_remove'),
		    handler	: this.removeSubscription,
		    scope	: this
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
            		fn		: function() {
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
		        	scope		:this
		        }
	         }
        });
        
        this.createSubscriptionWindow.show(e.target);
    },
    activateSelectedSubscription: function(btn, e) {
    	var cs = this.getSelectedAsList();
    	
        if (cs === false) {
        	return false;
        }
        
    	MODx.msg.confirm({
        	title 	: _('newsletter.subscription_activate_selected'),
        	text	: _('newsletter.subscription_activate_selected_confirm'),
        	url		: this.config.url,
        	params	: {
            	action	: 'mgr/subscriptions/activateSelected',
            	ids		: cs,
            	type	: btn.name
            },
            listeners: {
            	'success': {
            		fn		: function() {
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
            		scope	: this
            	}
            }
    	});
    },
    removeSelectedSubscription: function(btn, e) {
    	var cs = this.getSelectedAsList();
    	
        if (cs === false) {
        	return false;
        }
        
    	MODx.msg.confirm({
        	title 	: _('newsletter.subscription_remove_selected'),
        	text	: _('newsletter.subscription_remove_selected_confirm'),
        	url		: this.config.url,
        	params	: {
            	action	: 'mgr/subscriptions/removeSelected',
            	ids		: cs
            },
            listeners: {
            	'success': {
            		fn		: function() {
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
            		scope	: this
            	}
            }
    	});
    },
    importSubscriptions: function(btn, e) {
        if (this.importSubscriptionsWindow) {
	        this.importSubscriptionsWindow.destroy();
        }
        
        this.importSubscriptionsWindow = MODx.load({
	        xtype		: 'newsletter-window-subscription-import',
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
            		fn		: function() {
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
		        	scope		:this
		        }
	         }
        });
        
        this.importSubscriptionsWindow.show(e.target);
    },
    exportSubscriptions: function(btn, e) {
	    if (this.exportSubscriptionsWindow) {
	        this.exportSubscriptionsWindow.destroy();
        }
        
        this.exportSubscriptionsWindow = MODx.load({
	        xtype		: 'newsletter-window-subscription-export',
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
            		fn		: function() {
            			location.href = this.config.url + '?action=mgr/subscriptions/export&download=1&HTTP_MODAUTH=' + MODx.siteId;
            		},
		        	scope		:this
		        }
	         }
        });
        
        this.exportSubscriptionsWindow.show(e.target);
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
            		fn		: function() {
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
		        	scope		:this
		        }
	         }
        });
        
        this.updateSubscriptionWindow.setValues(this.menu.record);
        this.updateSubscriptionWindow.show(e.target);
    },
    removeSubscription: function(btn, e) {
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
            		fn		: function() {
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
            		scope	: this
            	}
            }
    	});
    },
    renderBoolean: function(d, c, e) {
    	c.css = 1 == parseInt(d) || d ? 'green' : 'red';
    	
    	return 1 == parseInt(d) || d ? _('yes') : _('no');
    }
});

Ext.reg('newsletter-grid-subscriptions', Newsletter.grid.Subscriptions);

Newsletter.window.CreateSubscription = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
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
				var items = [];

				Ext.each(Ext.decode(response.responseText).results, function(record) {
					items.push({
				        xtype		: 'checkbox',
			            boxLabel	: record.name + (0 == parseInt(Newsletter.config.context) ? '' : ' (' + record.context + ')'),
			            description	: MODx.expandHelp ? '' : record.description,
			            name		: 'groups[]',
			            inputValue	: record.id
			        });
				});

				var cmp = Ext.getCmp('newsletter-groups');
				
				cmp.add({
		        	xtype		: 'checkboxgroup',
		        	columns		: 2,
		        	items		: items
		        });
		        
		        cmp.doLayout();
			}
		});
	}
});

Ext.reg('newsletter-window-subscription-create', Newsletter.window.CreateSubscription);

Newsletter.window.UpdateSubscription = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
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
				var items = [];

				Ext.each(Ext.decode(response.responseText).results, function(record) {
					items.push({
				        xtype		: 'checkbox',
			            boxLabel	: record.name + (0 == parseInt(Newsletter.config.context) ? '' : ' (' + record.context + ')'),
			            description	: MODx.expandHelp ? '' : record.description,
			            name		: 'groups[]',
			            inputValue	: record.id,
			            checked		: -1 != value.indexOf(record.id) ? true : false
			        });
				});

				var cmp = Ext.getCmp('newsletter-groups');
				
				cmp.add({
		        	xtype		: 'checkboxgroup',
		        	columns		: 2,
		        	items		: items
		        });
		        
		        cmp.doLayout();
			}
		});
	}
});

Ext.reg('newsletter-window-subscription-update', Newsletter.window.UpdateSubscription);

Newsletter.window.ImportSubscriptions = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscription_import'),
        url			: Newsletter.config.connectorUrl,
        baseParams	: {
            action		: 'mgr/subscriptions/import'
        },
        defauls		: {
	        labelAlign	: 'top',
            border		: false
        },
        fields		: [{
	        html 		: _('newsletter.subscription_import_desc'),
	        cls			: 'panel-desc',
	        style		: 'margin-bottom: 10px;'
        }, {
	        xtype		: 'fileuploadfield',
            fieldLabel	: _('file'),
            buttonText	: _('upload.buttons.upload'),
            name		: 'file',
            anchor		: '100%'
        }, {
	        xtype		: 'checkbox',
            boxLabel	: _('newsletter.label_import_groups'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_import_groups_desc'),
            name		: 'groups',
            inputValue	: 1,
            checked		: true
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_import_groups_desc'),
            cls			: 'desc-under',
        }, {
	        xtype		: 'checkbox',
            boxLabel	: _('newsletter.label_import_subscription'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_import_subscription_desc'),
            name		: 'subscriptions',
            inputValue	: 1,
            checked		: true
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_import_subscription_desc'),
            cls			: 'desc-under'
        }],
        fileUpload	: true,
        saveBtnText	: _('import')
    });
    
    Newsletter.window.ImportSubscriptions.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.ImportSubscriptions, MODx.Window);

Ext.reg('newsletter-window-subscription-import', Newsletter.window.ImportSubscriptions);

Newsletter.window.ExportSubscriptions = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscription_export'),
        url			: Newsletter.config.connectorUrl,
        baseParams	: {
            action		: 'mgr/subscriptions/export'
        },
        defauls		: {
	        labelAlign	: 'top',
            border		: false
        },
        fields		: [{
	        html 		: _('newsletter.subscription_export_desc'),
	        cls			: 'panel-desc',
	        style		: 'margin-bottom: 10px;'
        }, {
	        xtype		: 'checkbox',
            boxLabel	: _('newsletter.label_export_groups'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_export_groups_desc'),
            name		: 'groups',
            inputValue	: 1,
            checked		: true
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_export_groups_desc'),
            cls			: 'desc-under',
        }, {
	        xtype		: 'checkbox',
            boxLabel	: _('newsletter.label_export_subscription'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_export_subscription_desc'),
            name		: 'subscriptions',
            inputValue	: 1,
            checked		: true
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_export_subscription_desc'),
            cls			: 'desc-under'
        }],
        saveBtnText	: _('export')
    });
    
    Newsletter.window.ExportSubscriptions.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.ExportSubscriptions, MODx.Window);

Ext.reg('newsletter-window-subscription-export', Newsletter.window.ExportSubscriptions);

Newsletter.combo.ConfirmTypes = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        store: new Ext.data.ArrayStore({
            mode	: 'local',
            fields	: ['type','label'],
            data	: [
	            ['1', _('newsletter.confirmed')],
               	['0', _('newsletter.notconfirmed')]
            ]
        }),
        remoteSort	: ['label', 'asc'],
        hiddenName	: 'type',
        valueField	: 'type',
        displayField: 'label',
        mode		: 'local'
    });
    
    Newsletter.combo.ConfirmTypes.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.ConfirmTypes, MODx.combo.ComboBox);

Ext.reg('newsletter-combo-confirm', Newsletter.combo.ConfirmTypes);