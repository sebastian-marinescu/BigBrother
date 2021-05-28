BigBrother.KeyMetrics = function(el) {
    let container;

    let buildDom = function() {
        container = document.createElement('ul');
        container.classList.add('bigbrother-key-metrics');
        el.appendChild(container);
    }


    let setData = function(data) {
        if (!container) {
            buildDom();
        }

        // Clear what may have been there before
        container.innerHTML = '';

        data.forEach((metric) => {
            let li = document.createElement('li');
            li.classList.add('bigbrother-key-metric');

            li.insertAdjacentHTML('beforeend', '' +
                '<span class="bigbrother-metric-value">' + metric.value + '</span>' +
                '<span class="bigbrother-metric-label">' + metric.label + '</span>' +
                '<span class="bigbrother-metric-previous ' + (metric.improved ? 'bigbrother-metric-up' : 'bigbrother-metric-down') + '">' +
                    metric.previous +
                '</span>'
            );

            container.appendChild(li);
        });
    };

    buildDom();
    return {
        key: 'key-metrics',
        el: el,
        setData: setData
    }
}