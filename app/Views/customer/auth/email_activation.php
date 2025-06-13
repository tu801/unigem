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
                <?php if (session('error')) : ?>
                    <div class="alert alert-danger"><?= session('error') ?></div>
                <?php endif ?>

                <p><?= lang('Auth.emailActivateBody') ?></p><br>

                <form action="<?= url_to('cus_activate_account_verify') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Code -->
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="floatingTokenInput" name="token"
                            placeholder="000000" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code"
                            value="<?= old('token') ?>" required>
                        <label for="floatingTokenInput"><?= lang('Auth.token') ?></label>
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