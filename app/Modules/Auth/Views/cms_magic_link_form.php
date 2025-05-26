<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>
<?=getenv('App.site_name')?> - <?= lang('Auth.useMagicLink') ?> 
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<div class="card">
    <div class="card-body login-card-body">
        <h4 class="login-box-msg"><?= lang('Auth.useMagicLink') ?></h4>
        <?= view('Modules\Auth\Views\error_message_block') ?>

        <form action="<?= url_to('magic-link') ?>" method="post">
            <?= csrf_field() ?>

            <!-- Email -->
            <div class="form-group mb-3">
                <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
                <div class="input-group mb-3">
                <input  type="email" class="form-control" 
                        id="floatingEmailInput" name="email" 
                        autocomplete="email" 
                        placeholder="<?= lang('Auth.email') ?>"
                        value="<?= old('email', auth()->user()->email ?? null) ?>" required>   
                    <div class="input-group-append">    
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>                 
            </div>

            <div class="row">
                <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.send') ?></button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <p class="mt-3 mb-1">
            <a href="<?= url_to('login') ?>"><?= lang('Auth.backToLogin') ?></a>
        </p>
        
    </div>
    <!-- /.login-card-body -->
</div>

<?= $this->endSection() ?>
