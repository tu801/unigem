<div class="card">
    <div class="card-header border-0">
        <div class="d-flex justify-content-between">
            <h3 class="card-title">Doanh số năm</h3>
<!--            <a href="javascript:void(0);">View Report</a>-->
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex">
            <p class="d-flex flex-column">
                <span class="text-bold text-lg"><?= vnd_encode($total_revenue, true) ?></span>
                <span>Tổng doanh thu trong năm nay</span>
            </p>
<!--            <p class="ml-auto d-flex flex-column text-right">-->
<!--                    <span class="text-success">-->
<!--                      <i class="fas fa-arrow-up"></i> 33.1%-->
<!--                    </span>-->
<!--                <span class="text-muted">Since last month</span>-->
<!--            </p>-->
        </div>
        <!-- /.d-flex -->

        <div class="position-relative mb-4">
            <canvas id="sales-chart" height="200"></canvas>
        </div>

<!--        <div class="d-flex flex-row justify-content-end">-->
<!--                  <span class="mr-2">-->
<!--                    <i class="fas fa-square text-primary"></i> This year-->
<!--                  </span>-->
<!---->
<!--            <span>-->
<!--                    <i class="fas fa-square text-gray"></i> Last year-->
<!--                  </span>-->
<!--        </div>-->
    </div>
</div>
<!-- /.card -->


<script src="<?=base_url($configs->scriptsPath)?>/plugins/chart.js/Chart.min.js"></script>

<script type="text/javascript">
    $(function () {
        'use strict'

        var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        };

        var mode = 'index';
        var intersect = true;
        var $salesChart = $('#sales-chart');
        // eslint-disable-next-line no-unused-vars
        var salesChart = new Chart($salesChart, {
            type: 'bar',
            data: {
                labels: <?=json_encode($month_name)?>,
                datasets: [
                    {
                        backgroundColor: '#007bff',
                        borderColor: '#007bff',
                        data: <?=json_encode($monthly_revenue)?>
                    },
                    // {
                    //     backgroundColor: '#ced4da',
                    //     borderColor: '#ced4da',
                    //     data: [700, 1700, 2700, 2000, 1800, 1500, 2000]
                    // }
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        // display: false,
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,

                            // Include a dollar sign in the ticks
                            callback: function (value) {
                                if (value >= 1000) {
                                    value /= 1000
                                    value += 'k'
                                }

                                return value + ' VND'
                            }
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
                }
            }
        })
    });
</script>
