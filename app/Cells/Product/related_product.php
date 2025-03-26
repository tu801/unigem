<section class="new_arrive section_padding_b">
    <div class="container">
        <div class="d-flex align-items-start justify-content-between">
            <h2 class="section_title_2"><?=lang('Home.related_product')?></h2>
        </div>
        <div class="row gy-4">
            <?php if ( count($products) ) :
                foreach ($products as $item) :?>
            <div class="col-lg-3 col-sm-6">
                <?=view('components/product_single_new_arrive', ['product' => $item, 'configs' => $configs])?>
            </div>
            <?php endforeach; endif; ?>

        </div>
    </div>
</section>