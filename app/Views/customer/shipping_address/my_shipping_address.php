<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 22/06/2025
 */

echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center"><?=$page_title?></div>
    </div>
</div>
<!-- /page-title -->

<!-- page-cart -->
<section class="flat-spacing-11">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <?= view('customer/components/account_sidebar', ['customer' => $customer]) ?>
            </div>
            <div class="col-lg-9">
                <div class="my-account-content account-dashboard">
                    <?=view('customer/components/customer_alert_block')?>
                    
                    <div class="text-center widget-inner-address">
                        <a class="tf-btn btn-fill animate-hover-btn mb_20" href="<?=route_to('add_new_address')?>"><?=lang('Customer.add_new_address')?></a>
                        
                        
                        <div class="list-account-address">
                        <?php
                        if ( !empty($customer->shippingAddress) ) :
                            foreach ($customer->shippingAddress as $address) : ?>
                            <div class="account-address-item">
                                <?php if (isset($address->is_default) && $address->is_default) : ?> 
                                <h6 class="mb_20"><?=lang('Customer.default_address')?></h6>
                                <?php endif; ?>
                                <p><?=esc($address->ship_full_name)?></p>
                                <p><?=esc($address->full_address)?></p>
                                <p><?=esc($address->ship_email)?></p>
                                <p class="mb_10"><?=esc($address->ship_telephone)?></p>
                                <div class="d-flex gap-10 justify-content-center">
                                    <a class="tf-btn btn-fill animate-hover-btn justify-content-center btn-edit-address" href="<?=route_to('edit_shipping_address', $address->id)?>">
                                        <span><?=lang('Common.edit')?></span>
                                    </a>
                                    <a class="tf-btn btn-outline animate-hover-btn justify-content-center delete-address"
                                        href="<?=route_to('delete_shipping_address', $address->id)?>" >
                                        <span><?=lang('Common.delete')?></span>
                                    </a>
                                </div>
                            </div>

                        <?php
                            endforeach;
                        else : ?>
                            <p class="text-muted"><?= lang('Customer.no_shipping_address') ?></p>
                        <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- page-cart -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function () {
    // Cấu hình toastr
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // Function xác nhận xóa địa chỉ
    function confirmDeleteAddress(deleteUrl) {
        // Remove existing event handlers trước khi tạo mới
        $(document).off('click', '#confirm-delete');
        $(document).off('click', '#cancel-delete');
        
        // Hiển thị toast xác nhận
        const confirmToast = toastr.warning(
            '<div>' +
                '<p><?= lang("Customer.confirm_delete_address") ?></p>' +
                '<div style="margin-top: 10px;">' +
                    '<button type="button" class="btn btn-danger btn-sm" id="confirm-delete" style="margin-right: 5px;">Xóa</button>' +
                    '<button type="button" class="btn btn-secondary btn-sm" id="cancel-delete">Hủy</button>' +
                '</div>' +
            '</div>',
            '',
            {
                "closeButton": false,
                "timeOut": 0,
                "extendedTimeOut": 0,
                "tapToDismiss": false,
                "positionClass": "toast-top-center"
            }
        );

        // Xử lý khi user nhấn "Xóa"
        $(document).on('click', '#confirm-delete', function() {
            // Remove event handlers
            $(document).off('click', '#confirm-delete');
            $(document).off('click', '#cancel-delete');
            
            // Clear toast
            toastr.remove();
            
            // Hiển thị loading
            toastr.info('<?=lang('Customer.processing')?>', '', {
                "timeOut": 0,
                "extendedTimeOut": 0
            });

            // Gửi request xóa
            window.location.href = deleteUrl;
        });

        // Xử lý khi user nhấn "Hủy"
        $(document).on('click', '#cancel-delete', function() {
            console.log('Xóa địa chỉ đã bị hủy');
            
            // Remove event handlers
            $(document).off('click', '#confirm-delete');
            $(document).off('click', '#cancel-delete');
            
            // Clear toast
            toastr.remove();
        });
    }

    // Gắn sự kiện click cho tất cả nút delete
    $('.delete-address').on('click', function(e) {
        e.preventDefault(); // Ngăn chặn hành động mặc định
        const deleteUrl = $(this).attr('href');
        confirmDeleteAddress(deleteUrl);
    });
});
</script>
<?= $this->endSection() ?>
