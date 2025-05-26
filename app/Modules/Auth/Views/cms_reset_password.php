<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title')?>
<?=getenv('App.site_name')?> - CMS Change Password
<?= $this->endSection()?>

<?= $this->section('main') ?>
<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <h4 class="login-box-msg"><?=lang('Common.cmsFirstTimeChangePassText')?></h4>
            <?= view('Modules\Auth\Views\error_message_block') ?>

            <form action="<?= url_to('reset-password') ?>" method="post">
                <?= csrf_field() ?>
                <!-- Username -->
                <div class="form-group mb-3">
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
                <div class="form-group mb-3">
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
                <div class="form-group mb-3">
                    <label for="floatingPasswordInput"><?= lang('Auth.password') ?></label>
                    <input type="password" class="form-control" 
                            id="floatingPasswordInput" name="password" 
                            inputmode="text" autocomplete="new-password" 
                            placeholder="<?= lang('Auth.password') ?>" required>
                </div>

                <!-- Password (Again) -->
                <div class="form-group mb-5">
                    <label for="floatingPasswordConfirmInput"><?= lang('Auth.passwordConfirm') ?></label>
                    <input type="password" class="form-control" 
                            id="floatingPasswordConfirmInput" 
                            name="password_confirm" inputmode="text" 
                            autocomplete="new-password" 
                            placeholder="<?= lang('Auth.passwordConfirm') ?>" required> 
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block"><?= lang('Common.cmsResetPassword') ?></button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <p class="mt-3 mb-1">
                <a href="<?=route_to('login')?>"><?= lang('Auth.backToLogin') ?></a>
            </p>
            
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<?= $this->endSection() ?>
