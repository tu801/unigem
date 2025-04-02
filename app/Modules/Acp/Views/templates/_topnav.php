<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>

        <?php if ( count($language) > 1 ) : ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php
                foreach ($language as $item) {
                    if ( $item->locale == $curLang->locale ) {
                        echo "<img src='{$item->icon}' width='16' title='{$item->name}' alt='{$item->name}'> {$item->name}";
                    }
                }
                ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="langDropdown">
                <?php foreach ($language as $item) : ?>
                    <a class="dropdown-item" href="<?=route_to('setLang', $item->locale)?>">
                        <?="<img src='{$item->icon}' width='16' title='{$item->name}' alt='{$item->name}'> {$item->name}"?>
                    </a>
                <?php endforeach; ?>
            </div>
        </li>
        <?php endif; ?>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?=base_url()?>" class="nav-link">Xem Trang Chủ</a>
        </li>

    </ul>

    <!-- SEARCH FORM -->
    <!--	<form class="form-inline ml-3">-->
    <!--		<div class="input-group input-group-sm">-->
    <!--			<input class="form-control form-control-navbar" type="search" placeholder="--><?//=lang('search')?><!--" aria-label="Search">-->
    <!--			<div class="input-group-append">-->
    <!--				<button class="btn btn-navbar" type="submit">-->
    <!--					<i class="fas fa-search"></i>-->
    <!--				</button>-->
    <!--			</div>-->
    <!--		</div>-->
    <!--	</form>-->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- User Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <?php $avata = $login_user->img_avatar ?>
                <img src="<?=$avata?>" alt="<?=@$login_user->username?>" class="img-size-32 mr-2">
                Chào <?=@$login_user->username?>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header"><?=@$User->fullname?></span>
                <div class="dropdown-divider"></div>
                <a href="<?=base_url('acp/user/profile')?>" class="dropdown-item">
                    <i class="far fa-file-alt mr-1"></i> Profile
                </a>
                <!--				<div class="dropdown-divider"></div>-->
                <!--				<a href="#" class="dropdown-item">-->
                <!--					<i class="fas fa-users mr-2"></i> 8 friend requests-->
                <!--					<span class="float-right text-muted text-sm">12 hours</span>-->
                <!--				</a>-->
                <!--				<div class="dropdown-divider"></div>-->
                <!--				<a href="#" class="dropdown-item">-->
                <!--					<i class="fas fa-file mr-2"></i> 3 new reports-->
                <!--					<span class="float-right text-muted text-sm">2 days</span>-->
                <!--				</a>-->
                <div class="dropdown-divider"></div>
                <a href="<?=base_url('logout')?>" class="dropdown-item dropdown-footer"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </li>

    </ul>
</nav>