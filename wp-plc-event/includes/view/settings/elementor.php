<article class="plc-admin-page-elementor plc-admin-page">
    <h1><?=__('Elementor Settings','wp-plc-event')?></h1>
    <p>Here you find all the settings related to elementor</p>

    <!-- Elementor Version -->
    <div class="plc-admin-settings-field">
        Found Elementor, Version <?=(defined('ELEMENTOR_VERSION')) ? ELEMENTOR_VERSION : '(unknown)'?>
    </div>
    <!-- Elementor Version -->

    <h2><?=__('Elementor Widget','wp-plc-event')?></h2>

    <!-- Event Slider Widget -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bArtSliderActive = get_option( 'plcevent_elementor_widget_event_slider_active', false ); ?>
            <input name="plcevent_elementor_widget_event_slider_active" type="checkbox" <?=($bArtSliderActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span><?=__('Event Slider Widget','wp-plc-event')?></span>
    </div>
    <!-- Event Slider Widget -->

    <!-- Year List Widget -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bYearListActive = get_option( 'plcevent_elementor_widget_calendar_year_list_active', false ); ?>
            <input name="plcevent_elementor_widget_calendar_year_list_active" type="checkbox" <?=($bYearListActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span><?=__('Year List Widget','wp-plc-event')?></span>
    </div>
    <!-- Year List Widget -->

    <!-- Compact List Widget -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $blistSmallActive = get_option( 'plcevent_elementor_widget_calendar_list_small_active', false ); ?>
            <input name="plcevent_elementor_widget_calendar_list_small_active" type="checkbox" <?=($blistSmallActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span><?=__('Compact List Widget','wp-plc-event')?></span>
    </div>
    <!-- Compact List Widget -->

    <!-- Save Button -->
    <hr/>
    <button class="plc-admin-settings-save plc-admin-btn plc-admin-btn-primary" plc-admin-page="page-elementor">
        <?=__('Save Elementor Settings','wp-plc-event')?>
    </button>
    <!-- Save Button -->
</article>