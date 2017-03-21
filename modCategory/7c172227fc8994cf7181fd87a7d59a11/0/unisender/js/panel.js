Unisender.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false,
		baseCls: 'modx-formpanel',
		items: [{
				html: '<h2>'+ _('Unisender')+'</h2>',
				border: false,
				cls: 'modx-page-header',
                marginTop: '10px',
			},{
				xtype: 'modx-tabs',
				bodyStyle: 'padding: 10px',
				defaults: { border: false, autoHeight: true },
				border: true,
				stateful: true,
				stateId: 'unisender-tabpanel',
				stateEvents: ['tabchange'],
				getState:function() {
					return { activeTab:this.items.indexOf(this.getActiveTab()) };
				},
				items: [
					{
						title: _('unisender.subscriberListTitle'),
						defaults: { autoHeight: true },
						items: [
							{
								html: '<p>' + _('unisender.subscriberListDesc') + '</p><br />',
								border: false
							},
                     				       {
					 	         xtype: 'unisender-grid-subscribers',
					 	         preventRender: true
							}
	
						]
					},{
						title: _('unisender.listListTitle'),
						defaults: { autoHeight: true },
						items: [
							{
								html: '<p>' + _('unisender.listListTitle') + '</p><br />',
								border: false
							},
                     				       {
					 	         xtype: 'unisender-grid-lists',
					 	         preventRender: true
							}
	
						]
					}
					
				]
			}
		]
    });
    Unisender.panel.Home.superclass.constructor.call(this,config);
};
/*	Регистрируем панель, чтобы ее мог потом вызвать предыдущий скрипт	*/
Ext.extend(Unisender.panel.Home, MODx.Panel);
Ext.reg('unisender-panel-home', Unisender.panel.Home);
