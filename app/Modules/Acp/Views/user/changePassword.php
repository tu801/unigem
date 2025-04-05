<?php
echo $this->extend($config->viewLayout);
echo $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <!-- form start -->
        <form id="<?= $module ?>Form" method="post" class="form-horizontal" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="card card-primary">

                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= lang('User.username') ?> </label>
                        <div class="col-sm-10">
                            <input type="test" class="form-control" disabled autocomplete="off"
                                value="<?= $userData->username ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= lang('User.password') ?> </label>
                        <div class="col-sm-10">
                            <input type="password"
                                class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                                name="password" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= lang('User.repassword') ?> </label>
                        <div class="col-sm-10">
                            <input type="password"
                                class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>"
                                name="pass_confirm" autocomplete="off">
                        </div>
                    </div>

                    <?php if ($login_user->inGroup('superadmin', 'admin')) : ?>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-5">
                            <div class="icheck-danger d-inline">
                                <input type="checkbox" checked name="force_pass_reset" value="1"
                                    id="checkboxRequiredChangePass">
                                <label for="checkboxRequiredChangePass"><?= lang('User.requiredChangePass') ?></label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="icheck-danger d-inline">
                                <input type="checkbox" checked name="send_email" value="1" id="checkboxSendMail">
                                <label for="checkboxSendMail"><?= lang('User.sendMail') ?></label>
                            </div>
                        </div>
                    </div>
                    <?php endif ?>

                </div>
            </div>

            <div class="card">
                <div class="card-footer">
                    <div class="col-sm-12 col-sm-offset-2">
                        <button class="btn btn-primary" name="save" type="submit"><?= lang('Acp.save') ?></button>
                        <a href="<?= $cancelUrl ?>" class="btn btn-default" type="reset"><?= lang('Acp.cancel') ?></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>