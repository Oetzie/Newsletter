Newsletter.combo.Resources = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : Newsletter.config.connector_url,
        baseParams  : {
            action      : 'mgr/resources/getlist',
            context     : MODx.request.context || MODx.config.default_context,
            combo       : true
        },
        fields      : ['id', 'name'],
        hiddenName  : 'resource',
        pageSize    : 15,
        valueField  : 'id',
        displayField : 'name',
        forceSelection : true,
        editable    : true,
        typeAhead   : true,
        enableKeyEvents : true
    });

    Newsletter.combo.Resources.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.Resources, MODx.combo.ComboBox);

Ext.reg('newsletter-combo-resources', Newsletter.combo.Resources);

Newsletter.combo.Lists = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : Newsletter.config.connector_url,
        baseParams  : {
            action      : 'mgr/lists/getlist',
            combo       : true
        },
        fields      : ['id', 'name', 'name_formatted'],
        hiddenName  : 'list',
        pageSize    : 15,
        valueField  : 'id',
        displayField : 'name_formatted'
    });

    Newsletter.combo.Lists.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.Lists, MODx.combo.ComboBox);

Ext.reg('newsletter-combo-lists', Newsletter.combo.Lists);

Newsletter.combo.Filters = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : Newsletter.config.connector_url,
        baseParams  : {
            action      : 'mgr/filters/getlist',
            combo       : true
        },
        fields      : ['id', 'name', 'description'],
        hiddenName  : 'filter',
        pageSize    : 15,
        valueField  : 'id',
        displayField : 'name',
        tpl         : new Ext.XTemplate('<tpl for=".">' +
            '<div class="x-combo-list-item">' +
                '<span style="font-weight: bold;">{name}</span>' +
                '<br /><i>{description}</i>' +
            '</div>' +
        '</tpl>')
    });

    Newsletter.combo.Filters.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.Filters, MODx.combo.ComboBox);

Ext.reg('newsletter-combo-filters', Newsletter.combo.Filters);

Newsletter.combo.SubscriptionConfirmTypes = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        store       : new Ext.data.ArrayStore({
            mode        : 'local',
            fields      : ['type','label'],
            data        : [
                ['0', _('newsletter.subscription_not_confirmed')],
                ['1', _('newsletter.subscription_confirmed')],
                ['2', _('newsletter.subscription_unsubscribed')]
            ]
        }),
        remoteSort  : ['label', 'asc'],
        hiddenName  : 'active',
        valueField  : 'type',
        displayField: 'label',
        mode        : 'local'
    });

    Newsletter.combo.SubscriptionConfirmTypes.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.SubscriptionConfirmTypes, MODx.combo.ComboBox);

Ext.reg('newsletter-combo-confirm', Newsletter.combo.SubscriptionConfirmTypes);

Newsletter.combo.NewsletterListsCheckbox = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        value       : [],
        columns     : 1,
        id          : 'newsletter-checkboxgroup-fixed',
        cls         : 'newsletter-checkboxgroup-fixed x-form-item',
        store       : new Ext.data.JsonStore({
            url         : Newsletter.config.connector_url,
            baseParams  : {
                action      : 'mgr/lists/getlist',
                context     : MODx.request.context || MODx.config.default_context,
                combo       : true
            },
            root        : 'results',
            totalProperty : 'total',
            fields      : ['id', 'name', 'name_formatted', 'hidden', 'subscriptions'],
            errorReader : MODx.util.JSONReader,
            remoteSort  : false,
            autoDestroy : true,
            autoLoad    : true,
            listeners   : {
                'load'          : {
                    fn              : this.setData,
                    scope           : this
                },
                'loadexception' : {
                    fn              : function(o, trans, resp) {
                    var status = _('code') + ': ' + resp.status + ' ' + resp.statusText + '<br/>';

                        MODx.msg.alert(_('error'), status + resp.responseText);
                    }
                }
            }
        })
    });

    Newsletter.combo.NewsletterListsCheckbox.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.NewsletterListsCheckbox, Ext.Panel, {
    setData: function(store, data) {
        var items = [];

        Ext.each(data, function(record) {
            items.push({
                xtype       : 'checkbox',
                boxLabel    : record.data.name_formatted + ' <em>(' + record.data.subscriptions + ')</em>',
                description : MODx.expandHelp ? '' : record.data.description,
                name        : 'lists[]',
                inputValue  : record.data.id,
                checked     : this.value.indexOf(record.data.id) !== -1,
                hidden      : Newsletter.config.permissions.admin || record.data.hidden === 0 ? false : true
            });
        }, this);

        this.add({
            xtype       : 'checkboxgroup',
            hideLabel   : true,
            columns     : this.columns,
            items       : items
        });

        this.doLayout();
    }
});

Ext.reg('newsletter-checkbox-lists', Newsletter.combo.NewsletterListsCheckbox);

Newsletter.combo.SubscriptionMove = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        store       : new Ext.data.ArrayStore({
            mode        : 'local',
            fields      : ['type','label'],
            data        : [
                ['add', _('newsletter.subscription_add_list')],
                ['remove', _('newsletter.subscription_remove_list')]
            ]
        }),
        remoteSort  : ['label', 'asc'],
        hiddenName  : 'type',
        valueField  : 'type',
        displayField : 'label',
        mode        : 'local'
    });

    Newsletter.combo.SubscriptionMove.superclass.constructor.call(this,config);
};

Ext.extend(Newsletter.combo.SubscriptionMove, MODx.combo.ComboBox);

Ext.reg('newsletter-combo-move', Newsletter.combo.SubscriptionMove);
