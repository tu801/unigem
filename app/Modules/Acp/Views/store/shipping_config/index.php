<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 10/26/2023
 */

echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row" id="shipConfigApp">
    <div class="col-4">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><?=lang('Shop.weight_default_shipping_fee')?></h3>
            </div>

            <div class="card-body">
                <form method="post" action="<?=route_to('shipping_config') ?>" >
                    <?=csrf_field()?>
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control <?= session('errors.weight_shipping_fee') ? 'is-invalid' : '' ?>" id="weight_shipping_fee"
                                   name="weight_shipping_fee" value="<?=$weight_shipping_fee?>" >
                            <div class="input-group-append">
                                <span class="input-group-text">đ</span>
                            </div>
                            <button type="submit" name="save-weight-shipping" class="btn btn-success ml-2">Save</button>
                        </div>
                    </div>
                    <span class="help-block"><?=lang('Shop.weight_default_shipping_fee_helper')?></span>
                </form>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><?=lang('Shop.province_default_shipping_fee')?></h3>
            </div>

            <div class="card-body">
                <form method="post" action="<?=route_to('shipping_config') ?>" >
                <?=csrf_field()?>
                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control <?= session('errors.default_shipping_fee') ? 'is-invalid' : '' ?>" id="default_shipping_fee"
                               name="default_shipping_fee" value="<?=$default_shipping_fee?>" >
                        <div class="input-group-append">
                            <span class="input-group-text">đ</span>
                        </div>
                        <button type="submit" name="save-default-shipping" class="btn btn-success ml-2">Save</button>
                    </div>
                </div>
                    <span class="help-block"><?=lang('Shop.province_default_shipping_fee_helper')?></span>
                </form>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><?=lang('Shop.shipping_by_province')?></h3>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="form-label"><?=lang('Acp.province')?></label>
                    <?php if ( isset($provinces) && count($provinces) ) : ?>
                    <select name="province_id" id="vProvince" class="form-control" v-model="configFrm.province_id">
                        <?php foreach ($provinces as $item) : ?>
                        <option value="<?=$item['id']?>"><?=$item['full_name']?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label"><?=lang('Shop.shipping_config_fee')?></label>
                    <div class="input-group">
                        <input class="form-control" id="vShipping" name="default_shipping_fee" v-model="configFrm.shipping_fee" >
                        <div class="input-group-append">
                            <span class="input-group-text">đ</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <input type="hidden" id="cstoken" value="<?= csrf_hash() ?>">
                <input type="hidden" id="csname" value="<?= csrf_token() ?>">
                <button  name="save-shipping" @click.prevent="onSave" class="btn btn-success ml-2">Save</button>
            </div>
        </div>
    </div>

    <div class="col-8">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div v-if="loading == false">
                    <table id="tblShippingConfig" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th><?= lang('Shop.config_id') ?></th>
                            <th><?= lang('Acp.province') ?></th>
                            <th><?= lang('Shop.shipping_by_province') ?></th>
                            <th><?= lang('Acp.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody v-if="shipConfigData.length">
                        <tr v-for="(item, index) in shipConfigData">
                            <td>#{{ index + 1 }}</td>
                            <td>{{ item.province_name }}</td>
                            <td>{{ vndFormat(item.value) }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm mb-2" v-on:click="onEdit(item)" :key="item.id"><i class="fas fa-edit"></i></a>&nbsp;
                                <a class="btn btn-danger btn-sm mb-2" v-on:click="onDelete(item, '<?= csrf_hash() ?>', '<?= csrf_token() ?>')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                        <tbody v-else>
                        <tr>
                            <td colspan="4">
                                <?=lang('Shop.no_shipping_fee')?>
                            </td>
                        </tr>
                        </tbody>

                    </table>
                </div>
                <div v-else>
                    <div><img class="image img-size-64" src="<?= base_url($config->templatePath) ?>/assets/img/loading.svg"></div>
                </div>
            </div>
        </div>

        <edit-modal :refs-item="currentItem" @on-success="onEditSuccess" ></edit-modal>
    </div>
</div>

<?=view($config->view . '/store/shipping_config/_vmodal_edit');?>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script src="<?= base_url($config->scriptsPath)?>/acp/shop.js"></script>
<script>
    $(function () {
        $('#default_shipping_fee').on('keyup', function () {
            var x = $('#default_shipping_fee').val();
            $('#default_shipping_fee').val(format_vnd(x));
        })
    });
    $(function () {
        $('#weight_shipping_fee').on('keyup', function () {
            var x = $('#weight_shipping_fee').val();
            $('#weight_shipping_fee').val(format_vnd(x));
        })
    });


    const app = Vue.createApp({
        data() {
            return {
                shipConfigData: [],
                configFrm: {
                    province_id: 0,
                    shipping_fee: null
                },
                loading: false,
                currentItem: null,
            }
        },
        mounted() {
            this.loading = true;
            var listUrl = bkUrl + '/shop/shipping-config/list-province-config';

            $.ajax({
                url: listUrl,
                method: "GET",
                dataType: "json",
                success: (response) => {
                    if (response.error === 0) {
                        this.shipConfigData = response.data;
                    } else {
                        this.showError(response.message);
                    }
                    this.loading = false;
                },
                error: (_xhr, _error) => {
                    this.loading = false;
                },
            });

            $(this.$refs.shippingModal).on("hidden.bs.modal", this.onCloseModal);
        },
        methods: {
            vndFormat(val) {
                return format_vnd(val) +' đ';
            },
            isNumeric: function (n) {
                return !isNaN(parseFloat(n)) && isFinite(n);
            },
            showError(mess) {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Lỗi...',
                    autohide: true,
                    delay: 3000,
                    body: mess
                });
            },
            onSave() {
                var errMess = [];
                if ( this.configFrm.province_id === 0 ) {
                    errMess.push('Vui lòng chọn Tỉnh / Thành phố');
                    $('#vProvince').addClass('is-invalid');
                } else {
                    $('#vProvince').removeClass('is-invalid');
                }
                if ( this.configFrm.shipping_fee === null || this.configFrm.shipping_fee === '' ) {
                    errMess.push('Vui lòng nhập phí giao hàng');
                    $('#vShipping').addClass('is-invalid');
                } else {
                    $('#vShipping').removeClass('is-invalid');
                }
                if ( errMess.length ) {
                    this.showError(errMess.join('<br>'));
                    return;
                }

                let csName = $("#csname").val();
                let csToken = $("#cstoken").val();
                let url = bkUrl + "/shop/shipping-config/province";

                let formData = new FormData();
                formData.append('province_id', this.configFrm.province_id);
                formData.append('shipping_fee', this.configFrm.shipping_fee.replace(/,/g, ''));
                // add a sign token
                formData.append(csName, csToken);

                // send data to server
                $.ajax({
                    url: url,
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    type: "POST",
                    success: (response) => {
                        if (response.error == 1) {
                            this.showError(response.message);
                        } else {
                            this.shipConfigData.push(response.data);
                            this.clearForm();
                            SwalAlert.fire({
                                icon: "success",
                                title: response.message,
                            });
                        }
                    },
                    error: (_xhr, _status, _error) => {
                        this.showError("Có lỗi xảy ra! Vui lòng làm mới trình duyệt và thử lại sau")
                    },
                });

                return;
            },
            onEdit(item) {
                this.currentItem = item;
                $("#vShippingModal").modal("show");
            },
            onEditSuccess(response) {
                let item = response.data;
                for (var i = 0; i < this.shipConfigData.length; i++) {
                    if (this.shipConfigData[i].id == item.id)
                        this.shipConfigData.splice(i, 1, item);
                }
                this.currentItem = null;
                $("#vShippingModal").modal("hide");
                SwalAlert.fire({
                    icon: "success",
                    title: response.message,
                });
            },
            clearForm() {
                this.configFrm = {
                    province_id: 0,
                    shipping_fee: null
                };
            },
            onCloseModal() {
                this.current_item = null;
                $("#vShippingModal").modal("hide");
            },
            onDelete(item, token, token_key) {
                let message = "Bạn chắc chắn muốn xóa cấu hình giao hàng ở " + item.province_name + " chứ?";

                Swal.fire({
                    title: message,
                    icon: "warning",
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: "Đồng ý",
                    cancelButtonText: "Bỏ qua",
                    cancelButtonColor: "#20c997",
                    confirmButtonColor: "#DD6B55",
                }).then((result) => {
                    /* Delete item */
                    if (result.isConfirmed) {
                        let url = bkUrl + "/shop/shipping-config/province/delete";
                        let formData = new FormData();
                        formData.append("id", item.id);
                        formData.append(token_key, token);
                        $.ajax({
                            url: url,
                            data: formData,
                            contentType: false,
                            processData: false,
                            dataType: "json",
                            type: "POST",
                            success: (response) => {
                                if (response.error == 1) {
                                    SwalAlert.fire({
                                        icon: "error",
                                        title: response.errMess,
                                    });
                                } else {
                                    for (var i = 0; i < this.shipConfigData.length; i++) {
                                        if (this.shipConfigData[i].id == item.id) {
                                            this.shipConfigData.splice(i, 1);
                                        }
                                    }

                                    SwalAlert.fire({
                                        icon: "success",
                                        title: response.message,
                                    });
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrow) {
                                SwalAlert.fire({
                                    icon: "error",
                                    title: "Có lỗi xảy ra! Vui lòng thử lại sau",
                                });
                            },
                        });
                    } else if (result.isDenied) {
                        return false;
                    }
                });
            },
        },
        watch: {
            configFrm: {
                handler(obj){
                    if ( obj.shipping_fee !== null ) {
                        let shipFee = obj.shipping_fee.replace(/,/g, '');
                        if (this.isNumeric(shipFee) === false ) {
                            this.showError('Vui lòng điền đúng số tiền vào phí giao hàng!');
                            return;
                        }
                    }

                    if ( obj.shipping_fee >= 1000 ) {
                        obj.shipping_fee = format_vnd(obj.shipping_fee);
                    }
                },
                deep: true
            }
        },
    });
    app.component('edit-modal', editModal);
    app.mount('#shipConfigApp');
</script>
<?= $this->endSection() ?>
