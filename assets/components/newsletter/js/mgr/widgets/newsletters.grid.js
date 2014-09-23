Newsletter.grid.Newsletters = function(config) {
    config = config || {};

	config.tbar = [{
        text	: _('newsletter.newsletter_create'),
        handler	: this.createNewsletter
   }, '->', {
    	xtype		: 'modx-combo-context',
    	hidden		: 0 == parseInt(Newsletter.config.context) ? true : false,
    	name		: 'newsletter-filter-context-newsletters',
        id			: 'newsletter-filter-context-newsletters',
        emptyText	: _('newsletter.filter_context'),
        listeners	: {
        	'select'	: {
	            	fn			: this.filterContext,
	            	scope		: this   
		    }
		},
		width: 250
    }, '-', {
        xtype		: 'textfield',
        name 		: 'newsletter-filter-search-newsletters',
        id			: 'newsletter-filter-search-newsletters',
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
    	id		: 'newsletter-filter-clear-newsletters',
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
            header		: _('newsletter.label_name'),
            dataIndex	: 'resource_name_alias',
            sortable	: true,
            editable	: false,
            width		: 150
        }, {
            header		: _('newsletter.label_published'),
            dataIndex	: 'resource_published',
            sortable	: true,
            editable	: false,
            width		: 100,
            fixed		: true,
			renderer	: this.renderPublished
        }, {
            header		: _('newsletter.label_send'),
            dataIndex	: 'send',
            sortable	: true,
            editable	: false,
            width		: 100,
            fixed		: true,
			renderer	: this.renderSend
        }, {
            header		: _('last_modified'),
            dataIndex	: 'editedon',
            sortable	: true,
            editable	: false,
            fixed		: true,
			width		: 200
        }, {
            header		: _('newsletter.label_context'),
            dataIndex	: 'resource_context_key',
            sortable	: true,
            hidden		: true,
            editable	: false
        }]
    });
    
    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'newsletter-grid-newsletters',
        url			: Newsletter.config.connectorUrl,
        baseParams	: {
        	action		: 'mgr/newsletters/getList'
        },
        autosave	: true,
        save_action	: 'mgr/newsletters/updateFromGrid',
        fields		: ['id', 'resource_id', 'resource_url', 'resource_name', 'resource_name_alias', 'resource_context_key', 'resource_published', 'groups', 'emails', 'send', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        grouping	: 0 == parseInt(Newsletter.config.context) ? false : true,
        groupBy		: 'resource_context_key',
        singleText	: _('newsletter.newsletter'),
        pluralText	: _('newsletter.newsletters')
    });
    
    Newsletter.grid.Newsletters.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.grid.Newsletters, MODx.grid.Grid, {
	filterContext: function(tf, nv, ov) {
        this.getStore().baseParams.context = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
    	this.getStore().baseParams.context = '';
	    this.getStore().baseParams.query = '';
	    Ext.getCmp('newsletter-filter-context-newsletters').reset();
	    Ext.getCmp('newsletter-filter-search-newsletters').reset();
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
    	menu = [{
	        text	: _('newsletter.newsletter_update'),
	        handler	: this.updateNewsletter
	    }, '-', {
	        text	: _('newsletter.newsletter_preview'),
	        handler	: this.previewNewsletter
	    }];
	    
	    if (1 == parseInt(this.menu.record.resource_published)) {
		    if (0 == parseInt(this.menu.record.send) || 2 == parseInt(this.menu.record.send)) {
			    menu.push({
			        text	: _('newsletter.newsletter_send'),
			        handler	: this.sendNewsletter
			    });
		    }
		}
	    
	    if (2 == parseInt(this.menu.record.send)) {
		    menu.push({
		        text	: _('newsletter.newsletter_cancel'),
		        handler	: this.cancelNewsletter
		    });
	    }
	    
	    menu.push('-', {
		    text	: _('newsletter.newsletter_remove'),
		    handler	: this.removeNewsletter
		 });
	    
        return menu;
    },
    createNewsletter: function(btn, e) {
        if (this.createNewsletterWindow) {
	        this.createNewsletterWindow.destroy();
        }
        
        this.createNewsletterWindow = MODx.load({
	        xtype		: 'newsletter-window-newsletter-create',
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: this.refresh,
		        	scope		: this
		        }
	         }
        });
        
        
        this.createNewsletterWindow.show(e.target);
    },
    updateNewsletter: function(btn, e) {
        if (this.updateNewsletterWindow) {
	        this.updateNewsletterWindow.destroy();
        }
        
        this.updateNewsletterWindow = MODx.load({
	        xtype		: 'newsletter-window-newsletter-update',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: this.refresh,
		        	scope		: this
		        }
	         }
        });
        
        this.updateNewsletterWindow.setValues(this.menu.record);
        this.updateNewsletterWindow.show(e.target);
    },
    previewNewsletter: function(btn, e) {
        if (this.previewNewsletterWindow) {
	        this.previewNewsletterWindow.destroy();
        }
        
        this.previewNewsletterWindow = MODx.load({
	        xtype		: 'newsletter-window-newsletter-preview',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        modal		: true,
			buttons		: [{
	    		text    	: _('ok'),
	    		handler		: function() {
	    			this.previewNewsletterWindow.close();
	    		},
	    		scope		: this
			}]
        });
        
        this.previewNewsletterWindow.setValues(this.menu.record);
        this.previewNewsletterWindow.show(e.target);
    },
    sendNewsletter: function(btn, e) {
        if (this.sendNewsletterWindow) {
	        this.sendNewsletterWindow.destroy();
        }
        
        this.sendNewsletterWindow = MODx.load({
	        xtype		: 'newsletter-window-newsletter-send',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: function() {
				        MODx.msg.status({
							title	: _('newsletter.newsletter_send_succes'),
							message	: _('newsletter.newsletter_send_succes_desc')
						});
						
						this.refresh();
					},
					scope		: this
		        }
	         }
        });
        
        this.sendNewsletterWindow.setValues(this.menu.record);
        this.sendNewsletterWindow.show(e.target);
    },
    cancelNewsletter: function() {
    	MODx.msg.confirm({
        	title 	: _('newsletter.newsletter_cancel'),
        	text	: _('newsletter.newsletter_cancel_confirm'),
        	url		: this.config.url,
        	params	: {
            	action	: 'mgr/newsletters/cancel',
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
    removeNewsletter: function() {
    	MODx.msg.confirm({
        	title 	: _('newsletter.newsletter_remove'),
        	text	: _('newsletter.newsletter_remove_confirm'),
        	url		: this.config.url,
        	params	: {
            	action	: 'mgr/newsletters/remove',
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
    renderPublished: function(d, c, e) {
    	c.css = 0 == parseInt(e.json.resource_published) || !e.json.resource_published ? 'red' : 'green';
    	
    	return 0 == parseInt(d) || !d ? _('no') : _('yes');
    },
    renderSend: function(d, c, e) {
    	c.css = 0 == parseInt(d) || !d ? 'red' : 'green';
    	
    	return 0 == parseInt(d) || !d ? _('no') : (2 == parseInt(d) ? _('newsletter.pending') : _('yes'));
    }
});

Ext.reg('newsletter-grid-newsletters', Newsletter.grid.Newsletters);

Newsletter.window.CreateNewsletter = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        title 		: _('newsletter.newsletter_create'),
        url			: Newsletter.config.connectorUrl,
        baseParams	: {
            action		: 'mgr/newsletters/create'
        },
        defauls		: {
	        labelAlign	: 'top',
            border		: false
        },
        fields		: [{
			xtype		: 'hidden',
			name		: 'resource_id',
			value		: 0,
			id			: 'modx-resource-parent-hidden'
		}, {
			xtype		: 'hidden',
			value		: 0,
			id			: 'modx-resource-parent-old-hidden'
		}, {
			xtype		: 'hidden',
			id			: 'modx-resource-context-key'
		}, {
    		xtype		: 'modx-field-parent-change',
    		fieldLabel	: _('newsletter.label_resource'),
			description	: MODx.expandHelp ? '' : _('newsletter.label_resource_desc'),
			anchor		: '100%',
			allowBlank	: false,
			formpanel	: 'newsletter-panel-home'
		}, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_resource_desc'),
            cls			: 'desc-under'
        }]	
    });
    
    Newsletter.window.CreateNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.CreateNewsletter, MODx.Window);

Ext.reg('newsletter-window-newsletter-create', Newsletter.window.CreateNewsletter);

Newsletter.window.UpdateNewsletter = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        title 		: _('newsletter.newsletter_update'),
        url			: Newsletter.config.connectorUrl,
        baseParams	: {
            action		: 'mgr/newsletters/update'
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
			name		: 'resource_id',
			value		: config.record.resource_id || 0,
			id			: 'modx-resource-parent-hidden'
		}, {
			xtype		: 'hidden',
			value		: config.record.resource_id || 0,
			id			: 'modx-resource-parent-old-hidden'
		}, {
			xtype		: 'hidden',
			id			: 'modx-resource-context-key'
		}, {
    		xtype		: 'modx-field-parent-change',
    		fieldLabel	: _('newsletter.label_resource'),
			description	: MODx.expandHelp ? '' : _('newsletter.label_resource_desc'),
			anchor		: '100%',
			allowBlank	: false,
			value		: config.record.resource_name_alias,
			formpanel	: 'newsletter-panel-home'
		}, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_resource_desc'),
            cls			: 'desc-under'
        }]
    });
    
    Newsletter.window.UpdateNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateNewsletter, MODx.Window);

Ext.reg('newsletter-window-newsletter-update', Newsletter.window.UpdateNewsletter);

Newsletter.window.PreviewNewsletter = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        title 		: _('newsletter.newsletter_preview') + ': ' + config.record.resource_name,
        layout		: 'fit',
    	width		: 850,
        height		: 550,
		autoHeight	: false,
        formFrame	: false,
        fields		: [{
			autoEl 		: {
                tag 		: 'iframe',
                src			: config.record.resource_url,
                width		: '100%',
				height		: '100%',
				frameBorder	: 0
			}
        }]
    });
    
    Newsletter.window.PreviewNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.PreviewNewsletter, MODx.Window);

Ext.reg('newsletter-window-newsletter-preview', Newsletter.window.PreviewNewsletter);

Newsletter.window.SendNewsletter = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        title 		: _('newsletter.newsletter_send'),
        url			: Newsletter.config.connectorUrl,
        baseParams	: {
            action		: 'mgr/newsletters/send'
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
            name		: 'resource_name'
        }, {
            xtype		: 'hidden',
            name		: 'resource_url'
        }, {
            xtype		: 'hidden',
            name		: 'resource_context_key'
        }, {
	       	xtype		: 'label',
	       	fieldLabel	: _('newsletter.label_send_to_groups')
	    }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_send_to_groups_desc'),
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
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('newsletter.label_send_to_emails'),
        	description	: MODx.expandHelp ? '' : _('newsletter.label_send_to_emails_desc'),
        	name		: 'emails',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_send_to_emails_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'newsletter-combo-xtype',
        	fieldLabel	: _('newsletter.label_send_as'),
        	description	: MODx.expandHelp ? '' : _('newsletter.label_send_as_desc'),
        	name		: 'type',
        	anchor		: '100%',
        	allowBlank	: false
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_send_as_desc'),
            cls			: 'desc-under'
	    }]
    });
    
    Newsletter.window.SendNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.SendNewsletter, MODx.Window, {
	groups : function() {
		var value = this.record.groups;
		
		Ext.Ajax.request({
			url		: Newsletter.config.connectorUrl,
			params	: {
            	action		: 'mgr/groups/getlist',
            	context		: this.record.resource_context_key
			},
			success : function(response, opts) {
				var response = Ext.decode(response.responseText);
				
				Ext.each(response.results, function(record) {
					Ext.getCmp('newsletter-groups').add({
				        xtype		: 'checkbox',
			            boxLabel	: record.name,
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

Ext.reg('newsletter-window-newsletter-send', Newsletter.window.SendNewsletter);

Newsletter.combo.SendType = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        store: new Ext.data.ArrayStore({
            mode	: 'local',
            fields	: ['type','label'],
            data	: [
               	[0, _('newsletter.test')],
                [1, _('newsletter.permanent')]
            ]
        }),
        remoteSort	: ['label', 'asc'],
        hiddenName	: 'type',
        valueField	: 'type',
        displayField: 'label',
        mode		: 'local'
    });
    
    Newsletter.combo.SendType.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.SendType, MODx.combo.ComboBox);

Ext.reg('newsletter-combo-xtype', Newsletter.combo.SendType);