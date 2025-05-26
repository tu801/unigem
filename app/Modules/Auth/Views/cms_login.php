<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title')?>
<?=getenv('App.site_name')?> - CMS Login
<?= $this->endSection()?>

<?= $this->section('main') ?>
<div class="login-box">
    
    <div class="card">
        <div class="card-body login-card-body">
            <h4 class="login-box-msg"><?=lang('Common.cmsLoginWelcomeText')?></h4>
            <?= view('Modules\Auth\Views\error_message_block') ?>

            <form action="<?= url_to('login') ?>" method="post">
                <?= csrf_field() ?>
                <!-- Username -->
                <div class="input-group mb-3">
                    <input  type="text" class="form-control" name="username" 
                            inputmode="username" autocomplete="username" 
                            placeholder="<?= lang('Auth.username') ?>" 
                            value="<?= old('username') ?>" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="input-group mb-3">
                    <input  type="password" class="form-control" 
                            name="password" inputmode="text" 
                            autocomplete="current-password" 
                            placeholder="<?= lang('Auth.password') ?>" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <!-- Remember me -->
                        <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember" <?php if (old('remember')): ?> checked<?php endif ?>>
                                <label for="remember">
                                    <?= lang('Auth.rememberMe') ?>
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <div class="social-auth-links text-center mb-3">
                <p>- OR -</p>
            </div>
            
            <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                <p class=" mb-1"><?= lang('Auth.forgotPassword') ?> <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a></p>
            <?php endif ?>
            
            <?php if (setting('Auth.allowRegistration')) : ?>
                <p class="mb-0"><?= lang('Auth.needAccount') ?> <a href="<?= url_to('register') ?>"><?= lang('Auth.register') ?></a></p>
            <?php endif ?>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<?= $this->endSection() ?>
