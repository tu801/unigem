<?php
echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row">
    <div class="col-12">
        <div class="callout callout-info">
            <h5><i class="fas fa-info"></i> <?=lang('Shop.note')?>:</h5>
            <?=lang('Shop.currency_exchange_info')?>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post" action="<?=route_to('list_exchange_rate') ?>" >
                <?=csrf_field()?>
                <div class="row">
                    <!-- Cột bên trái - Số tiền quy khách cần bán -->
                    <div class="col-md-2">
                        <h6 class="text-uppercase font-weight-bold mb-3"><?=lang('Shop.currency_from')?></h6>
                        
                        <div class="currency-input-container">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" data-toggle="dropdown">
                                        <img src="https://flagcdn.com/w20/us.png" alt="USD" class="me-2" width="20">
                                        <span class="ms-1 ml-1"> USD</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item d-flex align-items-center" href="#" data-currency="USD">
                                            <img src="https://flagcdn.com/w20/us.png" alt="USD" class="me-2" width="20">
                                            USD
                                        </a>
                                        <!-- <a class="dropdown-item d-flex align-items-center" href="#" data-currency="EUR">
                                            <img src="https://flagcdn.com/w20/eu.png" alt="EUR" class="me-2" width="20">
                                            EUR
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="#" data-currency="JPY">
                                            <img src="https://flagcdn.com/w20/jp.png" alt="JPY" class="me-2" width="20">
                                            JPY
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="#" data-currency="GBP">
                                            <img src="https://flagcdn.com/w20/gb.png" alt="GBP" class="me-2" width="20">
                                            GBP
                                        </a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cột giữa - Mũi tên -->
                    <div class="col-md-2 d-flex align-items-center justify-content-center">
                        <div class="conversion-arrow">
                            <i class="fas fa-arrow-right text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>

                    <!-- Cột bên phải - Số tiền quy khách sẽ nhận -->
                    <div class="col-md-5">
                        <h6 class="text-uppercase font-weight-bold mb-3"><?=lang('Shop.currency_to')?></h6>
                        
                        <div class="currency-input-container">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary d-flex align-items-center" type="button" disabled>
                                        <img src="https://flagcdn.com/w20/vn.png" alt="VND" class="me-2" width="20">
                                        <span class="ms-1 ml-1">VND</span>
                                    </button>
                                </div>
                                <input type="number" class="form-control form-control-lg text-center" name="rate" id="toAmount" value="0" style="font-size: 1.5rem; font-weight: bold; background-color: #f8f9fa;">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h6 class="text-uppercase font-weight-bold mb-3">&nbsp;</h6>
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="fas fa-exchange-alt me-2"></i>
                            <?=lang('Shop.save_exchange_rate')?>
                        </button>
                    </div>
                    
                </div>
                </form>

                <!-- Thông tin tỷ giá -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-light border">
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted">NGoại tệ:</small>
                                    <div class="font-weight-bold text-info">USD</div>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Tỷ giá hiện tại:</small>
                                    <div class="font-weight-bold" id="exchangeRate">1 USD = 24,000 VND</div>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Cập nhật lần cuối:</small>
                                    <div class="font-weight-bold"><?= date('d/m/Y H:i') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

        </div>
        
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tỷ giá mẫu
    const exchangeRates = {
        'USD': 24000,
        'EUR': 26500,
        'JPY': 170,
        'GBP': 30500
    };

    const currencyNames = {
        'USD': 'Đô la Mỹ',
        'EUR': 'Euro',
        'JPY': 'Yên Nhật',
        'GBP': 'Bảng Anh'
    };

    let currentCurrency = 'USD';
    
    const fromAmountInput = document.getElementById('fromAmount');
    const toAmountInput = document.getElementById('toAmount');
    const exchangeRateDiv = document.getElementById('exchangeRate');
    
    // Xử lý dropdown currency
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            const currency = this.getAttribute('data-currency');
            const currencyText = this.innerHTML;
            
            // Cập nhật button dropdown
            const dropdownButton = document.querySelector('.dropdown-toggle');
            dropdownButton.innerHTML = currencyText;
            
            currentCurrency = currency;
            console.log(`Selected currency: ${currentCurrency}`);
            updateExchangeRate();
            // calculateConversion();
        });
    });
    
    // Xử lý khi nhập số tiền
    // fromAmountInput.addEventListener('input', calculateConversion);
    
    function updateExchangeRate() {
        const rate = exchangeRates[currentCurrency];
        exchangeRateDiv.textContent = `1 ${currentCurrency} = ${rate.toLocaleString('vi-VN')} VND`;
    }
    
    function calculateConversion() {
        const fromAmount = parseFloat(fromAmountInput.value) || 0;
        const rate = exchangeRates[currentCurrency];
        const toAmount = fromAmount * rate;
        
        toAmountInput.value = Math.round(toAmount).toLocaleString('vi-VN');
    }

    // Khởi tạo
    updateExchangeRate();
    // calculateConversion();
});
</script>
<?= $this->endSection() ?>