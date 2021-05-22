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
            ,id: 'main-panel'
            ,items:[{
                html: _('bigbrother.main_description'),
                cls: 'main-wrapper bigbrother-panel-intro'
            },{
                xtype:'form'
                ,cls: 'main-wrapper form-with-labels bigbrother-panel'
                ,id: 'bigbrother-panel-initial-authorization'
                ,labelAlign: 'top'
                ,items: [{
                    html: '<h2>'+_('bigbrother.authorization')+'</h2>' + _('bigbrother.initial_authorization_description'),
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
                            handler: this.verifyCode
                        }]
                    }]
                }]
            }]
        }, BigBrother.attribution()]
        ,listeners: {
            // render: this.onRender,
        }
    });
    BigBrother.panel.AuthorizePanel.superclass.constructor.call(this,config);
};
Ext.extend(BigBrother.panel.AuthorizePanel, MODx.Panel,{
    // onRender: function (a,b,c) {
    //     console.log('onRender', a, b, this);
    // },
    getToken: false
    ,verifyCode: function() {
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
                        console.log(result);
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
    }
});
Ext.reg('bigbrother-panel-authorize', BigBrother.panel.AuthorizePanel);
