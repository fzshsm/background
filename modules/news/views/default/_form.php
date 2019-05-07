<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


AppAsset::registerJs($this , '@web/plugin/kindeditor/kindeditor-all.js');
AppAsset::registerJs($this , '@web/plugin/kindeditor/lang/zh-CN.js');

AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');

AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');

AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');

AppAsset::registerJs($this, '@web/plugin/fileinput.js');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
AppAsset::registerJs($this, '@web/plugin/validate/jquery-html5Validate.js');
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin(['id' => 'news']);
?>
<div class="row">
    <div class="col-md-8">
        <?php if(Yii::$app->session->hasFlash('dataError')){ ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('dataError')?>
            </div>
        <?php }?>
        <?php if(Yii::$app->session->hasFlash('error')){ ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('error')?>
            </div>
        <?php }?>
        <?php if(Yii::$app->session->hasFlash('success')){?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('success')?>
            </div>
        <?php }?>
    </div>
</div>
<?php
//    $form = ActiveForm::begin([
//        'id' => 'news-form',
//        'method' => 'post',
//        'options' => [
//            'data-pjax' => true,
//            'role' => 'form',
//            'class' => 'form-horizontal form-groups-bordered validate',
//            'enctype' => 'multipart/form-data',
//        ]
//    ] );
?>
<form id="news-form" method="post" class="form-horizontal form-groups-bordered validate"  enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        新闻信息
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">标题</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="title" name="title" autocomplete="false" required value="<?= isset($data['title']) ? $data['title'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reward" class="col-sm-3 control-label">副标题</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="subhead" autocomplete="false" required id="subhead" value="<?= isset($data['subhead']) ? $data['subhead'] : '';?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reward" class="col-sm-3 control-label">分享简介</label>
                        <div class="col-sm-5">
                            <textarea name="brief" id="brief" required class="form-control autogrow" >
                            </textarea>
                            <input type="hidden" id="hidden_brief" value="<?= isset($data['brief']) ? $data['brief'] : ''?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="headImage" class="col-sm-3 control-label" data-validate="required">封面</label>
                        <div class="col-sm-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 320px; height: 200px;" data-trigger="fileinput">
                                    <img src="<?= isset($data['cover']) && !empty($data['cover']) ? $data['cover'] : Url::to('@web/images/noimg.png')?>" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px"></div>
                                <span class="btn btn-white btn-file" style="display: none">
                                        <input type="file" name="cover" accept="image/*" onchange="checkUploadImage(this)">
                                    </span>
                                <input type="hidden" name="image"  value="<?= isset($data['cover'])?$data['cover']:''?>">
                                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">作者</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="author" name="author" autocomplete="false" required value="<?= isset($data['author']) ? $data['author'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="matchTime" class="col-sm-3 control-label">发布时间</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" readonly class="form-control" name="release_time" id="release_time" value="<?= isset($data['release_time']) ? $data['release_time'] : (date('Y-m-d H:i') );?>" >
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">位置</label>
                        <div class="col-sm-5">
                            <?=Html::dropDownList('postion' , isset($data['postion']) ? $data['postion'] : 'normal' , ['normal' => '资讯','banner' => '轮播','choice' => '精选']);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">游戏类型</label>
                        <div class="col-sm-5">
                            <?=Html::dropDownList('gameType' , isset($data['gameType']) ? $data['gameType'] : '1' , ['1' => '王者荣耀', '2' => '绝地求生']);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">新闻类型</label>
                        <div class="col-sm-5">
                            <?=Html::dropDownList('isVideo' , isset($data['isVideo']) ? $data['isVideo'] : 0 , [0 => '普通新闻', 1 => '视频新闻'],['onchange' => 'getNewsType()', 'id' => 'isVideo']);?>
                        </div>
                    </div>
                    <div class="form-group" id="display_videoUrl">
                        <label for="name" class="col-sm-3 control-label">视频地址</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="videoUrl" name="videoUrl" autocomplete="false" value="<?= isset($data['videoUrl']) ? $data['videoUrl'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sequence" class="col-sm-3 control-label">排序(从大到小)</label>
                        <div class="col-sm-5">
                            <div class="input-spinner">
                                <button type="button" class="btn btn-default btn-sm">-</button>
                                <input type="text" class="form-control size-1" data-min="0" name="sequence" value="<?= isset($data['sequence']) ? $data['sequence'] : 0;?>">
                                <button type="button" class="btn btn-default btn-sm">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="display_content">
                        <label for="name" class="col-sm-3 control-label">内容</label>
                        <div class="col-sm-8">
                            <textarea id="content" name="content" cols="20" rows="15" >
                                <?= isset($data['content']) ? $data['content'] : '' ?>
                            </textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="javascript:" class="priview col-sm-3 control-label">
                            <i class="entypo-mobile">内容预览</i>
                        </a>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">状态</label>
                        <div class="col-sm-5">
                            <?=Html::dropDownList('status' , isset($data['status']) ? $data['status'] : 'release' , ['release' => '发布','wait' => '待发布','close' => '关闭']);?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="form-group default-padding form-button">
        <button type="submit" class="btn btn-success">保　存</button>
        <a href="<?= \Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
    </div>
    <input  type="hidden" name="_csrf" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
</form>
<?php
//ActiveForm::end();
Pjax::end();
?>
<script>

    $(document).ready(function(){
        $('form select').select2( {
            minimumResultsForSearch: -1
        });
        var brief = $("#hidden_brief").val();
        $("#brief").val(brief);

        var _csrf = $("#_csrf").val();
        // 初始化编辑器
        KindEditor.ready(function(K) {
            var editor = K.create("#content", {
                uploadJson:'<?= Url::to(['/news/upload'])?>',
                filePostName: "img",
                items: [
                    'source', '|', 'undo', 'redo', '|', 'preview', 'cut', 'copy', 'paste',
                    'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                    'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                    'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                    'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
                    'table', 'hr', 'emoticons', 'pagebreak',
                    'anchor', 'link'
                ],
                minHeight:500,
                afterCreate: function () {
                    this.loadPlugin('autoheight');
                },
                afterBlur: function () {
                    this.sync();
                },
            });
        })
        $('#release_time').daterangepicker({
            startDate: "<?= date('Y-m-d H:i:s') ?>",
            showWeekNumbers : false, //是否显示第几周
            timePicker : true, //是否显示小时和分钟
            timePickerIncrement : 5, //时间的增量，单位为分钟
            timePicker12Hour : false, //是否使用12小时制来显示时间
            opens : 'right', //日期选择框的弹出位置
            buttonClasses : [ 'btn btn-default' ],
            applyClass : 'btn-small btn-success',
            cancelClass : 'btn-small',
            format : 'YYYY-MM-DD HH:mm:ss', //控件中from和to 显示的日期格式
            singleDatePicker : true,
            locale : {
                applyLabel : '确定',
                cancelLabel : '取消',
                fromLabel : '开始时间',
                toLabel : '结束时间',
                daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
                    '七月', '八月', '九月', '十月', '十一月', '十二月' ],

            }
        }, function(start, end, label) {//格式化日期显示框
            $('#release_time span').html(start.format('YYYY-MM-DD HH:mm:ss'));
        });

        $("#news-form").html5Validate(function() {
            var isVideo = $("#isVideo").val();
            if(isVideo == 1){
                var videoUrl = $("#videoUrl").val();
                if(checkUrl(videoUrl) == false){
                    layer.alert('请输入正确的视频地址')
                    return false;
                }
            }
            this.submit();
        });
        var newsType = $("#isVideo").val();

        if(newsType == 1){
            setTimeout(function(){ $("#display_content").hide();}, 300);
        }else{
            $("#display_videoUrl").hide();
        }
    })
    //pjax重新初始化编辑器等
    $(document).on('pjax:complete',function(){
        $('form select').select2( {
            minimumResultsForSearch: -1
        });
        var brief = $("#hidden_brief").val();
        $("#brief").val(brief);
        var _csrf = $("#_csrf").val();
        KindEditor.ready(function(K) {
            var editor = K.create("#content", {
                uploadJson:'<?= Url::to(['/news/upload'])?>',
                filePostName: "img",
                items: [
                    'source', '|', 'undo', 'redo', '|', 'preview', 'cut', 'copy', 'paste',
                    'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                    'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                    'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                    'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
                    'flash', 'media', 'table', 'hr', 'emoticons', 'pagebreak',
                    'anchor', 'link', 'unlink'
                ],
                minHeight: 500,
                afterCreate: function () {
                    this.loadPlugin('autoheight');
                },
                afterBlur: function () {
                    this.sync();
                },
            });

            var options = {
                filterMode: true
            };
        })

        $("#news-form").html5Validate(function() {
            var videoUrl = $("#videoUrl").val();
            if(checkUrl(videoUrl) == false){
                layer.alert('请输入正确的视频地址')
                return false;
            }
            this.submit();
        });
    })

    //
    $('a.priview').click(function () {
        var content  = $("#content").val();
        var title = $("#title").val()
        layer.open({
            type: 1,
            skin:'img-class',
            title: title,
            area:['375px','667px'],
            shadeClose: true,
            content:content
        });
    })

    function getNewsType(){
        var newsType = $("#isVideo").val();

        if(newsType == 1){
            $("#display_videoUrl").show();
            $("#display_content").hide();
        }else{
            $("#display_videoUrl").hide();
            $("#display_content").show();
        }
    }


    function checkUrl(url) {
        var reg = /^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/)(([A-Za-z0-9-~]+)\.)+([A-Za-z0-9-~\/])/;
        if (reg.test(url)) {
            return true;
        }else{
            return false;
        }
    }

</script>
<!--内容预览图片样式-->
<style>
    body .img-class img{max-width:100%;height:auto!important;border:0;}
</style>