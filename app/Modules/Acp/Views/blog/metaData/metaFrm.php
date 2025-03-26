<?php

/**
 * @author tmtuan
 * created Date: 30-Aug-20
 */
?>

<div class="ml-3">
    <p class="h5">
    <div class="d-flex justify-content-between align-items-center">
        <b><?= lang('Acp.search_engine_optimize'); ?></b>
        <a class="btn btn-link" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            <?= lang('Acp.edit_seo_meta'); ?>
        </a>
    </div>
    <div style="margin-top:5px;margin-bottom:5px;height:1px;width:100%;border-top:1px solid #ccc;"></div>
    <br />
    <?= lang('Acp.setup_seo_desc'); ?>
    </p>
</div>
<div class="collapse" id="collapseExample">
    <div class="card-body">
        <div class="form-group ">
            <label><?= lang('Post.meta_title') ?></label> - <?= lang('Acp.meta_desc_left'); ?> <span id="title_word_left">70</span>
            <input id="title_word_count" class="form-control" name="seo_title" value="<?= old('seo_title') ?>" />
        </div>
        <div class="form-group ">
            <label><?= lang('Acp.meta_keyword') ?></label>
            <textarea class="form-control" name="seo_keyword"><?= old('seo_keyword') ?></textarea>
        </div>

        <div class="form-group">
            <label><?= lang('Acp.meta_description') ?></label> - <?= lang('Acp.meta_desc_left'); ?> <span id="word_left">300</span>
            <textarea id="word_count" class="form-control" name="seo_description"><?= old('seo_description') ?></textarea>
        </div>
    </div>
</div>