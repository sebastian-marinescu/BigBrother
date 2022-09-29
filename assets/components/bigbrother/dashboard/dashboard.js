/**
 * The widget container for Quick overview
 *
 * @class BigBrother.Panel.Dashboard
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype bb-panel
 */
BigBrother.Panel.Dashboard = function(config) {
    config = config || {};
    var panels = [];

    if (BigBrother.showBanner) {
        panels.push({
            xtype: 'panel'
            ,id: 'bigbrother-eol-warning-container'
            ,items: [{
                xtype: 'panel'
                ,id: 'bigbrother-eol-warning'
                ,html:
                    '<div class="bigbrother-eol-main-col">' +
                    '<h4>' + _('bigbrother.eol_banner.heading', {link: '&nbsp;<a href="https://support.google.com/analytics/answer/10089681" target="_blank" rel="nofollow noreferrer"><i class="icon icon-external-link"></i></a>'}) + '</h4>' +
                    '<p>' + _('bigbrother.eol_banner.content', {link: '<a href="https://support.google.com/analytics/answer/11583528?hl=en" target="_blank" rel="nofollow noreferrer">stop working soon</a>'}) + '</p>' +
                    '</div>' +
                    '<a class="upgrade-btn" href="https://modmore.com/blog/2022/big-brother-3.0-now-available/" target="_blank" rel="noopener noreferrer"><span>' + _('bigbrother.eol_banner.button') + ' &nbsp;&nbsp;<i class="icon icon-arrow-right"></i></span></a>'
            }]
        });
    }

    panels.push({
        xtype: 'modx-desc-panel'
        ,startingMarkup: '<tpl for=".">' + _('bigbrother.desc_markup') + '</tpl>'
        ,startingText: _('bigbrother.desc_title')
    },{
        xtype: 'panel'
        ,cls: 'main-wrapper'
        ,id: 'bb-container'
        ,items:[]
    });

    Ext.applyIf(config,{
        id: 'modx-panel-bigbrother'
        ,unstyled: true
        ,defaults: { collapsible: false, autoHeight: true, unstyled: true }
        ,items: panels
        ,renderTo: "bb-panel"
    });
    BigBrother.Panel.Dashboard.superclass.constructor.call(this,config);

    this.init();
};
Ext.extend(BigBrother.Panel.Dashboard,Ext.Panel,{
    init: function(){
        var container = Ext.getCmp('bb-container');
        var total = 0;
        if(MODx.config['bigbrother.show_visits_on_dashboard'] == 1){
            container.add(this.getPanels('visits')); total++;
        }
        if(MODx.config['bigbrother.show_metas_on_dashboard'] == 1){
            container.add(this.getPanels('metas')); total++;
        }
        if(MODx.config['bigbrother.show_pies_on_dashboard'] == 1){
            container.add(this.getPanels('pies')); total++;
        }
        container.doLayout();
    }

    ,getPanels: function(panel){
        switch(panel){
            case 'visits':
                return {
                    xtype: 'bb-areachart-panel'
                    ,title: _('bigbrother.visits')
                    ,metrics: 'ga:visits'
                    ,id: 'visits-panel'
                    ,cls: 'bb-panel charts-wrapper charts-line visits'
                    ,height: 225
                };
                break;
            case 'metas':
                return {
                    xtype:'bb-meta-panel'
                    ,id:'report-content-metas'
                    ,metrics: 'ga:visits,ga:visitors,ga:pageviews,ga:uniquePageviews,ga:percentNewVisits,ga:exitRate,ga:avgSessionDuration,ga:visitBounceRate'
                    ,cols: 4
                };
                break;
            case 'pies':
                return {
                    layout: 'column'
                    ,id: 'widget-pies'
                    ,border: false
                    ,items:[{
                        xtype: 'bb-pie-panel'
                        ,title: _('bigbrother.traffic_sources')
                        ,dimensions: 'ga:medium'
                        ,metrics: 'ga:visits'
                        ,sort: 'ga:visits'
                        ,chartHeight: 250
                        ,replace: 'direct_traffic'
                        ,columnWidth: 0.5
                        ,listeners:{
                            chartsloaded: function(p){
                                Ext.getCmp('widget-pies').doLayout();
                            }
                        }
                    },{
                        xtype: 'bb-pie-panel'
                        ,title: _('bigbrother.visitors')
                        ,dimensions: 'ga:visitorType'
                        ,metrics: 'ga:visits'
                        ,sort: 'ga:visits'
                        ,chartHeight: 250
                        ,columnWidth: 0.5
                        ,listeners:{
                            chartsloaded: function(p){
                                Ext.getCmp('widget-pies').doLayout();
                            }
                        }
                    }]
                }
                break;
            default:
                break;
        }
    }

    ,fixWidth: function(){
        Ext.getCmp('bb-container').doLayout();
    }

    ,redirect: function(){
        location.href = BigBrother.RedirectUrl;
    }
});
Ext.reg('bb-panel', BigBrother.Panel.Dashboard);