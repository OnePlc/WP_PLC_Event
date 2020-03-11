<article class="plc-admin-page-general plc-admin-page">
    <h1><?=__('General Settings','wp-plc-event')?></h1>
    <p>Here you find the core settings for the event</p>

    <?php if(is_plugin_active('elementor/elementor.php')) { ?>
    <h3>Elementor Integration</h3>
    <!-- Elementor Integration -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bElementorActive = get_option( 'plcevent_elementor_active', false ); ?>
            <input name="plcevent_elementor_active" type="checkbox" <?=($bElementorActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span><?=__('Enable Elementor Integration','wp-plc-event')?></span>
    </div>
    <!-- Elementor Integration -->
    <?php } ?>

    <h3>Plugin Info</h3>
    <?php if(is_plugin_active('wp-plc-shop/wp-plc-shop.php')) { ?>
        <p style="color:green;">WP PLC Shop found - Ticket Plugin loaded</p>
    <?php } ?>

    <!-- Save Button -->
    <hr/>
    <button class="plc-admin-settings-save plc-admin-btn plc-admin-btn-primary" plc-admin-page="page-general">Save General Settings</button>
    <!-- Save Button -->
</article>