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
			text	: _('newsletter.move_selected'),
			handler	: this.moveSelectedSubscription,
			scope	: this
		}]
	}, '->', {
    	xtype		: 'modx-combo-context',
    	hidden		: 0 == parseInt(Newsletter.config.context) ? true : false,
    	name		: 'newsletter-filter-context-subscriptions',
        id			: 'newsletter-filter-context-subscriptions',
        emptyText	: _('newsletter.filter_context'),
        listeners	: {
        	'select'	: {
	            	fn			: this.filterContext,
	            	scope		: this   
		    }
		},
		width: 250
    }, {
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
		width: 200
    }, '-', {
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
            header		: _('newsletter.label_email'),
            dataIndex	: 'email',
            sortable	: true,
            editable	: true,
            width		: 150,
            editor		: {
            	xtype		: 'textfield'
            }
        }, {
            header		: _('newsletter.label_lists'),
            dataIndex	: 'lists_names',
            sortable	: true,
            editable	: false,
            width		: 150,
            fixed		: true
        }, {
            header		: _('newsletter.label_confirmed'),
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
        }, {
            header		: _('newsletter.label_context'),
            dataIndex	: 'context_name',
            sortable	: true,
            hidden		: true,
            editable	: false
        }]
    });
    
    Ext.applyIf(config, {
    	sm 			: sm,
    	cm			: columns,
        id			: 'newsletter-grid-subscriptions',
        url			: Newsletter.config.connector_url,
        baseParams	: {
        	action		: 'mgr/subscriptions/getList'
        },
        autosave	: true,
        save_action	: 'mgr/subscriptions/updateFromGrid',
        fields		: ['id', 'context', 'context_key', 'context_name', 'name', 'email', 'lists', 'lists_names', 'token', 'active', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'email',
        grouping	: 0 == parseInt(Newsletter.config.context) ? false : true,
        groupBy		: 'context_name',
        singleText	: _('newsletter.subscription'),
        pluralText	: _('newsletter.subscriptions')
    });
    
    Newsletter.grid.Subscriptions.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.grid.Subscriptions, MODx.grid.Grid, {
	filterContext: function(tf, nv, ov) {
        this.getStore().baseParams.context = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },
    filterConfirm: function(tf, nv, ov) {
        this.getStore().baseParams.confirm = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
	    this.getStore().baseParams.context = '';
	    this.getStore().baseParams.confirm = '';
	    this.getStore().baseParams.query = '';
	    Ext.getCmp('newsletter-filter-context-subscriptions').reset();
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
	        text	: _('newsletter.subscription_info'),
	        handler	: this.updateInfoSubscription,
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
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
            		fn		: function() {
	            		Ext.getCmp('newsletter-grid-lists').refresh();
	            		
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
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
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
            		fn		: function() {
	            		Ext.getCmp('newsletter-grid-lists').refresh();
	            		
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
    updateInfoSubscription: function(btn, e) {
        if (this.updateInfoSubscriptionWindow) {
	        this.updateInfoSubscriptionWindow.destroy();
        }
        
        this.updateInfoSubscriptionWindow = MODx.load({
	        xtype		: 'newsletter-window-subscription-info-update',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        buttons		: [{
	    		text    	: _('ok'),
	    		cls			: 'primary-button',
	    		handler		: function() {
		    		Ext.getCmp('newsletter-grid-lists').refresh();
	            		
        			this.getSelectionModel().clearSelections(true);
        			this.refresh();
            			
	    			this.updateInfoSubscriptionWindow.close();
	    		},
	    		scope		: this
			}]
        });
        
        this.updateInfoSubscriptionWindow.setValues(this.menu.record);
        this.updateInfoSubscriptionWindow.show(e.target);
    },
    activateSelectedSubscription: function(btn, e) {
    	var cs = this.getSelectedAsList();
    	
        if (cs === false) {
        	return false;
        }
        
    	MODx.msg.confirm({
        	title 	: 'activate' == btn.name ? _('newsletter.subscription_activate_selected') : _('newsletter.subscription_deactivate_selected'),
        	text	: 'activate' == btn.name ? _('newsletter.subscription_activate_selected_confirm') : _('newsletter.subscription_deactivate_selected_confirm'),
        	url		: this.config.url,
        	params	: {
            	action	: 'mgr/subscriptions/activateSelected',
            	ids		: cs,
            	type	: btn.name
            },
            listeners: {
            	'success': {
            		fn		: function() {
	            		Ext.getCmp('newsletter-grid-lists').refresh();
	            		
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
	            		Ext.getCmp('newsletter-grid-lists').refresh();
	            		
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
            		scope	: this
            	}
            }
    	});
    },
    moveSelectedSubscription: function(btn, e) {
        if (this.moveSubscriptionWindow) {
	        this.moveSubscriptionWindow.destroy();
        }
        
        var cs = this.getSelectedAsList();
    	
        if (cs === false) {
        	return false;
        }
        
        var record = {
	    	ids		: cs  
        };
        
        this.moveSubscriptionWindow = MODx.load({
	        xtype		: 'newsletter-window-subscription-move',
	        record		: record,
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
            		fn		: function() {
	            		Ext.getCmp('newsletter-grid-lists').refresh();
	            		
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
		        	scope		:this
		        }
	        }
        });
        
        this.moveSubscriptionWindow.setValues(record);
        this.moveSubscriptionWindow.show(e.target);
    },
    removeSubscription: function(btn, e) {
    	MODx.msg.confirm({
        	title 	: _('newsletter.subscription_remove'),
        	text	: _('newsletter.subscription_remove_confirm'),
        	url		: Newsletter.config.connector_url,
        	params	: {
            	action	: 'mgr/subscriptions/remove',
            	id		: this.menu.record.id
            },
            listeners: {
            	'success': {
            		fn		: function() {
	            		Ext.getCmp('newsletter-grid-lists').refresh();
	            		
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
        url			: Newsletter.config.connector_url,
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
	        	columnWidth	: .8,
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
		        columnWidth	: .2,
		        style		: 'margin-right: 0;',
		        items		: [{
			        xtype		: 'checkbox',
		            fieldLabel	: _('newsletter.label_confirmed'),
		            description	: MODx.expandHelp ? '' : _('newsletter.label_confirmed_desc'),
		            name		: 'active',
		            inputValue	: 1,
		            checked		: true
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_confirmed_desc'),
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
	    	layout		: 'form',
	    	hidden		: 0 == parseInt(Newsletter.config.context) ? true : false,
			defaults 	: {
				labelSeparator : ''	
			},
	    	items		: [{
	        	xtype		: 'modx-combo-context',
	        	fieldLabel	: _('newsletter.label_context'),
	        	description	: MODx.expandHelp ? '' : _('newsletter.label_context_desc'),
	        	name		: 'context',
	        	anchor		: '100%',
	        	allowBlank	: false,
	        	value		: MODx.config.default_context
	        }, {
	        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
	        	html		: _('newsletter.label_context_desc'),
	        	cls			: 'desc-under'
	        }]
	    }, {
	       	xtype		: 'label',
	       	fieldLabel	: _('newsletter.label_lists')
	    }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_lists_desc'),
            cls			: 'desc-under'
        }, {
			xtype		: 'newsletter-combo-lists'
		}]
    });
    
    Newsletter.window.CreateSubscription.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.CreateSubscription, MODx.Window);

Ext.reg('newsletter-window-subscription-create', Newsletter.window.CreateSubscription);

Newsletter.window.UpdateSubscription = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscription_update'),
        url			: Newsletter.config.connector_url,
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
	        	columnWidth	: .8,
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
		        columnWidth	: .2,
		        style		: 'margin-right: 0;',
		        items		: [{
			        xtype		: 'checkbox',
		            fieldLabel	: _('newsletter.label_confirmed'),
		            description	: MODx.expandHelp ? '' : _('newsletter.label_confirmed_desc'),
		            name		: 'active',
		            inputValue	: 1
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_confirmed_desc'),
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
	    	layout		: 'form',
	    	hidden		: 0 == parseInt(Newsletter.config.context) ? true : false,
			defaults 	: {
				labelSeparator : ''	
			},
	    	items		: [{
	        	xtype		: 'modx-combo-context',
	        	fieldLabel	: _('newsletter.label_context'),
	        	description	: MODx.expandHelp ? '' : _('newsletter.label_context_desc'),
	        	name		: 'context',
	        	anchor		: '100%',
	        	allowBlank	: false,
	        	value		: MODx.config.default_context
	        }, {
	        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
	        	html		: _('newsletter.label_context_desc'),
	        	cls			: 'desc-under'
	        }]
	    }, {
	       	xtype		: 'label',
	       	fieldLabel	: _('newsletter.label_lists')
	    }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_lists_desc'),
            cls			: 'desc-under'
        }, {
			xtype		: 'newsletter-combo-lists',
			value		: config.record.lists
		}]
    });
    
    Newsletter.window.UpdateSubscription.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateSubscription, MODx.Window);

Ext.reg('newsletter-window-subscription-update', Newsletter.window.UpdateSubscription);

Newsletter.window.UpdateInfoSubscription = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscription_info'),
        width		: 500,
        defauls		: {
	        labelAlign	: 'top',
            border		: false
        },
        fields		: [{
	        html 		: '<p>' + _('newsletter.subscription_info_desc') + '</p>',
	        cls			: 'panel-desc',
	        style		: 'margin-bottom: 10px;'
        }, {
			xtype			: 'newsletter-grid-subscriptions-info',
			record 			: config.record,
			preventRender	: true
		}]
    });
    
    Newsletter.window.UpdateInfoSubscription.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateInfoSubscription, MODx.Window);

Ext.reg('newsletter-window-subscription-info-update', Newsletter.window.UpdateInfoSubscription);

Newsletter.window.MoveSelectedSubscription = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscription_move_selected'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/moveSelected'
        },
        defauls		: {
	        labelAlign	: 'top',
            border		: false
        },
        fields		: [{
	        html 		: '<p>' + _('newsletter.subscription_move_selected_desc') + '</p>',
	        cls			: 'panel-desc',
	        style		: 'margin-bottom: 10px;'
        }, {
            xtype		: 'hidden',
            name		: 'ids'
        }, {
        	xtype		: 'newsletter-combo-move',
        	fieldLabel	: _('newsletter.label_move'),
        	description	: MODx.expandHelp ? '' : _('newsletter.label_move_desc'),
        	name		: 'type',
        	anchor		: '100%',
        	allowBlank	: false,
        	value		: 'add'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
        	html		: _('newsletter.label_move_desc'),
        	cls			: 'desc-under'
        }, {
	       xtype		: 'label',
		   fieldLabel	: _('newsletter.label_lists_subscriptions')
	    }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_lists_subscriptions_desc'),
            cls			: 'desc-under'
        }, {
			xtype		: 'newsletter-combo-lists',
			value		: config.record.lists
		}]
    });
    
    Newsletter.window.MoveSelectedSubscription.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.MoveSelectedSubscription, MODx.Window);

Ext.reg('newsletter-window-subscription-move', Newsletter.window.MoveSelectedSubscription);

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

Newsletter.combo.Move = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        store: new Ext.data.ArrayStore({
            mode	: 'local',
            fields	: ['type','label'],
            data	: [
	            ['add', _('newsletter.add')],
               	['remove', _('newsletter.remove')]
            ]
        }),
        remoteSort	: ['label', 'asc'],
        hiddenName	: 'type',
        valueField	: 'type',
        displayField: 'label',
        mode		: 'local'
    });
    
    Newsletter.combo.Move.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.Move, MODx.combo.ComboBox);

Ext.reg('newsletter-combo-move', Newsletter.combo.Move);