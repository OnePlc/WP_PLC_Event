<?php
?>
<div class="plc-admin">
    <div class="plc-settings-wrapper">
        <!-- Header START -->
        <div class="plc-settings-header">
            <div class="plc-settings-header-main">
                <div class="plc-settings-header-col header-col-first">
                    <div class="plc-settings-header-main-title">
                        WP PLC Event <small>Version <?=(defined('WPPLC_EVENT_VERSION')) ? WPPLC_EVENT_VERSION : '(unknown)'?></small>
                    </div>
                </div>
                <div class="plc-settings-header-col header-col-second">
                    <img src="<?=plugins_url('assets/img/icon.png', WPPLC_EVENT_PLUGIN_MAIN_FILE)?>" />
                </div>
                <div class="plc-settings-header-col header-col-third">
                    <a href="https://t.me/OnePlc" target="_blank" title="Telegram Support">
                        <?=__('Need help?','wp-plc-shop')?>
                    </a>
                </div>
            </div>
        </div>
        <!-- Header END -->
        <main class="plc-admin-main">
            <!-- Menu START -->
            <div class="plc-admin-menu-container">
                <nav class="plc-admin-menu" style="width:70%; float:left;">
                    <ul class="plc-admin-menu-list">
                        <li class="plc-admin-menu-list-item">
                            <a href="#/general">
                                <?=__('Settings','wp-plc-shop')?>
                            </a>
                        </li>
                        <?php
                        if(get_option( 'plcevent_elementor_active') == 1) { ?>
                            <li class="plc-admin-menu-list-item">
                                <a href="#/elementor">
                                    <?=__('Elementor','wp-plc-shop')?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
                <div class="plc-admin-alert-container">

                </div>
            </div>
            <!-- Menu END -->

            <!-- Content START -->
            <div class="plc-admin-page-container">
                <?php wp_nonce_field( 'oneplace-settings-update' ); ?>
                <?php
                // Include Settings Pages
                require_once __DIR__.'/settings/general.php';
                if(get_option( 'plcevent_elementor_active') == 1) {
                    require_once __DIR__.'/settings/elementor.php';
                }
                ?>
            </div>
            <!-- Content END -->
        </main>
    </div>
</div>