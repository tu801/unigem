<?php
$sliders = get_slider_config();
?>

<div class="container-lg home_2_hero_wrp">
    <div class="home_2_hero">
        <div class="container">
            <div class="hero_slider_active">
                <?php foreach ($sliders as $slider): ?>
                    <div class="single_hero_slider">
                        <div class="container">
                            <div class="row align-items-center">
                                <div>
                                    <a href="<?= $slider['slider_url'] ?>">
                                        <img loading="lazy" class="img-fluid"  src="<?=base_url($slider['image']['full_image']) ?>" alt="phone">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
