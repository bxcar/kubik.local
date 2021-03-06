<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}

?>

<div style="display: none;" id="hidden-content">
    <img src="<?= get_template_directory_uri(); ?>/img/exmpimg.jpg" style="width: 300px;" id="image">
    <form class="fanc-form">
        <input type="file" name="img" id="img">
    </form>
    <div style="display: flex">
        <input type="checkbox" name="favphoto" id="favphoto">
        <label for="favphoto">Избранное фото</label>
    </div>
    <button class="btn-crop" onclick="parent.$.fancybox.close()">Ok</button>
</div>
<div style="display: none;" id="hidden-content-2">
    <img src="<?= get_template_directory_uri(); ?>/img/exmpimg.jpg" style="width: 300px;" id="image-2">
    <form class="fanc-form">
        <input type="file" name="img-2" id="img-2">
    </form>
    <button class="btn-crop-2" onclick="parent.$.fancybox.close()">Ok</button>
</div>

<style>
    .fanc-form {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .btn-crop,
    .btn-crop-2 {
        text-align: center;
        width: 100px;
        margin-left: auto;
        margin-right: auto;
        display: block;
    }

    .cropper-container {
        display: none;
    }

    #img-thumb,
    #img-thumb2,
    #img-thumb3,
    #img-thumb4,
    #img-thumb5,
    #img-thumb6,
    #img-thumb7,
    #img-thumb8,
    #img-thumb9,
    #img-thumb10,
    #img-thumb11,
    #img-thumb12,
    #img-thumb13,
    #img-thumb14,
    #img-thumb15,
    #img-thumb16,
    #img-thumb17,
    #img-thumb18,
    #img-thumb19 {
        display: none;
    }

    #image-2 {
        display: none;
    }
</style>
<script>
    $("input[name='img']").on("change", function () {
        function func() {
            $('.cropper-container').css('display', 'block');
        }

        setTimeout(func, 100);
    });
    $("input[name='img-2']").on("change", function () {
        function func() {
            $('.cropper-container').css('display', 'block');
            // $('#image-2').css('display', 'block');
        }

        setTimeout(func, 100);
    });
    $(function ($) {
        var store = localStorage.getItem("profils") || []
        if (store.length >= 1) {
            store = JSON.parse(store);
            var img = store[0].name;
            var img2 = store[0].name;
            $("#image").attr("src", "/wp-content/themes/storefront/img/photo/" + img);
            $("#image-2").attr("src", "/wp-content/themes/storefront/img/photo/" + img2);
        }

        var image = document.getElementById("image");
        var image_2 = document.getElementById("image-2");
        var cropper = new Cropper(image, {
            aspectRatio: 4 / 4
        });
        var cropper2 = new Cropper(image_2, {
            aspectRatio: 4 / 4
        });
        cropper.crop();
        cropper2.crop();

        $("#hidden-content").on("click", function () {
            cropper2.destroy();
        });

        $("#hidden-content-2").on("click", function () {
            cropper2.destroy();
        });


        $("input[name='img']").on("change", function () {
            // var image = document.getElementById("image");
            var files = $(this)[0].files;
            var file = files[0];
            console.log(file);
            $("#image").attr("src", window.URL.createObjectURL(file));

            /*var cropper = new Cropper(image, {
                aspectRatio: 4/4
            });
            cropper.crop();*/

            cropper.destroy();
            cropper.replace(window.URL.createObjectURL(file), true);
            $('.cropper-container').css('display', 'block');


        });

        $("input[name='img-2']").on("change", function () {
            var files = $(this)[0].files;
            var file = files[0];
            $("#image-2").attr("src", window.URL.createObjectURL(file));

            cropper2.destroy();
            cropper2.replace(window.URL.createObjectURL(file), true);
            $('.cropper-container').css('display', 'block');


        });


        $(".btn-crop").on("click", function () {
            cropper.getCroppedCanvas().toBlob(function (blob) {
                var formData = new FormData();
                formData.append("croppedImage", blob);
                $.ajax({
                    type: "POST",
                    url: "<?= get_template_directory_uri(); ?>/img/crop2.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        var img = JSON.parse(data);
                        var loc = JSON.parse(localStorage.getItem("profils")) || [];
                        loc.push({
                            "name": img + '.png'
                        });
                        localStorage.setItem("profils", JSON.stringify(loc));

                        if ($('#img-thumb').attr('src') == '') {
                            $('#img-thumb').attr('src', '<?= get_template_directory_uri();?>/img/photo/' + JSON.parse(data) + '.png' + '?' + new Date().getTime());
                            $('#img-thumb').css('display', 'inline-block');

                            if($('input[name="favphoto"]:checked').length > 0) {
                                $('#custom_img_1').attr('value', 'Избранное <?= get_template_directory_uri();?>/img/photo/' + JSON.parse(data) + '.png' + '?' + new Date().getTime());
                            } else {
                                $('#custom_img_1').attr('value', '<?= get_template_directory_uri();?>/img/photo/' + JSON.parse(data) + '.png' + '?' + new Date().getTime());
                            }

                            $( "#img-thumb-span1 .close-img" ).click(function() {
                                $('#img-thumb').attr('src', '');
                                $('#custom_img_1').attr('value', '');
                                $('#img-thumb').css('display', 'none');
                            });

                        }
                        <?php for($i = 2; $i <= 19; $i ++) { ?>
                        else if ($("#img-thumb<?= $i ?>").attr('src') == '') {
                            $('#img-thumb<?= $i ?>').attr('src', '<?= get_template_directory_uri();?>/img/photo/' + JSON.parse(data) + '.png' + '?' + new Date().getTime());
                            $('#img-thumb<?= $i ?>').css('display', 'inline-block');

                            if($('input[name="favphoto"]:checked').length > 0) {
                                $('#custom_img_<?= $i ?>').attr('value', 'Избранное <?= get_template_directory_uri();?>/img/photo/' + JSON.parse(data) + '.png' + '?' + new Date().getTime());
                            } else {
                                $('#custom_img_<?= $i ?>').attr('value', '<?= get_template_directory_uri();?>/img/photo/' + JSON.parse(data) + '.png' + '?' + new Date().getTime());
                            }

                            $( "#img-thumb-span<?= $i ?> .close-img" ).click(function() {
                                $('#img-thumb<?= $i ?>').attr('src', '');
                                $('#custom_img_<?= $i ?>').attr('value', '');
                                $('#img-thumb<?= $i ?>').css('display', 'none');
                            });

                        }
                        <?php } ?>
                    },
                    error: function (err) {
                        // console.log("error");
                    }
                })
            });
            // $( '#favphoto' ).prop( "checked", false );
            $('.cropper-container').css('display', 'none');
            cropper.destroy();
        });


        $(".btn-crop-2").on("click", function () {
            cropper2.getCroppedCanvas().toBlob(function (blob) {
                var formData = new FormData();
                formData.append("croppedImage", blob);
                $.ajax({
                    type: "POST",
                    url: "<?= get_template_directory_uri(); ?>/img/crop2.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        var img = JSON.parse(data);
                        var loc = JSON.parse(localStorage.getItem("profils")) || [];
                        loc.push({
                            "name": img + '.png'
                        });
                        localStorage.setItem("profils", JSON.stringify(loc));

                        $('#img-thumb-background').attr('src', '<?= get_template_directory_uri();?>/img/photo/' + JSON.parse(data) + '.png' + '?' + new Date().getTime());

                        $('#custom_background').attr('value', '<?= get_template_directory_uri();?>/img/photo/' + JSON.parse(data) + '.png' + '?' + new Date().getTime());

                    },
                    error: function (err) {
                        // console.log("error");
                    }
                })
            });
            $('.cropper-container').css('display', 'none');
            cropper2.destroy();
        });


    });

</script>

<form name="checkout" method="post" class="checkout woocommerce-checkout"
      action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">


    <div class="addition-steps">
        <div class="addition-steps__step1">
            <div class="addition-steps__steps-numbers">
                <div class="addition-steps__number">2</div>
                <div class="addition-steps__steps-numbers-line"></div>
                <div class="addition-steps__number active">1</div>
            </div>
            <div class="addition-steps__title">העלה 7 תמונות לריבועים הגדולים בקובייה</div>
            <img class="addition-steps__exmp-img" src="/wp-content/themes/storefront/img/add-step-example-img.png">
            <div class="addition-steps__img-dwn">
                <span id="img-thumb-span1" class="img-thumb-span">
                    <img class="thumb-img" src="" id="img-thumb">
                    <img style="width: 15px;" class="close-img" src="/wp-content/themes/storefront/img/close-icon.png">
                </span>
                <?php for($i = 2; $i <= 19; $i++) { ?>
                    <span id="img-thumb-span<?= $i ?>" class="img-thumb-span">
                    <img class="thumb-img" src="" id="img-thumb<?= $i ?>">
                    <img style="width: 15px;" class="close-img" src="/wp-content/themes/storefront/img/close-icon.png">
                </span>
                <?php } ?>
                <!--                <img class="thumb-img" src="-->
                <? //= get_template_directory_uri(); ?><!--/img/exmpimg.jpg" id="img-thumb5">-->
                <span style="font-size: 16px;" class="addition-steps__img-dwn-title">Изображения</span>
                <span class="addition-steps__img-dwn-subtitle">גודל מקסימלי לתמונה 5 מגה</span>
            </div>
            <a class="fanc-box-button" data-fancybox data-src="#hidden-content" href="javascript:;">
                Загрузить изображения
            </a>
            <a class="delete-images" href="#">
                Удалить изображения
            </a>
            <div class="step-buttons">
                <button id="to2step" style="margin-top: 20px;" type="button" class="btn btn-secondary">Седующий шаг</button>
            </div>
        </div>

        <div class="addition-steps__step1 step2">
            <div class="step-buttons">
                <button id="to1step" style="margin-bottom: 20px;" type="button" class="btn btn-secondary">Предыдущий шаг</button>
            </div>
            <div class="addition-steps__steps-numbers">
                <div class="addition-steps__number active">2</div>
                <div class="addition-steps__steps-numbers-line"></div>
                <div class="addition-steps__number">1</div>
            </div>
            <div class="addition-steps__title">ניתן לרשום ברכה עד 15 מילים ולבחור רקע</div>
            <img style="width: 174px; height: 110px; object-fit: cover" class="addition-steps__exmp-img" id="img-thumb-background" src="/wp-content/themes/storefront/img/add-step-example-img.png">
            <a style="margin-top: -10px; margin-bottom: 20px;" class="fanc-box-button" data-fancybox="" data-src="#hidden-content-2" href="javascript:;">
                Загрузить свой фон
            </a>
               <!--<div class="addition-steps__img-dwn modif">
                   <span class="addition-steps__img-dwn-subtitle modif">מאחלים לכם עוד הרבה שנות נישואין יפות</span>
                   <span class="addition-steps__img-dwn-subtitle modif">עוד הרבה התנסויות</span>
                   <span class="addition-steps__img-dwn-subtitle modif">צמיחה, התקרבות, רוגע ושלווה</span>
               </div>-->
            <div id="alerts"></div>
            <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
                <div class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="icon-font"></i><b
                                class="caret"></b></a>
                    <ul class="dropdown-menu">
                    </ul>
                </div>
                <div class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i
                                class="icon-text-height"></i>&nbsp;<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
                        <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
                        <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
                    </ul>
                </div>
                <div class="btn-group">
                    <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="icon-bold"></i></a>
                    <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i
                                class="icon-italic"></i></a>
                    <a class="btn" data-edit="strikethrough" title="Strikethrough"><i
                                class="icon-strikethrough"></i></a>
                    <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i
                                class="icon-underline"></i></a>
                </div>
                <!--<div class="btn-group">
                    <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i
                                class="icon-list-ul"></i></a>
                    <a class="btn" data-edit="insertorderedlist" title="Number list"><i
                                class="icon-list-ol"></i></a>
                    <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i
                                class="icon-indent-left"></i></a>
                    <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="icon-indent-right"></i></a>
                </div>
                <div class="btn-group">
                    <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i
                                class="icon-align-left"></i></a>
                    <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i
                                class="icon-align-center"></i></a>
                    <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i
                                class="icon-align-right"></i></a>
                    <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i
                                class="icon-align-justify"></i></a>
                </div>
                <div class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i
                                class="icon-link"></i></a>
                    <div class="dropdown-menu input-append">
                        <input class="span2" placeholder="URL" type="text" data-edit="createLink"/>
                        <button class="btn" type="button">Add</button>
                    </div>
                    <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="icon-cut"></i></a>

                </div>

                <div class="btn-group">
                    <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i
                                class="icon-picture"></i></a>
                    <input type="file" data-role="magic-overlay" data-target="#pictureBtn"
                           data-edit="insertImage"/>
                </div>
                <div class="btn-group">
                <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="icon-undo"></i></a>
                <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="icon-repeat"></i></a>
            </div>
                <input type="text" data-edit="inserttext" id="voiceBtn" x-webkit-speech="">-->
            </div>

            <div id="editor">
                Go ahead&hellip;
            </div>
            <script>
                $(function () {
                    function initToolbarBootstrapBindings() {
                        var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
                                'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
                                'Times New Roman', 'Verdana'],
                            fontTarget = $('[title=Font]').siblings('.dropdown-menu');
                        $.each(fonts, function (idx, fontName) {
                            fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
                        });
                        $('a[title]').tooltip({container: 'body'});
                        $('.dropdown-menu input').click(function () {
                            return false;
                        })
                            .change(function () {
                                $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
                            })
                            .keydown('esc', function () {
                                this.value = '';
                                $(this).change();
                            });
                        $('[data-role=magic-overlay]').each(function () {
                            var overlay = $(this), target = $(overlay.data('target'));
                            overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
                        });
                        if ("onwebkitspeechchange" in document.createElement("input")) {
                            var editorOffset = $('#editor').offset();
                            $('#voiceBtn').css('position', 'absolute').offset({
                                top: editorOffset.top,
                                left: editorOffset.left + $('#editor').innerWidth() - 35
                            });
                        } else {
                            $('#voiceBtn').hide();
                        }
                    };

                    function showErrorAlert(reason, detail) {
                        var msg = '';
                        if (reason === 'unsupported-file-type') {
                            msg = "Unsupported format " + detail;
                        }
                        else {
                            console.log("error uploading file", reason, detail);
                        }
                        $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
                    };
                    initToolbarBootstrapBindings();
                    $('#editor').wysiwyg({fileUploadError: showErrorAlert});
                    window.prettyPrint && prettyPrint();
                });
            </script>
            <span class="addition-steps__bg-title">רקע</span>
            <div class="addition-steps__bg-tiles">
                <div class="addition-steps__bg-tile"
                     style="background-image: url(<?= get_template_directory_uri() ?>/img/bg1.jpg);"></div>
                <div class="addition-steps__bg-tile"
                     style="background-image: url(<?= get_template_directory_uri() ?>/img/bg2.jpg);"></div>
                <div class="addition-steps__bg-tile"
                     style="background-image: url(<?= get_template_directory_uri() ?>/img/bg3.jpg);"></div>
                <div class="addition-steps__bg-tile"
                     style="background-image: url(<?= get_template_directory_uri() ?>/img/bg4.jpg);"></div>
                <div class="addition-steps__bg-tile"
                     style="background-image: url(<?= get_template_directory_uri() ?>/img/bg5.jpg);"></div>
                <div class="addition-steps__bg-tile"
                     style="background-image: url(<?= get_template_directory_uri() ?>/img/bg1.jpg);"></div>
            </div>
            <div class="addition-steps__check">
                <span class="addition-steps__check-text">אני מעוניין ברקע בלבד ללא ברכה</span>
<!--                <span class="addition-steps__check-check"></span>-->
                <input type="checkbox" id="scales" name="scales"
                       checked style="width: 15px;height: 15px;margin-top: 0;">
            </div>
        </div>
    </div>

    <style>
        .woocommerce-billing-fields {
            position: relative;
        }

        #hear_about_us_field {
            position: absolute;
            top: -230px;
            left: 230px;
        }

        #hear_about_us {
            margin-right: 90px;
            margin-bottom: 100px;
        }

        #hear_about_us_field:first-child {
            margin-left: 15px;
        }

        .addition-steps__bg-tile {
            background-size: cover;
            background-repeat: no-repeat;
        }
        #editor {
            overflow: scroll;
            max-height: 150px;
            height: 150px;
            width: 300px;


            background-color: white;
            border-collapse: separate;
            border: 1px solid rgb(204, 204, 204);
            padding: 4px;
            box-sizing: content-box;
            -webkit-box-shadow: rgba(0, 0, 0, 0.0745098) 0px 1px 1px 0px inset;
            box-shadow: rgba(0, 0, 0, 0.0745098) 0px 1px 1px 0px inset;
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
            border-top-left-radius: 3px;
            outline: none;
        }

        .addition-steps__img-dwn {
            position: relative;
        }

        .close-img {
            position: absolute;
            top: -5px;
            right:  -5px;
            cursor: pointer;
        }

        .thumb-img {
            /*position: absolute;*/
            width: 55px;
            height: 55px;
            object-fit: cover
        }

        .img-thumb-span {
            position: absolute;
            margin-left: 0;
            float: none;
        }

        .img-thumb-span:nth-child(1) {
            top: 2px;
            left: 1px;
        }


        .img-thumb-span:nth-child(6) {
            top: 60px;
            left: 1px;
        }

        .img-thumb-span:nth-child(11) {
            top: 120px;
            left: 1px;
        }

        .img-thumb-span:nth-child(16) {
            top: 180px;
            left: 1px;
        }

        .img-thumb-span:nth-child(2) {
            top: 2px;
            left: 61px;
        }

        .img-thumb-span:nth-child(7) {
            top: 60px;
            left: 61px;
        }

        .img-thumb-span:nth-child(12) {
            top: 120px;
            left: 61px;
        }

        .img-thumb-span:nth-child(17) {
            top: 180px;
            left: 61px;
        }

        .img-thumb-span:nth-child(3) {
            top: 2px;
            left: 121px;
        }

        .img-thumb-span:nth-child(8) {
            top: 60px;
            left: 121px;
        }

        .img-thumb-span:nth-child(13) {
            top: 120px;
            left: 121px;
        }

        .img-thumb-span:nth-child(18) {
            top: 180px;
            left: 121px;
        }

        .img-thumb-span:nth-child(4) {
            top: 2px;
            left: 181px;
        }

        .img-thumb-span:nth-child(9) {
            top: 60px;
            left: 181px;
        }

        .img-thumb-span:nth-child(14) {
            top: 120px;
            left: 181px;
        }

        .img-thumb-span:nth-child(19) {
            top: 180px;
            left: 181px;
        }

        .img-thumb-span:nth-child(5) {
            top: 2px;
            left: 241px;
        }

        .img-thumb-span:nth-child(10) {
            top: 60px;
            left: 241px;
        }

        .img-thumb-span:nth-child(15) {
            top: 120px;
            left: 241px;
        }

        .fanc-box-button,
        .delete-images {
            background: #e6e6e6;
            width: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            border-radius: 5px;
            margin-top: 20px;
            color: #000;
            outline: none;
        }

        .delete-images {
            margin-top: 10px;
        }

        .addition-steps {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-bottom: 50px;
        }

        .addition-steps__step1 {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .addition-steps__steps-numbers {
            display: flex;
            justify-content: space-between;
            width: 300px;
            margin-bottom: 30px;
        }

        .addition-steps__number {
            padding: 5px 20px;
            background: #d6d0d0;
            border: 1px solid black;
            border-radius: 7px;
            color: black;
            font-weight: bold;
            font-family: sans-serif;
        }

        .addition-steps__number.active {
            background: #313030;
            color: #fff;
        }

        .addition-steps__steps-numbers-line {
            border-bottom: 1px solid black;
            height: 1px;
            width: 100%;
            margin-top: 17px;
        }

        .addition-steps__title {
            font-family: sans-serif;
            color: black;
            text-transform: uppercase;
            margin-bottom: 30px;
            font-weight: bold;
            font-size: 20px;
        }

        .addition-steps__exmp-img {
            margin-bottom: 30px;
        }

        .addition-steps__img-dwn {
            display: flex;
            width: 300px;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            border: 1px solid black;
            height: 240px;
        }

        .addition-steps__img-dwn.modif {
            height: 150px;
        }

        .addition-steps__img-dwn-title {
            font-family: sans-serif;
            color: black;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 24px;
        }

        .addition-steps__img-dwn-subtitle {
            font-family: sans-serif;
            color: #a29a9a;
            text-transform: uppercase;
            font-size: 16px;
            margin-top: 5px;
        }

        .addition-steps__img-dwn-subtitle.modif {
            color: black;
            margin-top: 0;
        }

        .addition-steps__step1.step2 {
            /*margin-top: 80px;*/
        }

        .addition-steps__bg-title {
            margin-top: 10px;
            text-align: right;
            width: 300px;
            color: black;
            font-size: 14px;
            margin-bottom: 7px;
        }

        .addition-steps__bg-tiles {
            width: 300px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }

        .addition-steps__bg-tile {
            width: 80px;
            height: 80px;
            border: 1px solid black;
            box-sizing: border-box;
            margin-bottom: 30px;
        }

        .addition-steps__check {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-top: 20px;
            width: 300px;
            position: relative;
            top: 20px;
        }

        .addition-steps__check-text {
            color: black;
            font-size: 12px;
            margin-right: 10px;
        }

        .addition-steps__check-check {
            width: 20px;
            height: 20px;
            border: 1px solid black;
            box-sizing: border-box;
            display: block;
        }

        #custom_checkout_field,
        #custom_img_1,
        #custom_img_2,
        #custom_img_3,
        #custom_img_4,
        #custom_img_5,
        #custom_img_6,
        #custom_img_7,
        #custom_img_8,
        #custom_img_9,
        #custom_img_10,
        #custom_img_11,
        #custom_img_12,
        #custom_img_13,
        #custom_img_14,
        #custom_img_15,
        #custom_img_16,
        #custom_img_17,
        #custom_img_18,
        #custom_img_19,
        #custom_background {
            visibility: hidden;
            position: absolute;
        }

        .step2,
        #customer_details,
        #order_review_heading,
        #order_review {
            display: none;
        }



    </style>

    <script>
        $("body").on('DOMSubtreeModified', "#editor", function() {
            // console.log('changed');
            var innertextlength = document.getElementById('editor').innerText.length;
            if(innertextlength > 50) {
                // document.getElementById("editor").disable = true;
                console.log(document.getElementById('editor').innerText.length);
                $('#editor').text(function (_,txt) {
                    return txt.slice(0, -1);
                });
                alert('Максимальное количество символов 30');
            }
            $('#custom_description').attr('value', $('#editor').html())
        });

        $(".delete-images").on("click", function (e) {
            e.preventDefault();
            $('#img-thumb').attr('src', '').css('display', 'none');
            for(var i = 2; i <= 19; i++) {
                $('#img-thumb'+i).attr('src', '').css('display', 'none');
            }

            for(var ix = 1; ix <= 19; ix++) {
                $('#custom_img_'+ix).attr('value', '');
            }
        });

        /*$("body").on('DOMSubtreeModified', "#editor", function() {
            console.log(document.getElementById('editor').innerText.length);
        });*/

        $( "#to2step" ).click(function() {
            $( ".addition-steps .addition-steps__step1:first-child" ).css('display', 'none');
            $( ".step2" ).css('display', 'flex');
            $( "#customer_details" ).css('display', 'block');
            $( "#order_review_heading" ).css('display', 'block');
            $( "#order_review" ).css('display', 'block');

            $([document.documentElement, document.body]).animate({
                scrollTop: $("#to1step").offset().top
            }, 20);
        });

        $( "#to1step" ).click(function() {
            $( ".addition-steps .addition-steps__step1:first-child" ).css('display', 'flex');
            $( ".step2" ).css('display', 'none');
            $( "#customer_details" ).css('display', 'none');
            $( "#order_review_heading" ).css('display', 'none');
            $( "#order_review" ).css('display', 'none');
        });

    </script>

<!--    --><?php //wp_woo_cart_attributes(); ?>

    <?php if ($checkout->get_checkout_fields()) : ?>

        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

        <div class="col2-set" id="customer_details">
            <div class="col-1">
                <?php do_action('woocommerce_checkout_billing'); ?>
            </div>

            <div class="col-2">
                <?php do_action('woocommerce_checkout_shipping'); ?>
            </div>
        </div>

        <?php do_action('woocommerce_checkout_after_customer_details'); ?>

    <?php endif; ?>

    <h3 id="order_review_heading"><?php esc_html_e('Your order', 'woocommerce'); ?></h3>

    <?php do_action('woocommerce_checkout_before_order_review'); ?>

    <div id="order_review" class="woocommerce-checkout-review-order">
        <?php do_action('woocommerce_checkout_order_review'); ?>
    </div>

    <?php do_action('woocommerce_checkout_after_order_review'); ?>

</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
