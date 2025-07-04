<div class="modal fade" id="customer-modal" tabindex="-1" aria-labelledby="customer-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-product-modal"><?= lang('Order.search_customer') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" @keyup="eventSearchCustomer()" class="form-control"
                            v-model="customer_keyword_search" placeholder="<?= lang('Order.search_customer') ?>">
                        <div class="input-group-append">
                            <button name="search" @click="searchCustomer()" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <ul class="list-group" v-if="customers_search.length > 0">
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            v-for="(item, index) in customers_search">
                            {{ item.cus_full_name }} - {{ item.cus_phone }}
                            <button type="button" class="btn btn-primary btn-sm" @click="selectCustomer(index)"> <i
                                    class="fa fa-plus text"></i> </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>