<?php if (session()->has('message')) : ?>
    <div class="alert alert-success">
        <?= esc(session('message')) ?>
    </div>
<?php endif; ?>

<?php if (session('errors')) : ?>
    <?php if (is_array(session('errors'))) : ?>
        <ul class="alert alert-danger alert-dismissible text-danger">
            <?php foreach (session('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    <?php else : ?>
        <div class="alert alert-danger"><?= esc(session('errors')) ?></div>
    <?php endif ?>
<?php endif ?>