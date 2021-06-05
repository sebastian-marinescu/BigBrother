BigBrother.Acquisition = function(el) {
    let canvas, ctx, chart;

    let buildDom = function() {
        canvas = document.createElement('canvas');
        canvas.setAttribute('width', el.clientWidth);
        canvas.setAttribute('height', el.clientHeight);
        el.appendChild(canvas);

        ctx = canvas.getContext('2d');
    }

    let buildChart = function() {
        window.acq = chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    {
                        label: _('bigbrother.this_month'),
                        data: [],
                        borderWidth: 0,
                        backgroundColor: '#234368',
                        pointHitRadius: 6,
                        barThickness: 10,
                    },
                    {
                        label: _('bigbrother.last_month'),
                        data: [],
                        borderWidth: 0,
                        backgroundColor: 'rgba(131,168,241, 0.3)',
                        pointHitRadius: 6,
                        barThickness: 10,
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                },
                // interaction: {
                //     mode: 'x'
                // },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            drawOnChartArea: false,
                        }
                    },
                    x: {
                        title: _('bigbrother.page_views'),
                        grid: {
                            drawBorder: false,
                        }
                    },
                }
            }
            // data: {
            //     datasets: [{
            //         label: 'Last 28 days',
            //         data: [],
            //         fill: false,
            //         borderColor: '#234368',
            //         borderWidth: 2,
            //         backgroundColor: 'rgba(35,67,104,0.1)',
            //         pointBackgroundColor: '#234368',
            //         pointRadius: 2,
            //         pointHoverRadius: 6,
            //         pointHitRadius: 6,
            //         tension: 0.1,
            //         xAxisID: 'x',
            //     },{
            //         label: '4 weeks before',
            //         data: [],
            //         fill: 'origin',
            //         borderColor: 'rgba(131,168,241, 0.5)',
            //         borderWidth: 1,
            //         backgroundColor: 'rgba(131,168,241, 0.3)',
            //         pointBackgroundColor: 'rgba(131,168,241, 1)',
            //         pointRadius: 2,
            //         pointHoverRadius: 6,
            //         pointHitRadius: 6,
            //         tension: 0.1,
            //         xAxisID: 'xPrev',
            //     }]
            // },
            // options: {
            //     maintainAspectRatio: false, // Doesn't work with dynamic resizing in MODX3, so best keep the height fixed
            //     interaction: {
            //         mode: 'x'
            //     },
            //     scales: {
            //         y: {
            //             grid: {
            //                 drawBorder: false,
            //             }
            //         },
            //         // x: {
            //         //     type: 'time',
            //         //     unit: 'week',
            //         //     min: luxon.DateTime.now().minus({days: 28}).toISODate(),
            //         //     max: luxon.DateTime.now().toISODate(),
            //         //     beginAtZero: true,
            //         //     ticks: {
            //         //         maxRotation: 0
            //         //     },
            //         //     grid: {
            //         //         drawBorder: false,
            //         //         drawOnChartArea: false,
            //         //         // drawTicks: false
            //         //     }
            //         // },
            //         // xPrev: {
            //         //     type: 'time',
            //         //     unit: 'week',
            //         //     min: luxon.DateTime.now().minus({days: 56}).toISODate(),
            //         //     max: luxon.DateTime.now().minus({days: 28}).toISODate(),
            //         //     display: false
            //         // }
            //     },
            //     plugins: {
            //         filler: {},
            //         legend: {
            //             display: false
            //         },
            //         // tooltip: { // While this adds dates to the tooltip, it also somehow breaks the hover effect on points
            //         //     callbacks: {
            //         //         label: function(ctx) {
            //         //             return ctx.label + ': ' + ctx.parsed.y;
            //         //         }
            //         //     }
            //         // }
            //     }
            // }
        });
    }

    let setData = function(data) {
        if (data.length < 1) {
            return;
        }
        if (!chart) {
            buildChart();
        }
        //
        // chart.clear();
        chart.data.labels = data[0].labels;
        chart.data.datasets.forEach((dataset, index) => {
            while (dataset.data.pop()) { }
            data[index].data.forEach((row) => {
                dataset.data.push(row);
            })
            // dataset.data =;
        });
        chart.update();
    };

    buildDom();
    return {
        key: 'acquisition',
        el: el,
        setData: setData
    }
}