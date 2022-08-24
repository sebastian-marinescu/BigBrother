BigBrother.panel.AuthorizePanel = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'bigbrother-panel-authorize'
        ,cls: 'container'
        ,unstyled: true
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('bigbrother.main_title')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            layout: 'form'
            ,autoHeight: true
            ,defaults: { border: false }
            ,items:[{
                html: _('bigbrother.main_description'),
                cls: 'main-wrapper bigbrother-panel-intro'
            }]
        }, {
            layout: 'form'
            ,cls: 'main-wrapper'
            ,autoHeight: true
            ,defaults: { border: false }
            ,id: 'bigbrother-loader'
            ,items:[{
                layout: 'column',
                items: [{
                    html: '<div class="bigbrother-spinner"></div>',
                    width: 30,
                },{
                    html: '<h2 style="margin: 0 0.25em; line-height: 37px;">' + _('bigbrother.loading') + '</h2>',
                }]
            }]
        }, {
            xtype: 'panel',
            cls: 'main-wrapper form-with-labels bigbrother-panel',
            id: 'bigbrother-panel-initial-authorization',
            hidden: true,
            labelAlign: 'top',
            items: [{
                html: '<h2>'+_('bigbrother.initial_authorization')+'</h2>' + _('bigbrother.initial_authorization_description'),
                xtype: 'modx-panel'
            },{
                xtype: 'modx-panel',
                layout: 'form',
                columnWidth: 1,
                items: [{
                    xtype: 'button',
                    text: '<img src="' + BigBrother.config.assetsUrl + 'images/signin-google.png" alt="' + _('bigbrother.sign_in_with_google') + '" width="191px" height="46px">',
                    name: 'authorize',
                    id: 'bigbrother-authorize-login-btn',
                    anchor: '100%',
                    cls: 'bigbrother-signin-button',
                    handler: function () {
                        window.location.href = BigBrother.config.authorizeUrl;
                    }
                },{
                    html: '<p class="bigbrother-desc">' + _('bigbrother.authorization_step1desc') + '</p>',
                }]
            }]
        },{
            xtype: 'panel'
            ,cls: 'main-wrapper form-with-labels bigbrother-panel'
            ,id: 'bigbrother-panel-authorized'
            ,hidden: true
            ,items: [{
                html: '<h2>'+_('bigbrother.authorized')+'</h2>' + '<p class="bigbrother-panel-intro">' + _('bigbrother.authorized_desc') + '</p>',
                xtype: 'modx-panel'
            },{
                xtype: 'button',
                text: _('bigbrother.revoke_authorization'),
                cls: 'bigbrother-revoke-button',
                handler: this.revokeAuthorization,
                scope: this
            }]
        },{
            xtype: 'panel'
            ,cls: 'main-wrapper form-with-labels bigbrother-panel'
            ,id: 'bigbrother-panel-accounts'
            ,hidden: true
            ,items: [{
                html: '<h2>'+_('bigbrother.property')+'</h2>' + '<p class="bigbrother-panel-intro">' + _('bigbrother.property_desc') + '</p>',
                xtype: 'modx-panel'
            }, {
                html: '',
                hidden: true,
                id: 'bigbrother-current-property'
            }, {
                xtype: 'button',
                text: _('bigbrother.save_property'),
                id: 'bigbrother-save-property',
                hidden: true,
                cls: 'primary-button',
                handler: this.saveProperty,
                scope: this
            }, {
                xtype: 'panel',
                layout: 'column',
                cls: 'bigbrother-columns',
                items: [{
                    columnWidth: 0.4,
                    items: [{
                        xtype: 'dataview',
                        id: 'bigbrother-list-accounts',
                        store: new Ext.data.ArrayStore({
                            autoDestroy: true,
                            storeId: 'bigbrother-accounts',
                            idIndex: 0,
                            fields: ['account', 'displayName', 'total_properties', 'properties'],
                        }),
                        tpl: new Ext.XTemplate(
                            '<div class="bigbrother-list-container">' +
                            '   <ul class="bigbrother-list">' +
                            '       <tpl for=".">' +
                            '           <li class="bigbrother-list-item">' +
                            '               <p>{displayName:htmlEncode}</p>' +
                            '               <span class="bigbrother-list-count">{total_properties}</span>' +
                            '           </li>' +
                            '       </tpl>' +
                            '   </ul>' +
                            '</div>'
                        ),
                        autoHeight: true,
                        singleSelect: true,
                        overClass: 'x-view-over',
                        itemSelector: 'li.bigbrother-list-item',
                        emptyText: '<p class="bigbrother-no-accounts">' + _('bigbrother.missing_web_properties') + '</p>',
                        listeners: {
                            click: {fn: function(view) {
                                var selected = view.getSelectedRecords()[0];
                                if (selected) {
                                    this.setProperties(selected.data.properties);
                                }
                            }, scope: this}
                        }
                    }]
                },{
                    columnWidth: 0.6,
                    items: [{
                        xtype: 'dataview',
                        id: 'bigbrother-list-properties',
                        hidden: true,
                        store: new Ext.data.ArrayStore({
                            autoDestroy: true,
                            storeId: 'bigbrother-properties',
                            idIndex: 0,
                            fields: ['propertyId', 'displayName'],
                        }),
                        tpl: new Ext.XTemplate(
                            '<div class="bigbrother-list-container">' +
                            '   <ul class="bigbrother-list">' +
                            '       <tpl for=".">' +
                            '           <li class="bigbrother-list-item">' +
                            '               <p>{displayName:htmlEncode}</p>' +
                            '               <span class="bigbrother-list-id">{propertyId:htmlEncode}</span>' +
                            '           </li>' +
                            '       </tpl>' +
                            '   </ul>' +
                            '</div>'
                        ),
                        autoHeight: true,
                        singleSelect: true,
                        overClass: 'x-view-over',
                        itemSelector: 'li.bigbrother-list-item',
                        emptyText: '<p class="bigbrother-no-properties">' + _('bigbrother.missing_ga4_web_properties') + '</p>',
                        listeners: {
                            click: {fn: function(view) {
                                var selected = view.getSelectedRecords()[0];
                                if (selected) {
                                    this.selectProperty(selected);
                                }
                            }, scope: this}
                        }
                    }]
                },]
            }]
        }, BigBrother.attribution()],
        listeners: {
            afterrender: {
                fn: this.onAfterRender,
                scope: this,
            },
        }
    });
    BigBrother.panel.AuthorizePanel.superclass.constructor.call(this,config);
};
Ext.extend(BigBrother.panel.AuthorizePanel, MODx.Panel,{
    onAfterRender: function () {
        this._loaderPanel = this.getComponent('bigbrother-loader');
        this._initialAuthPanel = this.getComponent('bigbrother-panel-initial-authorization');
        this._authorizedPanel = this.getComponent('bigbrother-panel-authorized');
        this._accountsPanel = this.getComponent('bigbrother-panel-accounts');
        this._currentPropertyPanel = Ext.getCmp('bigbrother-current-property');

        this.getState();
    },

    _loaderPanel: null,
    _initialAuthPanel: null,
    _authorizedPanel: null,
    _accountsPanel: null,
    _currentPropertyPanel: null,

    getState() {
        this._loaderPanel.show();
        this._initialAuthPanel.hide();
        this._authorizedPanel.hide();
        this._accountsPanel.hide();

        MODx.Ajax.request({
            url: BigBrother.config.connectorUrl,
            params: {
                action: 'mgr/authorize/get_state'
            },
            method: 'POST',
            scope: this,
            listeners: {
                success: {
                    fn: function (result) {
                        this.handleState(result.object);
                    },
                    scope: this
                },
                failure: {
                    fn: function (result) {
                        Ext.MessageBox.alert(_('error'), result.responseText);
                    },
                    scope: this
                }
            }
        });
    },

    handleState: function(state) {
        this._loaderPanel.hide();

        if (!state.has_refresh_token || !state.has_access_token) {
            this._initialAuthPanel.show();
            return;
        }

        this._authorizedPanel.show();

        if (state.property) {
            this._currentPropertyPanel.update(_('bigbrother.current_property', state.property)); //@todo potential for xss injection from name
            this._currentPropertyPanel.show();
        }
        else {
            this._currentPropertyPanel.hide();
        }

        this._accountsPanel.show();

        this.setAccounts(state.accounts);
    },

    verifyCode: function() {
        var code = Ext.getCmp('bigbrother-authorization-code').getValue();
        if (!code || code.length < 1) {
            MODx.msg.alert(_('error'), _('bigbrother.error.enter_auth_code'));
            return;
        }
        MODx.Ajax.request({
            url : BigBrother.config.connectorUrl,
            params : {
                action : 'mgr/authorize/verify_code',
                code : code
            },
            method: 'POST',
            scope: this,
            listeners: {
                success: {
                    fn: function (result) {
                        MODx.msg.status({
                            title: _('success'),
                            message: result.message,
                            delay: 5
                        });
                        this.getState();
                        if (window.authorizeWindow) {
                            window.authorizeWindow.close();
                        }
                    },
                    scope: this
                },
                failure: {
                    fn: function (result) {
                        Ext.MessageBox.alert(_('error'), result.message);
                        this.getState();
                    },
                    scope: this
                }
            }
        });
    },

    revokeAuthorization: function() {
        MODx.msg.confirm({
            title: _('bigbrother.revoke_authorization.confirm'),
            text: _('bigbrother.revoke_authorization.confirm_text'),
            url : BigBrother.config.connectorUrl,
            params: {
                action : 'mgr/authorize/revoke'
            },
            listeners: {
                success: {
                    fn: function (result) {
                        this.getState();
                        MODx.msg.status({
                            title: _('success'),
                            message: result.message,
                            delay: 5
                        });
                    },
                    scope: this
                },
                failure: {
                    fn: function (result) {
                        Ext.MessageBox.alert(_('error'), result.message);
                        this.getState();
                    },
                    scope: this
                }
            }
        });
    },

    setAccounts: function(accounts) {
        var accountView = Ext.getCmp('bigbrother-list-accounts'),
            cleanData = [];

        accounts.forEach(function(row) {
            cleanData.push([row.account, row.displayName, row.total_properties, row.properties]);
        });
        accountView.store.loadData(cleanData);

        // Hide the properties view and select button until an account is selected
        Ext.getCmp('bigbrother-list-properties').hide();
        Ext.getCmp('bigbrother-save-property').hide();
    },

    setProperties: function(properties) {
        var propertyView = Ext.getCmp('bigbrother-list-properties'),
            cleanData = [];
        properties.forEach(function(row) {
            cleanData.push([row.propertyId, row.displayName]);
        });
        propertyView.store.loadData(cleanData);
        propertyView.show();
    },

    selectProperty: function(record) {
        this._selectedProperty = record;
        Ext.getCmp('bigbrother-save-property').show();
    },

    saveProperty: function() {
        if (!this._selectedProperty) {
            MODx.msg.alert(_('error'), _('bigbrother.error.select_a_property'));
            return;
        }
        MODx.Ajax.request({
            url: BigBrother.config.connectorUrl,
            params: {
                action: 'mgr/authorize/set_property',
                property: this._selectedProperty.data.propertyId,
            },
            method: 'POST',
            scope: this,
            listeners: {
                success: {
                    fn: function (result) {
                        MODx.msg.status({
                            title: _('success'),
                            message: result.message,
                            delay: 5
                        });
                        this.getState();
                    },
                    scope: this
                },
                failure: {
                    fn: function (result) {
                        Ext.MessageBox.alert(_('error'), result.message);
                        this.getState();
                    },
                    scope: this
                }
            }
        });
    },
});
Ext.reg('bigbrother-panel-authorize', BigBrother.panel.AuthorizePanel);
