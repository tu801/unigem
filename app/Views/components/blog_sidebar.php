<aside class="tf-section-sidebar wrap-sidebar-mobile">
    <?php 
    echo view_cell('\App\Cells\Widgets\CategoryListCell', null, $configs->viewCellCacheTtl, 'post_sidebar_category_list_cell_'.$currentLang->locale);

    echo view_cell('\App\Cells\Widgets\NewPostListCell', null, $configs->viewCellCacheTtl, 'post_sidebar_new_post_list_cell'.$currentLang->locale);
     
    if ( isset($post->tags) && !empty($post->tags)) {
        echo view_cell('\App\Cells\Widgets\TagsListCell', ['postTags' => $post->tags], $configs->viewCellCacheTtl, 'post_sidebar_new_tags_list_cell'.$currentLang->locale);
    }
    ?>
    

    <!-- <div class="sidebar-item sidebar-instagram">
        <div class="sidebar-title">Instagram</div>
        <div class="sidebar-content">
            <ul>
                <li>
                    <img src="images/shop/file/img-1.jpg" alt="">
                </li>
                <li>
                    <img src="images/shop/file/img-2.jpg" alt="">
                </li>
                <li>
                    <img src="images/shop/file/img-3.jpg" alt="">
                </li>
                <li>
                    <img src="images/shop/file/img-4.jpg" alt="">
                </li>
                <li>
                    <img src="images/shop/file/img-5.jpg" alt="">
                </li>
                <li>
                    <img src="images/shop/file/img-6.png" alt="">
                </li>

            </ul>
        </div>
    </div> -->
</aside>