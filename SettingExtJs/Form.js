Ext.define('PropertiesApp.view.setting.Form',{
    extend: 'PropertiesApp.view.base.Form',

    title: 'setting',
    controller: 'setting',
    referenceHolder: true,
    items: [
        {
            fieldLabel:         'Name',
            name:               'name',
            allowBlank:         false,
            emptyText:          'Name...',
            reference:          'nameField',
            listeners: {
                change: 'onCutPrefix'
            }

        },{
            xtype:              'combobox',
            store:              Ext.create('PropertiesApp.store.SettingType'),
            queryMode:          'local',
            displayField:       'type',
            valueField:         'type',
            fieldLabel:         'Setting Type',
            allowBlank:         false,
            forceSelection:     true,
            name:               'type',
            reference:          'typeField',
            editable:           false,
            emptyText:          'Type...',
            listeners: {
                change:         'editValueField'
            }
        },{
            xtype:              'textarea',
            fieldLabel:         'Description',
            emptyText:          'Description...',
            name:               'description'
        },{
            fieldLabel:         'Value',
            name:               'value',
            emptyText:          'Value...',
            reference:          'valueField'
        },{
            xtype:              'textfield',
            name:               'prefix',
            fieldLabel:         'Hidden',
            hidden:             true,
            reference:          'prefixField'
        },{
            xtype:              'container',
            layout: {
                align:          'right',
                type:           'vbox',
                pack:           'start'
            },
            items:[
                {
                    xtype:      'button',
                    text:       'Edit serialize',
                    icon:       'app/resources/edit.png',
                    tooltip:    'Edit serialize.',
                    reference:  'edit_ser',
                    hidden:     true,
                    listeners:{
                        click:  'onEditSerialize'
                    }
                }
            ]
        }
    ]


});