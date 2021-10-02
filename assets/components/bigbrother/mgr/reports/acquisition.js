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
        chart = new Chart(ctx, {
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                },
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
        });
    }

    let setData = function(data) {
        if (data.length < 1) {
            return;
        }
        if (!chart) {
            buildChart();
        }

        chart.data.labels = data[0].labels;
        chart.data.datasets.forEach((dataset, index) => {
            while (dataset.data.pop()) { }
            if (data.hasOwnProperty(index)) {
                data[index].data.forEach((row) => {
                    dataset.data.push(row);
                })
            }
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
