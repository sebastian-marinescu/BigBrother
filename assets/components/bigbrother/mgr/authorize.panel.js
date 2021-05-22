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
                xtype: 'panel',
                layout: 'column',
                cls: 'bigbrother-columns',
                items: [{
                    html: '<div class="bigbrother-step"><span>1</span></div>',
                    width: 50,
                },{
                    layout: 'form',
                    columnWidth: 1,
                    items: [{
                        xtype: 'button',
                        text: '<img src="' + BigBrother.config.assetsUrl + 'images/signin-google.png" alt="Signin with Google" width="191px" height="46px">',
                        name: 'authorize',
                        id: 'bigbrother-authorize-login-btn',
                        anchor: '100%',
                        cls: 'bigbrother-signin-button',
                        handler: function () {
                            window.authorizeWindow = window.open(BigBrother.config.authorizeUrl, 'bigbrother_authorize', 'height=500,width=450');
                        }
                    },{
                        html: '<p class="bigbrother-desc">' + _('bigbrother.authorization_step1desc') + '</p>',
                    }]
                }]
            },{
                xtype: 'panel',
                layout: 'column',
                cls: 'bigbrother-columns',
                items: [{
                    html: '<div class="bigbrother-step"><span>2</span></div>',
                    width: 50,
                },{
                    layout: 'form',
                    labelAlign: 'top',
                    columnWidth: 1,
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: _('bigbrother.code'),
                        labelStyle: 'padding-top: 0;',
                        name: 'code',
                        id: 'bigbrother-authorization-code',
                        anchor: '50%'
                    }]
                }]
            },{
                xtype: 'panel',
                layout: 'column',
                cls: 'bigbrother-columns',
                items: [{
                    html: '<div class="bigbrother-step"><span>3</span></div>',
                    width: 50,
                },{
                    layout: 'form',
                    columnWidth: 1,
                    items: [{
                        xtype: 'button',
                        text: _('bigbrother.verify_code'),
                        cls: 'primary-button',
                        handler: this.verifyCode,
                        scope: this
                    }]
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
    onAfterRender: function (a,b,c) {
        console.log('onRender', a, b, this);
        this._loaderPanel = this.getComponent('bigbrother-loader');
        this._initialAuthPanel = this.getComponent('bigbrother-panel-initial-authorization');
        this._authorizedPanel = this.getComponent('bigbrother-panel-authorized');

        this.getState();
    },

    _loaderPanel: null,
    _initialAuthPanel: null,
    _authorizedPanel: null,

    getState() {
        console.log('getState');
        this._loaderPanel.show();
        this._initialAuthPanel.hide();
        this._authorizedPanel.hide();

        MODx.Ajax.request({
            url : BigBrother.config.connectorUrl,
            params : {
                action : 'mgr/authorize/get_state'
            },
            method: 'POST',
            scope: this,
            listeners: {
                success: {
                    fn: function (result) {
                        console.log(result);
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
    }
});
Ext.reg('bigbrother-panel-authorize', BigBrother.panel.AuthorizePanel);
