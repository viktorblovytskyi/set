Ext.define('PropertiesApp.model.Setting', {
	extend: 'PropertiesApp.model.Base',

    formName: 'PropertiesApp.view.setting.Form',

	fields: [

		{ name: 'id', type: 'int'},
		{ name: 'name', type: 'string' },
		{ name: 'value', type: 'string' },
		{ name: 'description', type: 'string' },
		{ name: 'type', type: 'string' },
		{ name: 'archive', type: 'boolean'},
        { name: 'prefix', type: 'string'}
	]
});
