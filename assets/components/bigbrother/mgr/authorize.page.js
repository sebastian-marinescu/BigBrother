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
            xtype: 'bigbrother-panel-authorize'
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
