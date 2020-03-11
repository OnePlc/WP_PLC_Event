<div style="width:100%; display: inline-block;">
    <?php
    $sLastMonth = '';
    $iCount = 0;
    $iBaseWidth = 150;
    $iBaseWidth = $iBaseWidth+$aSettings['compactlist_title_margin']['right']+$aSettings['compactlist_title_margin']['left'];
    $iBaseWidth = $iBaseWidth+$aSettings['compactlist_date_margin']['right']+$aSettings['compactlist_date_margin']['left'];

    foreach ($aEvents as $oEvent) {
        if ($iCount == $aSettings['compactlist_list_limit']) {
            break;
        }
        ?>
        <div class="plc-calendar-list-item" style="width:100%; float:left; height:70px;">
            <div style="display: inline-block; width:100%;">
                <div class="plc-calendar-list-date"
                     style="float:left; height:70px; text-align:center; vertical-align:middle; width:70px; display: inline-block;">
                    <span style="font-size:10px !important; font-weight:normal !important; float:left; width:100%; padding:0; text-transform: uppercase; margin:2px 0 0 0;"><?= strftime('%A', strtotime($oEvent->date_start)) ?></span>
                    <span style="font-size:32px !important;font-weight:bold; float:left; margin-top:-9px; width:100%; padding:0;"><?= utf8_encode(strftime('%d.', strtotime($oEvent->date_start))) ?></span>
                    <span style="float:left; width:100%; margin-top:-13px; font-weight:bold;  font-size:18px;"><?= utf8_encode(strftime('%b', strtotime($oEvent->date_start))) ?></span>
                </div>
                <div class="plc-calendar-list-widget-title" style="width:calc(100% - <?=$iBaseWidth?>px); float:left;">
                    <a href="#"
                       style="display:inline-block; padding:8px; float:left; width:100%; height:60px; vertical-align: middle;">
                        <?= $oEvent->label ?>
                    </a>
                </div>
                <div style="float:left; width:70px; height:70px; display: inline-block;">
                    <a class="event-load-info-modal plc-calendar-widget-button"
                       href="#<?= $oEvent->id ?>"
                       style="float:left; width:30px; height:30px;  padding-top:4px; text-align:center;">
                        <i class="<?= $aSettings['btn_more_selected_icon']['value'] ?>" aria-hidden="true"></i>
                    </a>
                    <a class="plc-shop-event-request plc-calendar-widget-button" href="#<?= $oEvent->id ?>"
                       style="float:left; width:30px; height:30px; padding-top:4px; text-align:center;">
                        <i class="<?= $aSettings['btn_request_selected_icon']['value'] ?>" aria-hidden="true"></i>
                    </a>
                    <?php if(isset($oEvent->tickets)) { ?>
                    <a class="plc-shop-additem-tobasket plc-calendar-widget-button"
                       href="#<?= $oEvent->tickets[0]->id ?>-<?= $oEvent->id ?>"
                       style="padding-top:4px;float:left; width:30px; height:31px; text-align:center;">
                        <i class="<?= $aSettings['btn1_selected_icon']['value'] ?>" aria-hidden="true"></i>
                    </a>
                    <a class="plc-shop-giftitem-tobasket plc-calendar-widget-button"
                       href="#<?= $oEvent->tickets[0]->id ?>-<?= $oEvent->id ?>"
                       style="padding-top:4px; height:31px; float:left; width:30px; text-align:center;">
                        <i class="<?= $aSettings['btn2_selected_icon']['value'] ?>" aria-hidden="true"></i>
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
        $iCount++;
    }
    ?>
</div>