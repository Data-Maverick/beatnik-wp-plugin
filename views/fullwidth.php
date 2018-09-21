<div class="col-xs-12 col-sm-6 col-md-12 beatnik-fp-fullwidth">
    <div class="feed_item feed_fullwidth hidden-sm hidden-xs" data-object-id="<?= $postId; ?>" style="background-image: url('<?= $beatnik_images->background ?>');">
        <a class="feed_link" href="<?= $postUrl; ?>">
            <img class="beatnik-logo" src="<?= $beatnikLogo ?>" alt="">
        </a>
        <a class="feed_link" href="<?= $postUrl; ?>">
            <img class="beatnik-blurb" src="<?= $beatnik_images->blurb ?>" alt="">
        </a>
        <a class="feed_link" href="<?= $postUrl; ?>">
            <div class="beatnik_sponsor_img_container">
                <img class="beatnik-sponsor" src="<?= $beatnik_images->sponsor ?>" alt="<?= $title; ?>">
            </div>
        </a>
    </div>

    <div class="feed_item feed_fullwidth visible-sm visible-xs" data-object-id="<?= $postId; ?>" style="background-image: url('<?= $images[0]->background ?>');">
        <a class="feed_link" href="<?= $postUrl; ?>">
            <img class="beatnik-logo" src="<?= $beatnikLogo ?>" alt="">
        </a>
        <a class="feed_link" href="<?= $postUrl; ?>">
            <img class="beatnik-blurb" src="<?= $images[0]->blurb ?>" alt="">
        </a>
        <a class="feed_link" href="<?= $postUrl; ?>">
            <div class="beatnik_sponsor_img_container">
                <img class="beatnik-sponsor" src="<?= $images[0]->sponsor ?>" alt="<?= $title; ?>">
            </div>
        </a>
    </div>
</div>
