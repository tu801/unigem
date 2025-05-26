<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.useMagicLink') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <h5 class="card-title mb-5"><?= lang('Auth.useMagicLink') ?></h5>

            <p><b><?= lang('Auth.checkYourEmail') ?></b></p>

            <p><?= lang('Auth.magicLinkDetails', [setting('Auth.magicLinkLifetime') / 60]) ?></p>
            
            <p class="mt-3 mb-1">
                <a href="<?=route_to('login')?>"><?= lang('Auth.backToLogin') ?></a>
            </p>
            
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<?= $this->endSection() ?>
