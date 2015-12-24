Ext.define('PropertiesApp.view.setting.Setting', {
	extend: 'PropertiesApp.view.base.GridPanel',
	requires: [
		'PropertiesApp.view.setting.SettingModel',
		'PropertiesApp.view.setting.SettingController',
        'PropertiesApp.view.serializeeditor.SerializeEditor'
	],
	controller: 'setting',
	viewModel: {
		type: 'setting'
	},
	bind: {
		store: '{store}'
	},
    referenceHolder: true,
	title: 'Settings',

    viewConfig: {
        stripeRows: false,
        getRowClass: function(record) {
            return record.get('archive') === true ? 'archive-true' : 'archive-false';
        }
    },

    columns: [
		{ text: 'Name',         dataIndex: 'name',          flex: 1,    filter: 'string'},
		{ text: 'Value',        dataIndex: 'value',         flex: 1,    filter: 'string'},
		{ text: 'Description',  dataIndex: 'description',   flex: 1,    filter: 'string', sortable: false },
		{ text: 'Type',         dataIndex: 'type',          flex: 0.3,  filter: {
                type: 'list',
                options: ['empty', 'text', 'serialised', 'boolean']
            }
        },
		{ text: 'Archive', xtype: 'booleancolumn', dataIndex: 'archive', flex: 0.3, filter: 'boolean', trueText: 'Yes', falseText: 'No'},
		{
			xtype: 'baseactioncolumn',
			items: [
				{
					icon: 'app/resources/edit.png',
					tooltip: 'Edit',
					handler: 'showEditForm',
                    isDisabled: function(view, rowIndex, colIndex, item, record) {
                        return record.get('archive');
                    }
				},{
                    getClass: function(view, meta, record) {
                        /**
                         * This function changes icon and tooltip.
                         */
                        if (record.get('archive') !== true) {
                            return 'icon-archive';
                        } else {
                            return 'icon-restore';
                        }
                    },
                    handler: 'archiveItem'
				}
			],
			flex: 0.3,
            sortable: false
		}
	],

	dockedItems: [
		{
			xtype: 'pagingtoolbar',
			bind: {
				store: '{store}'
			},
			dock: 'bottom',
			displayInfo: true
		},
		{
			xtype: 'toolbar',
			dock: 'top',
            layout: {
                type:  'hbox',
                align: 'bottom'
            },
			items: [
                {
                    xtype: 		   'combo',
                    labelAlign:    'top',
                    fieldLabel:    'Groups',
                    emptyText:	   'Select group...',
                    text:    	   'Add new setting',
                    typeAhead:      true,
                    autoSelect:     false,
                    minChars:       1,
                    reference: 	   'groupOfPrefixes',
                    listeners: {
                        beforerender: 'onLoadPrefix',
                        select:     'onSelectGroup',
                        specialkey: 'onEnterSelectGroup'
                    }
                },{
                    xtype:          'textfield',
                    labelAlign:     'top',
                    fieldLabel: 	'Name',
                    name:           'name',
                    emptyText:      'Input name...',
                    reference:      'nameFilter',
                    flex:           1,
                    listeners: {
                        change: 	'onGridFilter'
                    }
                },
                {
                    xtype:          'textfield',
                    labelAlign:     'top',
                    fieldLabel: 	'Value',
                    name:           'value',
                    emptyText:      'Input value...',
                    reference:      'valueFilter',
                    flex:           1,
                    listeners: {
                        change: 	'onGridFilter'
                    }
                },
                {
                    xtype:          'textfield',
                    labelAlign:     'top',
                    fieldLabel: 	'Description',
                    name:           'description',
                    emptyText:      'Input description...',
                    reference:      'descriptionFilter',
                    flex:           1,
                    listeners: {
                        change: 	'onGridFilter'
                    }
                },
                {
                    xtype: 		   'combobox',
                    labelAlign:    'top',
                    fieldLabel:    'Type',
                    name:          'type',
                    value: 		   'all',
                    editable:      false,
                    store: 		   ['all', 'text', 'boolean', 'serialised', 'empty'],
                    reference:     'typeFilter',
                    flex: 		   1,
                    listeners: {
                        change:     'onGridFilter'
                    }
                },
                {
                    xtype: 		   'combobox',
                    labelAlign:    'top',
                    fieldLabel:    'Archive',
                    name:          'archive',
                    value: 		   'all',
                    editable:      false,
                    store: 		   ['all', true, false],
                    reference:      'archiveFilter',
                    flex: 		   1,
                    listeners: {
                        change: 	'onGridFilter'
                    }
                },{
					xtype:          'container',
					flex: 1
				},{
                    xtype:          'button',
                    text:           'Clear',
                    listeners: {
                        click:      'onClearFilter'
                    }
                },{
					text:           'Add new setting',
                    handler:        'showAddForm'
				}
			]
		}
	]

});
