<div class="col-xs-12 col-sm-6 col-md-4 beatnik-fp-threecolumn">
    <div class="feed_item three-col" data-object-id="<?= $postId; ?>" style="background-image: url('<?= $beatnik_images->background ?>')">
        <a class="feed_link" href="<?= $postUrl; ?>">
            <img class="beatnik-logo" src="<?= $beatnikLogo ?>">
        </a>
        <a class="feed_link" href="<?= $postUrl; ?>">
            <img class="beatnik-blurb" src="<?= $beatnik_images->blurb ?>">
        </a>
        <a class="feed_link" href="<?= $postUrl; ?>">
            <div class="beatnik_sponsor_img_container">
                <img class="beatnik-sponsor" src="<?= $beatnik_images->sponsor ?>" alt="<?= $title; ?>">
            </div>
        </a>
    </div>
    <div class="feed_item three-col visible-sm" data-object-id="<?= $postId; ?>" style="background-image: url('<?= $images[0]->background ?>');">
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
