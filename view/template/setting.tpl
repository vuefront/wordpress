<div class="module-content">
    <div class="text-center module-content__image">
        <img src="<?php echo $logo; ?>"/>
    </div>
    <div class="module-content__form">
        <div class="module-content__row">
            <h3 class="text-center mb-3"><?php echo $text_title; ?></h3>
            <div class="input-group mb-2">
                <input id="connect_url" class="form-control" type="text" value="<?php echo $catalog; ?>" readonly>
                <div class="input-group-append">
                    <span class="btn btn-primary clipboard" data-clipboard-target="#connect_url"><?php echo $text_copy; ?></span>
                </div>
            </div>
            <p class="module-content__description"><?php echo $text_description; ?></p>
        </div>
        <hr/>
        <div class="module-content__row">
            <h3 class="text-center mb-3"><?php echo $text_woocommerce_plugin; ?></h3>
            <div class="text-center">
            <?php if($woocommerce) { ?>
                <span class="btn btn-success"><?php echo $text_woocommerce_enabled; ?></span>
                <p class="module-content__description"><?php echo $text_woocommerce_description; ?></p>
            <?php } else { ?>
                <a class="btn btn-danger" href="https://ru.wordpress.org/plugins/woocommerce/" target="_blank"><?php echo $text_woocommerce_disabled; ?></a>
                <p class="module-content__description"><?php echo $text_woocommerce_description; ?></p>
            <?php } ?>
            </div>
        </div>
    </div>
</div>
<script lang="text/javascript">
jQuery(document).ready(function() {
    var clipboard = new ClipboardJS('.clipboard')

    clipboard.on('success', function(e) {
        jQuery(e.trigger).text('<?php echo $text_copied; ?>')
        jQuery(e.trigger).addClass('btn-success')

        e.clearSelection()
    })
})
</script>