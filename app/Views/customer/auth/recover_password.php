<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="row">
            <div class="col-12">
                <div class="heading text-center"><?= $page_title ?? '' ?></div>
            </div>
        </div>
    </div>
</div>
<!-- /page-title -->

<!-- main page-->
<section class="mt-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
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

                <p><?= lang('Auth.recoverPasswordBody') ?></p><br>

                <?php if ( isset($customer) ) : ?>
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" id="emailInput"  disabled value="<?=$customer->cus_email?>" >
                    <label for="emailInput"><?= lang('Auth.email') ?></label>
                </div>
                <?php endif ?>

                <form action="<?= url_to('cus_recover_password') ?>?token=<?= $token ?>" method="post">
                    <?= csrf_field() ?>
                    <?php if ( isset($customer) ) : ?>
                    <input type="hidden" name="customer" value="<?= $customer->id ?>">
                    <?php endif ?>

                    <!-- Code -->
                    <div class="form-floating mb-2">
                        <input type="password" class="form-control" id="newPasswordInput" name="password"  required>
                        <label for="newPasswordInput"><?= lang('Auth.password') ?></label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="password" class="form-control" id="newPasswordConfirmInput" name="password_confirm"  required>
                        <label for="newPasswordConfirmInput"><?= lang('Auth.passwordConfirm') ?></label>
                    </div>

                    <div class="d-grid col-8 mx-auto m-3">
                        <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.send') ?></button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
<!-- /main page-->

<?= $this->endSection() ?>