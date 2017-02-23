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
			text	: _('newsletter.subscriptions_remove_selected'),
			handler	: this.removeSelectedSubscriptions,
			scope	: this
		}, '-', {
			text	: _('newsletter.subscriptions_confirm_selected'),
			name	: 'confirm',
			handler	: this.confirmSelectedSubscriptions,
			scope	: this
		}, {
			text	: _('newsletter.subscriptions_deconfirm_selected'),
			name	: 'deconfirm',
			handler	: this.confirmSelectedSubscriptions,
			scope	: this
		}, '-', {
			text	: _('newsletter.subscriptions_move_selected'),
			handler	: this.moveSelectedSubscriptions,
			scope	: this
		}, '-', {
			text	: _('newsletter.subscriptions_import'),
			handler	: this.importSubscriptions,
			scope	: this
		}, {
			text	: _('newsletter.subscriptions_export'),
			handler	: this.exportSubscriptions,
			scope	: this
		}]
	}, {
		xtype		: 'checkbox',
		name		: 'newsletter-refresh-subscriptions',
        id			: 'newsletter-refresh-subscriptions',
		boxLabel	: _('newsletter.auto_refresh_grid'),
		listeners	: {
			'check'		: {
				fn 			: this.autoRefresh,
				scope 		: this	
			},
		}
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
		}
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
    	id			: 'newsletter-filter-clear-subscriptions',
    	text		: _('filter_clear'),
    	listeners	: {
        	'click'		: {
        		fn			: this.clearFilter,
        		scope		: this
        	}
        }
    }];
    
    sm = new Ext.grid.CheckboxSelectionModel();

    columns = new Ext.grid.ColumnModel({
        columns: [sm, {
            header		: _('newsletter.label_subscription_name'),
            dataIndex	: 'name',
            sortable	: true,
            editable	: true,
            width		: 150,
            fixed		: true,
            editor		: {
            	xtype		: 'textfield'
            }
        }, {
            header		: _('newsletter.label_subscription_email'),
            dataIndex	: 'email',
            sortable	: true,
            editable	: true,
            width		: 150,
            editor		: {
            	xtype		: 'textfield'
            }
        }, {
            header		: _('newsletter.label_subscription_lists'),
            dataIndex	: 'lists_formatted',
            sortable	: true,
            editable	: false,
            width		: 150,
            fixed		: true
        }, {
            header		: _('newsletter.label_subscription_confirmed'),
            dataIndex	: 'active',
            sortable	: true,
            editable	: true,
            width		: 150,
            fixed		: true,
			renderer	: this.renderActive,
			editor		: {
            	xtype		: 'newsletter-combo-confirm'
            }
        }, {
            header		: _('last_modified'),
            dataIndex	: 'editedon',
            sortable	: true,
            editable	: false,
            fixed		: true,
			width		: 200,
			renderer	: this.renderDate
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
        	action		: 'mgr/subscriptions/getlist',
        	context 	: MODx.request.context || MODx.config.default_context
        },
        autosave	: true,
        save_action	: 'mgr/subscriptions/updatefromgrid',
        fields		: ['id', 'context', 'context_name', 'name', 'email', 'lists', 'lists_formatted', 'token', 'active', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'email',
        refreshCmp 	: '',
        gridRefresh	: {
	        timer 		: null,
	        duration	: 30,
	        count 		: 0
        }
    });
    
    Newsletter.grid.Subscriptions.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.grid.Subscriptions, MODx.grid.Grid, {
	autoRefresh: function(tf, nv) {
		var scope = this;
		
		if (nv) {
			scope.config.gridRefresh.timer = setInterval(function() {
				tf.setBoxLabel(_('newsletter.auto_refresh_grid') + ' (' + (scope.config.gridRefresh.duration - scope.config.gridRefresh.count) + ')');
				
				if (0 == (scope.config.gridRefresh.duration - scope.config.gridRefresh.count)) {
					scope.config.gridRefresh.count = 0;
					
					scope.getBottomToolbar().changePage(1);
				} else {
					scope.config.gridRefresh.count++;
				}
			}, 1000);
		} else {
			clearInterval(scope.config.gridRefresh.timer);
		}
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
    rRefresh : function() {
	    if ('string' == typeof this.config.refreshCmp) {
		    Ext.getCmp(this.config.refreshCmp).refresh();
	    } else {
		    for (var i = 0; i < this.config.refreshCmp.length; i++) {
			    Ext.getCmp(this.config.refreshCmp[i]).refresh();
		    }
		}
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
            		fn			: function() {
            			this.getSelectionModel().clearSelections(true);
            			
            			this.rRefresh();
            			this.refresh();
            		},
		        	scope		: this
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
            		fn			: function() {
	            		this.getSelectionModel().clearSelections(true);
            			
            			this.rRefresh();
            			this.refresh();
            		},
		        	scope		: this
		        }
	         }
        });
        
        this.updateSubscriptionWindow.setValues(this.menu.record);
        this.updateSubscriptionWindow.show(e.target);
    },
    removeSubscription: function(btn, e) {
    	MODx.msg.confirm({
        	title 		: _('newsletter.subscription_remove'),
        	text		: _('newsletter.subscription_remove_confirm'),
        	url				: Newsletter.config.connector_url,
        	params		: {
            	action		: 'mgr/subscriptions/remove',
            	id			: this.menu.record.id
            },
            listeners	: {
            	'success'	: {
            		fn			: function() {
	            		this.getSelectionModel().clearSelections(true);
            			
						this.rRefresh();
						this.refresh();
            		},
            		scope		: this
            	}
            }
    	});
    },
    confirmSelectedSubscriptions: function(btn, e) {
    	var cs = this.getSelectedAsList();
    	
        if (cs === false) {
        	return false;
        }
        
        if ('confirm' == btn.name) {
	        var data = {
		    	title	: _('newsletter.subscriptions_confirm_selected'),
		    	text	: _('newsletter.subscriptions_confirm_selected_confirm'),
		    	type	: 1
	        };
	    } else {
		    var data = {
		    	title	: _('newsletter.subscriptions_deconfirm_selected'),
		    	text	: _('newsletter.subscriptions_deconfirm_selected_confirm'),
		    	type	: 0
	        };
		}
		
        MODx.msg.confirm({
        	title 		: data.title,
        	text		: data.text,
        	url			: Newsletter.config.connector_url,
        	params		: {
            	action		: 'mgr/subscriptions/confirmselected',
            	type		: data.type,
            	ids			: cs
            },
            listeners	: {
            	'success'	: {
            		fn			: function() {
	            		this.getSelectionModel().clearSelections(true);
        			
						this.rRefresh();
						this.refresh();
            		},
            		scope		: this
            	}
            }
    	});
    },
    removeSelectedSubscriptions: function(btn, e) {
    	var cs = this.getSelectedAsList();
    	
        if (cs === false) {
        	return false;
        }
        
    	MODx.msg.confirm({
        	title 		: _('newsletter.subscriptions_remove_selected'),
        	text		: _('newsletter.subscriptions_remove_selected_confirm'),
        	url			: Newsletter.config.connector_url,
        	params		: {
            	action		: 'mgr/subscriptions/removeselected',
            	ids			: cs
            },
            listeners	: {
            	'success'	: {
            		fn			: function() {
	            		this.getSelectionModel().clearSelections(true);
        			
						this.rRefresh();
						this.refresh();
            		},
            		scope		: this
            	}
            }
    	});
    },
    moveSelectedSubscriptions: function(btn, e) {
        var cs = this.getSelectedAsList();
    	
        if (cs === false) {
        	return false;
        }
        
        if (this.moveSubscriptionsWindow) {
	        this.moveSubscriptionsWindow.destroy();
        }

        this.moveSubscriptionsWindow = MODx.load({
	        xtype		: 'newsletter-window-subscriptions-move',
	        record		: {
		    	ids		: cs  
	        },
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
            		fn			: function() {
	            		this.getSelectionModel().clearSelections(true);
        			
						this.rRefresh();
						this.refresh();
            		},
		        	scope		: this
		        }
	        }
        });
        
        this.moveSubscriptionsWindow.setValues({
	    	ids		: cs  
        });
        this.moveSubscriptionsWindow.show(e.target);
    },
    importSubscriptions: function(btn, e) {
        if (this.importSubscriptionsWindow) {
	        this.importSubscriptionsWindow.destroy();
        }
        
        this.importSubscriptionsWindow = MODx.load({
	        xtype		: 'newsletter-window-subscriptions-import',
	        record		: this.menu.record,
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
            		fn			: function() {
	            		this.getSelectionModel().clearSelections(true);
	            		
	            		this.rRefresh();
            			this.refresh();
            		},
		        	scope		: this
		        },
		        'failure'	: {
			        fn  		: function(response) {
				    	MODx.msg.alert(_('failure'), response.message);
					},
					scope		: this
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
	        xtype		: 'newsletter-window-subscriptions-export',
	        record		: this.menu.record,
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
            		fn			: function() {
	            		location.href = Newsletter.config.connector_url + '?action=' + this.exportSubscriptionsWindow.baseParams.action + '&download=1&HTTP_MODAUTH=' + MODx.siteId;
            		},
		        	scope		: this
		        },
		        'failure'	: {
			        fn  		: function(response) {
				    	MODx.msg.alert(_('failure'), response.message);
					},
					scope		: this
				}
	         }
        });
        
        this.exportSubscriptionsWindow.setValues(this.menu.record);
        this.exportSubscriptionsWindow.show(e.target);
    },
    renderActive: function(d, c, e) {
	    if (0 == parseInt(d)) {
			c.css = 'red'; 
	    } else if (1 == parseInt(d)) {
		    c.css = 'green';
	    } else if (2 == parseInt(d)) {
			c.css = 'orange';   
		}
		
		if (0 == parseInt(d)) {
			return _('newsletter.subscription_not_confirmed');
	    } else if (1 == parseInt(d)) {
		    return _('newsletter.subscription_confirmed');
	    } else if (2 == parseInt(d)) {
			return _('newsletter.subscription_unsubscribed');
		}
    },
	renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }

        return a;
    }
});

Ext.reg('newsletter-grid-subscriptions', Newsletter.grid.Subscriptions);

Newsletter.window.CreateSubscription = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	height		: 500,
    	width		: 700,
        title 		: _('newsletter.subscription_create'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/create'
        },
        bodyStyle	: 'padding: 0',
        fields		: [{
			xtype		: 'modx-vtabs',
            deferredRender : false,
			items		: [{
				layout		: 'form',
				title		: _('newsletter.subscription_general'),
	            labelSeparator : '',
				items		: [{
	            	html		: '<p>' + _('newsletter.subscription_general_desc') + '</p>',
	                cls			: 'panel-desc'
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
				            fieldLabel	: _('newsletter.label_subscription_name'),
				            description	: MODx.expandHelp ? '' : _('newsletter.label_subscription_name_desc'),
				            name		: 'name',
				            anchor		: '100%'
				        }, {
				        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
				            html		: _('newsletter.label_subscription_name_desc'),
				            cls			: 'desc-under'
				        }]
			        }, {
				        columnWidth	: .2,
				        style		: 'margin-right: 0;',
				        items		: [{
					        xtype		: 'checkbox',
				            fieldLabel	: _('newsletter.label_subscription_confirmed'),
				            description	: MODx.expandHelp ? '' : _('newsletter.label_subscription_confirmed_desc'),
				            name		: 'active',
				            inputValue	: 1
				        }, {
				        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
				            html		: _('newsletter.label_subscription_confirmed_desc'),
				            cls			: 'desc-under'
				        }]
			        }]	
			    }, {
		        	xtype		: 'textfield',
		        	fieldLabel	: _('newsletter.label_subscription_email'),
		        	description	: MODx.expandHelp ? '' : _('newsletter.label_subscription_email_desc'),
		        	name		: 'email',
		        	anchor		: '100%',
		        	allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_subscription_email_desc'),
		            cls			: 'desc-under'
		        }, {
			    	layout		: 'form',
			    	labelSeparator : '',
			    	hidden		: Newsletter.config.context,
			    	items		: [{
			        	xtype		: 'modx-combo-context',
			        	fieldLabel	: _('newsletter.label_subscription_context'),
			        	description	: MODx.expandHelp ? '' : _('newsletter.label_subscription_context_desc'),
			        	name		: 'context',
			        	anchor		: '100%',
			        	allowBlank	: false,
			        	baseParams	: {
				        	action		: 'context/getlist',
				        	exclude		: 'mgr'
			        	}
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			        	html		: _('newsletter.label_subscription_context_desc'),
			        	cls			: 'desc-under'
			        }]
			    }, {
			       	xtype		: 'label',
			       	fieldLabel	: _('newsletter.label_subscription_lists')
			    }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_subscription_lists_desc'),
		            cls			: 'desc-under'
		        }, {
					xtype		: 'newsletter-checkbox-lists'
				}]
			}]
		}]
    });
    
    Newsletter.window.CreateSubscription.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.CreateSubscription, MODx.Window);

Ext.reg('newsletter-window-subscription-create', Newsletter.window.CreateSubscription);

Newsletter.window.UpdateSubscription = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	height		: 500,
    	width		: 700,
        title 		: _('newsletter.subscription_update'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/update'
        },
        bodyStyle	: 'padding: 0',
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
			xtype		: 'modx-vtabs',
            deferredRender : false,
			items		: [{
				layout		: 'form',
				title		: _('newsletter.subscription_general'),
	            labelSeparator : '',
				items		: [{
	            	html		: '<p>' + _('newsletter.subscription_general_desc') + '</p>',
	                cls			: 'panel-desc'
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
				            fieldLabel	: _('newsletter.label_subscription_name'),
				            description	: MODx.expandHelp ? '' : _('newsletter.label_subscription_name_desc'),
				            name		: 'name',
				            anchor		: '100%'
				        }, {
				        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
				            html		: _('newsletter.label_subscription_name_desc'),
				            cls			: 'desc-under'
				        }]
			        }, {
				        columnWidth	: .2,
				        style		: 'margin-right: 0;',
				        items		: [{
					        xtype		: 'checkbox',
				            fieldLabel	: _('newsletter.label_subscription_confirmed'),
				            description	: MODx.expandHelp ? '' : _('newsletter.label_subscription_confirmed_desc'),
				            name		: 'active',
				            inputValue	: 1
				        }, {
				        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
				            html		: _('newsletter.label_subscription_confirmed_desc'),
				            cls			: 'desc-under'
				        }]
			        }]	
			    }, {
		        	xtype		: 'textfield',
		        	fieldLabel	: _('newsletter.label_subscription_email'),
		        	description	: MODx.expandHelp ? '' : _('newsletter.label_subscription_email_desc'),
		        	name		: 'email',
		        	anchor		: '100%',
		        	allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_subscription_email_desc'),
		            cls			: 'desc-under'
		        }, {
			    	layout		: 'form',
			    	labelSeparator : '',
			    	hidden		: Newsletter.config.context,
			    	items		: [{
			        	xtype		: 'modx-combo-context',
			        	fieldLabel	: _('newsletter.label_subscription_context'),
			        	description	: MODx.expandHelp ? '' : _('newsletter.label_subscription_context_desc'),
			        	name		: 'context',
			        	anchor		: '100%',
			        	allowBlank	: false,
			        	baseParams	: {
				        	action		: 'context/getlist',
				        	exclude		: 'mgr'
			        	}
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			        	html		: _('newsletter.label_subscription_context_desc'),
			        	cls			: 'desc-under'
			        }]
			    }, {
			       	xtype		: 'label',
			       	fieldLabel	: _('newsletter.label_subscription_lists')
			    }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_subscription_lists_desc'),
		            cls			: 'desc-under'
		        }, {
					xtype		: 'newsletter-checkbox-lists',
					value		: config.record.lists
				}]
			}, {
				layout		: 'form',
				title		: _('newsletter.subscription_extra'),
	            labelSeparator : '',
				items		: [{
	            	html		: '<p>' + _('newsletter.subscription_extra_desc') + '</p>',
	                cls			: 'panel-desc'
	            }, {
	                xtype			: 'newsletter-grid-subscriptions-extras',
	                record			: config.record,
	                preventRender	: true
	            }]
	        }]
		}]
    });
    
    Newsletter.window.UpdateSubscription.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateSubscription, MODx.Window);

Ext.reg('newsletter-window-subscription-update', Newsletter.window.UpdateSubscription);

Newsletter.window.MoveSelectedSubscriptions = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscriptions_move_selected'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/moveselected'
        },
        fields		: [{
	        html 		: '<p>' + _('newsletter.subscriptions_move_selected_desc') + '</p>',
	        cls			: 'panel-desc',
        }, {
            xtype		: 'hidden',
            name		: 'ids'
        }, {
        	xtype		: 'newsletter-combo-move',
        	fieldLabel	: _('newsletter.label_subscription_move'),
        	description	: MODx.expandHelp ? '' : _('newsletter.label_subscription_move_desc'),
        	name		: 'type',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
        	html		: _('newsletter.label_subscription_move_desc'),
        	cls			: 'desc-under'
        }, {
	       xtype		: 'label',
		   fieldLabel	: _('newsletter.label_subscriptions_lists')
	    }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_subscriptions_lists_desc'),
            cls			: 'desc-under'
        }, {
			xtype		: 'newsletter-checkbox-lists'
		}]
    });
    
    Newsletter.window.MoveSelectedSubscriptions.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.MoveSelectedSubscriptions, MODx.Window);

Ext.reg('newsletter-window-subscriptions-move', Newsletter.window.MoveSelectedSubscriptions);

Newsletter.window.ImportSubscriptions = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.list_import'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/import'
        },
        fields		: [{
	        xtype		: 'fileuploadfield',
            fieldLabel	: _('newsletter.label_import_file'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_import_file_desc'),
            buttonText	: _('upload.buttons.choose'),
            name		: 'file',
            anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_import_file_desc'),
            cls			: 'desc-under'
        }, {
	        xtype		: 'textfield',
            fieldLabel	: _('newsletter.label_import_delimiter'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_import_delimiter_desc'),
            name		: 'delimiter',
            anchor		: '100%',
            allowBlank	: false,
            value 		: ';'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_import_delimiter_desc'),
            cls			: 'desc-under'
        }, {
			xtype		: 'checkboxgroup',
			hideLabel 	: true,
			columns 	: 1,
			items 		: [{
				xtype		: 'checkbox',
				boxLabel	: _('newsletter.label_import_headers'),
				anchor		: '100%',
				name		: 'headers',
				checked		: true,
				inputValue	: 1
			}]
	    }],
        fileUpload	: true,
        saveBtnText	: _('import')
    });
    
    Newsletter.window.ImportSubscriptions.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.ImportSubscriptions, MODx.Window);

Ext.reg('newsletter-window-subscriptions-import', Newsletter.window.ImportSubscriptions);

Newsletter.window.ExportSubscriptions = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.subscriptions_export'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/subscriptions/export'
        },
        fields		: [{
	        xtype		: 'textfield',
            fieldLabel	: _('newsletter.label_import_delimiter'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_import_delimiter_desc'),
            name		: 'delimiter',
            anchor		: '100%',
            allowBlank	: false,
            value 		: ';'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_import_delimiter_desc'),
            cls			: 'desc-under'
        }, {
			xtype		: 'checkboxgroup',
			hideLabel 	: true,
			columns 	: 1,
			items 		: [{
				xtype		: 'checkbox',
				boxLabel	: _('newsletter.label_import_headers'),
				anchor		: '100%',
				name		: 'headers',
				checked		: true,
				inputValue	: 1
			}]
	    }],
        saveBtnText	: _('export')
    });
    
    Newsletter.window.ExportSubscriptions.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.ExportSubscriptions, MODx.Window);

Ext.reg('newsletter-window-subscriptions-export', Newsletter.window.ExportSubscriptions);

Newsletter.combo.SubscriptionConfirmTypes = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        store: new Ext.data.ArrayStore({
            mode	: 'local',
            fields	: ['type','label'],
            data	: [
				['0', _('newsletter.subscription_not_confirmed')],
				['1', _('newsletter.subscription_confirmed')],
				['2', _('newsletter.subscription_unsubscribed')]
            ]
        }),
        remoteSort	: ['label', 'asc'],
        hiddenName	: 'type',
        valueField	: 'type',
        displayField: 'label',
        mode		: 'local'
    });
    
    Newsletter.combo.SubscriptionConfirmTypes.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.SubscriptionConfirmTypes, MODx.combo.ComboBox);

Ext.reg('newsletter-combo-confirm', Newsletter.combo.SubscriptionConfirmTypes);

Newsletter.combo.SubscriptionMove = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        store: new Ext.data.ArrayStore({
            mode	: 'local',
            fields	: ['type','label'],
            data	: [
	            ['add', _('newsletter.subscription_add_list')],
				['remove', _('newsletter.subscription_remove_list')]
            ]
        }),
        remoteSort	: ['label', 'asc'],
        hiddenName	: 'type',
        valueField	: 'type',
        displayField: 'label',
        mode		: 'local'
    });
    
    Newsletter.combo.SubscriptionMove.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.SubscriptionMove, MODx.combo.ComboBox);

Ext.reg('newsletter-combo-move', Newsletter.combo.SubscriptionMove);