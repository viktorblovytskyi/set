Ext.define('PropertiesApp.view.serializeeditor.SerializeEditor',{

    extend: 'Ext.window.Window',

    xtype:  'serializeeditor',

    requires:[
        'Ext.form.Panel',
        'PropertiesApp.view.serializeeditor.SerializeEditorController'
    ],

    controller:      'serialize',

    width:           600,
    height:          500,
    title:           'Serializer',
    layout:          'fit',
    referenceHolder: true,
    tools: [
        {
            type:        'help',
            tooltip:     '<b>Types:    </b><br />' +
                         'a - array<br />' +
                         'o - object<br />' +
                         'i - integer<br />' +
                         'd - double<br />' +
                         's - string<br />' +
                         'b - boolean<br />' +
                         'n - null<br />'
        }
    ],
    items: [
        {
            xtype:     'treepanel',
            reference: 'refTreePanel',
            columns: [
                {
                    xtype:     'treecolumn',
                    dataIndex: 'text',
                    text:      'Keys',
                    flex:       1,
                    rootVisible: true,
                    editor:
                    {
                        xtype:              'textfield',
                        allowBlank:          false,
                        allowOnlyWhitespace: false,
                        maxLength:           100
                    }
                },
                {
                    dataIndex:  'type',
                    text:       'Value Type',
                    flex:        0.5,
                    rootVisible: true,
                    editor:
                    {
                        xtype:              'combobox',
                        allowBlank:          false,
                        editable:            false,
                        allowOnlyWhitespace: false,
                        store: 		         Ext.create('PropertiesApp.store.SerializedDataType'),
                        valueField:          'key',
                        displayField:        'value',
                        maxLength:           100
                    }
                },
                {
                    dataIndex:  'value',
                    text:       'Value',
                    flex:        0.5,
                    rootVisible: true,
                    editor:
                    {
                        xtype:              'textfield',
                        allowBlank:          true,
                        maxLength:           100
                    }
                }
            ],
            plugins: [
                {
                    ptype:       'cellediting',
                    clicksToEdit: 2
                }
            ],
            listeners: {
                'edit': 'onNodeEdit'
            },
            bind: {
                selection: '{selectedNode}'
            },

            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock:  'bottom',
                    layout:
                    {
                        type: 'hbox',
                        pack: 'center'
                    },
                    defaults:
                    {
                        flex: 1
                    },
                    items: [
                        {
                            text:    'Add',
                            handler: 'onAddNode',
                            bind: {
                                disabled: '{!selectedNode}'
                            }
                        },
                        {
                            text:    'Delete',
                            handler: 'onDeleteNode',
                            bind: {
                                disabled: '{!selectedNode}'
                            }
                        },
                        {
                            text:     'Commit',
                            handler:  'onSave'
                        }
                    ]
                }
            ]
        }
    ],

    listeners: {
        beforerender:   'onGetData'
    }
});