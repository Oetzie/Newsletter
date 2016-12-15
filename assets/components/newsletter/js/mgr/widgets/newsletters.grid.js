Newsletter.grid.Newsletters = function(config) {
    config = config || {};

	config.tbar = [{
        text	: _('newsletter.newsletter_create'),
        cls		:'primary-button',
        handler	: this.createNewsletter,
        scope	: this
   }, '->', {
    	xtype		: 'modx-combo-context',
    	hidden		: Newsletter.config.context,
    	name		: 'newsletter-filter-context-newsletters',
        id			: 'newsletter-filter-context-newsletters',
        value 		: MODx.config.default_context,
        emptyText	: _('newsletter.filter_context'),
        listeners	: {
    		'select'	: {
            	fn			: this.filterContext,
            	scope		: this   
		    }
		},
		baseParams 	: {
			action		: 'context/getlist',
			exclude		: 'mgr'
		}
    }, {
        xtype		: 'textfield',
        name 		: 'newsletter-filter-search-newsletters',
        id			: 'newsletter-filter-search-newsletters',
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
		        scope	: this
	        }
        }
    }, {
    	xtype		: 'button',
    	cls			: 'x-form-filter-clear',
    	id			: 'newsletter-filter-clear-newsletters',
    	text		: _('filter_clear'),
    	listeners	: {
        	'click'		: {
        		fn			: this.clearFilter,
        		scope		: this
        	}
        }
    }];
    
    expander = new Ext.grid.RowExpander({
	    getRowClass : function(record, rowIndex, p, ds) {
	        return 1 == parseInt(record.json.hidden) ? ' grid-row-inactive' : '';
	    }
    });

    columns = new Ext.grid.ColumnModel({
        columns: [{
            header		: _('newsletter.label_newsletter_resource'),
            dataIndex	: 'pagetitle',
            sortable	: true,
            editable	: false,
            width		: 150,
            renderer	: this.renderName
        }, {
            header		: _('newsletter.label_newsletter_published'),
            dataIndex	: 'published',
            sortable	: true,
            editable	: false,
            width		: 100,
            fixed		: true,
			renderer	: this.renderPublished
        }, {
            header		: _('newsletter.label_newsletter_send_status'),
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
        	action		: 'mgr/newsletters/getlist',
        	context 	: MODx.config.default_context
        },
        fields		: ['id', 'context_key', 'context_name', 'resource_id', 'url', 'pagetitle', 'published', 'lists', 'lists_names', 'newsletter_type', 'send_status', 'send_repeat', 'send_interval', 'send_date', 'send_date_format', 'send_emails', 'send_details', 'hidden', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        plugins		: expander
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
    	this.getStore().baseParams.context = MODx.config.default_context;
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
	    
	    if (0 < parseInt(this.menu.record.send_details.length)) {
		    menu.push({
		        text	: _('newsletter.newsletter_stats'),
		        handler	: this.statsNewsletter,
		        scope	: this
		    });
	    }

	    if ((0 == parseInt(this.menu.record.send_status) || 1 == parseInt(this.menu.record.send_status)) && 1 == parseInt(MODx.config.site_status)) {
		    menu.push({
		        text	: _('newsletter.newsletter_send'),
		        handler	: this.sendNewsletter,
		        scope	: this
		    });
	    } 
	    
	    if (1 == parseInt(this.menu.record.send_status)) {
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
    statsNewsletter: function(btn, e) {
        if (this.statsNewsletterWindow) {
	        this.statsNewsletterWindow.destroy();
        }
        
        this.statsNewsletterWindow = MODx.load({
	        xtype		: 'newsletter-window-newsletter-stats',
	        record		: this.menu.record,
	        closeAction	: 'close',
			buttons		: [{
	    		text    	: _('ok'),
	    		cls			: 'primary-button',
	    		handler		: function() {
	    			this.statsNewsletterWindow.close();
	    		},
	    		scope		: this
			}]
        });
        
        this.statsNewsletterWindow.show(e.target);
    },
    sendNewsletter: function(btn, e) {
        if (this.sendNewsletterWindow) {
	        this.sendNewsletterWindow.destroy();
        }

        this.sendNewsletterWindow = MODx.load({
	        xtype		: 'newsletter-window-newsletter-send',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        saveBtnText	: _('newsletter.send'),
	        listeners	: {
		        'success'	: {
		        	fn			: function(data) {
			        	var newsletter_type = data.a.result.object.newsletter_type;
			        	
			        	if (2 == newsletter_type || 3 == newsletter_type) {
							this.sendNewsletterConsole = MODx.load({
								xtype		: 'modx-console',
								register	: 'mgr',
								topic		: '/newsletter/'
							});
							
							MODx.Ajax.request({
							    url			: Newsletter.config.connector_url,
							    params		: {
							        action			: 'mgr/newsletters/sendimmediately',
							        id				: data.a.result.object.id,
							        newsletter_type : newsletter_type,
							        register		: 'mgr',
							        topic			: '/newsletter/'
							    },
							    listeners	: {
							        'success'	: {
								        fn			: function() {
									        this.sendNewsletterConsole.fireEvent('complete');
									        
									        MODx.msg.status({
												title	: _('newsletter.newsletter_send_succes'),
												message	: _('newsletter.newsletter_send_succes_desc')
											});
											
											this.refresh();
							        	},
							        	scope		: this
							    	},
							    	'failure'	: {
								    	fn 			: function(data) {
									    	MODx.msg.alert(_('warning'), data.message, function() {
										    	
											}, this);
								    	},
								    	scope 		: this
							    	}
							    }
							});

							this.sendNewsletterConsole.show();
						} else {
				        	MODx.msg.status({
								title	: _('newsletter.newsletter_send_save'),
								message	: _('newsletter.newsletter_send_save_desc')
							});
							
							this.refresh();
						}
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
        	title 		: _('newsletter.newsletter_cancel'),
        	text		: _('newsletter.newsletter_cancel_confirm'),
        	url			: Newsletter.config.connector_url,
        	params		: {
            	action		: 'mgr/newsletters/cancel',
            	id			: this.menu.record.id
            },
            listeners	: {
            	'success'	: {
            		fn			: this.refresh,
            		scope		: this
            	}
            }
    	});
    },
    removeNewsletter: function() {
    	MODx.msg.confirm({
        	title 		: _('newsletter.newsletter_remove'),
        	text		: _('newsletter.newsletter_remove_confirm'),
        	url			: Newsletter.config.connector_url,
        	params		: {
            	action		: 'mgr/newsletters/remove',
            	id			: this.menu.record.id
            },
            listeners	: {
            	'success'	: {
            		fn			: this.refresh,
            		scope		: this
            	}
            }
    	});
    },
    renderName: function(d, c, e) {
	    var action = (MODx.action && MODx.action['resource/update']) ? MODx.action['resource/update'] : 'resource/update';
	    
    	return String.format('<a href="?a={0}&id={1}" title="{2}" class="x-grid-link">{3}</a>', action, e.json.resource_id, _('edit'), Ext.util.Format.htmlEncode(d));
    },
    renderPublished: function(d, c, e) {
    	c.css = 0 == parseInt(d) || !d ? 'red' : 'green';
    	
    	return 0 == parseInt(d) || !d ? _('no') : _('yes');
    },
    renderSend: function(d, c, e) {
    	if (0 == parseInt(d)) {
	    	c.css = 'red';
	    	
	    	return _('newsletter.newsletter_status_0');
    	} else if (1 == parseInt(d)) {
	    	c.css = 'orange';
	    	
	    	return _('newsletter.newsletter_status_1') + ' <em>(' + e.data.send_date_format + ')</em>';
    	} else if (2 == parseInt(d)) {
	    	c.css = 'green';
	    	
	    	return _('newsletter.newsletter_status_2') + ' <em>(' + e.data.send_date_format + ')</em>';
    	}
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
        fields		: [{
	        html 		: '<p>' + _('newsletter.newsletter_create_desc') + '</p>',
	        cls			: 'panel-desc',
        }, {
			xtype		: 'hidden',
			name		: 'resource_id',
			value		: 0,
			id			: 'modx-resource-parent-hidden'
		}, {
    		xtype		: 'modx-field-parent-change',
    		fieldLabel	: _('newsletter.label_newsletter_resource'),
			description	: MODx.expandHelp ? '' : _('newsletter.label_newsletter_resource_desc'),
			anchor		: '100%',
			name		: 'resource',
			allowBlank	: false,
			formpanel	: 'newsletter-panel-home',
			contextcmp	: null
		}, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_newsletter_resource_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'checkbox',
        	boxLabel	: _('newsletter.label_newsletter_hidden_desc'),
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
        fields		: [{
	        html 		: '<p>' + _('newsletter.newsletter_create_desc') + '</p>',
	        cls			: 'panel-desc',
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
    		fieldLabel	: _('newsletter.label_newsletter_resource'),
			description	: MODx.expandHelp ? '' : _('newsletter.label_newsletter_resource_desc'),
			anchor		: '100%',
			name 		: 'resource',
			allowBlank	: false,
			value		: config.record.pagetitle,
			formpanel	: 'newsletter-panel-home',
			contextcmp	: null
		}, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_newsletter_resource_desc'),
            cls			: 'desc-under'
        }, {
        	xtype		: 'checkbox',
        	boxLabel	: _('newsletter.label_newsletter_hidden_desc'),
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
	    width		: 850,
        height		: 550,
        title 		: _('newsletter.newsletter_preview') + ': ' + config.record.pagetitle,
        layout		: 'fit',
		autoHeight	: false,
		bodyStyle 	: 'padding: 0;',
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
	                src			: config.record.url,
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

Newsletter.window.StatsNewsletter = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	width		: 600,
    	height		: 400,
        title 		: _('newsletter.newsletter_stats') + ': ' + config.record.pagetitle,
        fields		: this.getStats(config.record.send_details)
    });
    
    Newsletter.window.StatsNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.StatsNewsletter, MODx.Window, {
	getStats: function(data) {
		var fields = [];
		
		fields.push({
			html 	: _('newsletter.newsletter_stats_desc', {
				total	: data.length	
			}),
			cls 	: 'panel-desc',
		});
		
		Ext.each(data, function(item, index) {
			if (index < 10) {
				fields.push({
					xtype 		: 'fieldset',
					title 		: _('newsletter.label_newsletter_stats_newsletter', {
						current		: data.length - index
					}),
					collapsible : true,
					collapsed 	: 0 == index ? false : true,
					labelSeparator : '',
					items 		: [{
				        layout		: 'column',
			            defaults	: {
			                layout		: 'form',
			                labelSeparator : ''
			            },
			            items: [{
			            	columnWidth	: .5,
			                items		: [{
					        	xtype		: 'textfield',
					        	fieldLabel	: _('newsletter.label_newsletter_stats_date'),
					        	anchor		: '100%',
					        	value 		: item.timestamp,
					        	readOnly	: true,
					        	cls 		: 'x-static-text-field x-item-disabled',
					        }]        
				        }, {
							columnWidth	: .5,
							style		: 'margin-right: 0;',
							items		: [{
					        	xtype		: 'textfield',
					        	fieldLabel	: _('newsletter.label_newsletter_stats_lists'),
					        	anchor		: '100%',
					        	value 		: item.lists_formatted,
					        	readOnly	: true,
					        	cls 		: 'x-static-text-field x-item-disabled',
					        }]
						}]
					}, {
			        	xtype		: 'textarea',
			        	fieldLabel	: _('newsletter.label_newsletter_stats_emails', {
				        	total		: item.emails_total
				        }),
			        	anchor		: '100%',
			        	value 		: item.emails,
			        	readOnly	: true,
			        	cls 		: 'x-static-text-field x-item-disabled',
			        }]
			    });
			}
		});
		
		return fields;
	}
});

Ext.reg('newsletter-window-newsletter-stats', Newsletter.window.StatsNewsletter);

Newsletter.window.SendNewsletter = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	autoHeight	: true,
    	width		: 600,
        title 		: _('newsletter.newsletter_send'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/newsletters/send'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            xtype		: 'hidden',
            name		: 'resource_id'
        }, {
	    	xtype 		: 'newsletter-combo-newsletter-type',
	    	fieldLabel 	: _('newsletter.label_newsletter_type'),
	    	description	: MODx.expandHelp ? '' : _('newsletter.label_newsletter_type_desc'),
	    	name 		: 'newsletter_type',
	    	anchor 		: '100%',
        	listeners	: {
	        	'change'	: {
		        	fn 			: this.setNewsletterType,
		        	scope		: this
	        	}
        	}
	    }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_newsletter_type_desc'),
            cls			: 'desc-under'
	    }, {
	    	layout		: 'form',
	    	style 		: 'padding-top: 15px;',
	    	id			: 'newsletter-type-1',
	    	hidden 		: false,
	    	defaults	: {
                labelSeparator : ''
            },
			items		: [{
	        	xtype		: 'datefield',
	        	fieldLabel	: _('newsletter.label_newsletter_send_date'),
	        	description	: MODx.expandHelp ? '' : _('newsletter.label_newsletter_send_date_desc'),
	        	name		: 'send_date',
	        	anchor		: '100%',
	        	allowBlank	: false,
	        	format		: MODx.config.manager_date_format,
	        	startDay	: parseInt(MODx.config.manager_week_start),
	        	minValue 	: this.getFirstDate().format(MODx.config.manager_date_format),
	        	listeners 	: {
					'afterrender' : {
						fn			: function(tf) {
							if ('' == tf.getValue()) {
								tf.setValue(this.getFirstDate());
							}
						},
						scope 		: this
        			}
				}
	        }, {
	        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
	            html		: _('newsletter.label_newsletter_send_date_desc'),
	            cls			: 'desc-under'
	        }, {
		        layout		: 'column',
		        style 		: 'padding-top: 15px;',
	            defaults	: {
	                layout		: 'form',
	                labelSeparator : ''
	            },
	            items: [{
	            	columnWidth	: .4,
	                items		: [{
			        	xtype		: 'textfield',
			        	fieldLabel	: _('newsletter.label_newsletter_send_repeat'),
			        	description	: MODx.expandHelp ? '' : _('newsletter.label_newsletter_send_repeat_desc'),
			        	name		: 'send_repeat',
			        	anchor		: '100%',
			        	value 		: '1'
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		: _('newsletter.label_newsletter_send_repeat_desc'),
			            cls			: 'desc-under'
			        }]        
		        }, {
					columnWidth	: .6,
					style		: 'margin-right: 0;',
					items		: [{
			        	xtype		: 'textfield',
			        	fieldLabel	: _('newsletter.label_newsletter_send_interval'),
			        	description	: MODx.expandHelp ? '' : _('newsletter.label_newsletter_send_interval_desc'),
			        	name		: 'send_interval',
			        	anchor		: '100%',
			        	value 		: '7'
			        }, {
			        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
			            html		: _('newsletter.label_newsletter_send_interval_desc'),
			            cls			: 'desc-under'
			        }]
				}]
		    }],
	    }, {
	        layout		: 'column',
	        style 		: 'padding-top: 15px;',
            defaults	: {
                layout		: 'form',
                labelSeparator : '',
            },
            items: [{
            	columnWidth	: .4,
                items		: [{
			       	xtype		: 'label',
			       	fieldLabel	: _('newsletter.label_newsletter_send_lists')
			    }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_newsletter_send_lists_desc'),
		            cls			: 'desc-under'
		        }, {
			       xtype		: 'newsletter-checkbox-lists',
			       value		: config.record.lists
			    }]
			}, {
				columnWidth	: .6,
				style		: 'margin-right: 0;',
				items		: [{
		        	xtype		: 'textfield',
		        	fieldLabel	: _('newsletter.label_newsletter_send_emails'),
		        	description	: MODx.expandHelp ? '' : _('newsletter.label_newsletter_send_emails_desc'),
		        	name		: 'send_emails',
		        	anchor		: '100%'
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_newsletter_send_emails_desc'),
		            cls			: 'desc-under'
		        }]
			}]
		}]
    });
    
    Newsletter.window.SendNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.SendNewsletter, MODx.Window, {
	setNewsletterType: function(tf, nv) {
		var scope = this;
		
		if (1 == tf.getValue()) {
			Ext.getCmp('newsletter-type-1').show();
		} else {
			Ext.MessageBox.confirm(_('warning'), _('newsletter.newsletter_type_confirm'), function(btn) {
				if ('no' == btn) {
					scope.fp.getForm().findField('newsletter_type').setValue(1);
				} else {
					scope.fp.getForm().findField('send_date').setValue(scope.getFirstDate());
					
					Ext.getCmp('newsletter-type-1').hide();
				}
			});
		}
	},
	getFirstDate: function() {
		var date = new Date();
    
		date.setDate(date.getDate() + 1);
		
		return date;
	}
});

Ext.reg('newsletter-window-newsletter-send', Newsletter.window.SendNewsletter);

Newsletter.combo.NewsletterType = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        store: new Ext.data.ArrayStore({
            mode	: 'local',
            fields	: ['type', 'label'],
            data	: [
	            [1, _('newsletter.newsletter_type_1')],
	            [2, _('newsletter.newsletter_type_2')],
				[3, _('newsletter.newsletter_type_3')]
            ]
        }),
        remoteSort	: ['type', 'asc'],
        hiddenName	: 'newsletter_type',
        valueField	: 'type',
        displayField: 'label',
        mode		: 'local'
    });
    
    Newsletter.combo.NewsletterType.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.NewsletterType, MODx.combo.ComboBox);

Ext.reg('newsletter-combo-newsletter-type', Newsletter.combo.NewsletterType);