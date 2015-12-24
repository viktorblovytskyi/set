Ext.define('PropertiesApp.store.Setting', {
	extend: 'PropertiesApp.store.Base',
	model: 'PropertiesApp.model.Setting',
	sorters: [
		{
			property: 'archive',
			direction: 'ASC'
		},
        {
            property: 'prefix',
            direction: 'ASC'
        }
    ]

});