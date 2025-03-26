<?php
echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

    <div class="row" id="payment-app">
        <div class="col-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><?=lang('PaymentConfig.edit_payment_title')?></h3>
                </div>

                <div class="card-body">
                    <form method="post" >
                        <?=csrf_field()?>
                        <div class="form-group">
                            <label for="inputProductStatus"><?= lang('PaymentConfig.bank') ?> <span class="text-danger">*</span></label>
                            <select class="custom-select form-control" v-model="formBank.bank" @change="onChangeBank()">
                                <option v-for="(item, index) in banks" :value="index" > {{ item.name }}</option>
                            </select>
                        </div>
                        <div class="form-group text-center" v-if="banks.length">
                            <img :src="'/'+banks[formBank.bank].logo" width="100" height="100" class="img-fluid border border-2"  alt="">
                        </div>
                        <div class="form-group">
                            <label for="inputProductStatus"><?= lang('PaymentConfig.name') ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" :class="{ 'is-invalid': errors.name }"  v-model="formBank.name" >
                            <div class="invalid-feedback" v-if="errors.name">
                                {{ errors.name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputProductStatus"><?= lang('PaymentConfig.account_number') ?><span class="text-danger">*</span></label>
                            <input type="number" class="form-control" :class="{ 'is-invalid': errors.account_number }"  v-model="formBank.account_number">
                            <div class="invalid-feedback" v-if="errors && errors.account_number">
                                {{ errors.account_number }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputProductStatus"><?= lang('PaymentConfig.branch') ?><span class="text-danger">*</span></label>
                            <textarea class="form-control"rows="3"  v-model="formBank.branch" :class="{ 'is-invalid': errors.branch }"></textarea>
                            <div class="invalid-feedback" v-if="errors.branch">
                                {{ errors.branch }}
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-success ml-2" @click="saveBankConfig()">Lưu</button>
                        </div>
                    </form>
                    <input type="hidden" id="cstoken" value="<?= csrf_hash() ?>">
                    <input type="hidden" id="csname" value="<?= csrf_token() ?>">
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
                                <th>#</th>
                                <th><?= lang('PaymentConfig.bank') ?></th>
                                <th><?= lang('PaymentConfig.name') ?></th>
                                <th><?= lang('PaymentConfig.account_number') ?></th>
                                <th><?= lang('PaymentConfig.branch') ?></th>
                                <th><?= lang('Acp.actions') ?></th>
                            </tr>
                            </thead>
                            <tbody v-if="bank_configs.length">
                            <tr v-for="(item, index) in bank_configs">
                                <td>#{{ index + 1 }}</td>
                                <td>
                                    <img :src="'/'+banks[item.bank].logo" width="100" height="100" class="img-fluid border border-2"  alt="">
                                </td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.account_number }}</td>
                                <td>{{ item.branch }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm mb-2" v-on:click="onEdit(index)"><i class="fas fa-edit"></i></a>&nbsp;
                                    <a class="btn btn-danger btn-sm mb-2" v-on:click="onDelete(item.bank)">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="6">
                                        <?=lang('Chưa có dữ liệu')?>
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
        </div>

        <!-- Modal edit -->
        <div class="modal fade" id="edit-payment-modal" tabindex="-1" aria-labelledby="edit-payment-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-payment-modal"><?=lang('PaymentConfig.edit_payment_title')?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group text-center" v-if="banks.length">
                            <img :src="'/'+banks[formBank.bank].logo" width="100" height="100" class="img-fluid border border-2"  alt="">
                        </div>
                        <div class="form-group">
                            <label for="inputProductStatus"><?= lang('PaymentConfig.name') ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" :class="{ 'is-invalid': errors.name }"  v-model="formBank.name" >
                            <div class="invalid-feedback" v-if="errors.name">
                                {{ errors.name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputProductStatus"><?= lang('PaymentConfig.account_number') ?><span class="text-danger">*</span></label>
                            <input type="number" class="form-control" :class="{ 'is-invalid': errors.account_number }"  v-model="formBank.account_number">
                            <div class="invalid-feedback" v-if="errors && errors.account_number">
                                {{ errors.account_number }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputProductStatus"><?= lang('PaymentConfig.branch') ?><span class="text-danger">*</span></label>
                            <textarea class="form-control"rows="3"  v-model="formBank.branch" :class="{ 'is-invalid': errors.branch }"></textarea>
                            <div class="invalid-feedback" v-if="errors.branch">
                                {{ errors.branch }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-success ml-2" @click="saveBankConfig()">Lưu</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
    <script>
        const app = Vue.createApp({
            data() {
                return {
                    banks: [],
                    bank_configs: [],
                    formBank: {
                        bank: 0,
                        account_number: '',
                        name: '',
                        branch: '',
                    },
                    loading: false,
                    edit: false,
                    errors: {},
                }
            },
            mounted() {
                this.loading = true;
                this.getbankList();
                this.getListBankConfig();
            },
            methods: {
                getbankList() {
                    $.ajax({
                        url: '<?= route_to('get_banks') ?>',
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        type: "GET",
                        success: (response) => {
                            if (response.error == 1) {
                                this.showError(response.message);
                            } else {
                                this.banks = response.banks;
                            }
                        },
                        error: (_xhr, _status, _error) => {
                            this.showError("Có lỗi xảy ra! Vui lòng làm mới trình duyệt và thử lại sau")
                        },
                    });
                },
                getListBankConfig() {
                    $.ajax({
                        url: '<?= route_to('list_payment_config') ?>',
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        type: "GET",
                        success: (response) => {
                            if (response.error == 1) {
                                this.showError(response.message);
                            } else {
                                this.bank_configs = response.banks;
                            }
                            this.loading = false
                        },
                        error: (_xhr, _status, _error) => {
                            this.showError("Có lỗi xảy ra! Vui lòng làm mới trình duyệt và thử lại sau")
                        },
                    });
                },
                saveBankConfig() {
                    let formData = new FormData();

                    for (const [key, _value] of Object.entries(this.formBank)) {
                        formData.append(key, this.formBank[key]);
                    }
                    let csName = $("#csname").val();
                    let csToken = $("#cstoken").val();

                    // add a sign token
                    formData.append(csName, csToken);

                    // send data to server
                    $.ajax({
                        url: '<?= route_to('save_payment') ?>',
                        data: formData,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        type: "POST",
                        success: (response) => {
                            if (response.error === 1) {
                                this.errors = response.messages
                            }else {
                                SwalAlert.fire({
                                    icon: "success",
                                    title: response.message,
                                });
                                this.getListBankConfig();
                            }
                        }
                    });
                    if (this.edit) {
                        $('#edit-payment-modal').modal('toggle')
                        this.edit = false
                    }
                    this.errors = {}
                },
                onEdit(index) {
                    $('#edit-payment-modal').modal('toggle')
                    this.formBank.bank = this.bank_configs[index].bank
                    this.formBank.account_number = this.bank_configs[index].account_number
                    this.formBank.name = this.bank_configs[index].name
                    this.formBank.branch = this.bank_configs[index].branch

                    this.edit = true
                },
                onChangeBank() {
                    this.formBank.account_number = '';
                    this.formBank.name = '';
                    this.formBank.branch = '';

                    const isBank = (element) =>  {
                        return element.bank == this.formBank.bank;
                    }
                    let index = this.bank_configs.findIndex(isBank);
                    if (index != -1) {
                        this.formBank.account_number = this.bank_configs[index].account_number
                        this.formBank.name = this.bank_configs[index].name
                        this.formBank.branch = this.bank_configs[index].branch
                    }
                },
                onDelete(bank) {
                    Swal.fire({
                        title: "Bạn có chắc chắn muốn xóa item này ??",
                        showDenyButton: false,
                        showCancelButton: true,
                        confirmButtonText: 'Xóa',
                        cancelButtonText: 'Hủy',
                        cancelButtonColor: "#20c997",
                        confirmButtonColor: "#DD6B55",
                    }).then((result) => {
                        /* Delete item */
                        if (result.isConfirmed) {
                            let formData = new FormData();

                            for (const [key, _value] of Object.entries(this.formBank)) {
                                formData.append(key, this.formBank[key]);
                            }
                            let csName = $("#csname").val();
                            let csToken = $("#cstoken").val();

                            // add a sign token
                            formData.append(csName, csToken);

                            $.ajax({
                                url: '/acp/payment-config/remove/' + bank,
                                data: formData,
                                dataType: "json",
                                contentType: false,
                                processData: false,
                                type: "POST",
                                success: (response) => {
                                    if (response.error === 1) {
                                        this.showError(response.message);
                                    } else {
                                        SwalAlert.fire({
                                            icon: "success",
                                            title: response.message,
                                        });
                                        this.getListBankConfig();
                                    }
                                }
                            });
                        }
                    })
                },
                showError(mess) {
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Lỗi...',
                        autohide: true,
                        delay: 3000,
                        body: mess
                    });
                }
            },
        });
        app.mount('#payment-app');
    </script>
<?= $this->endSection() ?>