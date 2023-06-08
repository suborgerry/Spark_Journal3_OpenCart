<?VerifyAccess_x('main.manufacturers.templ'); ?>
<div class="container" style="background-color: #f7f7f7;margin: 30px 0" >
<h1 class="page-heading">CAR BRANDS</h1>
</div>
<div class="container">
<div class="page-content">
    <div class="sidebar_mobile_wrapper">
        <div class="item item-iconSidebar text-left">
            <div class="sidebar_mobile">
                <span class="text">sidebar:</span>
                <svg viewBox="0 0 48 48" id="sidebar" class="icon">
                    <g>
                        <path d="M6,48c-0.552,0-1-0.447-1-1v-7c0-0.553,0.448-1,1-1s1,0.447,1,1v7C7,47.553,6.552,48,6,48z"></path>
                        <path d="M6,31c-0.552,0-1-0.447-1-1V1c0-0.553,0.448-1,1-1s1,0.447,1,1v29C7,30.553,6.552,31,6,31z"></path>
                        <g>
                            <path d="M6,41c-3.309,0-6-2.691-6-6s2.691-6,6-6s6,2.691,6,6S9.309,41,6,41z M6,31c-2.206,0-4,1.794-4,4s1.794,4,4,4s4-1.794,4-4 S8.206,31,6,31z"></path>
                            <path d="M42,48c-0.552,0-1-0.447-1-1V26c0-0.553,0.448-1,1-1s1,0.447,1,1v21C43,47.553,42.552,48,42,48z"></path>
                            <path d="M42,17c-0.552,0-1-0.447-1-1V1c0-0.553,0.448-1,1-1s1,0.447,1,1v15C43,16.553,42.552,17,42,17z"></path>
                            <path d="M42,27c-3.309,0-6-2.691-6-6s2.691-6,6-6s6,2.691,6,6S45.309,27,42,27z M42,17c-2.206,0-4,1.794-4,4s1.794,4,4,4 s4-1.794,4-4S44.206,17,42,17z"></path>
                            <path d="M24,48c-0.552,0-1-0.447-1-1V21c0-0.553,0.448-1,1-1s1,0.447,1,1v26C25,47.553,24.552,48,24,48z"></path>
                            <path d="M24,12c-0.552,0-1-0.447-1-1V1c0-0.553,0.448-1,1-1s1,0.447,1,1v10C25,11.553,24.552,12,24,12z"></path>
                            <path d="M24,22c-3.309,0-6-2.691-6-6s2.691-6,6-6c3.309,0,6,2.691,6,6S27.309,22,24,22z M24,12c-2.206,0-4,1.794-4,4s1.794,4,4,4 s4-1.794,4-4S26.206,12,24,12z"></path>
                        </g>
                    </g>
                </svg>
            </div>
        </div>
    </div>


<!--    <p>Below FAQ are some common concerns of our clients before purchasing the theme.</p>-->
    <div class="page-content">

    <div id="accordion">
        <div class="card">
            <div class="card-header" id="heading-a4b24df7-50de-45a6-8c89-d18de155a875">
                <button class="title collapsed" data-toggle="collapse" data-target="#a4b24df7-50de-45a6-8c89-d18de155a875" aria-expanded="false" aria-controls="a4b24df7-50de-45a6-8c89-d18de155a875">
                    <span class="label-question"><?=$aRes['COUNT_PAS']?></span><?=Lng_x('Passengers')?>
                </button>
                <svg aria-hidden="true" focusable="false" role="presentation" class="icon icon--wide icon-chevron-down" viewBox="0 0 498.98 284.49"><defs></defs><path class="cls-1" d="M80.93 271.76A35 35 0 0 1 140.68 247l189.74 189.75L520.16 247a35 35 0 1 1 49.5 49.5L355.17 511a35 35 0 0 1-49.5 0L91.18 296.5a34.89 34.89 0 0 1-10.25-24.74z" transform="translate(-80.93 -236.76)"></path></svg>
            </div>
            <div id="a4b24df7-50de-45a6-8c89-d18de155a875" class="collapse" aria-labelledby="heading-a4b24df7-50de-45a6-8c89-d18de155a875" data-parent="#accordion" style="">
                <div class="card-body" style="padding-left:0!important;">
                    <?if($aRes['COUNT_PAS']){?>
                        <div id="CmPassVehicBlock" class="CmManufContBlock">
                            <?php
                            $aa = array_merge($aRes['PAS']['FAVORITE'],$aRes['PAS']['MAIN']);
                            $ab = array_merge($aRes['COM']['FAVORITE'],$aRes['COM']['MAIN']);
                            $ac = array_merge($aRes['MOT']['FAVORITE'],$aRes['MOT']['MAIN']);
                            ?>
                            <?if($aRes['COUNT_PAS_FAVORITE']){?>
                                <div class="CmMainManuf">
                                    <?foreach($aa as $aProd){?>
                                    <a href="<?=$aProd['LINK']?>/" class="mbut_x CmColorTxh CmColorBgLh">
                                        <div class="mbutlogo_x" style="background-image:url(/<?=CM_DIR?>/media/brands/<?=$aProd['CODE']?>.png)"></div>
                                        <div class="mbuttext_x"><?=$aProd['NAME']?></div>
                                        </a><?
                                    }?>
                                </div>
                            <?}?>
                        </div>
                    <?}?>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="heading-d8458fa7-f101-47a1-85b6-05e2ac9f12a6">
                <button class="title collapsed" data-toggle="collapse" data-target="#d8458fa7-f101-47a1-85b6-05e2ac9f12a6" aria-expanded="false" aria-controls="d8458fa7-f101-47a1-85b6-05e2ac9f12a6">
                    <span class="label-question"><?=$aRes['COUNT_COM']?></span><?=Lng_x('Commercial_vehicles')?>
                </button>
                <svg aria-hidden="true" focusable="false" role="presentation" class="icon icon--wide icon-chevron-down" viewBox="0 0 498.98 284.49"><defs></defs><path class="cls-1" d="M80.93 271.76A35 35 0 0 1 140.68 247l189.74 189.75L520.16 247a35 35 0 1 1 49.5 49.5L355.17 511a35 35 0 0 1-49.5 0L91.18 296.5a34.89 34.89 0 0 1-10.25-24.74z" transform="translate(-80.93 -236.76)"></path></svg>
            </div>
            <div id="d8458fa7-f101-47a1-85b6-05e2ac9f12a6" class="collapse" aria-labelledby="heading-d8458fa7-f101-47a1-85b6-05e2ac9f12a6" data-parent="#accordion" style="">
                <div class="card-body" style="padding-left:0!important;">
<!--                    <p><span class="label-answer">Answer</span>Please make sure that you follow below steps:</p>-->
                    <?if($aRes['COUNT_COM']){?>
                            <?if($aRes['COUNT_COM_MAIN']){?>
                                <div class="CmMainManuf">
                                    <?foreach($ab as $aProd){?>
                                    <a href="<?=$aProd['LINK']?>/" class="mbut_x CmColorTxh CmColorBgLh">
                                        <div class="mbutlogo_x" style="background-image:url(/<?=CM_DIR?>/media/brands/<?=$aProd['CODE']?>.png)"></div>
                                        <div class="mbuttext_x"><?=$aProd['NAME']?></div>
                                        </a><?
                                    }?>
                                </div>
                            <?}?>
                    <?}?>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="heading-ac6545cf-782b-4b04-8cb5-c61496ab2fcb">
                <button class="title collapsed" data-toggle="collapse" data-target="#ac6545cf-782b-4b04-8cb5-c61496ab2fcb" aria-expanded="false" aria-controls="ac6545cf-782b-4b04-8cb5-c61496ab2fcb">
                    <span class="label-question"><?=$aRes['COUNT_MOT']?></span><?=Lng_x('Motorcycles')?>
                </button>
                <svg aria-hidden="true" focusable="false" role="presentation" class="icon icon--wide icon-chevron-down" viewBox="0 0 498.98 284.49"><defs></defs><path class="cls-1" d="M80.93 271.76A35 35 0 0 1 140.68 247l189.74 189.75L520.16 247a35 35 0 1 1 49.5 49.5L355.17 511a35 35 0 0 1-49.5 0L91.18 296.5a34.89 34.89 0 0 1-10.25-24.74z" transform="translate(-80.93 -236.76)"></path></svg>
            </div>
            <div id="ac6545cf-782b-4b04-8cb5-c61496ab2fcb" class="collapse" aria-labelledby="heading-ac6545cf-782b-4b04-8cb5-c61496ab2fcb" data-parent="#accordion" style="">
                <div class="card-body" style="padding-left:0!important;">
                    <?if($aRes['COUNT_MOT']){?>
                            <?if($aRes['COUNT_MOT_MAIN']){?>
                                <div class="CmMainManuf">
                                    <?foreach($ac as $aProd){?>
                                    <a href="<?=$aProd['LINK']?>/" class="mbut_x CmColorTxh CmColorBgLh">
                                        <div class="mbutlogo_x" style="background-image:url(/<?=CM_DIR?>/media/brands/<?=$aProd['CODE']?>.png)"></div>
                                        <div class="mbuttext_x"><?=$aProd['NAME']?></div>
                                        </a><?
                                    }?>
                                </div>
                            <?}?>
                    <?}?>
                </div>
            </div>
        </div>
    </div>
</div>
