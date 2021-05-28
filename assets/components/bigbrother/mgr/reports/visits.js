BigBrother.VisitsLineGraph = function(el) {
    let canvas, ctx, chart;

    let buildDom = function() {
        canvas = document.createElement('canvas');
        canvas.setAttribute('width', el.clientWidth);
        canvas.setAttribute('height', el.clientHeight);
        el.appendChild(canvas);

        ctx = canvas.getContext('2d');
    }

    let buildChart = function() {
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Daily pageviews',
                    data: [],
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    xAxisID: 'x',
                },{
                    label: '4 weeks before',
                    data: [],
                    fill: false,
                    borderColor: 'rgba(131,168,241, 0.6)',
                    tension: 0.1,
                    xAxisID: 'xPrev',
                }]
            },
            options: {
                interaction: {
                    mode: 'x'
                },
                scales: {
                    x: {
                        type: 'time',
                        unit: 'week',
                        min: luxon.DateTime.now().minus({days: 28}).toISODate(),
                        max: luxon.DateTime.now().toISODate(),
                        beginAtZero: true,
                        ticks: {
                            maxRotation: 0
                        }
                    },
                    xPrev: {
                        type: 'time',
                        unit: 'week',
                        min: luxon.DateTime.now().minus({days: 56}).toISODate(),
                        max: luxon.DateTime.now().minus({days: 28}).toISODate(),
                        display: false
                    }
                }
            }
        });
    }

    let setData = function(data) {
        if (!chart) {
            buildChart();
        }

        if (data.data) {
            chart.data.datasets.forEach((dataset, index) => {
                dataset.data = data.data[index];
            });
        }
        if (data.labels) {
            chart.data.labels = data.labels;
        }
        chart.update();
    };

    buildDom();
    return {
        key: 'visits/line',
        el: el,
        setData: setData
    }
}