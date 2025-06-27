<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.emailActivateTitle') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

<div class="container d-flex justify-content-center p-5">
    <div class="card col-12 col-md-5 shadow-sm">
        <div class="card-header">
            <h3 class="card-title"><?= lang('Auth.emailActivateTitle') ?></h3>
        </div>
        <div class="card-body">
            <?php if (session('error')) : ?>
                <div class="alert alert-danger mb-2"><?= session('error') ?></div>
            <?php endif ?>

            <div class="text"><?= lang('Auth.emailActivateBody') ?></div>

            <form action="<?= url_to('auth-action-verify') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Code -->
                <div class="form-floating mb-2">
                    <label for="floatingTokenInput"><?= lang('Auth.token') ?></label>
                    <input type="text" class="form-control" id="floatingTokenInput" name="token" placeholder="000000"
                        inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" value="<?= old('token') ?>"
                        required>
                </div>

                <div class="d-grid col-8 mx-auto m-3">
                    <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.send') ?></button>
                </div>

            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>