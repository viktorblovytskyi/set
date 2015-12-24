/**
 * Created by viktorb on 8/21/15.
 */
Ext.define('PropertiesApp.model.SerializedDataType', {
    extend: 'PropertiesApp.model.Base',

    fields: [
        { name: 'key',   type: 'string' },
        { name: 'value', type: 'string' }
    ]
});