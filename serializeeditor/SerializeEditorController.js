Ext.define('PropertiesApp.view.serializeeditor.SerializeEditorController', {

    extend: 'PropertiesApp.view.base.Controller',

    alias: 'controller.serialize',

    /**
     * This function checks type and return message.
     * @param obj - Object
     * @returns {*|{result, msg}}
     */
    nodeTypeValidator: function (obj) {
        var type  = obj.type,
            value = obj.value,
            re = '';

        switch (type) {
            case 'a':
            case 'o':
                return this.isValidValue('Current type shouldn\'t contains any value.<br />Value will be erased.', value);
                break;
            case 'i':
                re = /^[0-9]*$/;
                return this.createMessage(re.test(value), 'Value should contains only <b>integer</b>.');
                break;
            case 'd':
                re = /^[0-9]*\.?[0-9]*$/;
                return this.createMessage(re.test(value), 'Value should contains only <b>double</b>.');
                break;
            case 'b':
            case 's':
                return this.createMessage(true,'');
                break;
            case 'n':
                return this.createMessage(false, 'Null shouldn\'t contains any value.');
                break;
            default:
                return this.createMessage(false, 'Unexpected error.');
                break;
        }
    },

    /**
     * This function checks value for array and object.
     * @param msg - String
     * @param value - String
     * @returns {*|{result, msg}|{result: Boolean, msg: String}}
     */
    isValidValue: function(msg, value){
        return value === undefined || value === ''  ? this.createMessage(true, '') : this.createMessage(false, msg);
    },

    /**
     * This function creates result message.
     * @param result - Boolean
     * @param msg - String
     * @returns {{result: Boolean, msg: String}}
     */
    createMessage: function(result, msg){
        return {
            'result': result,
            'msg': msg
        }
    },

    /**
     * On node edit listener.
     * @param editor
     * @param context
     */
    onNodeEdit: function(editor, context) {
        var rec   = context.record,
            value = rec.data.text,
            obj   = rec.data,
            isValid = this.nodeTypeValidator(obj);

        if (isValid.result) {
            obj['key'] = value;
        } else {
            Ext.Msg.alert('Caution', isValid.msg);
        }
    },

    /**
     * This function checks type.
     * @param root - Object
     * @param value - Mixed
     * @returns {*}
     */
    isObject: function (root, value) {
        if (root.data.type === 'o') {
            return [
                {
                    'key':'',
                    'type':'o',
                    'value':value
                }
            ]
        }

        return value;
    },

    /**
     * This function cuts result if object type is 'o'.
     * @param root - Object
     * @param value - Mixed
     * @returns {*}
     */
    serializedObjectFormat: function (root, value) {
        if (root.data.type === 'o') {
            return value.slice(value.indexOf('O'), -1);
        }

        return value;
    },

    /**
     * On save listener.
     * This function sends ajax request to server.
     */
    onSave: function() {
        var panel      = this.getView().down('treepanel'),
            view       = this.getView(),
            root       = panel.getRootNode(),
            serialised = root.serialize(),
            scope      = this;

        var value = this.getDataFromTree(serialised, []);
        value = this.isObject(root, value);

        value = Ext.encode(value).replace(/\[]/g, '');

        Ext.Ajax.request({
            url:     'rest-api/setting/get-serializer?token=' + localStorage.getItem('api-token'),
            method:  'POST',
            scope:   scope,
            params: {
                'value': value,
                'flag':  true
            },

            success: function (response) {
                var result  = Ext.decode(response.responseText).response;
                // setting data to textareafield on caller form
                panel.param = result;
                view.callerReference.setValue(scope.serializedObjectFormat(root, result));
            },
            failure: function (e) {
                Ext.Msg.alert('failure!', e.status);
            }
        });

        this.closeView();
    },

    /**
     * This function gets data from tree.
     * @param obj
     * @param array
     * @returns {*}
     */
    getDataFromTree: function (obj, array) {
        if (obj.hasOwnProperty('children')) {
            for (var i = 0; i < obj.children.length; i++) {
                array[i] =
                {
                    "key":   obj.children[i].key,
                    "type":  obj.children[i].type,
                    "value": this.getDataFromTree(obj.children[i], [])
                };
            }
        } else {
            array = obj.value;
        }

        return array;
    },

    /**
     * This function deletes node from tree.
     */
    onDeleteNode: function() {
        var selectedNode = this.getViewModel().get('selectedNode');

        if (selectedNode === null) {
            Ext.Msg.alert("Delete Node", "Error. No element chosen.");
        } else if (selectedNode.id != 'root') {
            Ext.Msg.confirm("Delete Node", "Delete " + selectedNode.get('text'),

                function(b) {
                    if (b == 'yes') {
                        selectedNode.erase();
                    }
                });
        }
    },

    /**
     * This function checks type.
     * @param node
     * @returns {boolean}
     */
    checkTypes: function (node) {
        return node !== 'a' && node !== 'o';
    },

    /**
     * This function returns array.
     * @param leaf - boolean
     * @param text - string
     * @param type - string
     * @param value - string
     * @param key - string
     * @returns {{leaf: *, text: *, type: *, value: *, key: *, icon: string, expanded: boolean}}
     */
    createNode: function(leaf,text,type,value,key){
        return {
            leaf:     leaf,
            text:     text,
            type:     type,
            value:    value,
            key:      key,
            icon:    'app/resources/key.png',
            expanded: true
        }
    },

    /**
     * This function adds node in tree.
     */
    onAddNode: function() {
        var selectedNode = this.getViewModel().get('selectedNode');

        if (selectedNode === null) {
            Ext.Msg.alert("Add Node", "Error. No element chosen.");
        } else if (this.checkTypes(selectedNode.data.type)) {
            Ext.Msg.alert('Error', 'Cannot add item to item with scalar type!');
        } else {
            var n = selectedNode.appendChild(this.createNode(true, 'Set new key...', 's', '','Set new key...'));
            n.expand();
            // Set focus on new node.
            this.getViewModel().set('selectedNode', n);
        }
    },

    /**
     * This function gets data from form.
     */
    onGetData: function () {
        var obj  = Ext.decode(this.getView().param),
            root = this.lookupReference('refTreePanel').getRootNode();
        root.data.icon = 'app/resources/root.png';

        if (this.getView().type == 'O') {
            root.data.text  = 'stdClass';
            root.data.type  = 'o';
            root.data.key   = '';
        } else {
            root.data.text  = 'Array';
            root.data.type  = 'a';
        }

        root.expand();
        this.buildNode(obj, root);
    },

    /**
     * This function builds tree.
     * @param obj
     * @param root
     */
    buildNode: function (obj, root) {
        var childNode, value, key;

        for (var i = 0; i < obj.length; i++) {
            value = obj[i].type === 'a' ||  obj[i].type === 'o' ? '' : obj[i].value;
            key = obj[i].type === 'o' ? 'stdClass' : obj[i].key;
            childNode = root.appendChild(this.createNode(false, key, obj[i].type, value, key));

            if (obj[i].type === 'a' || obj[i].type === 'o') {
                this.buildNode(obj[i].value[0], childNode);
            }
        }
    }
});