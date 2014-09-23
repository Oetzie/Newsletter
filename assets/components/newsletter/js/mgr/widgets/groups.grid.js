Newsletter.grid.Groups = function(config) {
    config = config || {};

	config.tbar = [{
        text	: _('newsletter.group_create'),
        handler	: this.createGroup
	}, '->', {
    	xtype		: 'modx-combo-context',
    	hidden		: 0 == parseInt(Newsletter.config.context) ? true : false,
    	name		: 'newsletter-filter-context-groups',
        id			: 'newsletter-filter-context-groups',
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
        name 		: 'newsletter-filter-search-groups',
        id			: 'newsletter-filter-search-groups',
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
    	id		: 'newsletter-filter-clear-groups',
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
            dataIndex	: 'name',
            sortable	: true,
            editable	: true,
            width		: 150,
            editor		: {
            	xtype		: 'textfield'
            }
        }, {
            header		: _('newsletter.label_description'),
            dataIndex	: 'description',
            sortable	: true,
            editable	: true,
            width		: 250,
            editor		: {
            	xtype		: 'textfield'
            }
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
        id			: 'newsletter-grid-groups',
        url			: Newsletter.config.connectorUrl,
        baseParams	: {
        	action		: 'mgr/groups/getList'
        },
        autosave	: true,
        save_action	: 'mgr/groups/updateFromGrid',
        fields		: ['id', 'context', 'name', 'description', 'active', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        grouping	: 0 == parseInt(Newsletter.config.context) ? false : true,
        groupBy		: 'context',
        singleText	: _('newsletter.group'),
        pluralText	: _('newsletter.groups')
    });
    
    Newsletter.grid.Groups.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.grid.Groups, MODx.grid.Grid, {
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
	    Ext.getCmp('newsletter-filter-context-groups').reset();
	    Ext.getCmp('newsletter-filter-search-groups').reset();
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
	        text	: _('newsletter.group_update'),
	        handler	: this.updateGroup
	    }, '-', {
		    text	: _('newsletter.group_remove'),
		    handler	: this.removeGroup
		 }];
    },
    createGroup: function(btn, e) {
        if (this.createGroupWindow) {
	        this.createGroupWindow.destroy();
        }
        
        this.createGroupWindow = MODx.load({
	        xtype		: 'newsletter-window-group-create',
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
		        	fn			:this.refresh,
		        	scope		:this
		        }
	         }
        });
        
        
        this.createGroupWindow.show(e.target);
    },
    updateGroup: function(btn, e) {
        if (this.updateGroupWindow) {
	        this.updateGroupWindow.destroy();
        }
        
        this.updateGroupWindow = MODx.load({
	        xtype		: 'newsletter-window-group-update',
	        record		: this.menu.record,
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
		        	fn			:this.refresh,
		        	scope		:this
		        }
	         }
        });
        
        this.updateGroupWindow.setValues(this.menu.record);
        this.updateGroupWindow.show(e.target);
    },
    removeGroup: function() {
    	MODx.msg.confirm({
        	title 	: _('newsletter.group_remove'),
        	text	: _('newsletter.group_remove_confirm'),
        	url		: this.config.url,
        	params	: {
            	action	: 'mgr/groups/remove',
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

Ext.reg('newsletter-grid-groups', Newsletter.grid.Groups);

Newsletter.window.CreateGroup = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        title 		: _('newsletter.group_create'),
        url			: Newsletter.config.connectorUrl,
        baseParams	: {
            action		: 'mgr/groups/create'
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
		            description	: MODx.expandHelp ? '' : _('newsletter.label_name_group_desc'),
		            name		: 'name',
		            anchor		: '100%',
		            allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_name_group_desc'),
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
        	xtype		: 'textarea',
        	fieldLabel	: _('newsletter.label_description'),
        	description	: MODx.expandHelp ? '' : _('newsletter.label_description_desc'),
        	name		: 'description',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_description_desc'),
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
	    }]
    });
    
    Newsletter.window.CreateGroup.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.CreateGroup, MODx.Window);

Ext.reg('newsletter-window-group-create', Newsletter.window.CreateGroup);

Newsletter.window.UpdateGroup = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        title 		: _('newsletter.group_update'),
        url			: Newsletter.config.connectorUrl,
        baseParams	: {
            action		: 'mgr/groups/update'
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
		            description	: MODx.expandHelp ? '' : _('newsletter.label_name_group_desc'),
		            name		: 'name',
		            anchor		: '100%',
		            allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_name_group_desc'),
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
        	xtype		: 'textarea',
        	fieldLabel	: _('newsletter.label_description'),
        	description	: MODx.expandHelp ? '' : _('newsletter.label_description_desc'),
        	name		: 'description',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_description_desc'),
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
	        	allowBlank	: false
	        }, {
	        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
	        	html		: _('newsletter.label_context_desc'),
	        	cls			: 'desc-under'
			}]
	    }]
    });
    
    Newsletter.window.UpdateGroup.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateGroup, MODx.Window);

Ext.reg('newsletter-window-group-update', Newsletter.window.UpdateGroup);