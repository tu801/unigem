<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 10/28/2023
 */
?>
<script type="text/x-template" id="v-edit-modal-tmpl">
    <transition name="modal">
        <div class="modal fade modal-attach" id="vShippingModal" ref="shippingModal"  @click.self="closeModal">
            <div class="modal-dialog" style="width: 40%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?=lang('Shop.edit_shipping_config')?></h4>
                        <button type="button" class="close" data-dismiss="modal" @click="closeModal">
                            <span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger fade show" role="alert" v-if="errors.length">
                            <ul class = "list-unstyled">
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group row">
                            <label for="postInputTitle" class="col-sm-3 col-form-label"><?=lang('Acp.province')?></label>
                            <div class="col-sm-9">
                                {{ config.province_name }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="postInputTitle" class="col-sm-3 col-form-label"><?=lang('Shop.shipping_config_fee')?></label>
                            <div class="col-sm-9">
                                <input type="text" name="description" class="form-control" v-model="config.shipping_fee">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" @click.prevent="onSubmit" class="btn btn-primary float-right">Lưu</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</script>

<script>
    const editModal = {
        template: "#v-edit-modal-tmpl",
        props: [
            "refsItem",
            "active",
        ],
        data() {
            return {
                config: {
                    config_id: 0,
                    province_id: 0,
                    shipping_fee: null,
                    province_name: ''
                },
                errors: [],
            };
        },
        methods: {
            onSubmit() {
                this.errors = [];
                if (this.config.shipping_fee === null || this.config.shipping_fee === '') {
                    this.errors.push("Vui lòng nhập phí giao hàng");
                }

                if ( this.errors.length > 0 ) return;

                let csName = $("#csname").val();
                let csToken = $("#cstoken").val();
                let url = bkUrl + "/shop/shipping-config/province/edit/"+this.config.config_id;

                let formData = new FormData();
                formData.append('province_id', this.config.province_id);
                formData.append('shipping_fee', this.config.shipping_fee.replace(/,/g, ''));
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
                            $(document).Toasts('create', {
                                class: 'bg-danger',
                                title: 'Lỗi...',
                                autohide: true,
                                delay: 3000,
                                body: response.message
                            });
                        } else {
                            this.$emit("on-success", response);
                            this.clearForm();
                        }
                    },
                    error: (_xhr, _status, _error) => {
                        this.showError("Có lỗi xảy ra! Vui lòng làm mới trình duyệt và thử lại sau")
                    },
                });

            },
            clearForm() {
                this.config = {
                    config_id: 0,
                    province_id: 0,
                    shipping_fee: null,
                    province_name: ''
                };
            },
            closeModal() {
                this.$emit("close-modal");
                this.clearForm();
            }
        },
        watch: {
            refsItem(newVal) {
                if ( newVal !== undefined ) {
                    this.config.config_id = newVal.id;
                    this.config.province_id = newVal.title;
                    this.config.shipping_fee = newVal.value;
                    this.config.province_name = newVal.province_name;
                }

            },
        },
    };
</script>
