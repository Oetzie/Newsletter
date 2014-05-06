Newsletter.grid.Newsletters = function(config) {
    config = config || {};

	config.tbar = [{
        text	: _('newsletter.newsletter_create'),
        handler	: this.createNewsletter
   }, '->', {
    	xtype		: 'modx-combo-context',
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
            dataIndex	: 'resource_name',
            sortable	: true,
            editable	: false,
            width		: 150
        }, {
            header		: _('newsletter.label_send'),
            dataIndex	: 'send',
            sortable	: true,
            editable	: false,
            width		: 100,
            fixed		: true,
			renderer	: this.renderSend
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
        }, {
            header		: _('newsletter.label_context'),
            dataIndex	: 'context',
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
        fields		: ['id', 'resource', 'resource_id', 'resource_name', 'resource_url', 'resource_context', 'context', 'groups', 'send', 'active', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        grouping	: true,
        groupBy		: 'context',
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
	    
	    if (1 != parseInt(this.menu.record.send)) {
		    menu.push({
		        text	: _('newsletter.newsletter_send'),
		        handler	: this.sendNewsletter
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
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
		        	fn			:this.refresh,
		        	scope		:this
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
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
		        	fn			:this.refresh,
		        	scope		:this
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
	        closeAction	:'close',
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
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
		        	fn			:this.refresh,
		        	scope		:this
		        }
	         }
        });
        
        this.sendNewsletterWindow.setValues(this.menu.record);
        this.sendNewsletterWindow.show(e.target);
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
    renderActive: function(d, c) {
    	c.css = 1 == parseInt(d) || d ? 'green' : 'red';
    	
    	return 1 == parseInt(d) || d ? _('yes') : _('no');
    },
    renderSend: function(d, c) {
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
        	layout		: 'column',
        	border		: false,
            defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
        	items		: [{
	        	columnWidth	: .9,
	        	items		: [{
			        xtype		: 'numberfield',
		            fieldLabel	: _('newsletter.label_resource'),
		            description	: MODx.expandHelp ? '' : _('newsletter.label_resource_desc'),
		            name		: 'resource_id',
		            anchor		: '100%',
		            allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_resource_desc'),
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
        	layout		: 'column',
        	border		: false,
            defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
        	items		: [{
	        	columnWidth	: .9,
	        	items		: [{
			        xtype		: 'numberfield',
		            fieldLabel	: _('newsletter.label_resource'),
		            description	: MODx.expandHelp ? '' : _('newsletter.label_resource_desc'),
		            name		: 'resource_id',
		            anchor		: '100%',
		            allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_resource_desc'),
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
	    }]
    });
    
    Newsletter.window.UpdateNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateNewsletter, MODx.Window);

Ext.reg('newsletter-window-newsletter-update', Newsletter.window.UpdateNewsletter);

Newsletter.window.PreviewNewsletter = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        title 		: _('newsletter.newsletter_preview'),
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
            name		: 'resource_context'
        }, {
	        layout		: 'form',
	        defaults	: {
                labelSeparator : ''
            },
	        items		: [{
		       	xtype		: 'label',
		       	fieldLabel	: _('newsletter.label_send_to')
		    }, {
	        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
	            html		: _('newsletter.label_send_to_desc'),
	            cls			: 'desc-under'
	        }, this.groups(config.record.groups), {
	        	xtype		: 'label',
	            html		: '&nbsp;',
	            cls			: 'desc-under'
	        }]
	    }, {
	        xtype		: 'checkbox',
	        fieldLabel	: _('newsletter.label_timing'),
			boxLabel	: _('newsletter.label_timing_desc'),
			name		: 'timing',
            inputValue	: 1
	    }]
    });
    
    Newsletter.window.SendNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.SendNewsletter, MODx.Window, {
	groups: function(value) {
		var groups = [];
		var _this = this;

		value = value.split(',');
		
		Ext.each(Newsletter.config.groups, function(group) {
			groups.push({
		        xtype		: 'checkbox',
	            boxLabel	: group.name,
	            description	: MODx.expandHelp ? '' : group.description,
	            name		: 'groups[]',
	            inputValue	: group.id,
	            checked		: -1 != value.indexOf(group.id.toString()) ? true : false
	        });
		});
		
		return groups;
	}
});

Ext.reg('newsletter-window-newsletter-send', Newsletter.window.SendNewsletter);