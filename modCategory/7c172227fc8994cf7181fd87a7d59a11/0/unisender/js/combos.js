/**
 * Lists combobox
 */
Unisender.combo.ULists = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        name: 'list_id',
		hiddenName: 'list_id',
		displayField: 'name',
		valueField: 'unisender_id',
		fields: ['unisender_id','name'],
		forceSelection: true,
		typeAhead: true,
		editable: false,
		allowBlank: true,
		autocomplete: true,
		url: Unisender.config.connector_url,
		baseParams: {
        action: 'mgr/getUnisenderLists',
			combo: true
        }
    });
	
    Unisender.combo.ULists.superclass.constructor.call(this, config);
};

Ext.extend( Unisender.combo.ULists, MODx.combo.ComboBox);
Ext.reg('unisender-combo-ulists',  Unisender.combo.ULists);