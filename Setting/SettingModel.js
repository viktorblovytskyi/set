Ext.define('PropertiesApp.view.setting.SettingModel', {
    extend: 'Ext.app.ViewModel',

    alias: 'viewmodel.setting',

    data: {
        store: Ext.create('PropertiesApp.store.Setting')
    }
});