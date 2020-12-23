Newsletter.panel.Home = function(config) {
    config = config || {};

    Ext.apply(config, {
        id          : 'newsletter-panel-home',
        cls         : 'container',
        items       : [{
            html        : '<h2>' + _('newsletter') + '</h2>',
            cls         : 'modx-page-header'
        }, {
            xtype       : 'modx-tabs',
            items       : [{
                layout      : 'form',
                title       : _('newsletter.newsletters'),
                items       : [{
                    html            : '<p>' + _('newsletter.newsletters_desc') + '</p>',
                    bodyCssClass    : 'panel-desc'
                }, {
                    html            : parseInt(MODx.config['newsletter.cronjob']) === 0 ? '<p>' + _('newsletter.cronjob_notice_desc') + '</p>' : '',
                    cls             : parseInt(MODx.config['newsletter.cronjob']) === 0 ? 'modx-config-error panel-desc' : ''
                }, {
                    xtype           : 'newsletter-grid-newsletters',
                    cls             : 'main-wrapper',
                    preventRender   : true
                }]
            }, {
                layout      : 'form',
                title       : _('newsletter.subscriptions'),
                items       : [{
                    html            : '<p>'+_('newsletter.subscriptions_desc')+'</p>',
                    bodyCssClass    : 'panel-desc'
                }, {
                    xtype           : 'newsletter-grid-subscriptions',
                    cls             : 'main-wrapper',
                    preventRender   : true,
                    refreshGrid     : ['newsletter-grid-lists']
                }]
            }, {
                layout      : 'form',
                title       : _('newsletter.lists'),
                items       : [{
                    html            : '<p>' + _('newsletter.lists_desc') + '</p>',
                    bodyCssClass    : 'panel-desc'
                }, {
                    xtype           : 'newsletter-grid-lists',
                    cls             : 'main-wrapper',
                    preventRender   : true,
                    refreshGrid     : ['newsletter-grid-subscriptions']
                }]
            }]
        }]
    });

    Newsletter.panel.Home.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter.panel.Home, MODx.FormPanel);

Ext.reg('newsletter-panel-home', Newsletter.panel.Home);