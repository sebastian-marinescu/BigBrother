var BigBrother = function(config) {
    config = config || {};
    BigBrother.superclass.constructor.call(this,config);
};
Ext.extend(BigBrother,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},tabs:{},combo:{},
    config: {
        connectorUrl: ''
    },
    attribution: function() {
        return {
            xtype: 'panel',
            bodyStyle: 'text-align: right; background: none; padding: 10px 0;',
            html: '<p class="bigbrother-credits">' +
                '<span class="bigbrother-credits__version">BigBrother v' + BigBrother.config.version + '</span>' +
                '<a href="https://www.modmore.com/extras/bigbrother/?utm_source=bigbrother_footer" target="_blank" rel="noopener"  class="bigbrother-credits__logo">' +
                    '<img src="' + BigBrother.config.assetsUrl + 'images/modmore.svg" alt="a modmore product"/>' +
                '</a>' +
            '</p>',
            border: false,
            anchor: '100%'
        };
    }
});
Ext.reg('bigbrother',BigBrother);
BigBrother = new BigBrother();
