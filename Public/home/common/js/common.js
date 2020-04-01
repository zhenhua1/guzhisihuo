//插入固定在右边的悬浮按钮
$(function () {
    var html = '<div class="fixed_box">'
            + '<a class="add-fixed" href="/user/index/apply"><label></label><p>发布</p></a>'
            + '<a class="release-zb" href="/charts"><label></label><p>排行榜</p></a>'
            + '<a class="look_telephone"><label></label><p>手机访问</p><div class="telephone_pop"><p></p><span>扫一扫体验小程序</span></div></a>'
            + '<a class="to_top"><label></label></a>'
            + '</div>';
    $(".body-container").append(html);
    var wWidth = $(window).width();
    var wHeight = $(window).height();
    var imgLeft = 0;
    var scrollTop = 0;
    if ($(this).scrollTop() > 200) {
        $(".fixed_box .to_top").show()
    } else {
        $(".fixed_box .to_top").hide()
    }
    $(window).scroll(function () {
        scrollTop = $(this).scrollTop()
        if ($(this).scrollTop() > 200) {
            $(".fixed_box .to_top").fadeIn()
        } else {
            $(".fixed_box .to_top").fadeOut()
        }
    })

    // $(".fixed_box").css("bottom", "100px")
    // if (wWidth < 980) {
    //     imgLeft = (wWidth - 980) - 140;
    // } else {
    //     imgLeft = (wWidth - 980) / 2 - 140
    // }
    // $(".fixed_box").css("right", imgLeft);
    // $(window).resize(function () {
    //     wWidth = $(window).width();
    //     wHeight = $(window).height();
    //     if (wWidth < 980) {
    //         imgLeft = (wWidth - 980) - 140;
    //     } else {
    //         imgLeft = (wWidth - 980) / 2 - 140;
    //     }
    //     $(".fixed_box").css("bottom", "100px")
    //     $(".fixed_box").css("right", imgLeft);
    // })

    $(".fixed_box").css({"bottom":"100px","right":"10px"})
    //二维码显示
    $(".look_telephone").hover(function () {
        $(".telephone_pop").stop(true, true).fadeIn();
    }, function () {
        $(".telephone_pop").stop(true, true).fadeOut();
    })
    $(".official").hover(function () {
        $(".official_pop").stop(true, true).fadeIn();
    }, function () {
        $(".official_pop").stop(true, true).fadeOut();
    })
    //	回到顶部
    $(".to_top").click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 200);
    })
})

//处理图片的宽高
function setImgWH(obj) {
    if (obj.width > obj.height) {
        $(obj).css('height', '100%');
    } else {
        $(obj).css('width', '100%');
    }
}
//处理图片的宽高
function setImgHW(obj) {
    if (obj.width > obj.height) {
        $(obj).css('height', '80%');
    } else {
        $(obj).css('width', '80%');
    }
}

$(function () {
    //弹窗
    var H_ture = $(".switchbox .content").height();
    var flag = true;
    if (H_ture > 36) {
        $(".switchbox .content").css("height", "36px")
        $(".slideBtn a").click(function () {
            if (flag) {
                $(".switchbox .content").animate({
                    "height": H_ture
                })
                $(this).addClass("active")
            } else {
                $(".switchbox .content").animate({
                    "height": "36px"
                })
                $(this).removeClass("active")
            }
            flag = !flag;
        })
    }

})

$(function () {
    //图片变大变小
    $(".imgScale").on('mouseenter', '.content', function () {
        $(this).find("img").addClass("scale").removeClass("scale_return")
    });
    $(".imgScale").on('mouseleave', '.content', function () {
        $(this).find("img").addClass("scale_return").removeClass("scale")
    });
})
