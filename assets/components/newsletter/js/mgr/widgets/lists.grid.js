Newsletter.grid.Lists = function(config) {
    config = config || {};

    config.tbar = [{
        text        : _('newsletter.list_create'),
        cls         : 'primary-button',
        handler     : this.createList,
        scope       : this
    }, {
        text        : _('bulk_actions'),
        menu        : [{
            text        : '<i class="x-menu-item-icon icon icon-times"></i> ' + _('newsletter.lists_remove_selected'),
            handler     : this.removeSelectedLists,
            scope       : this
        }, '-', {
            text        : '<i class="x-menu-item-icon icon icon-upload"></i> ' + _('newsletter.list_import'),
            handler     : this.importList,
            scope       : this
        }, {
            text        : '<i class="x-menu-item-icon icon icon-download"></i> ' + _('newsletter.list_export'),
            handler     : this.exportList,
            scope       : this
        }]
    }, '->', {
        xtype       : 'textfield',
        name        : 'newsletter-filter-lists-search',
        id          : 'newsletter-filter-lists-search',
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
                scope       : this
            }
        }
    }, {
        xtype       : 'button',
        cls         : 'x-form-filter-clear',
        id          : 'newsletter-filter-lists-clear',
        text        : _('filter_clear'),
        listeners   : {
            'click'     : {
                fn          : this.clearFilter,
                scope       : this
            }
        }
    }];

    var expander = new Ext.grid.RowExpander({
        tpl : new Ext.Template('<p class="desc">{description_formatted}</p>'),
        getRowClass : function(record, rowIndex, p, ds) {
            p.cols = p.cols-1;

            var content = this.bodyContent[record.id];

            if (!content && !this.lazyRender) {
                content = this.getBodyContent(record, rowIndex);
            }

            if (content) {
                p.body = content;
            }

            return this.state[record.id] ? 'x-grid3-row-expanded' : 'x-grid3-row-collapsed';
        }
    });

    var sm = new Ext.grid.CheckboxSelectionModel();

    var columns = new Ext.grid.ColumnModel({
        columns     : [expander, sm, {
            header      : _('newsletter.label_list_name'),
            dataIndex   : 'name_formatted',
            sortable    : true,
            editable    : false,
            width       : 200,
            renderer    : this.renderName
        }, {
            header      : _('newsletter.label_list_subscriptions'),
            dataIndex   : 'subscriptions',
            sortable    : true,
            editable    : false,
            width       : 150,
            fixed       : true
        }, {
            header      : _('newsletter.label_list_active'),
            dataIndex   : 'active',
            sortable    : true,
            editable    : true,
            width       : 100,
            fixed       : true,
            renderer    : this.renderBoolean,
            editor      : {
                xtype   : 'modx-combo-boolean'
            }
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
        sm          : sm,
        cm          : columns,
        id          : 'newsletter-grid-lists',
        url         : Newsletter.config.connector_url,
        baseParams  : {
            action      : 'mgr/lists/getlist',
            context     : MODx.request.context || MODx.config.default_context
        },
        autosave    : true,
        save_action : 'mgr/lists/updatefromgrid',
        fields      : ['id', 'name', 'description', 'primary', 'hidden', 'active', 'editedon', 'name_formatted', 'description_formatted', 'subscriptions'],
        paging      : true,
        pageSize    : MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        sortBy      : 'id',
        plugins     : expander,
        singleText  : _('newsletter.list'),
        pluralText  : _('newsletter.lists'),
        refreshGrid : []
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

        Ext.getCmp('newsletter-filter-lists-search').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        var menu = [{
            text    : '<i class="x-menu-item-icon icon icon-edit"></i>' + _('newsletter.list_update'),
            handler : this.updateList,
            scope   : this
        }, '-', {
            text    :'<i class="x-menu-item-icon icon icon-upload"></i>' +  _('newsletter.list_import'),
            handler : this.importList,
            scope   : this
        }, {
            text    : '<i class="x-menu-item-icon icon icon-download"></i>' + _('newsletter.list_export'),
            handler : this.exportList,
            scope   : this
        }];

        if (parseInt(this.menu.record.primary) === 0) {
            menu.push('-', {
                text    : '<i class="x-menu-item-icon icon icon-times"></i>' + _('newsletter.list_remove'),
                handler : this.removeList,
                scope   : this
            });
        }

        return menu;
    },
    refreshGrids: function() {
        if (typeof this.config.refreshGrid === 'string') {
            Ext.getCmp(this.config.refreshGrid).refresh();
        } else {
            this.config.refreshGrid.forEach(function(grid) {
                Ext.getCmp(grid).refresh();
            });
        }
    },
    createList: function(btn, e) {
        if (this.createListWindow) {
            this.createListWindow.destroy();
        }

        this.createListWindow = MODx.load({
            xtype       : 'newsletter-window-list-create',
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.getSelectionModel().clearSelections(true);

                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
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
            xtype       : 'newsletter-window-list-update',
            record      : this.menu.record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.getSelectionModel().clearSelections(true);

                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
             }
        });

        this.updateListWindow.setValues(this.menu.record);
        this.updateListWindow.show(e.target);
    },
    removeList: function(btn, e) {
        MODx.msg.confirm({
            title       : _('newsletter.list_remove'),
            text        : _('newsletter.list_remove_confirm'),
            url         : Newsletter.config.connector_url,
            params      : {
                action      : 'mgr/lists/remove',
                id          : this.menu.record.id
            },
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.getSelectionModel().clearSelections(true);

                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });
    },
    removeSelectedLists: function(btn, e) {
        var cs = this.getSelectedAsList();

        if (cs === false) {
            return false;
        }

        MODx.msg.confirm({
            title       : _('newsletter.lists_remove_selected'),
            text        : _('newsletter.lists_remove_selected_confirm'),
            url         : Newsletter.config.connector_url,
            params      : {
                action      : 'mgr/lists/removeselected',
                ids         : cs
            },
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.getSelectionModel().clearSelections(true);

                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                }
            }
        });
    },
    importList: function(btn, e) {
        if (this.importListWindow) {
            this.importListWindow.destroy();
        }

        this.importListWindow = MODx.load({
            xtype       : 'newsletter-window-list-import',
            record      : this.menu.record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function() {
                        this.getSelectionModel().clearSelections(true);

                        this.refreshGrids();
                        this.refresh();
                    },
                    scope       : this
                },
                'failure'   : {
                    fn          : function(response) {
                        MODx.msg.alert(_('failure'), response.message);
                    },
                    scope       : this
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
            xtype       : 'newsletter-window-list-export',
            record      : this.menu.record,
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function() {
                        location.href = Newsletter.config.connector_url + '?action=' + this.exportListWindow.baseParams.action + '&download=1&HTTP_MODAUTH=' + MODx.siteId;
                    },
                    scope       : this
                },
                'failure'   : {
                    fn          : function(response) {
                        MODx.msg.alert(_('failure'), response.message);
                    },
                    scope       : this
                }
             }
        });

        this.exportListWindow.setValues(this.menu.record);
        this.exportListWindow.show(e.target);
    },
    renderName: function(d, c, e) {
        if (e.data.hidden === 1) {
            return '<i class="icon icon-lock"></i> ' + d;
        }

        if (e.data.primary === 1) {
            return '<i class="icon icon-star"></i> ' + d;
        }

        return d;
    },
    renderBoolean: function(d, c) {
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

Ext.reg('newsletter-grid-lists', Newsletter.grid.Lists);

Newsletter.window.CreateList = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('newsletter.list_create'),
        url         : Newsletter.config.connector_url,
        baseParams  : {
            action      : 'mgr/lists/create'
        },
        fields      : [{
            layout      : 'column',
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            items       : [{
                columnWidth : .85,
                items       : [{
                    xtype       : 'textfield',
                    fieldLabel  : _('newsletter.label_list_name'),
                    description : MODx.expandHelp ? '' : _('newsletter.label_list_name_desc'),
                    name        : 'name',
                    anchor      : '100%',
                    allowBlank  : false
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('newsletter.label_list_name_desc'),
                    cls         : 'desc-under'
                }]
            }, {
                columnWidth : .15,
                items       : [{
                    xtype       : 'checkbox',
                    fieldLabel  : _('newsletter.label_list_active'),
                    description : MODx.expandHelp ? '' : _('newsletter.label_list_active_desc'),
                    name        : 'active',
                    inputValue  : 1,
                    checked     : true
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('newsletter.label_list_active_desc'),
                    cls         : 'desc-under'
                }]
            }]
        }, {
            xtype       : 'textarea',
            fieldLabel  : _('newsletter.label_list_description'),
            description : MODx.expandHelp ? '' : _('newsletter.label_list_description_desc'),
            name        : 'description',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('newsletter.label_list_description_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'checkbox',
            boxLabel    : _('newsletter.label_list_primary_desc'),
            anchor      : '100%',
            name        : 'primary',
            inputValue  : 1
        }, {
            xtype       : 'checkbox',
            boxLabel    : _('newsletter.label_list_hidden_desc'),
            anchor      : '100%',
            name        : 'hidden',
            inputValue  : 1,
            hidden      : !Newsletter.config.permissions.admin
        }]
    });

    Newsletter.window.CreateList.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.CreateList, MODx.Window);

Ext.reg('newsletter-window-list-create', Newsletter.window.CreateList);

Newsletter.window.UpdateList = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('newsletter.list_update'),
        url         : Newsletter.config.connector_url,
        baseParams  : {
            action      : 'mgr/lists/update'
        },
        fields      : [{
            xtype       : 'hidden',
            name        : 'id'
        }, {
            layout      : 'column',
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            items       : [{
                columnWidth : .85,
                items       : [{
                    xtype       : 'textfield',
                    fieldLabel  : _('newsletter.label_list_name'),
                    description : MODx.expandHelp ? '' : _('newsletter.label_list_name_desc'),
                    name        : 'name',
                    anchor      : '100%',
                    allowBlank  : false
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('newsletter.label_list_name_desc'),
                    cls         : 'desc-under'
                }]
            }, {
                columnWidth : .15,
                items       : [{
                    xtype       : 'checkbox',
                    fieldLabel  : _('newsletter.label_list_active'),
                    description : MODx.expandHelp ? '' : _('newsletter.label_list_active_desc'),
                    name        : 'active',
                    inputValue  : 1,
                    checked     : true
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('newsletter.label_list_active_desc'),
                    cls         : 'desc-under'
                }]
            }]
        }, {
            xtype       : 'textarea',
            fieldLabel  : _('newsletter.label_list_description'),
            description : MODx.expandHelp ? '' : _('newsletter.label_list_description_desc'),
            name        : 'description',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('newsletter.label_list_description_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'checkbox',
            boxLabel    : _('newsletter.label_list_primary_desc'),
            hideLabel   : true,
            anchor      : '100%',
            name        : 'primary',
            inputValue  : 1
        }, {
            xtype       : 'checkbox',
            boxLabel    : _('newsletter.label_list_hidden_desc'),
            hideLabel   : true,
            anchor      : '100%',
            name        : 'hidden',
            inputValue  : 1,
            hidden      : !Newsletter.config.permissions.admin
        }]
    });

    Newsletter.window.UpdateList.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.UpdateList, MODx.Window);

Ext.reg('newsletter-window-list-update', Newsletter.window.UpdateList);

Newsletter.window.ImportList = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('newsletter.list_import'),
        url         : Newsletter.config.connector_url,
        baseParams  : {
            action      : 'mgr/lists/import'
        },
        fields      : [{
            xtype       : 'newsletter-combo-lists',
            fieldLabel  : _('newsletter.label_import_list'),
            description : MODx.expandHelp ? '' : _('newsletter.label_import_list_desc'),
            name        : 'id',
            hiddenName  : 'id',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('newsletter.label_import_list_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'fileuploadfield',
            fieldLabel  : _('newsletter.label_import_file'),
            description : MODx.expandHelp ? '' : _('newsletter.label_import_file_desc'),
            buttonText  : _('upload.buttons.choose'),
            name        : 'file',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('newsletter.label_import_file_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('newsletter.label_import_delimiter'),
            description : MODx.expandHelp ? '' : _('newsletter.label_import_delimiter_desc'),
            name        : 'delimiter',
            anchor      : '100%',
            allowBlank  : false,
            value       : ';'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('newsletter.label_import_delimiter_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'checkboxgroup',
            hideLabel   : true,
            columns     : 1,
            items       : [{
                xtype       : 'checkbox',
                boxLabel    : _('newsletter.label_import_headers'),
                anchor      : '100%',
                name        : 'headers',
                checked     : true,
                inputValue  : 1
            }, {
                xtype       : 'checkbox',
                boxLabel    : _('newsletter.label_import_reset'),
                anchor      : '100%',
                name        : 'reset',
                checked     : false,
                inputValue  : 1
            }]
        }],
        fileUpload  : true,
        saveBtnText : _('import')
    });

    Newsletter.window.ImportList.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.ImportList, MODx.Window);

Ext.reg('newsletter-window-list-import', Newsletter.window.ImportList);

Newsletter.window.ExportList = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('newsletter.list_export'),
        url         : Newsletter.config.connector_url,
        baseParams  : {
            action      : 'mgr/lists/export'
        },
        fields      : [{
            xtype       : 'newsletter-combo-lists',
            fieldLabel  : _('newsletter.label_export_list'),
            description : MODx.expandHelp ? '' : _('newsletter.label_export_list_desc'),
            name        : 'id',
            hiddenName  : 'id',
            anchor      : '100%',
            allowBlank  : false
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('newsletter.label_export_list_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('newsletter.label_import_delimiter'),
            description : MODx.expandHelp ? '' : _('newsletter.label_import_delimiter_desc'),
            name        : 'delimiter',
            anchor      : '100%',
            allowBlank  : false,
            value       : ';'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('newsletter.label_import_delimiter_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'checkboxgroup',
            hideLabel   : true,
            columns     : 1,
            items       : [{
                xtype       : 'checkbox',
                boxLabel    : _('newsletter.label_import_headers'),
                anchor      : '100%',
                name        : 'headers',
                checked     : true,
                inputValue  : 1
            }]
        }],
        saveBtnText : _('export')
    });

    Newsletter.window.ExportList.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.window.ExportList, MODx.Window);

Ext.reg('newsletter-window-list-export', Newsletter.window.ExportList);