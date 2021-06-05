var BigBrother = function(config) {
    config = config || {};
    this.widgetCount = 0;
    this.waitTime = 500;
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
    },

    _charts: [],
    _keys: [],

    registerCharts(charts) {
        charts.forEach((ch) => {
            this._charts.push(ch);
            this._keys.push(ch.key);
        });

        const refreshCharts = this.debounce(() => this.refreshCharts());

        // Once the first widget's chart is registered, set a timer to allow the others to register before sending a
        // request. This ensures all widgets are populated via a single request instead of one per widget.
        this.widgetCount++;
        if (this.widgetCount === 1) {
            refreshCharts();
        }
    },

    debounce(func, timeout = this.waitTime){
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    },

    _spinners: null,
    enableSpinners() {
        if (!this._spinners) {
            this._spinners = document.querySelectorAll('.bigbrother-spinner');
        }

        this._spinners.forEach((spinner) => {
            spinner.style.display = 'initial';
        })
    },

    disableSpinners() {
        if (!this._spinners) {
            this._spinners = document.querySelectorAll('.bigbrother-spinner');
        }

        this._spinners.forEach((spinner) => {
            spinner.style.display = 'none';
        })
    },

    renderPeriodDates(visitsChart) {
        if (visitsChart['first_date'] && visitsChart['last_date']) {
            let period = visitsChart['first_date'] + ' - ' + visitsChart['last_date'];
            let element = document.querySelector('#bb-title-period');
            element.innerHTML = period;
        }
    },

    refreshCharts() {
        this.enableSpinners();
        MODx.Ajax.request({
            url: BigBrother.config.connectorUrl,
            params: {
                action : 'mgr/reports',
                reports: this._keys.join(',')
            },
            method: 'GET',
            scope: this,
            listeners: {
                success: {
                    fn: function (result) {
                        this._charts.forEach((ch) => {
                            if (result.data[ch.key]) {
                                ch.setData(result.data[ch.key]);
                            }
                        });
                        if (result.data['visits/line']) {
                            this.renderPeriodDates(result.data['visits/line']);
                        }
                        this.disableSpinners();
                    },
                    scope: this
                },
                failure: {
                    fn: function (result) {
                        Ext.MessageBox.alert(_('error'), result.responseText);
                        this.disableSpinners();
                    },
                    scope: this
                }
            }
        });
    }
});
Ext.reg('bigbrother',BigBrother);
BigBrother = new BigBrother();
