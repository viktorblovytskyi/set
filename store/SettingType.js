/**
 * Created by viktorb on 7/20/15.
 */
Ext.define('PropertiesApp.store.SettingType',{
    extend: 'Ext.data.Store',
    fields: ['type'],
    data: [
        {type: 'empty'},
        {type: 'text'},
        {type: 'serialised'},
        {type: 'boolean'}
    ]
});