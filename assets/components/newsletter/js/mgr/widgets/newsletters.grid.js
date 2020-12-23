Newsletter.grid.Newsletters = function(config) {
    config = config || {};

    config.tbar = [{
        text        : _('newsletter.newsletter_create'),
        cls         : 'primary-button',
        handler     : this.createNewsletter,
        scope       : this
    }, '->', {
        xtype       : 'textfield',
        name        : 'newsletter-filter-newsletters-search',
        id          : 'newsletter-filter-newsletters-search',
        emptyText   : _('search') + '...',
        listeners   : {
            'change'    : {
                fn          : this.filterSearch,
                scope       : this
            },
            'render'    : {
                fn          : function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key     : Ext.EventObject.ENTER,
                        fn      : this.blur,
                        scope   : cmp
                    });
                },
                scope   : this
            }
        }
    }, {
        xtype       : 'button',
        cls         : 'x-form-filter-clear',
        id          : 'newsletter-filter-newsletters-clear',
        text        : _('filter_clear'),
        listeners   : {
            'click'     : {
                fn          : this.clearFilter,
                scope       : this
            }
        }
    }];

    var columns = new Ext.grid.ColumnModel({
        columns     : [{
            header      : _('newsletter.label_newsletter_resource'),
            dataIndex   : 'name',
            sortable    : true,
            editable    : false,
            width       : 150,
            renderer    : this.renderName
        }, {
            header      : _('newsletter.label_newsletter_status'),
            dataIndex   : 'status',
            sortable    : true,
            editable    : false,
            width       : 200,
            fixed       : true,
            renderer    : this.renderStatus
        }, {
            header      : _('newsletter.label_newsletter_published'),
            dataIndex   : 'published',
            sortable    : true,
            editable    : false,
            width       : 125,
            fixed       : true,
            renderer    : this.renderPublished
        }, {
            header      : _('last_modified'),
            dataIndex   : 'editedon',
            sortable    : true,
            editable    : false,
            fixed       : true,
            width       : 200,
            renderer    : this.renderDate
        }]
    });
    
    Ext.applyIf(config, {
        cm          : columns,
        id          : 'newsletter-grid-newsletters',
        url         : Newsletter.config.connector_url,
        baseParams  : {
            action      : 'mgr/newsletters/getlist',
            context     : MODx.request.context || MODx.config.default_context
        },
        fields      : ['id', 'resource_id', 'filter', 'hidden', 'editedon', 'name', 'published', 'url', 'status', 'last_date', 'last_date_format', 'last_time_format', 'history'],
        paging      : true,
        pageSize    : MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy      : 'id'
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

        Ext.getCmp('newsletter-filter-newsletters-search').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        var menu = [];

        if (parseInt(this.menu.record.send_status) !== 2) {
            menu.push({
                text    : '<i class="x-menu-item-icon icon icon-edit"></i>' + _('newsletter.newsletter_update'),
                handler : this.updateNewsletter,
                scope   : this
            }, '-');
        }

        menu.push({
            text    :  '<i class="x-menu-item-icon icon icon-eye"></i>' + _('newsletter.newsletter_preview'),
            handler : this.previewNewsletter,
            scope   : this
        }, {
            text    : '<i class="x-menu-item-icon icon icon-paper-plane"></i>' + _('newsletter.newsletter_queue_test'),
            handler : this.queueTestNewsletter,
            scope   : this
        });

        if (parseInt(this.menu.record.status) !== 2) {
            if (parseInt(this.menu.record.status) === 1) {
                menu.push('-', {
                    text    :  '<i class="x-menu-item-icon icon icon-ban"></i>' + _('newsletter.newsletter_queue_cancel'),
                    handler : this.queueCancelNewsletter,
                    scope   : this
                });
            } else {
                menu.push('-', {
                    text    : '<i class="x-menu-item-icon icon icon-paper-plane"></i>' + _('newsletter.newsletter_queue_send'),
                    handler : this.queueSendNewsletter,
                    scope   : this
                });
            }
        }

        if (parseInt(this.menu.record.history.length) > 0) {
            menu.push('-', {
                text    : '<i class="x-menu-item-icon icon icon-info"></i>' + _('newsletter.newsletter_history'),
                handler : this.historyNewsletter,
                scope   : this
            });
        }

        menu.push('-', {
            text    : '<i class="x-menu-item-icon icon icon-times"></i>' + _('newsletter.newsletter_remove'),
            handler : this.removeNewsletter,
            scope   : this
         });

        return menu;
    },
    createNewsletter: function(btn, e) {
        if (this.createNewsletterWindow) {
            this.createNewsletterWindow.destroy();
        }

        this.createNewsletterWindow = MODx.load({
            xtype       : 'newsletter-window-newsletter-create',
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
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
            xtype       : 'newsletter-window-newsletter-update',
            record      : this.menu.record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
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
            xtype       : 'newsletter-window-newsletter-preview',
            record      : this.menu.record,
            closeAction : 'close',
            buttons     : [{
                text        : _('ok'),
                cls         : 'primary-button',
                handler     : function() {
                    this.previewNewsletterWindow.close();
                },
                scope       : this
            }]
        });

        this.previewNewsletterWindow.show(e.target);
    },
    queueTestNewsletter: function(btn, e) {
        if (this.queueTestNewsletterWindow) {
            this.queueTestNewsletterWindow.destroy();
        }

        this.queueTestNewsletterWindow = MODx.load({
            xtype       : 'newsletter-window-newsletter-queue-test',
            record      : this.menu.record,
            closeAction : 'close',
            saveBtnText : _('newsletter.send'),
            listeners   : {
                'success'   : {
                    fn          : function(data) {
                        this.queueTestNewsletterConsole = MODx.load({
                            xtype       : 'modx-console',
                            register    : 'mgr',
                            topic       : '/newsletter/'
                        });

                        MODx.Ajax.request({
                            url         : Newsletter.config.connector_url,
                            params      : {
                                action      : 'mgr/newsletters/send',
                                id          : data.a.result.object.id,
                                type        : 'test',
                                register    : 'mgr',
                                topic       : '/newsletter/'
                            },
                            listeners   : {
                                'success'   : {
                                    fn          : function() {
                                        this.queueTestNewsletterConsole.fireEvent('complete');

                                        MODx.msg.status({
                                            title   : _('newsletter.newsletter_send_succes'),
                                            message : _('newsletter.newsletter_send_succes_desc')
                                        });

                                        this.refresh();
                                    },
                                    scope       : this
                                },
                                'failure'   : {
                                    fn          : function(data) {
                                        MODx.msg.alert(_('warning'), data.message);
                                    },
                                    scope       : this
                                }
                            }
                        });

                        this.queueTestNewsletterConsole.show();
                    },
                    scope       : this
                }
             }
        });

        this.queueTestNewsletterWindow.setValues(this.menu.record);
        this.queueTestNewsletterWindow.show(e.target);
    },
    queueSendNewsletter: function(btn, e) {
        if (this.queueSendNewsletterWindow) {
            this.queueSendNewsletterWindow.destroy();
        }

        this.queueSendNewsletterWindow = MODx.load({
            xtype       : 'newsletter-window-newsletter-queue-send',
            record      : this.menu.record,
            closeAction : 'close',
            saveBtnText : _('newsletter.queue'),
            listeners   : {
                'success'   : {
                    fn          : function(data) {
                        MODx.msg.status({
                            title   : _('newsletter.newsletter_queue_save'),
                            message : _('newsletter.newsletter_queue_save_desc')
                        });

                        this.refresh();
                    },
                    scope       : this
                }
             }
        });

        this.queueSendNewsletterWindow.setValues(this.menu.record);
        this.queueSendNewsletterWindow.show(e.target);
    },
    queueCancelNewsletter: function() {
        MODx.msg.confirm({
            title       : _('newsletter.newsletter_queue_cancel'),
            text        : _('newsletter.newsletter_queue_cancel_confirm'),
            url         : Newsletter.config.connector_url,
            params      : {
                action      : 'mgr/newsletters/queuecancel',
                id          : this.menu.record.id
            },
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });
    },
    historyNewsletter: function(btn, e) {
        if (this.historyNewsletterWindow) {
            this.historyNewsletterWindow.destroy();
        }

        this.historyNewsletterWindow = MODx.load({
            xtype       : 'newsletter-window-newsletter-history',
            record      : this.menu.record,
            closeAction : 'close',
            buttons     : [{
                text        : _('ok'),
                cls         : 'primary-button',
                handler     : function() {
                    this.historyNewsletterWindow.close();
                },
                scope       : this
            }]
        });

        this.historyNewsletterWindow.show(e.target);
    },
    removeNewsletter: function() {
        MODx.msg.confirm({
            title       : _('newsletter.newsletter_remove'),
            text        : _('newsletter.newsletter_remove_confirm'),
            url         : Newsletter.config.connector_url,
            params      : {
                action      : 'mgr/newsletters/remove',
                id          : this.menu.record.id
            },
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });
    },
    renderName: function(d, c, e) {
        if (e.data.hidden === 1) {
            return '<i class="icon icon-lock"></i><a href="?a=resource/update&id=' + e.data.resource_id + '" title="' + _('edit') + '" class="x-grid-link">' + Ext.util.Format.htmlEncode(d) + '</a>';
        }

        return '<a href="?a=resource/update&id=' + e.data.resource_id + '" title="' + _('edit') + '" class="x-grid-link">' + Ext.util.Format.htmlEncode(d) + '</a>';
    },
    renderStatus: function(d, c, e) {
        if (parseInt(d) === 0) {
            c.css = 'red';

            return _('newsletter.newsletter_status_0');
        }

        if (parseInt(d) === 1) {
            c.css = 'orange';

            return _('newsletter.newsletter_status_1') + ' <em>(' + e.data.last_date_format + ', ' + e.data.last_time_format + ')</em>';
        }

        c.css = 'green';

        return _('newsletter.newsletter_status_2') + ' <em>(' + e.data.last_date_format + ', ' + e.data.last_time_format + ')</em>';
    },
    renderPublished: function(d, c, e) {
        c.css = parseInt(d) === 1 || d ? 'green' : 'red';

        return parseInt(d) === 1 || d ? _('yes') : _('no');
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
        autoHeight  : true,
        title       : _('newsletter.newsletter_create'),
        url         : Newsletter.config.connector_url,
        baseParams  : {
            action      : 'mgr/newsletters/create'
        },
        fields      : [{
            html        : '<p>' + _('newsletter.newsletter_create_desc') + '</p>',
            cls         : 'panel-desc',
        }, {
            xtype       : 'newsletter-combo-resources',
            fieldLabel  : _('newsletter.label_newsletter_resource'),
            description : MODx.expandHelp ? '' : _('newsletter.label_newsletter_resource_desc'),
            anchor      : '100%',
            name        : 'resource_id',
            hiddenName  : 'resource_id',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('newsletter.label_newsletter_resource_desc'),
            cls         : 'desc-under'
        }, {
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            hidden      : !Newsletter.config.permissions.admin,
            items       : [{
                items       : [{
                    xtype       : 'newsletter-combo-filters',
                    fieldLabel  : _('newsletter.label_newsletter_filter'),
                    description : MODx.expandHelp ? '' : _('newsletter.label_newsletter_filter_desc'),
                    name        : 'filter',
                    hiddenName  : 'filter',
                    anchor      : '100%'
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('newsletter.label_newsletter_filter_desc'),
                    cls         : 'desc-under'
                }]
            }]
        }, {
            xtype       : 'checkbox',
            hideLabel   : true,
            boxLabel    : _('newsletter.label_newsletter_hidden_desc'),
            anchor      : '100%',
            name        : 'hidden',
            inputValue  : 1,
            hidden      : !Newsletter.config.permissions.admin
        }]
    });

    Newsletter.window.CreateNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.CreateNewsletter, MODx.Window);

Ext.reg('newsletter-window-newsletter-create', Newsletter.window.CreateNewsletter);

Newsletter.window.UpdateNewsletter = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('newsletter.newsletter_update'),
        url         : Newsletter.config.connector_url,
        baseParams  : {
            action      : 'mgr/newsletters/update'
        },
        fields      : [{
            html        : '<p>' + _('newsletter.newsletter_update_desc') + '</p>',
            cls         : 'panel-desc',
        }, {
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'newsletter-combo-resources',
            fieldLabel  : _('newsletter.label_newsletter_resource'),
            description : MODx.expandHelp ? '' : _('newsletter.label_newsletter_resource_desc'),
            anchor      : '100%',
            name        : 'resource_id',
            hiddenName  : 'resource_id',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('newsletter.label_newsletter_resource_desc'),
            cls         : 'desc-under'
        }, {
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            hidden      : !Newsletter.config.permissions.admin,
            items       : [{
                items       : [{
                    xtype       : 'newsletter-combo-filters',
                    fieldLabel  : _('newsletter.label_newsletter_filter'),
                    description : MODx.expandHelp ? '' : _('newsletter.label_newsletter_filter_desc'),
                    name        : 'filter',
                    hiddenName  : 'filter',
                    anchor      : '100%'
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('newsletter.label_newsletter_filter_desc'),
                    cls         : 'desc-under'
                }]
            }]
        }, {
            xtype       : 'checkbox',
            hideLabel   : true,
            boxLabel    : _('newsletter.label_newsletter_hidden_desc'),
            anchor      : '100%',
            name        : 'hidden',
            inputValue  : 1,
            hidden      : !Newsletter.config.permissions.admin
        }]
    });
    
    Newsletter.window.UpdateNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateNewsletter, MODx.Window);

Ext.reg('newsletter-window-newsletter-update', Newsletter.window.UpdateNewsletter);

Newsletter.window.PreviewNewsletter = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        width       : 850,
        height      : 550,
        title       : _('newsletter.newsletter_preview'),
        layout      : 'fit',
        autoHeight  : false,
        bodyStyle   : 'padding: 0;',
        fields      : [{
            xtype       : 'container',
            layout      : {
                type        : 'vbox',
                align       : 'stretch'
            },
            width       : '100%',
            height      : '100%',
            items       :[{
                autoEl      : {
                    tag         : 'iframe',
                    src         : config.record.url,
                    width       : '100%',
                    height      : '100%',
                    frameBorder : 0
                }
            }]
        }]
    });
    
    Newsletter.window.PreviewNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.PreviewNewsletter, MODx.Window);

Ext.reg('newsletter-window-newsletter-preview', Newsletter.window.PreviewNewsletter);

Newsletter.window.QueueTestwNewsletter = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        width       : 600,
        title       : _('newsletter.newsletter_queue_test'),
        url         : Newsletter.config.connector_url,
        baseParams  : {
            action      : 'mgr/newsletters/queuetest'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'hidden',
            name        : 'resource_id'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('newsletter.label_newsletter_queue_emails'),
            description : MODx.expandHelp ? '' : _('newsletter.label_newsletter_queue_emails_desc'),
            name        : 'emails',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('newsletter.label_newsletter_queue_emails_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'label',
            fieldLabel  : _('newsletter.label_newsletter_queue_lists')
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('newsletter.label_newsletter_queue_lists_desc'),
            cls         : 'desc-under'
        }, {
           xtype        : 'newsletter-checkbox-lists',
           columns      : 2
        }]
    });
    
    Newsletter.window.QueueTestwNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.QueueTestwNewsletter, MODx.Window);

Ext.reg('newsletter-window-newsletter-queue-test', Newsletter.window.QueueTestwNewsletter);

Newsletter.window.QueueSendNewsletter = function(config) {
    config = config || {};

    var date = new Date();

    date.setDate(date.getDate() + 1);
    date.setMinutes(Math.ceil(date.getMinutes() / 15) * 15);

    Ext.applyIf(config, {
        autoHeight  : true,
        width       : 600,
        title       : _('newsletter.newsletter_queue_send'),
        url         : Newsletter.config.connector_url,
        baseParams  : {
            action      : 'mgr/newsletters/queuesend'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            xtype       : 'hidden',
            name        : 'resource_id'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('newsletter.label_newsletter_queue_emails'),
            description : MODx.expandHelp ? '' : _('newsletter.label_newsletter_queue_emails_Desc'),
            name        : 'emails',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('newsletter.label_newsletter_queue_emails_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'label',
            fieldLabel  : _('newsletter.label_newsletter_queue_lists')
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('newsletter.label_newsletter_queue_lists_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'newsletter-checkbox-lists',
            columns     : 2
        }, {
            layout      : 'column',
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            items       : [{
                columnWidth : .5,
                items       : [{
                    xtype       : 'datefield',
                    fieldLabel  : _('newsletter.label_newsletter_date'),
                    description : MODx.expandHelp ? '' : _('newsletter.label_newsletter_date_desc'),
                    name        : 'date',
                    anchor      : '100%',
                    allowBlank  : false,
                    format      : MODx.config.manager_date_format,
                    startDay    : parseInt(MODx.config.manager_week_start),
                    value       : date
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('newsletter.label_newsletter_date_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'textfield',
                    fieldLabel  : _('newsletter.label_newsletter_repeat'),
                    description : MODx.expandHelp ? '' : _('newsletter.label_newsletter_repeat_desc'),
                    name        : 'repeat',
                    anchor      : '100%',
                    value       : '1'
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('newsletter.label_newsletter_repeat_desc'),
                    cls         : 'desc-under'
                }]
            }, {
                columnWidth : .5,
                items       : [{
                    xtype       : 'timefield',
                    fieldLabel  : _('newsletter.label_newsletter_time'),
                    description : MODx.expandHelp ? '' : _('newsletter.label_newsletter_time_desc'),
                    name        : 'time',
                    anchor      : '100%',
                    allowBlank  : false,
                    format      : MODx.config.manager_time_format,
                    increment   : 60,
                    editable    : false,
                    value       : date
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('newsletter.label_newsletter_time_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'checkboxgroup',
                    columns     : 4,
                    fieldLabel  : _('newsletter.label_newsletter_days'),
                    items       : [{
                        name        : 'days[]',
                        boxLabel    : _('newsletter.monday'),
                        inputValue  : 1
                    }, {
                        name        : 'days[]',
                        boxLabel    : _('newsletter.tuesday'),
                        inputValue  : 2
                    }, {
                        name        : 'days[]',
                        boxLabel    : _('newsletter.wednesday'),
                        inputValue  : 3
                    }, {
                        name        : 'days[]',
                        boxLabel    : _('newsletter.thursday'),
                        inputValue  : 4
                    }, {
                        name        : 'days[]',
                        boxLabel    : _('newsletter.friday'),
                        inputValue  : 5
                    }, {
                        name        : 'days[]',
                        boxLabel    : _('newsletter.saturday'),
                        inputValue  : 6
                    }, {
                        name        : 'days[]',
                        boxLabel    : _('newsletter.sunday'),
                        inputValue  : 7
                    }]
                }]
            }]
        }]
    });
    
    Newsletter.window.QueueSendNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.QueueSendNewsletter, MODx.Window);

Ext.reg('newsletter-window-newsletter-queue-send', Newsletter.window.QueueSendNewsletter);

Newsletter.window.HistoryNewsletter = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        width       : 600,
        height      : 400,
        title       : _('newsletter.newsletter_history'),
        fields      : this.getStats(config.record.history)
    });
    
    Newsletter.window.HistoryNewsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.HistoryNewsletter, MODx.Window, {
    getStats: function(data) {
        var fields = [];

        fields.push({
            html    : _('newsletter.newsletter_history_desc', {
                total   : data.length
            }),
            cls     : 'panel-desc',
        });

        Ext.each(data, function(item, index) {
            fields.push({
                xtype       : 'fieldset',
                title       : _('newsletter.label_newsletter_history_newsletter', {
                    date        : item.date,
                    type        : _('newsletter.newsletter_type_' + item.type)
                }),
                collapsible : true,
                collapsed   : 0 !== index,
                labelSeparator : '',
                items       : [{
                    xtype       : 'textfield',
                    fieldLabel  : _('newsletter.label_newsletter_history_lists'),
                    anchor      : '100%',
                    value       : item.lists.join(', '),
                    readOnly    : true,
                    cls         : 'x-static-text-field x-item-disabled',
                }, {
                    xtype       : 'textarea',
                    fieldLabel  : _('newsletter.label_newsletter_history_emails_success', {
                        total       : item.log.success ? item.log.success.length : 0
                    }),
                    anchor      : '100%',
                    value       : item.log.success ? item.log.success.join(', ') : '',
                    readOnly    : true,
                    cls         : 'x-static-text-field x-item-disabled',
                }, {
                    xtype       : 'textarea',
                    fieldLabel  : _('newsletter.label_newsletter_history_emails_failed', {
                        total       : item.log.failed ? item.log.failed.length : 0
                    }),
                    anchor      : '100%',
                    value       : item.log.failed ? item.log.failed.join(', ') : '',
                    readOnly    : true,
                    cls         : 'x-static-text-field x-item-disabled',
                }]
            });
        });

        return fields;
    }
});

Ext.reg('newsletter-window-newsletter-history', Newsletter.window.HistoryNewsletter);