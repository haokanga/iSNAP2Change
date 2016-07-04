<?php
    //check login status
    require_once('./student-validation.php');

    require_once("../mysql-lib.php");
    require_once("../debug.php");
    $pageName = "reading-material";

    $conn = null;

    try {
        $conn = db_connect();

        //get student week
        $studentWeek = getStudentWeek($conn, $studentID);

        //get max week
        $maxWeek = getMaxWeek($conn)->WeekNum;


    } catch(Exception $e) {
        if($conn != null) {
            db_close($conn);
        }

        debug_err($pageName, $e);
        //to do: handle sql error
        //...
        exit;
    }

    db_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, width=device-width, user-scalable=no">
    <title>Reading Material</title>
    <link rel="stylesheet" href="./css/common.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <style>
        .page-wrapper {
            padding-top: 50px;
            margin: 0 auto;
        }
        .header-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1024;
        }
        .sitenav {
            position: fixed;
            left: 20px;
            top: 20%;
        }
        .sitenav-item a {
            display: block;
            width: 64px;
            height: 64px;
            background-size: 100% 100%;
            margin-bottom: 20px;
        }
        .sitenav-game-home a {
            background-image: url("./img/game_icon.png");
        }
        .sitenav-achievement a {
            background-image: url("./img/achievement_logo.png");

        }
        .sitenav-progress a {
            background-image: url("./img/progress_icon.png");

        }
        .sitenav-reading-material a {
            background-image: url("./img/reading_material_icon.png");
        }


        .reading-detail {
            padding-top: 20px;
        }
        .reading-header {
            text-align: center;
        }
        .reading-logo {
            width: 128px;
            height: 128px;
            margin: 0 auto;
            background-size: 100% 100%;
            background-image: url("./img/reading_material_icon.png");
        }
        .reading-title {
            font-size: 28px;
        }
        .reading-intro {
            width: 300px;
            font-family: "Maitree", serif;
            font-size: 18px;
            margin: 0 auto;
        }

        .reading-tab {
            padding-top: 40px;
            padding-bottom: 30px;
            text-align: center;
        }
        .reading-tab-title {
            font-size: 14px;
            margin: 10px 0;
        }
        .reading-tab-item {
            width: 48px;
            height: 48px;
            line-height: 44px;
            margin: 0 10px;
            display: inline-block;
            border: 2px solid;
            border-radius: 50%;
            color:  #fcee2d;
            font-size: 32px;
            cursor: pointer;
        }
        .reading-tab-item-active {
            color: #fff;
            background-color: #fcee2d;
            border-color: #fcee2d;
        }
        .reading-tab-item-disabled {
            text-indent: -999em;
            border: 0;
            background-image: url("./img/locked_icon.png");
            cursor: not-allowed;
            background-size: 100% 100%;
        }

        .reading-tab-content {
            max-width: 1000px;
            margin: 0 auto;
        }

        .reading-week-detail {
            display: none;
        }
        .reading-week-detail-active {
            display: block;
        }

        .reading-item {
            width: 340px;
            margin: 0 auto 20px;
            text-align: center;
            cursor: pointer;
        }
        .reading-item-logo {
            width: 110px;
            height: 110px;
            margin: 0 auto 10px;
            background-size: 100% 100%;
        }
        .reading-item-smoking {
            color: #FCEE2D;
        }
        .reading-item-smoking .reading-item-logo {
            background-image: url("./img/smoking_icon.png");
        }
        .reading-item-nutrition {
            color: #f7751e;
        }
        .reading-item-nutrition .reading-item-logo {
            background-image: url("./img/nutrition_icon.png");
        }
        .reading-item-alcohol {
            color: #AF24D1;
        }
        .reading-item-alcohol .reading-item-logo {
            background-image: url("./img/alcohol_icon.png");
        }
        .reading-item-physical-activity {
            color: #f7751e;
        }
        .reading-item-physical-activity .reading-item-logo {
            background-image: url("./img/physical_activity_icon.png");
        }
        .reading-item-title {
            font-size: 20px;
        }
        .reading-item-divider {
            border-top: 2px solid;
            margin: 10px 0;
        }
        .reading-item-desc {
            color: #fff;
            width: 200px;
            font-size: 18px;
            font-family: Maitree, serif;
            margin: 0 auto;
        }

        .material-list {
            margin: 30px auto 0;
            max-width: 1000px;
        }
        .material-item {
            display: none;
        }
        .material-item-active {
            display: block;
        }
        .material-title {
            width: 200px;
            margin: 0 auto 20px;
            border-bottom: 2px solid;
            font-size: 24px;
            text-align: center;
        }
        .material-alcohol .material-title {
            color: #af24d1;
        }
        .material-content {
            padding: 20px 60px;
            background-color: #dedfdf;
            color: #000;
        }
        .material-content p {
            margin-bottom: 20px;
            font-size: 18px;
        }

        .back-top {
            width: 160px;
            margin: 50px auto 20px;
            cursor: pointer;
        }
        .back-top .icon-top {
            width: 48px;
            height: 48px;
            margin: 0 auto 20px;
            background-size: 100% 100%;
            background-image: url("./img/direction_icon.png");
            transform: rotate(270deg);
        }
        .back-top-label {
            text-align: center;
            color: #fcee2d;
            font-size: 18px;
        }


    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="header-wrapper header-fixed">
        <div class="header">
            <a class="home-link" href="#">SNAP</a>
            <ul class="nav-list">
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">GAME HOME</a></li>
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">Snap Facts</a></li>
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">Resources</a></li>
            </ul>
            <a href="#" class="settings">
                <span class="setting-icon"></span>
                <span class="setting-text"><?php echo $_SESSION["studentUsername"]?></span>
            </a>
        </div>
    </div>

    <div class="content-wrapper">

        <div class="reading-detail">
            <div class="reading-header">
                <div class="reading-logo"></div>
                <div class="reading-title">Learning Material</div>
                <div class="reading-intro">View all past learning material at the click of a button</div>
            </div>
            <div class="reading-tab">
                <h2 class="reading-tab-title">Select Your week</h2>
                <div class="reading-tab-list">
        <?php
                for($i = 0; $i < ($studentWeek-1); $i++) { ?>
                    <div class="reading-tab-item"><?php echo ($i+1) ?></div>
        <?php   } ?>

                    <div class="reading-tab-item reading-tab-item-active"><?php echo $studentWeek ?></div>

        <?php
                for($i = $studentWeek; $i < $maxWeek; $i++) { ?>
                    <div class="reading-tab-item reading-tab-item-disabled"><?php echo ($i+1) ?></div>
        <?php   } ?>
                </div>
            </div>
            <div class="reading-tab-content">

        <?php
                for ($i = 0; $i < ($studentWeek-1); $i++) { ?>
                    <div class="reading-week-detail mini-row">
                        for () {

        <?php                }

        ?>
                <!-- index=0 -->
                <div class="reading-week-detail reading-week-detail-active mini-row">
                    <div class="col-6">
                        <div class="reading-item reading-item-nutrition" data-target=".material-nutrition">
                            <div class="reading-item-logo"></div>
                            <div class="reading-item-title">Nutrition</div>
                            <div class="reading-item-divider"></div>
                            <div class="reading-item-desc">Information on the types of food we eat</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="reading-item reading-item-nutrition" data-target=".material-nutrition">
                            <div class="reading-item-logo"></div>
                            <div class="reading-item-title">Nutrition</div>
                            <div class="reading-item-divider"></div>
                            <div class="reading-item-desc">Information on the types of food we eat</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="reading-item reading-item-alcohol" data-target=".material-alcohol">
                            <div class="reading-item-logo"></div>
                            <div class="reading-item-title">Nutrition</div>
                            <div class="reading-item-divider"></div>
                            <div class="reading-item-desc">Information on the types of food we eat</div>
                        </div>
                    </div>
                </div>


                <!-- index=1 -->
                <div class="reading-week-detail mini-row">
                    <div class="col-6">
                        <div class="reading-item reading-item-alcohol" data-target=".material-alcohol">
                            <div class="reading-item-logo"></div>
                            <div class="reading-item-title">Nutrition</div>
                            <div class="reading-item-divider"></div>
                            <div class="reading-item-desc">Information on the types of food we eat</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="reading-item reading-item-nutrition" data-target=".material-nutrition">
                            <div class="reading-item-logo"></div>
                            <div class="reading-item-title">Nutrition</div>
                            <div class="reading-item-divider"></div>
                            <div class="reading-item-desc">Information on the types of food we eat</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="reading-item reading-item-nutrition" data-target=".material-nutrition">
                            <div class="reading-item-logo"></div>
                            <div class="reading-item-title">Nutrition</div>
                            <div class="reading-item-divider"></div>
                            <div class="reading-item-desc">Information on the types of food we eat</div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="material-list">
                <div class="material-item material-alcohol ">
                    <h2 class="material-title">Alcohhol</h2>
                    <div class="material-content">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minus ducimus, quia atque, velit ipsum adipisci ad voluptas totam, accusantium culpa obcaecati eum voluptatibus vel vero aliquam iste commodi. Necessitatibus, odit!</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque nam, quod mollitia unde consectetur facilis, id in tempora nesciunt officia quam suscipit velit aliquam molestias, ratione, veritatis. Fuga, culpa, vero.</p>
                    </div>
                </div>
                <div class="material-item material-nutrition">
                    <h2 class="material-title">Alcohhol</h2>
                    <div class="material-content">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minus ducimus, quia atque, velit ipsum adipisci ad voluptas totam, accusantium culpa obcaecati eum voluptatibus vel vero aliquam iste commodi. Necessitatibus, odit!</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque nam, quod mollitia unde consectetur facilis, id in tempora nesciunt officia quam suscipit velit aliquam molestias, ratione, veritatis. Fuga, culpa, vero.</p>
                    </div>
                </div>

            </div>



            <div class="back-top">
                <div class="icon-top"></div>
                <div class="back-top-label">Back to Top</div>
            </div>


        </div>


    </div>

    <ul class="sitenav">
        <li class="sitenav-item sitenav-game-home"><a href="#"></a></li>
        <li class="sitenav-item sitenav-achievement"><a href="#"></a></li>
        <li class="sitenav-item sitenav-progress"><a href="#"></a></li>
        <li class="sitenav-item sitenav-reading-material"><a href="#"></a></li>
    </ul>
    <div class="footer">
        <div class="footer-content">
            <a href="#" class="footer-logo"></a>
            <ul class="footer-nav">
                <li class="footer-nav-item"><a href="#">Any Legal Stuff</a></li>
                <li class="footer-nav-item"><a href="#">Acknowledgements</a></li>
            </ul>
        </div>
    </div>
</div>


<script>
    var TabCtrl = {
        cls: {
            tabActive: 'reading-tab-item-active',
            tabDisabled: 'reading-tab-item-disabled',
            tabContentActive: 'reading-week-detail-active'
        },
        init: function (opt) {
            opt = opt || {
                    onTabChange: $.noop
                };
            this.onTabChange = opt.onTabChange;
            this.cacheElements();
            this.addListeners()
        },
        cacheElements: function () {
            var $main = $('.reading-tab');
            this.$main = $main;
            this.$tabItems = $main.find('.reading-tab-item');
            this.$tabContent = $('.reading-tab-content');
            this.$tabContentItems = this.$tabContent.find('.reading-week-detail')
        },
        addListeners: function () {
            var that = this;

            this.$main.on('click', '.reading-tab-item', function (e) {
                var $target = $(e.currentTarget);
                var cls = that.cls;

                if (!$target.hasClass(cls.tabActive) && !$target.hasClass(cls.tabDisabled)) {
                    var index = that.$tabItems.index(e.currentTarget);
                    that.activeItem(index);
                    that.onTabChange(index)
                }
            })
        },
        activeItem: function (index) {
            this.$tabItems.removeClass(this.cls.tabActive)
                .eq(index)
                .addClass(this.cls.tabActive);
            this.$tabContentItems.removeClass(this.cls.tabContentActive)
                .eq(index)
                .addClass(this.cls.tabContentActive)
        }
    };
    TabCtrl.init({
        onTabChange: function (index) {
            MaterialCtrl.showNavPanel(index)
        }
    });


    var MaterialCtrl = {
        init: function () {
            this.cacheElements();
            this.addListeners()
        },
        cacheElements: function () {
            var $navMain = $('.reading-tab-content');
            this.$navMain = $navMain;
            this.$navPanels = $navMain.find('.reading-week-detail');
            var $materialList = $('.material-list');
            this.$materialList = $materialList;
            this.$materialItems = $materialList.find('.material-item')
        },
        addListeners: function () {
            var that = this;
            that.$navMain.on('click', '.reading-item', function (e) {
                var $target = $(e.currentTarget);
                var targetMaterialCls = $target.data('target');
                that.showMaterialDetail(targetMaterialCls)
            })
        },
        showNavPanel: function (index) {
            this.$navMain.show();
            this.$navPanels.hide()
                .eq(index)
                .show();
            this.$materialItems.removeClass('material-item-active');
            this.$materialList.hide()
        },
        showMaterialDetail: function (targetMaterialCls) {
            this.$navMain.hide();
            this.$materialList.show();
            this.$materialItems.removeClass('material-item-active')
                .filter(targetMaterialCls)
                .addClass('material-item-active')
        },
        hideMaterialDetail: function () {
            this.$materialList.hide();
            this.$materialItems.removeClass('material-item-active')
        }
    };
    MaterialCtrl.init();

    // back to top
    var $body = $('body');
    $body.on('click', '.back-top', function () {
        $body.animate({ scrollTop: 0 }, 'fast');
    })
</script>

</body>
</html>



