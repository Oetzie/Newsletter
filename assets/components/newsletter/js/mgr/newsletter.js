var Newsletter = function(config) {
    config = config || {};

    Newsletter.superclass.constructor.call(this, config);
};

Ext.extend(Newsletter, Ext.Component, {
    page    : {},
    window  : {},
    grid    : {},
    tree    : {},
    panel   : {},
    combo   : {},
    config  : {}
});

Ext.reg('newsletter', Newsletter);

Newsletter = new Newsletter();