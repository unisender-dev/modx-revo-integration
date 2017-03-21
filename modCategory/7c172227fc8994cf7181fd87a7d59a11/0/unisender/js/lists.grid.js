Unisender.grid.Lists = function (config) {
    config = config || {};


    Ext.applyIf(config, {
        id: 'unisender-grid-lists',
        url: Unisender.config.connector_url,
        baseParams: { action: 'mgr/getunisenderlists' },
        fields: ['id', 'unisender_id', 'name'],

        columns: [

            {header: "id", width: 20, sortable: true, dataIndex: 'id'},
            {header: "Unisender ID", width: 50, sortable: true, dataIndex: 'unisender_id'},
            {header: _('unisender.listTitle'), width: 100, sortable: true, dataIndex: 'name'},

        ], tbar: [
            {
                text: _('unisender.listCreate'),
                handler: {
                    xtype: 'unisender-window-list-create', blankValues: true
                },
                xtype: 'button',
                scope: this
            },
            '-',
            {
                text: _('unisender.listListSync'),
                handler: {
                    xtype: 'unisender-window-list-sync', blankValues: true
                },
                xtype: 'button', scope: this
            }
        ],

        autoHeight: true,
        loadMask: true,
        paging: true,
        autosave: true,
        remoteSort: true,
        anchor: '97%'
    });

    Unisender.grid.Lists.superclass.constructor.call(this, config);
};
Ext.extend(Unisender.grid.Lists, MODx.grid.Grid, {
    search: function (tf, nv, ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    getMenu: function () {


        var m = [
            {
                text: _('unisender.listRefresh'),
                handler: this.updateList
            },
            '-',
            {
                text: _('unisender.listDelete'),
                handler: this.removeList
            }
        ];
        this.addContextMenuItem(m);
        return true;
    }, updateList: function (btn, e) {
        if (!this.updateListWindow) {
            this.updateListWindow = MODx.load({
                xtype: 'unisender-window-list-update',
                record: this.menu.record,
                listeners: {
                    'success': {fn: this.refresh, scope: this}
                }
            });
        } else {
            this.updateListWindow.setValues(this.menu.record);
        }
        this.updateListWindow.show(e.target);
    }, removeList: function () {
        MODx.msg.confirm({
            title: _('unisender.listDeleteRequest'),
            text: _('unisender.listDeleteRequestDesc'),
            url: this.config.url, params: {
                action: 'mgr/removeList', id: this.menu.record.id
            }, listeners: {
                'success': {fn: this.refresh, scope: this}
            }
        });
    },
    clearFilter: function () {
        var s = this.getStore();
        s.baseParams.query = '';
        Ext.getCmp('lists-search-filter').reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }

});
Ext.reg('unisender-grid-lists', Unisender.grid.Lists);


Unisender.window.UpdateList = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        title: _('unisender.listRefresh'), url: Unisender.config.connectorUrl, baseParams: {
            action: 'mgr/updateList'
        }, fields: [
            {
                xtype: 'hidden',
                name: 'id'
            },
            {
                xtype: 'hidden',
                name: 'unisender_id'
            },
            {
                xtype: 'textfield',
                name: 'name',
                fieldLabel: _('unisender.listTitle'),
                width: 300
            }
        ]
    });
    Unisender.window.UpdateList.superclass.constructor.call(this, config);
};
Ext.extend(Unisender.window.UpdateList, MODx.Window);
Ext.reg('unisender-window-list-update', Unisender.window.UpdateList);


Unisender.window.CreateList = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        title: _('unisender.listCreate'), url: Unisender.config.connectorUrl, baseParams: {
            action: 'mgr/createList'
        }, fields: [
            {
                xtype: 'hidden', name: 'id'
            },
            {
                xtype: 'textfield',
                name: 'name',
                fieldLabel: _('unisender.listTitle'),
                width: 300
            }
        ]
    });
    Unisender.window.CreateList.superclass.constructor.call(this, config);
};
Ext.extend(Unisender.window.CreateList, MODx.Window);
Ext.reg('unisender-window-list-create', Unisender.window.CreateList);


Unisender.window.Synchronize = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        title: _('unisender.listListSync'), url: Unisender.config.connectorUrl, baseParams: {
            action: 'mgr/synchronizeUnisender'
        }, fields: [
            {
                html: _('unisender.listListSyncDesc')
            },
            {
                xtype: 'hidden',
                name: 'name',
                fieldLabel: _('unisender.listTitle'),
                width: 300
            }
        ]
    });
    Unisender.window.Synchronize.superclass.constructor.call(this, config);
};
Ext.extend(Unisender.window.Synchronize, MODx.Window);
Ext.reg('unisender-window-list-sync', Unisender.window.Synchronize);


