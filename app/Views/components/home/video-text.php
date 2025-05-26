<section class="flat-spacing-9">
    <div class="container">
        <div class="tf-grid-layout md-col-2 tf-img-video-text">
            <div class="content-wrap bg_orange radius-20">
                <div class="heading text-white wow fadeInUp" data-wow-delay="0s"><?=get_theme_config('design_title')?></div>
                <p class="text-white fs-16 wow fadeInUp" data-wow-delay="0s"><?=get_theme_config('design_sub_title')?></p>
                <ul>
                    <li>
                        <div class="number text-white">1</div>
                        <div class="text text-white"><?=get_theme_config('design_step_1')?></div>
                    </li>
                    <li>
                        <div class="number text-white">2</div>
                        <div class="text text-white"><?=get_theme_config('design_step_2')?></div>
                    </li>
                    <li>
                        <div class="number text-white">3</div>
                        <div class="text text-white"><?=get_theme_config('design_step_3')?></div>
                    </li>
                    <li>
                        <div class="number text-white">4</div>
                        <div class="text text-white"><?=get_theme_config('design_step_4')?></div>
                    </li>
                </ul>
            </div>
            <div class="video-wrap">
                <?php
                    $video = get_theme_config('design_intro_video_url');
                    
                    function isYoutubeUrl($url) {
                        return preg_match('/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/', $url);
                    }
                    
                    if (!empty($video)) {
                        if (isYoutubeUrl($video)) {
                            // Extract video ID from YouTube URL
                            preg_match('/^.*(youtu.be\/|v\/|\/u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/', $video, $matches);
                            $videoId = $matches[2];
                            ?>
                            <div class="video-responsive">
                                <iframe
                                    src="https://www.youtube.com/embed/<?= $videoId ?>" 
                                    class="radius-20"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                            <?php
                        } else {
                            // Assume it's an MP4 file in uploads folder
                            ?>
                            <video class="radius-20" src="<?= $video ?>" playsinline metadata controls></video>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</section>
