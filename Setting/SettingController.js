Ext.define('PropertiesApp.view.setting.SettingController', {
	extend: 'PropertiesApp.view.base.Controller',
	alias: 'controller.setting',

    /**
     * This function creates field.
     * @param type
     * @returns {*}
     */
    createValueField: function (type) {
        return Ext.create ('Ext.form.field.'+type, {
            fieldLabel: 'Value',
            name: 'value',
            emptyText: 'Value...',
            reference: 'valueField'
        })
    },

    /**
     * This function choose xtype.
     * @param form
     */
    editValueField: function (form) {
        form = form.up('form');
        var value = form.lookupReference('valueField').getValue();
        form.remove(form.lookupReference('valueField'));

        form.lookupReference('edit_ser').setVisible(false);
        switch (form.lookupReference('typeField').getValue()) {
            case 'empty':
                this.addValueField(form,'Text',value);
                break;
            case 'text':
                this.addValueField(form,'Text',value);
                break;
            case 'serialised':
                this.addValueField(form,'TextArea',value);
                form.lookupReference('edit_ser').setVisible(true);
                break;
            case 'boolean':
                this.addValueField(form,'Checkbox',value);
                break;
        }
    },

    /**
     * This function adds value field on form.
     * @param form
     * @param type
     * @param value
     */
    addValueField: function (form, type, value) {
        if (type === 'Checkbox') {
            value = (value === 'true');
        } else {
            if (value === true || value === false) {
                value = '';
            }
        }
        form.add(this.createValueField(type));
        form.lookupReference('valueField').setValue(value);
    },

    /**
     * This function disable name and type on edit form.
     * @param form
     */
    afterFormRecordLoad: function(form) {
        if (!form.getRecord().phantom) {
            form.getForm().findField('name').disable();
            if (form.getForm().findField('type').getValue()!=='empty') {
                form.getForm().findField('type').disable();
            }
        }
    },

    /**
     * This function update archive.
     * @param view
     * @param rowIndex
     * @param colIndex
     * @param item
     * @param record
     */
    archiveItem: function(view,rowIndex, colIndex, item, record){
        Ext.Msg.confirm("Confirmation", "Are you sure you want to do that?", function(conf){
            if (conf === 'yes') {
                record.record.set('archive',!record.record.get('archive'));
                record.record.save();
            }
        });

    },

    /**
     * On select item in combobox listener.
     */
    onSelectGroup:function () {
        this.onGroupSearch(this.lookupReference('groupOfPrefixes').getValue());
    },

    /**
     * On special key listener.
     * @param field
     * @param key
     */
    onEnterSelectGroup: function (field, key) {
        if (key.getKey() == key.ENTER) {
            var value = this.lookupReference('groupOfPrefixes').getRawValue();
            if (value === ''){
                this.onClearFilter();
            }else{
                this.onGroupSearch(value);
            }
        }

    },

    /**
     * This function clear filter and fields.
     */
    onClearFilter: function () {
        this.getView().getStore('store').clearFilter();

        var field = [
            'groupOfPrefixes',
            'valueFilter',
            'nameFilter',
            'descriptionFilter',
            'typeFilter',
            'archiveFilter'
        ];

        for (var i = 0; i < field.length; i++) {
            this.lookupReference(field[i]).reset();
        }
    },

    /**
     * This function cuts prefix from name.
     * @param sender
     */
    onCutPrefix: function(sender) {
        var form   = sender.up('form'),
            prefix = form.lookupReference('nameField').getValue().split(/\.|\-|\_/),
            cut    = prefix[0] == prefix ? 'OTHER' : prefix[0];

        form.lookupReference('prefixField').setValue(cut);
    },

    /**
     * This function opens serialize editor form and get data from server.
     * @param form
     */
    onEditSerialize: function(form) {
        var valueField = form.up('form').lookupReference('valueField'),
            scope = this,
            value = valueField.getValue(),
            win = Ext.create({
                xtype: 'serializeeditor'
            });

        // ajax request to the server side to unserialize data
        Ext.Ajax.request({
            url:     'rest-api/setting/get-serializer?token=' + localStorage.getItem('api-token'),
            method:  'POST',
            scope: scope,
            params: {
                'value': value,
                'flag':  false
            },

            success: function (response) {
                //decoding json as a server response and getting string from current response
                var JSONstring = Ext.decode(response.responseText);

                if (scope.isValidResponse(value, JSONstring.response)) {
                    win.callerReference = valueField;
                    win.param = JSONstring.response;
                    win.type = value[0];

                    win.show();
                } else {
                    Ext.Msg.alert('Error', 'Invalid data.');
                }
            },
            failure: function (e) {
                Ext.Msg.alert('failure!', e.status);
            }
        });
    },

    /**
     * This function check serialize result.
     * @param value
     * @param response
     * @returns {boolean}
     */
    isValidResponse: function (value, response) {
        if (response === '[]') {
            return value === 'a:0:{}' || value === 'O:8:"stdClass":0:{}';
        } else {
            return response !== '[]';
        }
    },

    /**
     * Filter for whole fields.
     * @param field
     */
    onGridFilter: function (field) {
        var value 	 = field.getValue() === 'all' ? '' : field.getValue(),
            operator = field.getValue() !== false ? 'like' : '=';

        field.up('grid').getStore().filter({
            property: field.getName(),
            value:    value,
            operator: operator
        });
    },

    /**
     * Prefix filter.
     * @param value
     */
    onGroupSearch: function (value) {
        var store = this.getView().getStore('store');
        store.filter({
            property: 'prefix',
            value:    value,
            operator: '='
        });

    },

    /**
     * This function gets prefixes from server and sets data in local store.
     */
    onLoadPrefix: function () {
        var scope = this;
        Ext.Ajax.request({
            url:     'rest-api/setting/get-prefix?token=' + localStorage.getItem('api-token'),
            method:  'POST',
            scope: scope,

            success: function (response) {
                var groups = Ext.decode(response.responseText).response;
                var store = [];
                for (var i = 0; i < groups.length; i++) {
                    store.push(groups[i].prefix);
                }
                scope.lookupReference('groupOfPrefixes').setStore(store.sort());
            },
            failure: function (e) {
                Ext.Msg.alert('failure!', e.status);
            }
        });
    }

});