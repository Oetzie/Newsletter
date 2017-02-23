Newsletter.grid.Newsletters = function(config) {
    config = config || {};

	config.tbar = [{
        text	: _('newsletter.newsletter_create'),
        cls		:'primary-button',
        handler	: this.createNewsletter,
        scope	: this
   }, '->', {
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
            dataIndex	: 'name',
            sortable	: true,
            editable	: false,
            width		: 150,
            renderer	: this.renderName
        }, {
            header		: _('newsletter.label_newsletter_send_status'),
            dataIndex	: 'send_status',
            sortable	: true,
            editable	: false,
            width		: 200,
            fixed		: true,
			renderer	: this.renderStatus
        }, {
            header		: _('newsletter.label_newsletter_published'),
            dataIndex	: 'published',
            sortable	: true,
            editable	: false,
            width		: 125,
            fixed		: true,
			renderer	: this.renderPublished
        }, {
            header		: _('last_modified'),
            dataIndex	: 'editedon',
            sortable	: true,
            editable	: false,
            fixed		: true,
			width		: 200,
			renderer	: this.renderDate
        }]
    });
    
    Ext.applyIf(config, {
    	cm			: columns,
        id			: 'newsletter-grid-newsletters',
        url			: Newsletter.config.connector_url,
        baseParams	: {
        	action		: 'mgr/newsletters/getlist',
        	context 	: MODx.request.context || MODx.config.default_context
        },
        fields		: ['id', 'resource_id', 'resource_url', 'name', 'published', 'hidden', 'lists', 'send_status', 'send_date', 'send_time', 'send_days', 'send_repeat', 'send_date_format', 'send_time_format', 'send_emails', 'send_details', 'editedon'],
        paging		: true,
        pageSize	: MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy		: 'id',
        plugins		: expander
    });
    
    Newsletter.grid.Newsletters.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.grid.Newsletters, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();   
        
        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
	    this.getStore().baseParams.query = '';
	    
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
	    }, {
	        text	: _('newsletter.newsletter_send_test'),
	        handler	: this.sendTestNewsletter,
	        scope	: this
	    }];
	    
	    if (0 == parseInt(this.menu.record.send_status)) {
		    if (1 == parseInt(MODx.config.site_status)) {
			    menu.push('-', {
			        text	: _('newsletter.newsletter_send_live'),
			        handler	: this.sendLiveNewsletter,
			        scope	: this
			    });
			}
	    } else if (1 == parseInt(this.menu.record.send_status)) {
		    menu.push('-', {
		        text	: _('newsletter.newsletter_cancel'),
		        handler	: this.cancelNewsletter,
		        scope	: this
		    });
	    }
	    
	    if (0 < parseInt(this.menu.record.send_details.length)) {
		    menu.push('-', {
		        text	: _('newsletter.newsletter_stats'),
		        handler	: this.statsNewsletter,
		        scope	: this
		    });
	    }

		/*if ((0 == parseInt(this.menu.record.send_status) || 1 == parseInt(this.menu.record.send_status)) && 1 == parseInt(MODx.config.site_status)) {
		    menu.push({
		        text	: _('newsletter.newsletter_send_live'),
		        handler	: this.sendLiveNewsletter,
		        scope	: this
		    });
	    }
	    
	    if (1 == parseInt(this.menu.record.send_status)) {
		    menu.push({
		        text	: _('newsletter.newsletter_cancel'),
		        handler	: this.cancelNewsletter,
		        scope	: this
		    });
	    }*/
	    
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
    sendLiveNewsletter: function(btn, e) {
        if (this.sendLiveNewsletterWindow) {
	        this.sendLiveNewsletterWindow.destroy();
        }

        this.sendLiveNewsletterWindow = MODx.load({
	        xtype		: 'newsletter-window-newsletter-send-live',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        saveBtnText	: _('newsletter.send'),
	        listeners	: {
		        'success'	: {
		        	fn			: function(data) {
						MODx.msg.status({
							title	: _('newsletter.newsletter_send_save'),
							message	: _('newsletter.newsletter_send_save_desc')
						});
						
						this.refresh();
					},
					scope		: this
		        }
	         }
        });
        
        this.sendLiveNewsletterWindow.setValues(this.menu.record);
        this.sendLiveNewsletterWindow.show(e.target);
    },
    sendTestNewsletter: function(btn, e) {
        if (this.sendTestNewsletterWindow) {
	        this.sendTestNewsletterWindow.destroy();
        }

        this.sendTestNewsletterWindow = MODx.load({
	        xtype		: 'newsletter-window-newsletter-send-test',
	        record		: this.menu.record,
	        closeAction	: 'close',
	        saveBtnText	: _('newsletter.send'),
	        listeners	: {
		        'success'	: {
		        	fn			: function(data) {
						this.sendTestNewsletterConsole = MODx.load({
							xtype		: 'modx-console',
							register	: 'mgr',
							topic		: '/newsletter/'
						});
						
						MODx.Ajax.request({
						    url			: Newsletter.config.connector_url,
						    params		: {
						        action			: 'mgr/newsletters/sendtestnewsletter',
						        id				: data.a.result.object.id,
						        register		: 'mgr',
						        topic			: '/newsletter/'
						    },
						    listeners	: {
						        'success'	: {
							        fn			: function() {
								        this.sendTestNewsletterConsole.fireEvent('complete');
								        
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
								    	MODx.msg.alert(_('warning'), data.message);
							    	},
							    	scope 		: this
						    	}
						    }
						});

						this.sendTestNewsletterConsole.show();
					},
					scope		: this
		        }
	         }
        });
        
        this.sendTestNewsletterWindow.setValues(this.menu.record);
        this.sendTestNewsletterWindow.show(e.target);
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
    renderStatus: function(d, c, e) {
    	if (0 == parseInt(d)) {
	    	c.css = 'red';
	    	
	    	return _('newsletter.newsletter_status_0');
    	} else if (1 == parseInt(d)) {
	    	c.css = 'orange';
	    	
	    	return _('newsletter.newsletter_status_1') + ' <em>(' + e.data.send_date_format + ', ' + e.data.send_time_format + ')</em>';
    	} else if (2 == parseInt(d)) {
	    	c.css = 'green';
	    	
	    	return _('newsletter.newsletter_status_2') + ' <em>(' + e.data.send_date_format + ', ' + e.data.send_time_format + ')</em>';
    	}
    },
    renderPublished: function(d, c, e) {
    	c.css = 0 == parseInt(d) || !d ? 'red' : 'green';
    	
    	return 0 == parseInt(d) || !d ? _('no') : _('yes');
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
			id			: 'modx-resource-parent-hidden-create',
			value 		: 0
		}, {
			xtype		: 'hidden',
			name		: 'resource_context',
			id			: 'modx-resource-context-hidden-create',
			value 		: MODx.config.default_context
		}, {
    		xtype		: 'modx-field-parent-change',
    		fieldLabel	: _('newsletter.label_newsletter_resource'),
			description	: MODx.expandHelp ? '' : _('newsletter.label_newsletter_resource_desc'),
			anchor		: '100%',
			name		: 'resource',
			allowBlank	: false,
			formpanel	: 'newsletter-panel-home',
			parentcmp	: 'modx-resource-parent-hidden-create',
			contextcmp	: 'modx-resource-context-hidden-create'
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
			id			: 'modx-resource-parent-hidden-update',
			value 		: config.record.resource_id || 0
		}, {
			xtype		: 'hidden',
			name		: 'resource_context',
			id			: 'modx-resource-context-hidden-update',
			value 		: config.record.resource_context || MODx.config.default_context
		}, {
    		xtype		: 'modx-field-parent-change',
    		fieldLabel	: _('newsletter.label_newsletter_resource'),
			description	: MODx.expandHelp ? '' : _('newsletter.label_newsletter_resource_desc'),
			anchor		: '100%',
			name 		: 'resource',
			allowBlank	: false,
			value		: config.record.name,
			formpanel	: 'newsletter-panel-home',
			parentcmp	: 'modx-resource-parent-hidden-update',
			contextcmp	: 'modx-resource-context-hidden-update'
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
        title 		: _('newsletter.newsletter_preview'),
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

Newsletter.window.StatsNewsletter = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
    	width		: 600,
    	height		: 400,
        title 		: _('newsletter.newsletter_stats'),
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
				        	total		: item.emails.length
				        }),
			        	anchor		: '100%',
			        	value 		: item.emails.join(', '),
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

Newsletter.window.SendLiveNewsletter = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
    	width		: 600,
        title 		: _('newsletter.newsletter_send_live'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/newsletters/sendlive'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            xtype		: 'hidden',
            name		: 'resource_id'
        }, {
	        layout		: 'column',
            defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
            items: [{
            	columnWidth	: .5,
                items		: [{
		        	xtype		: 'datefield',
		        	fieldLabel	: _('newsletter.label_newsletter_send_date'),
		        	description	: MODx.expandHelp ? '' : _('newsletter.label_newsletter_send_date_desc'),
		        	name		: 'send_date',
		        	anchor		: '100%',
		        	allowBlank	: false,
		        	format		: MODx.config.manager_date_format,
		        	startDay	: parseInt(MODx.config.manager_week_start),
		        	listeners 	: {
						'afterrender' : {
							fn			: function(tf) {
								if (Ext.isEmpty(tf.getValue()) || tf.getValue() < new Date('2010/01/01 00:00:00')) {
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
					xtype 		: 'checkboxgroup',
					columns		: 4,
					fieldLabel	: _('newsletter.label_newsletter_send_days'),
					items		: [{
						name 		: 'send_days[]',
						boxLabel	: _('newsletter.monday'),
						inputValue	: '1',
						checked 	: -1 == config.record.send_days.indexOf('1') ? false : true
					}, {
						name 		: 'send_days[]',
						boxLabel	: _('newsletter.tuesday'),
						inputValue 	: '2',
						checked 	: -1 == config.record.send_days.indexOf('2') ? false : true
					}, {
						name 		: 'send_days[]',
						boxLabel	: _('newsletter.wednesday'),
						inputValue	: '3',
						checked 	: -1 == config.record.send_days.indexOf('3') ? false : true
					}, {
						name 		: 'send_days[]',
						boxLabel	: _('newsletter.thursday'),
						inputValue	: '4',
						checked 	: -1 == config.record.send_days.indexOf('4') ? false : true
					}, {
						name 		: 'send_days[]',
						boxLabel	: _('newsletter.friday'),
						inputValue	: '5',
						checked 	: -1 == config.record.send_days.indexOf('5') ? false : true
					}, {
						name 		: 'send_days[]',
						boxLabel	: _('newsletter.saturday'),
						inputValue	: '6',
						checked 	: -1 == config.record.send_days.indexOf('6') ? false : true
					}, {
						name 		: 'send_days[]',
						boxLabel	: _('newsletter.sunday'),
						inputValue	: '7',
						checked 	: -1 == config.record.send_days.indexOf('7') ? false : true
					}] 
				}, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            cls			: 'desc-under'
			    }]
            }, {
				columnWidth	: .5,
				style		: 'margin-right: 0;',
				items		: [{
		        	xtype		: 'timefield',
		        	fieldLabel	: _('newsletter.label_newsletter_send_time'),
		        	description	: MODx.expandHelp ? '' : _('newsletter.label_newsletter_send_time_desc'),
		        	name		: 'send_time',
		        	anchor		: '100%',
		        	allowBlank	: false,
		        	format		: MODx.config.manager_time_format,
		        	increment	: 60,
		        	editable	: false,
		        	listeners 	: {
						'afterrender' : {
							fn			: function(tf) {
								if (Ext.isEmpty(tf.getValue())) {
									tf.setValue(this.getFirstDate());
								}
							},
							scope 		: this
	        			}
					}
		        }, {
		        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
		            html		: _('newsletter.label_newsletter_send_time_desc'),
		            cls			: 'desc-under'
		        }, {
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
			}]
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('newsletter.label_newsletter_send_emails'),
        	description	: MODx.expandHelp ? '' : _('newsletter.label_newsletter_send_emails_desc'),
        	name		: 'send_emails',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_newsletter_send_emails_desc'),
            cls			: 'desc-under'
        }, {
	    	xtype		: 'label',
			fieldLabel	: _('newsletter.label_newsletter_send_lists')
	    }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_newsletter_send_lists_desc'),
            cls			: 'desc-under'
        }, {
	       xtype		: 'newsletter-checkbox-lists',
	       columns		: 3,
	       value		: config.record.lists
	    }]
    });
    
    Newsletter.window.SendLiveNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.SendLiveNewsletter, MODx.Window, {
	getFirstDate: function() {
		var date = new Date();
    
		date.setHours(1);
		date.setMinutes(0);
		date.setSeconds(0);
		date.setDate(date.getDate() + 1);
		
		return date;
	},
	getCurrentDate: function() {
		return new Date();
	}
});

Ext.reg('newsletter-window-newsletter-send-live', Newsletter.window.SendLiveNewsletter);

Newsletter.window.SendTestNewsletter = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	autoHeight	: true,
    	width		: 600,
        title 		: _('newsletter.newsletter_send_test'),
        url			: Newsletter.config.connector_url,
        baseParams	: {
            action		: 'mgr/newsletters/sendtest'
        },
        fields		: [{
            xtype		: 'hidden',
            name		: 'id'
        }, {
            xtype		: 'hidden',
            name		: 'resource_id'
        }, {
        	xtype		: 'textfield',
        	fieldLabel	: _('newsletter.label_newsletter_send_emails'),
        	description	: MODx.expandHelp ? '' : _('newsletter.label_newsletter_send_emails_desc'),
        	name		: 'send_emails',
        	anchor		: '100%'
        }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_newsletter_send_emails_desc'),
            cls			: 'desc-under'
        }, {
	    	xtype		: 'label',
			fieldLabel	: _('newsletter.label_newsletter_send_lists')
	    }, {
        	xtype		: MODx.expandHelp ? 'label' : 'hidden',
            html		: _('newsletter.label_newsletter_send_lists_desc'),
            cls			: 'desc-under'
        }, {
	       xtype		: 'newsletter-checkbox-lists',
	       columns		: 3,
	       value		: config.record.lists
	    }]
    });
    
    Newsletter.window.SendTestNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.SendTestNewsletter, MODx.Window);

Ext.reg('newsletter-window-newsletter-send-test', Newsletter.window.SendTestNewsletter);

Newsletter.combo.NewsletterType = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        store: new Ext.data.ArrayStore({
            mode	: 'local',
            fields	: ['type', 'label'],
            data	: [
	            [1, _('newsletter.newsletter_type_1')],
	            [2, _('newsletter.newsletter_type_2')],
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