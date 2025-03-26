<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="container" style="margin-top: 5%">
    <div class="row">
        <div class="col-sm-6 offset-sm-3">

            <div class="card">
                <h2 class="card-header"><?=lang('Auth.resetYourPassword')?></h2>
                <div class="card-body">

                    <?= view($config->views['messageBlock']) ?>

                    <p><?=lang('Auth.forceChangePass')?></p>

                    <form action="<?= route_to('change-password') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="email"><?=lang('Auth.email')?></label>
                            <input type="email" class="form-control"
                                   name="email" aria-describedby="emailHelp" disabled placeholder="<?=lang('Auth.email')?>" value="<?= $userData->email ?>">
                        </div>

                        <br>

                        <div class="form-group">
                            <label for="password"><?=lang('Auth.newPassword')?></label>
                            <input type="password" class="form-control <?php if(session('errors.password')) : ?>is-invalid<?php endif ?>"
                                   name="password">
                            <div class="invalid-feedback">
                                <?= session('errors.password') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pass_confirm"><?=lang('Auth.newPasswordRepeat')?></label>
                            <input type="password" class="form-control <?php if(session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>"
                                   name="pass_confirm">
                            <div class="invalid-feedback">
                                <?= session('errors.pass_confirm') ?>
                            </div>
                        </div>

                        <br>
                        <input type="hidden" name="username" value="<?=$userData->username?>">
                        <button type="submit" class="btn btn-primary btn-block"><?=lang('Auth.resetPassword')?></button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
