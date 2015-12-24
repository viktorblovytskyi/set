/**
 * Created by viktorb on 8/21/15.
 */
Ext.define('PropertiesApp.store.SerializedDataType', {
    extend: 'Ext.data.Store',

    fields: ['key', 'value'],

    data: [
        { key: 'a', value: 'array'   },
        { key: 'o', value: 'object'  },
        { key: 'i', value: 'integer' },
        { key: 'd', value: 'double'  },
        { key: 's', value: 'string'  },
        { key: 'b', value: 'boolean' },
        { key: 'n', value: 'null'    }
    ]
});