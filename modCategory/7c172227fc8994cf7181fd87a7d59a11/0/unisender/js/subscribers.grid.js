Unisender.grid.Subscribers = function(config) {
    config = config || {};
	this.sm = new Ext.grid.CheckboxSelectionModel();
    this.wnd_params=new Object();

    Ext.applyIf(config, {
		id: 'unisender-grid-subscribers',
		url: Unisender.config.connector_url,
		baseParams: { action: 'mgr/getSubscribersList' },
		fields: ['id','username','fullname','email','phone','subscribe'],
	    sm: this.sm,
        columns: [
			this.sm,
            {header: 'Id', width: 10, sortable: true, dataIndex: 'id'},
			{header: _('unisender.username'), width: 20, sortable: true, dataIndex: 'username'},
			{header: _('unisender.fullname'), width: 20, sortable: true, dataIndex: 'fullname'},
			{header: _('unisender.email'), width: 20, sortable: true, dataIndex: 'email'},
			{header: _('unisender.phone'), width: 30, sortable: true, dataIndex: 'phone'},
			{header: _('unisender.subscribe'), width: 150, sortable: true, dataIndex: 'subscribe'},
        ]
	,tbar: [{
           
            text: _('unisender.subscribeSelected')
            ,handler: this.subscribeSelectedUsers 
           ,xtype: 'button'
            ,scope: this
					         
						},'-',{
		
            xtype: 'textfield'
            ,id: 'unisender-search-filter'
            ,emptyText: _('unisender.find') + "..."
            ,listeners: {
                'change': {fn:this.search,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this);
                            this.blur();
                            return true;
                        }
                        ,scope: cmp
                    });
                },scope:this}
            }
        }],

        autoHeight: true,
        loadMask: true,
		paging: true,
		autosave: true,
		remoteSort: true,
		anchor: '97%'
    });
	
    Unisender.grid.Subscribers.superclass.constructor.call(this, config);
};
Ext.extend(Unisender.grid.Subscribers, MODx.grid.Grid, {
    search: function(tf, nv, ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
		getMenu: function() {
			  var r = this.getSelectionModel().getSelected();
        var p = r.data.cls;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
			 m.push({
                text: _('unisender.changeSelected')
                ,handler: this.subscribeSelectedUsers
                ,scope: this
            });
			
		}
        else m = [{
            text: _('unisender.changeSubscription')
            ,handler: this.subscribeUser
      	 },'-',{
            text: _('unisender.edit')
            ,handler: this.viewProfile
        }];
        this.addContextMenuItem(m);
        return true;
    },	
	
	  subscribeSelectedUsers: function(btn,e) {
		 
		var cs = this.getSelectedAsList();
        if (cs === false) return false;
		
		
		if(this.menu.record)
			this.wnd_params.id=this.menu.record.id;
		else this.wnd_params.id='';
		this.wnd_params.users=cs;
		
        if (!this.subscribeUserWindow) {
            this.subscribeUserWindow = MODx.load({
                xtype: 'unisender-window-subscribe'
                ,record: this.wnd_params
		        ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.subscribeUserWindow.setValues(this.wnd_params);
        }
        this.subscribeUserWindow.show(e.target);
    },
	
	 unsubscribeSelectedUsers: function(btn,e) {
		  
			
		if(this.menu.record)
			this.wnd_params.id=this.menu.record.id;
		else this.wnd_params.id='';
		this.wnd_params.users=cs;
		
        if (!this.unsubscribeUserWindow) {
            this.unsubscribeUserWindow = MODx.load({
                xtype: 'unisender-window-unsubscribe'
               ,record: this.wnd_params
		        ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.unsubscribeUserWindow.setValues(this.wnd_params);
        }
        this.unsubscribeUserWindow.show(e.target);
    },
	
	
	viewProfile: function(btn, e) {
        if (!this.menu.record || !this.menu.record.id) return false;
        location.href = '?a=security/user/update&id=' + this.menu.record.id;
    }
	,subscribeUser: function(btn,e) {
		this.menu.record.users='';
        if (!this.subscribeUserWindow) {
            this.subscribeUserWindow = MODx.load({
                xtype: 'unisender-window-subscribe'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.subscribeUserWindow.setValues(this.menu.record);
        }
        this.subscribeUserWindow.show(e.target);
    }
	
	  ,unsubscribeUser: function(btn,e) {
	  this.menu.record.users='';
        if (!this.unsubscribeUserWindow) {
            this.unsubscribeUserWindow = MODx.load({
                xtype: 'unisender-window-unsubscribe'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.unsubscribeUserWindow.setValues(this.menu.record);
        }
        this.unsubscribeUserWindow.show(e.target);
    },
	
	clearFilter: function() {
        var s = this.getStore();
        s.baseParams.query = '';
        Ext.getCmp('subscribers-search-filter').reset();
    	this.getBottomToolbar().changePage(1);
        this.refresh();
	}
	
});
Ext.reg('unisender-grid-subscribers', Unisender.grid.Subscribers);



Unisender.window.Subscribe = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('unisender.subscribeTitle')
        ,url: Unisender.config.connectorUrl
        ,baseParams: {
            action: 'mgr/subscribeUser'
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'users'
		},{
            xtype: 'hidden'
            ,name: 'id'
		},{
			html: _('unisender.subscribeDesc')
			},{
	
			xtype: 'unisender-combo-ulists',
                name: 'list_id',
				id: 'unisender-list-id-1',
				emptyText: "- " + _('unisender.subscribeChooseSubs') + " ",
				width: 300 
				,fieldLabel: _('unisender.subscribeSubTitle')
		},{
         xtype: 'checkbox',
             name: 'subscribe_email'	
			 ,fieldLabel: _('unisender.subscribeSubTo'),
            boxLabel : "Email"
	
         
		},{
         xtype: 'checkbox',
             name: 'subscribe_sms'	
			 ,fieldLabel: ""
             ,boxLabel : "SMS"
	
         }]
    });
    Unisender.window.Subscribe.superclass.constructor.call(this,config);
};
Ext.extend(Unisender.window.Subscribe,MODx.Window);
Ext.reg('unisender-window-subscribe',Unisender.window.Subscribe);

