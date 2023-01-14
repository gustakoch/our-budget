jQuery(document).ready(function() {
    jQuery.ajax({
        url: 'charts/index',
        type: 'get',
        dataType: 'json',
        timeout: 20000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        beforeSend: function() {
            // Colocar um loading...
        },
        error: function(xhr, status, error) {
            console.log(status, error)
        },
        success: function(response) {
            const categories = []
            const percetages = []
            const values = []
            const colors = []

            jQuery(response).each(function() {
                categories.push(this.category)
                percetages.push(this.percentage)
                values.push(this.total)
                colors.push(this.color)
            })

            const data = {
                // labels: categories,
                datasets: [{
                    label: 'Where is my money going?',
                    backgroundColor: colors,
                    data: percetages,
                }]
            };

            const config = {
                type: 'pie',
                data: data,
                options: {
                    responsive: true
                }
            };

            let myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
        }
    })
})
