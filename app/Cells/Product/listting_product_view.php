<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/7/2023
 */

foreach ($products as $item):
?>
    <div class="col-lg-3 col-sm-6">
        <?=view('components/product_single_new_arrive', ['product' => $item, 'configs' => $configs])?>
    </div>
<?php endforeach; ?>
