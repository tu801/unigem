<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Đơn hàng trong tháng</h3>
<!--        <div class="card-tools">-->
<!--            <a href="#" class="btn btn-sm btn-tool">-->
<!--                <i class="fas fa-download"></i>-->
<!--            </a>-->
<!--            <a href="#" class="btn btn-sm btn-tool">-->
<!--                <i class="fas fa-bars"></i>-->
<!--            </a>-->
<!--        </div>-->
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
            <p class="text-success text-xl">
                <i class="ion ion-ios-analytics-outline"></i>
            </p>
            <p class="d-flex flex-column text-right">
                <span class="font-weight-bold">
                <?= vnd_encode($revenue_in_month, true)?>
                </span>
                <span class="text-muted">Doanh Thu trong tháng</span>
            </p>
        </div>

        <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
            <p class="text-success text-xl">
                <i class="ion ion-ios-refresh-empty"></i>
            </p>
            <p class="d-flex flex-column text-right">
                <span class="font-weight-bold">
                <?=round($conversion_rate, 2)?>%
                </span>
                <span class="text-muted">Tỉ lệ chuyển đổi</span>
            </p>
        </div>

        <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
            <p class="text-warning text-xl">
                <i class="ion ion-ios-cart-outline"></i>
            </p>
            <p class="d-flex flex-column text-right">
                <span class="font-weight-bold">
                <?= number_format($total_order_in_month, 0, ',',',')?>
                </span>
                <span class="text-muted">Đơn hàng trong tháng</span>
            </p>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <p class="text-danger text-xl">
                <i class="ion ion-ios-people-outline"></i>
            </p>
            <p class="d-flex flex-column text-right">
                <span class="font-weight-bold">
                <?=number_format($new_customer_in_month, 0, ',',',')?>
                </span>
                <span class="text-muted">Khách hàng mới trong tháng</span>
            </p>
        </div>

    </div>
</div>