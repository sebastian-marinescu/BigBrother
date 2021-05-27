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
                    label: 'Visits per day',
                    data: [],
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },{
                    label: 'Visits per day (prev period)',
                    data: [],
                    fill: false,
                    borderColor: 'rgba(131,168,241, 0.6)',
                    tension: 0.1
                }]
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