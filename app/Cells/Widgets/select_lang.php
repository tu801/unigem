<div class="tf-languages">
    <select class="image-select center style-default type-languages">
        <?php foreach ($langList as $lang):?>
        <option data-href="/lang/<?=$lang->locale?>" <?=$lang->id == $currentLang->id ? 'selected' : ''?> data-thumbnail="<?=$lang->icon?>" >
            <?=$lang->name?>
        </option>
        <?php endforeach;?>
    </select>
</div>