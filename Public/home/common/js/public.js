// 图片上传
function upload1() {
    //调用上传图片
    var uploader = WebUploader.create({
        // 选完文件后，是否自动上传。
        auto: false,
        // swf文件路径
        swf: SWF_URL,
        // 文件接收服务端。
        server: UPLOAD_URL,
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: {
            id: '#filePicker1',
            multiple: false
        },
        //限制图片数量
        fileSizeLimit: 40 * 1024 * 1024,
        fileSingleSizeLimit: 8 * 1024 * 1024,
        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'jpg,gif,png,jpeg',
            mimeTypes: 'image/jpg,image/gif,image/png,image/jpeg'
        }
    });

    // 当有文件添加进来的时候
    uploader.on('error', function (handler) {
        if (handler == 'Q_EXCEED_SIZE_LIMIT' || handler == 'F_EXCEED_SIZE') {
            layer.msg('上传图片最大不超过8M');
            return;
        }
    });

    // 当有文件添加进来的时候
    uploader.on('fileQueued', function (file) {
        $('#img1').hide();
        uploader.upload();
    });

    // 文件上传过程中创建进度条实时显示。
    uploader.on('uploadProgress', function (file) {
        $('#img1').after('<label class="loading_icon"></label>');

        /*
         var $li = $( '#'+file.id ),
         $percent = $li.find('.progress .progress-bar');
         
         // 避免重复创建
         if ( !$percent.length ) {
         $percent = $('<div class="progress progress-striped active">' +
         '<div class="progress-bar" role="progressbar" style="width: 0%">' +
         '</div>' +
         '</div>').appendTo( $li ).find('.progress-bar');
         }
         
         $li.find('p.state').text('上传中');
         
         $percent.css( 'width', percentage * 100 + '%' );
         */
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on('uploadSuccess', function (file, ret) {

        
        if (ret.status == 0) {
            $('#img1').siblings('.loading_icon').remove();
            $('#img1').show();
            layer.msg(ret.info);
        } else {
            console.log(ret);
            $('#icon').val(ret.path);
            $("#img1").attr('src', ret.path);

            setTimeout(function () {
                $("#img1").show();
                $('#img1').siblings('.loading_icon').remove();
            }, 100);
        }

        //$( '#'+file.id ).find('p.state').text('已上传');
    });

    // 文件上传失败，显示上传出错。
    uploader.on('uploadError', function (file) {
        $('#img1').siblings('.loading_icon').remove();
        $("#img1").show();
        layer.msg('上传出错');

        //$( '#'+file.id ).find('p.state').text('上传出错');
    });

    // 完成上传完了，成功或者失败，先删除进度条。
    uploader.on('uploadComplete', function (file) {
        //$( '#'+file.id ).find('.progress').fadeOut();
    });
}

function upload2() {
    //调用上传图片
    var uploader = WebUploader.create({
        // 选完文件后，是否自动上传。
        auto: false,
        // swf文件路径
        swf: SWF_URL,
        // 文件接收服务端。
        server: UPLOAD_URL,
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: {
            id: '#filePicker2',
            multiple: false
        },
        // 图片限制
        fileSizeLimit: 40 * 1024 * 1024,
        fileSingleSizeLimit: 8 * 1024 * 1024,
        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'jpg,gif,png,jpeg',
            mimeTypes: 'image/jpg,image/gif,image/png,image/jpeg'
        }
    });

    // 当有文件添加进来的时候
    uploader.on('error', function (handler) {
        if (handler == 'Q_EXCEED_SIZE_LIMIT' || handler == 'F_EXCEED_SIZE') {
            layer.msg('上传图片最大不超过8M');
            return;
        }
    });

    // 当有文件添加进来的时候
    uploader.on('fileQueued', function (file) {
        $('#img2').hide();
        uploader.upload();
    });

    // 文件上传过程中创建进度条实时显示。
    uploader.on('uploadProgress', function (file) {
        $('#img2').after('<label class="loading_icon"></label>');

        /*
         var $li = $( '#'+file.id ),
         $percent = $li.find('.progress .progress-bar');
         
         // 避免重复创建
         if ( !$percent.length ) {
         $percent = $('<div class="progress progress-striped active">' +
         '<div class="progress-bar" role="progressbar" style="width: 0%">' +
         '</div>' +
         '</div>').appendTo( $li ).find('.progress-bar');
         }
         
         $li.find('p.state').text('上传中');
         
         $percent.css( 'width', percentage * 100 + '%' );
         */
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on('uploadSuccess', function (file, ret) {
        if (ret.status == 0) {
            $('#img2').siblings('.loading_icon').remove();
            $('#img2').show();
            layer.msg(ret.info);
        } else {
            $('#qrcode').val(ret.data.url);
            $("#img2").attr('src', ret.data.thumb);
            setTimeout(function () {
                $("#img2").show();
                $('#img2').siblings('.loading_icon').remove();
            }, 100);
        }

        //$( '#'+file.id ).find('p.state').text('已上传');
    });

    // 文件上传失败，显示上传出错。
    uploader.on('uploadError', function (file) {
        $('#img2').siblings('.loading_icon').remove();
        $("#img2").show();
        layer.msg('上传出错');

        //$( '#'+file.id ).find('p.state').text('上传出错');
    });

    // 完成上传完了，成功或者失败，先删除进度条。
    uploader.on('uploadComplete', function (file) {
        //$( '#'+file.id ).find('.progress').fadeOut();
    });
}

function upload3() {
    //调用上传图片
    var uploader = WebUploader.create({
        // 选完文件后，是否自动上传。
        auto: false,
        // swf文件路径
        swf: SWF_URL,
        // 文件接收服务端。
        server: UPLOAD_URL,
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: {
            id: '#filePicker3',
            multiple: false
        },
        // 图片限制
        fileSizeLimit: 40 * 1024 * 1024,
        fileSingleSizeLimit: 8 * 1024 * 1024,
        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'jpg,gif,png,jpeg',
            mimeTypes: 'image/jpg,image/gif,image/png,image/jpeg'
        }
    });

    // 当有文件添加进来的时候
    uploader.on('error', function (handler) {
        if (handler == 'Q_EXCEED_SIZE_LIMIT' || handler == 'F_EXCEED_SIZE') {
            layer.msg('上传图片最大不超过8M');
            return;
        }
    });

    // 当有文件添加进来的时候
    uploader.on('fileQueued', function (file) {
        $('#img3').hide();
        uploader.upload();
    });

    // 文件上传过程中创建进度条实时显示。
    uploader.on('uploadProgress', function (file) {
        $('#img3').after('<label class="loading_icon"></label>');

        /*
         var $li = $( '#'+file.id ),
         $percent = $li.find('.progress .progress-bar');
         
         // 避免重复创建
         if ( !$percent.length ) {
         $percent = $('<div class="progress progress-striped active">' +
         '<div class="progress-bar" role="progressbar" style="width: 0%">' +
         '</div>' +
         '</div>').appendTo( $li ).find('.progress-bar');
         }
         
         $li.find('p.state').text('上传中');
         
         $percent.css( 'width', percentage * 100 + '%' );
         */
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on('uploadSuccess', function (file, ret) {
        if (ret.status == 0) {
            $('#img3').siblings('.loading_icon').remove();
            $('#img3').show();
            layer.msg(ret.info);
        } else {
            $('#open_qrcode').val(ret.data.url);
            $("#img3").attr('src', ret.data.thumb);
            setTimeout(function () {
                $("#img3").show();
                $('#img3').siblings('.loading_icon').remove();
            }, 100);
        }

        //$( '#'+file.id ).find('p.state').text('已上传');
    });

    // 文件上传失败，显示上传出错。
    uploader.on('uploadError', function (file) {
        $('#img3').siblings('.loading_icon').remove();
        $("#img3").show();
        layer.msg('上传出错');

        //$( '#'+file.id ).find('p.state').text('上传出错');
    });

    // 完成上传完了，成功或者失败，先删除进度条。
    uploader.on('uploadComplete', function (file) {
        //$( '#'+file.id ).find('.progress').fadeOut();
    });
}

function upload4() {
    var len = $(".file-img").length;
    var limit = 5;
    //调用上传图片
    var uploader = WebUploader.create({
        // 选完文件后，是否自动上传。
        auto: false,
        // swf文件路径
        swf: SWF_URL,
        // 文件接收服务端。
        server: UPLOAD_URL,
        // 选择文件的按钮。可选。
        pick: '#filePicker4',
        // 图片限制
        fileNumLimit: limit,
        fileSizeLimit: 40 * 1024 * 1024,
        fileSingleSizeLimit: 8 * 1024 * 1024,
        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'jpg,gif,png,jpeg',
            mimeTypes: 'image/jpg,image/gif,image/png,image/jpeg'
        }
    });

    // 当有文件添加进来的时候
    uploader.on('error', function (handler) {
        if (handler == 'Q_EXCEED_SIZE_LIMIT' || handler == 'F_EXCEED_SIZE') {
            layer.msg('上传图片最大不超过8M');
        }
        if (handler == 'Q_EXCEED_SIZE_LIMIT' || handler == 'F_EXCEED_SIZE') {
            layer.msg('上传图片最大不超过8M');
        }
        if (handler == "Q_EXCEED_NUM_LIMIT") {
            layer.msg("最多选择5张图片");
        }
        if (handler == "F_DUPLICATE") {
            layer.msg("图片已经存在");
        }
    });

    // 当有文件添加进来的时候
    uploader.on('fileQueued', function (file) {
        if (len < limit) {
            len++;
        } else {
            uploader.removeFile(file);
            layer.msg("最多上传5张图片");
            return;
        }
        if (len > 4) {
            $('#filePicker4').hide();
        }
        var $li = $('<span id="' + file.id + '" class="file-img">' +
                '<label class="delete">删除</label>' +
                '<img class="img4" onload="setImgWH(this)" alt="..." style="display: none;">' +
                '</span>'
                );
        $("#filePicker4").before($li);
        uploader.upload();
    });

    // 文件上传过程中创建进度条实时显示。
    uploader.on('uploadProgress', function (file) {
        $('#' + file.id + ' .img4').after('<label class="loading_icon"></label>');

        /*
         var $li = $( '#'+file.id ),
         $percent = $li.find('.progress .progress-bar');
         
         // 避免重复创建
         if ( !$percent.length ) {
         $percent = $('<div class="progress progress-striped active">' +
         '<div class="progress-bar" role="progressbar" style="width: 0%">' +
         '</div>' +
         '</div>').appendTo( $li ).find('.progress-bar');
         }
         
         $li.find('p.state').text('上传中');
         
         $percent.css( 'width', percentage * 100 + '%' );
         */
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on('uploadSuccess', function (file, ret) {
        if (ret.status == 0) {
            layer.msg(ret.info);
            uploader.removeFile(file);
            len--;
        } else {
            $('#' + file.id).attr('data-img', ret.data.url);
            $('#' + file.id).find('.img4').attr('src', ret.data.thumb);
            attrImg();
            setTimeout(function () {
                $('#' + file.id).find('.img4').show();
                $('#' + file.id).find('.loading_icon').remove();
            }, 100);
        }

        //$( '#'+file.id ).find('p.state').text('已上传');
    });

    // 文件上传失败，显示上传出错。
    uploader.on('uploadError', function (file) {
        uploader.removeFile(file);
        len--;
        $('#' + file.id).remove();
        layer.msg('上传出错');

        //$( '#'+file.id ).find('p.state').text('上传出错');
    });

    // 完成上传完了，成功或者失败，先删除进度条。
    uploader.on('uploadComplete', function (file) {
        if (len < 5) {
            $('#filePicker4').show();
        }

        //$( '#'+file.id ).find('.progress').fadeOut();
    });

    //删除图片
    $("#filelist4").on("click", ".delete", function () {
        // 移除队列中文件
        var file_id = $(this).parent().attr('id');
        if (file_id != undefined) {
            var rm_file = uploader.getFile(file_id);
            uploader.removeFile(rm_file);
        }
        len--;
        // 移除图片
        $(this).parent().remove();
        $('#filePicker4').show();
        
        attrImg();
    });
}

function attrImg() {
    var attr_imgs = '';
    var count = $("#filelist4 .file-img").length;
    for (var i = 0; i < count; i++) {
        attr_imgs += $("#filelist4 .file-img").eq(i).attr('data-img');
        if (i != count - 1) {
            attr_imgs += ',';
        }
    }
    $('#attr_imgs').val(attr_imgs);
    
    return attr_imgs;
}
// 认领小程序截图上传
function upload5() {
    var len = $("#filelist5 .file-img").length;
    var limit = 5;
    //调用上传图片
    var uploader = WebUploader.create({
        // 选完文件后，是否自动上传。
        auto: false,
        // swf文件路径
        swf: SWF_URL,
        // 文件接收服务端。
        server: UPLOAD_URL,
        // 选择文件的按钮。可选。
        pick: '#filePicker5',
        // 图片限制
        fileNumLimit: limit,
        fileSizeLimit: 40 * 1024 * 1024,
        fileSingleSizeLimit: 8 * 1024 * 1024,
        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'jpg,gif,png,jpeg',
            mimeTypes: 'image/jpg,image/gif,image/png,image/jpeg'
        }
    });

    // 当有文件添加进来的时候
    uploader.on('error', function (handler) {
        if (handler == 'Q_EXCEED_SIZE_LIMIT' || handler == 'F_EXCEED_SIZE') {
            layer.msg('上传图片最大不超过8M');
        }
        if (handler == 'Q_EXCEED_SIZE_LIMIT' || handler == 'F_EXCEED_SIZE') {
            layer.msg('上传图片最大不超过8M');
        }
        if (handler == "Q_EXCEED_NUM_LIMIT") {
            layer.msg("最多选择5张图片");
        }
        if (handler == "F_DUPLICATE") {
            layer.msg("图片已经存在");
        }
    });

    // 当有文件添加进来的时候
    uploader.on('fileQueued', function (file) {
        if (len < limit) {
            len++;
        } else {
            uploader.removeFile(file);
            layer.msg("最多上传5张图片");
            return;
        }
        if (len > 4) {
            $('#filePicker5').hide();
        }
        var $li = $('<span id="' + file.id + '" class="file-img">' +
                '<label class="delete">删除</label>' +
                '<img class="img5" onload="setImgWH(this)" alt="..." style="display: none;">' +
                '</span>'
                );
        $("#filePicker5").before($li);
        uploader.upload();
    });

    // 文件上传过程中创建进度条实时显示。
    uploader.on('uploadProgress', function (file) {
        $('#' + file.id + ' .img5').after('<label class="loading_icon"></label>');

        /*
         var $li = $( '#'+file.id ),
         $percent = $li.find('.progress .progress-bar');
         
         // 避免重复创建
         if ( !$percent.length ) {
         $percent = $('<div class="progress progress-striped active">' +
         '<div class="progress-bar" role="progressbar" style="width: 0%">' +
         '</div>' +
         '</div>').appendTo( $li ).find('.progress-bar');
         }
         
         $li.find('p.state').text('上传中');
         
         $percent.css( 'width', percentage * 100 + '%' );
         */
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on('uploadSuccess', function (file, ret) {
        if (ret.status == 0) {
            layer.msg(ret.info);
            uploader.removeFile(file);
            len--;
        } else {
			console.log(ret);
            $('#' + file.id).attr('data-img', ret.data.url);
            $('#' + file.id).find('.img5').attr('src', ret.data.thumb);
            attrImg2();
            setTimeout(function () {
                $('#' + file.id).find('.img5').show();
                $('#' + file.id).find('.loading_icon').remove();
            }, 100);
        }

        //$( '#'+file.id ).find('p.state').text('已上传');
    });

    // 文件上传失败，显示上传出错。
    uploader.on('uploadError', function (file) {
        uploader.removeFile(file);
        len--;
        $('#' + file.id).remove();
        layer.msg('上传出错');

        //$( '#'+file.id ).find('p.state').text('上传出错');
    });

    // 完成上传完了，成功或者失败，先删除进度条。
    uploader.on('uploadComplete', function (file) {
        if (len < 5) {
            $('#filePicker5').show();
        }

        //$( '#'+file.id ).find('.progress').fadeOut();
    });

    //删除图片
    $("#filelist5").on("click", ".delete", function () {
        // 移除队列中文件
        var file_id = $(this).parent().attr('id');
        if (file_id != undefined) {
            var rm_file = uploader.getFile(file_id);
            uploader.removeFile(rm_file);
        }
        len--;
        // 移除图片
        $(this).parent().remove();
        $('#filePicker5').show();
        
        attrImg2();
    });
}
function attrImg2() {
    var attr_imgs = '';
    var count = $("#filelist5 .file-img").length;
    for (var i = 0; i < count; i++) {
        attr_imgs += $("#filelist5 .file-img").eq(i).attr('data-img');
        if (i != count - 1) {
            attr_imgs += ',';
        }
    }
    $('#attr_imgs').val(attr_imgs);
    
    return attr_imgs;
}