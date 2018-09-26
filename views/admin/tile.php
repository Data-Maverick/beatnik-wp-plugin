<h4>Tile <?= $x + 1 ?></h4>
<div style="display: grid; grid-template-columns: auto auto auto;">
    <div class="beatnik_tile" id="beatnik_tile_<?= $x ?>_sponsor" data-tile_id="<?= $x ?>" data-tile_target="sponsor">
        <a id="upload_beatnik_sponsor_logo_<?= $x ?>" class="upload-beatnik_image upload-beatnik_sponsor_logo <?= ( $sponsor["is_set"]  ) ? 'hidden' : '' ?>" href="<?php echo $upload_link ?>">Set Sponsor Logo</a>
        <a id="delete_beatnik_sponsor_logo_<?= $x ?>" class="delete-beatnik_image delete-beatnik_sponsor_logo <?= ( $sponsor["is_set"]  ) ? '' : 'hidden' ?>" href="#">Remove Sponsor Logo</a>
        <input class="beatnik_sponsor_img_id" name="beatnik_sponsor_img_<?= $x ?>" type="hidden" value="<?= $sponsor[img_id] ?>" />
        <div class="preview-img-container beatnik_sponsor_img_container"><?php
            if ($sponsor["is_set"]) { ?>
                <img style="width: 200px; height: 200px; border: 1px #CCC solid;" src="<?= $sponsor["img_src"][0] ?>" />
            <?php } ?>
        </div>
    </div>
    <div class="beatnik_tile" id="beatnik_tile_<?= $x ?>" data-tile_id="<?= $x ?>_background"  data-tile_target="background">
        <a id="upload_beatnik_background_img_<?= $x ?>" class="upload-beatnik_image upload-beatnik_background_img <?= ( $background["is_set"]  ) ? 'hidden' : '' ?>" href="<?php echo $upload_link ?>">Set Background</a>
        <a id="delete_beatnik_background_img_<?= $x ?>" class="delete-beatnik_image delete-beatnik_background_img <?= ( $background["is_set"]  ) ? '' : 'hidden' ?>" href="#">Remove Background</a>
        <input class="beatnik_background_img_id" name="beatnik_background_img_<?= $x ?>" type="hidden" value="<?= $background[img_id] ?>" />
        <div class="preview-img-container beatnik_background_img_container"><?php
            if ($background["is_set"]) { ?>
                <img src="<?= $background["img_src"][0] ?>" />
            <?php } ?>
        </div>
    </div>
    <div class="beatnik_tile" id="beatnik_tile_<?= $x ?>" data-tile_id="<?= $x ?>_blurb"  data-tile_target="blurb">
        <a id="upload_beatnik_blurb_img_<?= $x ?>" class="upload-beatnik_image upload-beatnik_blurb_img <?= ( $blurb["is_set"]  ) ? 'hidden' : '' ?>" href="<?php echo $upload_link ?>">Set Blurb</a>
        <a id="delete_beatnik_blurb_img_<?= $x ?>" class="delete-beatnik_image delete-beatnik_blurb_img <?= ( $blurb["is_set"]  ) ? '' : 'hidden' ?>" href="#">Remove Blurb</a>
        <input class="beatnik_blurb_img_id" name="beatnik_blurb_img_<?= $x ?>" type="hidden" value="<?= $blurb[img_id] ?>" />
        <div class="preview-img-container beatnik_blurb_img_container"><?php
            if ($blurb["is_set"]) { ?>
                <img src="<?= $blurb["img_src"][0] ?>" />
            <?php } ?>
        </div>
    </div>
</div>
