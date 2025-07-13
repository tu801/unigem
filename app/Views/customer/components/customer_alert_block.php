<?php if (session()->has('message')) : ?>
    <div class="alert alert-success mb_60">
        <?= esc(session('message')) ?>
    </div>
<?php endif; ?>
<?php if (session()->has('error')) : ?>
    <div class="alert alert-danger mb_60">
        <?= esc(session('error')) ?>
    </div>
<?php endif; ?>
<?php if (session()->has('errors')) : ?>
    <div class="alert alert-danger mb_60">
        <?php
        foreach (session('errors') as $error) {
            echo esc($error) . '<br>';
        }
        ?>
    </div>
<?php endif; ?>