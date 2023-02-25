jQuery(document).ready(function() {
    jQuery.ajax({
        url: 'charts/index',
        type: 'get',
        dataType: 'json',
        timeout: 20000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function(response) {
            let categories = []
            let percetages = []
            let values = []
            let colors = []

            jQuery(response).each(function() {
                categories.push(this.category)
                percetages.push(this.percentage)
                values.push(this.total)
                colors.push(this.color)
            })

            let data = {
                labels: categories,
                datasets: [{
                    backgroundColor: colors,
                    data: percetages,
                }]
            };

            let config = {
                type: 'polarArea',
                data: data,
                options: {
                    responsive: true
                }
            };

            new Chart(document.getElementById('myChart'), config);
        }
    })
})
