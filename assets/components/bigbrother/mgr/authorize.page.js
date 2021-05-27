Ext.onReady(function() {
    MODx.load({
        xtype: 'bigbrother-page-authorize',
        renderTo: 'bigbrother-page'
    });
});

BigBrother.page.Authorize = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        border: false,
        id : 'bigbrother-page-wrapper',
        components: [{
            cls: 'container',
            xtype: 'bigbrother-panel-authorize',
            // items: [{
            //     html: '<h2>' + _('bigbrother.mgr.authorize') + '</h2>',
            //     border: false,
            //     id: 'modx-bigbrother-header',
            //     cls: 'modx-page-header'
            // }, {
            //     xtype: 'modx-tabs',
            //     id: 'bigbrother-page-authorize-tabs',
            //     width: '98%',
            //     border: false,
            //
            //     stateful: true,
            //     stateId: 'bigbrother-page-authorize',
            //     stateEvents: ['tabchange'],
            //     getState: function () {
            //         return {
            //             activeTab: this.items.indexOf(this.getActiveTab())
            //         };
            //     },
            //
            //     defaults: {
            //         border: false,
            //         autoHeight: true,
            //         defaults: {
            //             border: false
            //         }
            //     },
            //     items: this.getTabs(config)
            // }, BigBrother.attribution()],
        }],
        buttons: this.getButtons(config)
    });
    BigBrother.page.Authorize.superclass.constructor.call(this,config);
};
Ext.extend(BigBrother.page.Authorize, MODx.Component,{
    getButtons: function() {
        var buttons = [{
            text: _('help_ex'),
            handler: this.loadHelpPane,
            scope: this,
            id: 'modx-abtn-help'
        }];

        if (!BigBrother.config.has_donated) {
            buttons.push(['-', {
                text: _('bigbrother.donate'),
                handler: this.donate,
                scope: this
            }]);
        }

        return buttons;
    },

    loadHelpPane: function() {
        MODx.config.help_url = 'https://docs.modmore.com/en/Open_Source/BigBrother/index.html?embed=1';
        MODx.loadHelpPane();
    },

    donate: function() {
        window.open('https://modmore.com/extras/bigbrother/donate/');
    }
});
Ext.reg('bigbrother-page-authorize',BigBrother.page.Authorize);
