BigBrother.TopCountries = function(el) {
    let container;

    let buildDom = function() {
        container = document.createElement('ul');
        container.classList.add('bigbrother-report-list-items');
        el.appendChild(container);
    }


    let setData = function(data) {
        if (!container) {
            buildDom();
        }

        // Clear what may have been there before
        container.innerHTML = '';

        data.forEach((item) => {
            let li = document.createElement('li');
            li.classList.add('bigbrother-report-list-item');

            li.insertAdjacentHTML('beforeend', '' +
                '<span class="bigbrother-report-list-item--label" target="_blank" rel="noopener"><span class="bigbrother-ellipsis">' + item.title + '</span></span>' +
                '<span class="bigbrother-report-list-item--value">' + item.value + '</span>' +
                '<span class="bigbrother-report-list-item--previous ' + (item.improved ? 'bigbrother-list-item--up' : 'bigbrother-list-item--down') + '">' +
                    item.previous +
                '</span>'
            );

            container.appendChild(li);
        });
    };

    buildDom();
    return {
        key: 'top-countries',
        el: el,
        setData: setData
    }
}