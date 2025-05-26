<div class="modal modalCentered fade modalDemo tf-product-modal modal-part-content" id="share_social">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="header">
                <div class="demo-title"><?=lang('Product.social_share')?></div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="overflow-y-auto">
                <ul class="tf-social-icon d-flex gap-10">
                    <li>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode($shareUrl)?>" rel="nofollow" target="_blank" class="box-icon social-facebook bg_line">
                            <i class="icon icon-fb"></i>
                        </a>
                    </li>
                    <li><a href="https://api.whatsapp.com/send?text=<?=urlencode($shareUrl)?>" rel="nofollow" target="_blank" class="box-icon social-whatsapp bg_line">
                            <i class="icon icon-whatsapp"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://button-share.zalo.me/share_external?layout=1&color=blue&customize=false&d=eyJ1cmwiOiJodHRwczovL3VuaWdlbS52bi9zYW4tcGhhbS9kYS1xdXktdGhpZW4tbmhpZW4tbW9yZ2FuaXRlLTQtNmN0In0%253D&shareType=0" rel="nofollow" target="_blank" class="box-icon social-zalo bg_line">
                            <span class="icon-zalo"></span>
                        </a>
                    </li>

                    <li><a href="#" class="box-icon social-twiter bg_line"><i class="icon icon-Icon-x"></i></a></li>
                    <!-- <li><a href="#" class="box-icon social-instagram bg_line"><i class="icon icon-instagram"></i></a></li> -->
                    <!-- <li><a href="#" class="box-icon social-tiktok bg_line"><i class="icon icon-tiktok"></i></a></li> -->
                    <li><a href="#" class="box-icon social-pinterest bg_line"><i class="icon icon-pinterest-1"></i></a></li>
                </ul>
                <form class="form-share"  method="post" accept-charset="utf-8">
                    <fieldset>
                        <input type="text" value="<?=$shareUrl?>" tabindex="0" aria-required="true">
                    </fieldset>
                    <div class="button-submit">
                        <button class="tf-btn btn-sm radius-3 btn-fill btn-icon animate-hover-btn" type="button">Copy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>