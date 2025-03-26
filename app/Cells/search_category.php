<div class="search_category">
    <select name="category[]" class="nice_select" id="searchPdCat">
        <option value="0">All category</option>
        <?php
        if (count($categories)) {
            foreach ($categories as $cat) {
                echo '<option value="'.$cat->id.'">'.$cat->title.'</option>';
            }
        }
        ?>
    </select>
</div>