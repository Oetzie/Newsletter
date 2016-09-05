Newsletter.grid.Newsletters = function(config) {
    config = config || {};

	config.tbar = [{
        text	: _('newsletter.newsletter_create'),
        cls		:'primary-button',
        handler	: this.createNewsletter,
        scope	: this
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
    	cls		: 'x-form-filter-clear',
    	id		: 'newsletter-filter-clear-newsletters',
    	text	: _('filter_clear'),
    	listeners: {
        	'click': {
        		fn		: this.clearFilter,
        		scope	: this
        	}
        }
    }];
    
    expander = new Ext.grid.RowExpander({
        tpl : new Ext.XTemplate('<tpl for="send_details">'
            + '<p class="desc">{#}. ' + _('newsletter.newsletter_send_detail') + '</p>'
        + '</tpl>'),
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

    columns = new Ext.grid.ColumnModel({
        columns: [expander, {
            header		: _('newsletter.label_resource'),
            dataIndex	: 'resource_name',
            sortable	: true,
            editable	: false,
            width		: 150,
            renderer	: this.renderAlias
        }, {
            header		: _('newsletter.label_published'),
            dataIndex	: 'resource_published',
            sortable	: true,
            editable	: false,
            width		: 100,
            fixed		: true,
			renderer	: this.renderPublished
        }, {
            header		: _('newsletter.label_send_status'),
            dataIndex	: 'send_status',
            sortable	: true,
            editable	: false,
            width		: 150,
            fixed		: true,
			renderer	: this.renderSend
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
    	cm			: columns,
        id			: 'newsletter-grid-newsletters',
        url			: Newsletter.config.connector_url,
        baseParams	: {
        	action		: 'mgr/newsletters/getList'
        },
        autosave	: true,
        save_action	: 'mgr/newsletters/updateFromGrid',
        fields		: ['id', 'resource_id', 'resource_url', 'resource_name', 'resource_context_key', 'resource_published', 'context_key', 'context_name', 'lists', 'lists_names', 'send_at', 'send_status', 'send_repeat', 'send_interval', 'send_date', 'send_date_format', 'send_emails', 'send_details', 'hidden', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        plugins		: expander,
        grouping	: 0 == parseInt(Newsletter.config.context) ? false : true,
        groupBy		: 'context_name',
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
	        handler	: this.updateNewsletter,
	        scope	: this
	    }, '-', {
	        text	: _('newsletter.newsletter_preview'),
	        handler	: this.previewNewsletter,
	        scope	: this
	    }];

	    if (1 == parseInt(this.menu.record.resource_published)) {
		    if (0 == parseInt(this.menu.record.send_status) || 2 == parseInt(this.menu.record.send_status)) {
			    menu.push({
			        text	: _('newsletter.newsletter_send'),
			        handler	: this.sendNewsletter,
			        scope	: this
			    });
		    }
		}
	    
	    if (2 == parseInt(this.menu.record.send_status)) {
		    menu.push({
		        text	: _('newsletter.newsletter_cancel'),
		        handler	: this.cancelNewsletter,
		        scope	: this
		    });
	    }
	    
	    menu.push('-', {
		    text	: _('newsletter.newsletter_remove'),
		    handler	: this.removeNewsletter,
		    scope	: this
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
	    		cls			: 'primary-button',
	    		handler		: function() {
	    			this.previewNewsletterWindow.close();
	    		},
	    		scope		: this
			}]
        });
        
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
        	url		: Newsletter.config.connector_url,
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
        	url		: Newsletter.config.connector_url,
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
    renderAlias: function(d, c, e) {
    	return String.format('<a href="?a=resource/update&id={0}" title="{1}" class="x-grid-link">{2}</a>', e.json.resource_id, _('edit'), Ext.util.Format.htmlEncode(d) + ' (' + e.json.resource_id + ')');
    },
    renderPublished: function(d, c, e) {
    	c.css = 0 == parseInt(e.json.resource_published) || !e.json.resource_published ? 'red' : 'green';
    	
    	return 0 == parseInt(d) || !d ? _('no') : _('yes');
    },
    renderSend: function(d, c, e) {
    	c.css = 0 == parseInt(d) || !d ? 'red' : (2 == parseInt(d) ? 'orange' : 'green');
    	
    	return 0 == parseInt(d) || !d ? _('newsletter.newsletter_status_notsend') : (2 == parseInt(d) ? _('newsletter.newsletter_status_pending') : _('newsletter.newsletter_status_send')) + ' <em>(' + e.json.send_date_format + ')</em>';
    },
	renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }

        return a;
    }
});

Ext.reg('newsletter-grid-newsletters', Newsletter.grid.Newsletters);

Newsletter.window.CreateNewsletter = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.newsletter_create'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/newsletters/create'
        },
        defauls		: {
	        labelAlign	: 'top',
            border		: false
        },
        fields		: [{
	        html 		: '<p>' + _('newsletter.newsletter_create_desc') + '</p>',
	        cls			: 'panel-desc',
	        style		: 'margin-bottom: 10px;'
        }, {
			xtype		: 'hidden',
			name		: 'resource_id',
			value		: 0,
			id			: 'modx-resource-parent-hidden'
		}, {
    		xtype		: 'modx-field-parent-change',
    		fieldLabel	: _('newsletter.label_resource'),
			description	: MODx.expandHelp ? '' : _('newsletter.label_resource_desc'),
			anchor		: '100%',
			name		: 'resource',
			allowBlank	: false,
			formpanel	: 'newsletter-panel-home',
			contextcmp	: null
		}, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_resource_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'checkbox',
        	boxLabel	: _('newsletter.label_hidden_newsletter_desc'),
        	anchor		: '100%',
        	name		: 'hidden',
        	inputValue	: 1,
        	disabled	: Newsletter.config.admin ? false : true,
        	hidden		: Newsletter.config.admin ? false : true
        }]
    });
    
    Newsletter.window.CreateNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.CreateNewsletter, MODx.Window);

Ext.reg('newsletter-window-newsletter-create', Newsletter.window.CreateNewsletter);

Newsletter.window.UpdateNewsletter = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
        title 		: _('newsletter.newsletter_update'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/newsletters/update'
        },
        defauls		: {
	        labelAlign	: 'top',
            border		: false
        },
        fields		: [{
	        html 		: '<p>' + _('newsletter.newsletter_create_desc') + '</p>',
	        cls			: 'panel-desc',
	        style		: 'margin-bottom: 10px;'
        }, {
            xtype		: 'hidden',
            name		: 'id'
        }, {
			xtype		: 'hidden',
			name		: 'resource_id',
			value		: config.record.resource_id || 0,
			id			: 'modx-resource-parent-hidden'
		}, {
    		xtype		: 'modx-field-parent-change',
    		fieldLabel	: _('newsletter.label_resource'),
			description	: MODx.expandHelp ? '' : _('newsletter.label_resource_desc'),
			anchor		: '100%',
			name 		: 'resource',
			allowBlank	: false,
			value		: config.record.resource_name + ' (' + config.record.resource_id + ')',
			formpanel	: 'newsletter-panel-home',
			contextcmp	: null
		}, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_resource_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'checkbox',
        	boxLabel	: _('newsletter.label_hidden_newsletter_desc'),
        	anchor		: '100%',
        	name		: 'hidden',
        	inputValue	: 1,
        	disabled	: Newsletter.config.admin ? false : true,
        	hidden		: Newsletter.config.admin ? false : true
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
        bodyStyle	: 'padding: 0;',
        fields		: [{
        	xtype		: 'container',
			layout		: {
            	type		: 'vbox',
				align		: 'stretch'
			},
			width		: '100%',
			height		: '100%',
			items		:[{
				autoEl 		: {
	                tag 		: 'iframe',
	                src			: config.record.resource_url,
	                width		: '100%',
					height		: '100%',
					frameBorder	: 0
				}
			}]
        }]
    });
    
    Newsletter.window.PreviewNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.PreviewNewsletter, MODx.Window);

Ext.reg('newsletter-window-newsletter-preview', Newsletter.window.PreviewNewsletter);

Newsletter.window.SendNewsletter = function(config) {
    config = config || {};
    
    var date = new Date();
    
    date.setDate(date.getDate() + 1);

    Ext.applyIf(config, {
    	autoHeight	: true,
    	width		: 600,
        title 		: _('newsletter.newsletter_send'),
        url			: Newsletter.config.connector_url,
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
            name		: 'resource_id'
        }, {
        	xtype		: 'newsletter-combo-xtype',
        	fieldLabel	: _('newsletter.label_send_at'),
        	description	: MODx.expandHelp ? '' : _('newsletter.label_send_at_desc'),
        	name		: 'send_at',
        	anchor		: '100%',
        	allowBlank	: false,
        	listeners	: {
	        	'select'	: {
		        	fn 			: this.information,
		        	scope		: this
	        	}
        	}
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_send_at_desc'),
            cls			: 'desc-under'
	    }, {
	    	xtype		: 'fieldset',
	    	title		: _('newsletter.extra_settings'),
			collapsible : true,
			collapsed 	: true,
			id			: 'newsletter-send-timestamp',
			defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
			items		: [{
	        	xtype		: 'datefield',
	        	fieldLabel	: _('newsletter.label_send_date'),
	        	description	: MODx.expandHelp ? '' : _('newsletter.label_send_date_desc'),
	        	name		: 'send_date',
	        	anchor		: '100%',
	        	format		: MODx.config.manager_date_format,
	        	startDay	: parseInt(MODx.config.manager_week_start),
	        	minValue 	: date.format(MODx.config.manager_date_format),
	        	listeners 	: {
					'render'	: function() {
						if ('' == config.record.send_date || '0000-00-00' == config.record.send_date) {
							this.setValue(date);
						}
        			}
				}
	        }, {
	        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
	            html		: _('newsletter.label_send_date_desc'),
	            cls			: 'desc-under'
	        }, {
		        layout		: 'column',
	            border		: false,
	            style		: 'padding-top: 15px',
	            defaults	: {
	                layout		: 'form',
	                labelSeparator : ''
	            },
	            items: [{
	            	columnWidth	: .4,
	                items		: [{
			        	xtype		: 'textfield',
			        	fieldLabel	: _('newsletter.label_send_repeat'),
			        	description	: MODx.expandHelp ? '' : _('newsletter.label_send_repeat_desc'),
			        	name		: 'send_repeat',
			        	anchor		: '100%',
			        	value 		: '1'
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		: _('newsletter.label_send_repeat_desc'),
			            cls			: 'desc-under'
			        }]        
		        }, {
					columnWidth	: .6,
					style		: 'margin-right: 0;',
					items		: [{
			        	xtype		: 'textfield',
			        	fieldLabel	: _('newsletter.label_send_interval'),
			        	description	: MODx.expandHelp ? '' : _('newsletter.label_send_interval_desc'),
			        	name		: 'send_interval',
			        	anchor		: '100%',
			        	value 		: '7'
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		: _('newsletter.label_send_interval_desc'),
			            cls			: 'desc-under'
			        }]
				}]
		    }],
		    listeners	: {
	        	'render'	: {
		        	fn 			: this.information,
		        	scope		: this
	        	}
        	}
	    }, {
	        layout		: 'column',
            border		: false,
            style		: 'padding-top: 15px',
            defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
            items: [{
            	columnWidth	: .4,
                items		: [{
			       	xtype		: 'label',
			       	fieldLabel	: _('newsletter.label_send_to_lists')
			    }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_send_to_lists_desc'),
		            cls			: 'desc-under'
		        }, {
			       xtype		: 'newsletter-combo-lists',
			       value		: config.record.lists
			    }]
			}, {
				columnWidth	: .6,
				style		: 'margin-right: 0;',
				items		: [{
		        	xtype		: 'textfield',
		        	fieldLabel	: _('newsletter.label_send_to_emails'),
		        	description	: MODx.expandHelp ? '' : _('newsletter.label_send_to_emails_desc'),
		        	name		: 'send_emails',
		        	anchor		: '100%'
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_send_to_emails_desc'),
		            cls			: 'desc-under'
		        }]
			}]
		}]
    });
    
    Newsletter.window.SendNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.SendNewsletter, MODx.Window, {
	information: function(event) {
		if ('immediately' == event.value || '' == event.value) {
			Ext.getCmp('newsletter-send-timestamp').collapse();
		} else {
			Ext.getCmp('newsletter-send-timestamp').expand();
		}
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
               	['immediately', _('newsletter.send_immediately')],
                ['timestamp', _('newsletter.send_timestamp')]
            ]
        }),
        remoteSort	: ['label', 'asc'],
        hiddenName	: 'send_at',
        valueField	: 'type',
        displayField: 'label',
        mode		: 'local'
    });
    
    Newsletter.combo.SendType.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.SendType, MODx.combo.ComboBox);

Ext.reg('newsletter-combo-xtype', Newsletter.combo.SendType);

Newsletter.combo.Lists = function(config) {
    config = config || {}; 
    
    Ext.Ajax.request({
	    url 	: Newsletter.config.connector_url,
	    params 	: { 	
	        action 	: 'mgr/lists/getlist',
	        hidden 	: true
	    },
	    method	: 'POST',
		success	: function(result, request) { 
	        this.setData(Ext.util.JSON.decode(result.responseText));
	    },
	    scope	: this
	});
    
    Ext.applyIf(config, {
	    xtype		: 'container',
		name		: 'lists',
		value		: '',
		columns		: 2
	});  
    
	Newsletter.combo.Lists.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.Lists, Ext.Panel, {
    setData: function(data) {
	    var items = [];

		Ext.each(data.results, function(record) {
			items.push({
				xtype		: 'checkbox',
			    boxLabel	: record.name,
			    description	: MODx.expandHelp ? '' : record.description,
			    name		: this.name + '[]',
			    inputValue	: record.id,
			    checked		: -1 != this.value.indexOf(record.id) ? true : false,
			    hidden 		: Newsletter.config.admin || !record.hidden ? false : true
			});
		}, this);

		this.add({
		    xtype		: 'checkboxgroup',
		    columns		: this.columns,
		    items		: items
		});
		        
		this.doLayout();
    }
});

Ext.reg('newsletter-combo-lists', Newsletter.combo.Lists);