Unisender.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'unisender-panel-home'
            ,renderTo: 'unisender-main-div'
        }]
    }); 
    Unisender.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(Unisender.page.Home,MODx.Component);
Ext.reg('unisender-page-home', Unisender.page.Home);

Ext.onReady(function() {
    MODx.load({ xtype: 'unisender-page-home'});
});