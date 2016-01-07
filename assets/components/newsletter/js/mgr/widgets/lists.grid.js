Newsletter.grid.Lists = function(config) {
    config = config || {};

	config.tbar = [{
        text	: _('newsletter.list_create'),
        cls		:'primary-button',
        handler	: this.createList,
        scope	: this
	}, {
		text	: _('bulk_actions'),
		menu	: [{
			text	: _('newsletter.remove_selected'),
			handler	: this.removeSelectedLists,
			scope	: this
		}]
	}, '->', {
        xtype		: 'textfield',
        name 		: 'newsletter-filter-search-lists',
        id			: 'newsletter-filter-search-lists',
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
    	id		: 'newsletter-filter-clear-lists',
    	text	: _('filter_clear'),
    	listeners: {
        	'click': {
        		fn		: this.clearFilter,
        		scope	: this
        	}
        }
    }];
    
    expander = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="desc">{description}</p>'
        ),
	    getRowClass : function(record, rowIndex, p, ds){
	        p.cols = p.cols-1;
	        var content = this.bodyContent[record.id];
	        if(!content && !this.lazyRender){
	            content = this.getBodyContent(record, rowIndex);
	        }
	        if(content){
	            p.body = content;
	        }
	        
	        var cls = this.state[record.id] ? 'x-grid3-row-expanded' : 'x-grid3-row-collapsed';
	        
	        return 1 == parseInt(record.json.hidden) ? cls + ' grid-row-inactive' : cls;
	    }
    });

    sm = new Ext.grid.CheckboxSelectionModel();

    columns = new Ext.grid.ColumnModel({
        columns: [expander, sm, {
            header		: _('newsletter.label_name'),
            dataIndex	: 'name',
            sortable	: true,
            editable	: true,
            width		: 200,
            renderer	: this.renderName,
            editor		: {
            	xtype		: 'textfield'
            }
        }, {
            header		: _('newsletter.label_subscriptions'),
            dataIndex	: 'subscriptions',
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
        id			: 'newsletter-grid-lists',
        url			: Newsletter.config.connector_url,
        baseParams	: {
        	action		: 'mgr/lists/getList'
        },
        autosave	: true,
        save_action	: 'mgr/lists/updateFromGrid',
        fields		: ['id', 'name', 'description', 'subscriptions', 'primary', 'hidden', 'active', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        plugins		: expander,
        singleText	: _('newsletter.list'),
        pluralText	: _('newsletter.lists'),
    });
    
    Newsletter.grid.Lists.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.grid.Lists, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
	    this.getStore().baseParams.query = '';
	    Ext.getCmp('newsletter-filter-search-lists').reset();
        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        var menu = [{
	        text	: _('newsletter.list_update'),
	        handler	: this.updateList,
	        scope	: this
	    }, '-', {
		    text 	: _('newsletter.list_import'),
		    handler	: this.importList,
		    scope	: this
	    }, {
		    text 	: _('newsletter.list_export'),
		    handler	: this.exportList,
		    scope	: this
	    }];

	    if (0 == parseInt(this.menu.record.primary)) {
	    	menu.push('-', {
		    	text	: _('newsletter.list_remove'),
				handler	: this.removeList,
				scope	: this
			});
		}
		
		return menu;
    },
    createList: function(btn, e) {
        if (this.createListWindow) {
	        this.createListWindow.destroy();
        }
        
        this.createListWindow = MODx.load({
	        xtype		: 'newsletter-window-list-create',
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn		: function() {
			        	Ext.getCmp('newsletter-grid-subscriptions').refresh();
			        	
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
		        	scope		:this
		        }
	         }
        });
        
        this.createListWindow.show(e.target);
    },
    updateList: function(btn, e) {
        if (this.updateListWindow) {
	        this.updateListWindow.destroy();
        }
        
        this.updateListWindow = MODx.load({
	        xtype		: 'newsletter-window-list-update',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn		: function() {
			        	Ext.getCmp('newsletter-grid-subscriptions').refresh();
			        	
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
		        	scope		:this
		        }
	         }
        });
        
        this.updateListWindow.setValues(this.menu.record);
        this.updateListWindow.show(e.target);
    },
    removeList: function(btn, e) {
    	MODx.msg.confirm({
        	title 	: _('newsletter.list_remove'),
        	text	: _('newsletter.list_remove_confirm'),
        	url		: this.config.url,
        	params	: {
            	action	: 'mgr/lists/remove',
            	id		: this.menu.record.id
            },
            listeners: {
            	'success': {
            		fn		: function() {
	            		Ext.getCmp('newsletter-grid-subscriptions').refresh();
	            		
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
            		scope	: this
            	}
            }
    	});
    },
    removeSelectedList: function(btn, e) {
    	var cs = this.getSelectedAsList();
    	
        if (cs === false) {
        	return false;
        }
        
    	MODx.msg.confirm({
        	title 	: _('newsletter.list_remove_selected'),
        	text	: _('newsletter.list_remove_selected_confirm'),
        	url		:Newsletter.config.connector_url,
        	params	: {
            	action	: 'mgr/lists/removeSelected',
            	ids		: cs
            },
            listeners: {
            	'success': {
            		fn		: function() {
	            		Ext.getCmp('newsletter-grid-subscriptions').refresh();
	            		
            			this.getSelectionModel().clearSelections(true);
            			this.refresh();
            		},
            		scope	: this
            	}
            }
    	});
    },
    importList: function(btn, e) {
        if (this.importListWindow) {
	        this.importListWindow.destroy();
        }
        
        this.importListWindow = MODx.load({
	        xtype		: 'newsletter-window-list-import',
	        record		: this.menu.record,
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
            		fn			: function() {
	            		Ext.getCmp('newsletter-grid-subscriptions').refresh();
	            		
	            		this.getSelectionModel().clearSelections(true);
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
        
        this.importListWindow.show(e.target);
    },
    exportList: function(btn, e) {
	    if (this.exportListWindow) {
	        this.exportListWindow.destroy();
        }
        
        this.exportListWindow = MODx.load({
	        xtype		: 'newsletter-window-list-export',
	        record		: this.menu.record,
	        closeAction	:'close',
	        listeners	: {
		        'success'	: {
            		fn			: function() {
            			location.href = this.config.url + '?action=mgr/lists/export&download=1&HTTP_MODAUTH=' + MODx.siteId;
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
        
        this.exportListWindow.show(e.target);
    },
    renderName: function(d, c, e) {
	    c.css = 1 == parseInt(e.json.hidden) ? 'grid-row-inactive' : '';
	    
	    return d;
	},
    renderBoolean: function(d, c) {
    	c.css = 1 == parseInt(d) || d ? 'green' : 'red';
    	
    	return 1 == parseInt(d) || d ? _('yes') : _('no');
    }
});

Ext.reg('newsletter-grid-lists', Newsletter.grid.Lists);

Newsletter.window.CreateList = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.list_create'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/lists/create'
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
		            fieldLabel	: _('newsletter.label_list_name'),
		            description	: MODx.expandHelp ? '' : _('newsletter.label_list_name_desc'),
		            name		: 'name',
		            anchor		: '100%',
		            allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_list_name_desc'),
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
        	fieldLabel	: _('newsletter.label_list_description'),
        	description	: MODx.expandHelp ? '' : _('newsletter.label_list_description_desc'),
        	name		: 'description',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_list_description_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'checkbox',
        	boxLabel	: _('newsletter.label_primary_list_desc'),
        	anchor		: '100%',
        	name		: 'primary',
        	inputValue	: 1,
        	checked		: false,
        	disabled	: Newsletter.config.admin ? false : true
        }, {
        	xtype		: 'checkbox',
        	boxLabel	: _('newsletter.label_hidden_list_desc'),
        	anchor		: '100%',
        	name		: 'hidden',
        	inputValue	: 1,
        	checked		: false,
        	disabled	: Newsletter.config.admin ? false : true,
        	hidden		: Newsletter.config.admin ? false : true
        }]
    });
    
    Newsletter.window.CreateList.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.CreateList, MODx.Window);

Ext.reg('newsletter-window-list-create', Newsletter.window.CreateList);

Newsletter.window.UpdateList = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.list_update'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/lists/update'
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
		            fieldLabel	: _('newsletter.label_list_name'),
		            description	: MODx.expandHelp ? '' : _('newsletter.label_list_name_desc'),
		            name		: 'name',
		            anchor		: '100%',
		            allowBlank	: false
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_list_name_desc'),
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
        	fieldLabel	: _('newsletter.label_list_description'),
        	description	: MODx.expandHelp ? '' : _('newsletter.label_list_description_desc'),
        	name		: 'description',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_list_description_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'checkbox',
        	boxLabel	: _('newsletter.label_primary_list_desc'),
        	anchor		: '100%',
        	name		: 'primary',
        	inputValue	: 1,
        	disabled	: Newsletter.config.admin ? false : true
        }, {
        	xtype		: 'checkbox',
        	boxLabel	: _('newsletter.label_hidden_list_desc'),
        	anchor		: '100%',
        	name		: 'hidden',
        	inputValue	: 1,
        	disabled	: Newsletter.config.admin ? false : true,
        	hidden		: Newsletter.config.admin ? false : true
        }]
    });
    
    Newsletter.window.UpdateList.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateList, MODx.Window);

Ext.reg('newsletter-window-list-update', Newsletter.window.UpdateList);

Newsletter.window.ImportList = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.list_import'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/lists/import'
        },
        defauls		: {
	        labelAlign	: 'top',
            border		: false
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
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
            fieldLabel	: _('newsletter.label_delimiter'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_delimiter_desc'),
            name		: 'delimiter',
            anchor		: '100%',
            allowBlank	: false,
            value 		: ';'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_delimiter_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'checkbox',
        	boxLabel	: _('newsletter.label_headers'),
        	anchor		: '100%',
        	name		: 'headers',
        	checked		: true,
        	inputValue	: 1
        }],
        fileUpload	: true,
        saveBtnText	: _('import')
    });
    
    Newsletter.window.ImportList.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.ImportList, MODx.Window);

Ext.reg('newsletter-window-list-import', Newsletter.window.ImportList);

Newsletter.window.ExportList = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.list_export'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/lists/export'
        },
        defauls		: {
	        labelAlign	: 'top',
            border		: false
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
	        xtype		: 'textfield',
            fieldLabel	: _('newsletter.label_delimiter'),
            description	: MODx.expandHelp ? '' : _('newsletter.label_delimiter_desc'),
            name		: _('newsletter.label_delimiter_desc'),
            anchor		: '100%',
            allowBlank	: false,
            value 		: ';'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_delimiter_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'checkbox',
        	boxLabel	: _('newsletter.label_headers'),
        	anchor		: '100%',
        	name		: 'headers',
        	checked		: true,
        	inputValue	: 1
        }],
        saveBtnText	: _('export')
    });
    
    Newsletter.window.ExportList.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.ExportList, MODx.Window);

Ext.reg('newsletter-window-list-export', Newsletter.window.ExportList);