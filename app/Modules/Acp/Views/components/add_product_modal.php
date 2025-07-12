<div class="modal fade" id="add-product-modal" tabindex="-1" aria-labelledby="add-product-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-product-modal"><?= lang('Order.search_product') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" @keyup="eventSearchProduct()" class="form-control"
                            v-model="product_keyword_search" placeholder="<?= lang('Order.search_product') ?>">
                        <div class="input-group-append">
                            <button name="search" @click="searchProduct()" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            v-for="(item, index) in products_search">
                            {{ item.pd_name }} - {{ getDisplayPrice(item) }}
                            <button type="button" class="btn btn-primary btn-sm" @click="addProduct(index)"> <i
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