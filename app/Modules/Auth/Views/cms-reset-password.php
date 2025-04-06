<?= $this->extend(config('Auth')->views['layout']) ?>
<?= $this->section('main') ?>

<div style="width: 500px;">
    <div class="login-logo">
        <a href="<?=base_url()?>">
            <img src="<?=base_url()?>/themes/unigem/unigem-logo.png" alt="Fox CMS" width="200px" height="200px" class="img-fluid rounded mx-auto d-block">
        </a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <h4 class="login-box-msg"><?=lang('Common.cmsFirstTimeChangePassText')?></h4>
            <?php if (session('error') !== null) : ?>
                <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
            <?php elseif (session('errors') !== null) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php if (is_array(session('errors'))) : ?>
                        <?php foreach (session('errors') as $error) : ?>
                            <?= $error ?>
                            <br>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?= session('errors') ?>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <?php if (session('message') !== null) : ?>
                <div class="alert alert-success" role="alert"><?= session('message') ?></div>
            <?php endif ?>

            <form action="<?= url_to('reset-password') ?>" method="post">
                <!-- Username -->
                <div class="form-floating mb-3">
                    <label for="floatingUsernameInput"><?= lang('Auth.username') ?></label>
                    <div class="input-group mb-3">
                        <input  type="text" class="form-control" value="<?= $username ?>" disabled>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-floating mb-3">
                    <label for="floatingEmailInput"><?= lang('Auth.email')?></label>
                    <div class="input-group mb-3">
                        <input  type="text" class="form-control" value="<?= $email?>" disabled>
                        <div class="input-group-append">    
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-floating mb-3">
                    <label for="floatingPasswordInput"><?= lang('Auth.password') ?></label>
                    <input type="password" class="form-control" 
                            id="floatingPasswordInput" name="password" 
                            inputmode="text" autocomplete="new-password" 
                            placeholder="<?= lang('Auth.password') ?>" required>
                </div>

                <!-- Password (Again) -->
                <div class="form-floating mb-5">
                    <label for="floatingPasswordConfirmInput"><?= lang('Auth.passwordConfirm') ?></label>
                    <input type="password" class="form-control" 
                            id="floatingPasswordConfirmInput" 
                            name="password_confirm" inputmode="text" 
                            autocomplete="new-password" 
                            placeholder="<?= lang('Auth.passwordConfirm') ?>" required> 
                </div>

                <div class="d-grid col-12 col-md-8 mx-auto m-3">
                        <button type="submit" class="btn btn-primary btn-block"><?= lang('Common.cmsResetPassword') ?></button>
                    </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>

<?= $this->endSection() ?>
