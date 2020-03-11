<?php
?>
<!-- Year List Widget -->
<div>
    <div style="width:100%; display: inline-block;">
        <?php
        // Month List Settings
        $sLastMonth = '';
        $bFirstTitle = true;
        $iBaseWidth = 224;
        if($aSettings['yearlist_image_width'] != '100%' && $aSettings['yearlist_show_list_item_image'] == 'yes') {
            $bIsPx = stripos($aSettings['yearlist_image_width'],'px');
            if($bIsPx === false) {

            } else {
                $iBaseWidth = $iBaseWidth + (int)str_replace(['px'],[''],$aSettings['yearlist_image_width']);
            }
            $iBaseWidth = $iBaseWidth + $aSettings['yearlist_image_margin']['right'] + $aSettings['yearlist_image_margin']['left'];
        }
        // Loop Events
        foreach ($aEvents as $oEvent) {
            // check if we are on next month
            $sNewMonth = strftime('%B %Y', strtotime($oEvent->date_start));
            if ($sLastMonth != $sNewMonth) {
                $sLastMonth = $sNewMonth;
                if(!$bFirstTitle) {
                    echo '<div style="width:100%; height:20px; float:left;"></div>';
                }
                echo '<h3 class="plc-calendar-list-month"">' . utf8_encode($sNewMonth) . '</h3><hr style="margin:-8px 0 7px 0;"/>';
                $bFirstTitle = false;
            }

            // Event Ticket Plugin Checks
            $bFullyBooked = false;
            $sAppendClass = '';
            if ($oEvent->tickets[0]->slots_free == 0) {
                $bFullyBooked = true;
                //sAppendClass .= ' plc-shop-event-fully-booked';
                if($aSettings['yearlist_general_mode'] == 'onlybookable') {
                    continue;
                }
            }
            ?>
            <!-- Event -->
            <div class="<?= $sAppendClass ?> plc-calendar-list-item" style="width:100%; padding:0; float:left;">
                <div style="display: inline-block; width:100%; overflow-y:hidden;">
                    <?php if($aSettings['yearlist_show_list_item_image'] == 'yes') { ?>
                    <a class="event-load-info-modal" href="#<?= $oEvent->id ?>" title="Mehr Informationen zu diesem Event">
                        <div class="plc-calendar-list-img"
                             style="background:url(<?= $sHost ?><?= $oEvent->featured_image ?>) no-repeat 100% 50%; background-size:cover; width:<?=$aSettings['yearlist_image_width']?>; float:left;">
                            &nbsp;
                        </div>
                    </a>
                    <?php } ?>
                    <a href="<?=$sServerURL?>/calendar/event/getics/<?=$oEvent->id?>" title="Im Kalender speichern">
                        <div class="plc-calendar-list-date" style="width:<?=$aSettings['yearlist_date_width']?>; float:left; padding-top:6px;">
                            <span class="date-weekday"><?= strftime('%A', strtotime($oEvent->date_start)) ?></span>
                            <span class="date-day"><?= utf8_encode(strftime('%d.', strtotime($oEvent->date_start))) ?></span>
                            <span class="date-month"><?= utf8_encode(strftime('%b', strtotime($oEvent->date_start))) ?></span>
                        </div>
                    </a>
                    <div class="plc-calendar-list-describe" style="width:<?=str_replace(['##OTHERWIDTH##'],[$iBaseWidth.'px'],$aSettings['yearlist_title_width'])?>; float:left;">
                        <a class="event-load-info-modal" href="#<?= $oEvent->id ?>" title="Mehr Informationen zu diesem Event">
                            <h4 class="plc-calendar-list-title"><?= $oEvent->label ?></h4>
                        </a>
                    </div>
                    <div class="plc-calendar-list-buttons" style="float:left; display: inline-block; width:110px;">
                        <a href="#<?= $oEvent->id ?>" class="plc-event-show-popup plc-slider-button" style="display: inline-block; width:100%; margin-bottom:1px;">
                            <i class="<?=$aSettings['btn3_selected_icon']['value']?>" aria-hidden="true"></i>
                            &nbsp;<?=$aSettings['btn3_text']?>
                        </a>
                        <?php if($aSettings['yearlist_general_mode'] == 'all' || $aSettings['yearlist_general_mode'] == 'onlybookable') { ?>
                            <?php if(isset($oEvent->tickets)) { ?>
                                <?php if ($oEvent->tickets[0]->slots_free > 0) { ?>
                                    <a href="#<?= $oEvent->id ?>" class="plc-shop-additem-tobasket plc-slider-button" style="display: inline-block; width:100%; margin-bottom:1px;">
                                        <?php if(isset($aSettings['btn1_selected_icon']['value'])) { ?>
                                            <i class="<?=$aSettings['btn1_selected_icon']['value']?>" aria-hidden="true"></i>
                                        <?php } ?>
                                        &nbsp;<?=$aSettings['btn1_text']?>
                                    </a>
                                <?php } else { ?>
                                    <a href="#<?= $oEvent->id ?>" class="plc-event-show-popup plc-slider-button" style="display: inline-block; width:100%; margin-bottom:1px;">
                                        Ausgebucht
                                    </a>
                                <?php } ?>
                                <?php if ($oEvent->tickets[0]->slots_free > 0) { ?>
                                    <a href="#<?= $oEvent->id ?>" class="plc-shop-giftitem-tobasket plc-slider-button" style="display: inline-block; width:100%;">
                                        <?php if(isset($aSettings['btn2_selected_icon']['value'])) { ?>
                                            <i class="<?=$aSettings['btn2_selected_icon']['value']?>" aria-hidden="true"></i>
                                        <?php } ?>
                                        &nbsp;<?=$aSettings['btn2_text']?>
                                    </a>
                                <?php } else { ?>
                                    <a href="#<?= $oEvent->id ?>" class="plc-event-show-popup plc-slider-button" style="display: inline-block; width:100%;">
                                        Ausgebucht
                                    </a>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- Event -->
            <?php
        }
        ?>
    </div>
</div>
<!-- Year List Widget -->