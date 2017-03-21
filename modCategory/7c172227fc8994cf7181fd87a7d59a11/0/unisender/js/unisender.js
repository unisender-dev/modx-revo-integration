
var Unisender = function(config) {
    config = config || {};
    Unisender.superclass.constructor.call(this, config);
};

Ext.extend(Unisender, Ext.Component,{
    page:{}, window:{}, grid:{}, tree:{}, panel:{}, combo:{}, config:{}
});

Ext.reg('unisender', Unisender);

/*	Запускем	*/
Unisender = new Unisender();
