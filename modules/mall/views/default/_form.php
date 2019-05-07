<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

AppAsset::registerJs($this , '@web/plugin/kindeditor/kindeditor-all.js');
AppAsset::registerJs($this , '@web/plugin/kindeditor/lang/zh-CN.js');
AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this, '@web/plugin/fileinput.js');
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin(['id' => 'team']);
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
$form = ActiveForm::begin([
    'id' => 'goods-form',
    'method' => 'post',
    'options' => [
        'data-pjax' => true,
        'role' => 'form',
        'class' => 'form-horizontal form-groups-bordered validate'
    ]
] );
?>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <?= isset($data['name']) ? "{$data['name']} 信息" : '创建商品'?>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">商品名称</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" autocomplete="false" name="goodsName" required id="goodsName" value="<?= isset($data['goodsName'])?$data['goodsName']:''?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="teamLogo" class="col-sm-3 control-label" data-validate="required">商品图(168*168)</label>
                    <div class="col-sm-7">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <input type="hidden" name="image" value="<?= isset($data['goodsImg']) && !empty($data['goodsImg']) ? $data['goodsImg']: '' ?>">
                            <div class="fileinput-new thumbnail" style="width: 320px; height: 200px;" data-trigger="fileinput">
                                <img src="<?= isset($data['goodsImg']) && !empty($data['goodsImg']) ? $data['goodsImg'] : Url::to('@web/images/noimg.png')?>" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px"></div>
                            <span class="btn btn-white btn-file" style="display: none">
                                <input type="file" name="goodsImg" id="goodsImg" accept="image/*" onchange="checkUploadImage(this)">
                            </span>
                            <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="type" class="col-sm-3 control-label">商品类型</label>
                    <div class="col-sm-5">
                        <?= Html::dropDownList('type',isset($data['type']) ? $data['type'] : 1, [1 => '充值卡', 2 => '优惠券', 3 => '游戏周边', 4 => '电子产品', 5 => '房卡'],['onchange' => 'getType()','id' => 'type'])?>
                    </div>
                </div>
                <div class="form-group" id="display_roomcard">
                    <label for="type" class="col-sm-3 control-label">房卡</label>
                    <div class="col-sm-5">
                        <?= Html::dropDownList('propId', isset($data['propId']) ? $data['propId'] : 1 ,$roomCardList, ['onchange' => 'getProp()', 'id' => 'propId']) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="freeTrial" class="col-sm-3 control-label">虚拟商品</label>
                    <div class="col-sm-5">
                        <?= Html::radioList('isEntity',isset($data['isEntity']) ? $data['isEntity'] : 0,[1 => '否',0 => '是'],
                            ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']]]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="price" class="col-sm-3 control-label">商品价格</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" autocomplete="false" name="price" required id="price" value="<?= isset($data['price'])?$data['price']:''?>"
                               onkeyup="value=value.replace(/[^\d\.]/g,'')"   onafterpaste="this.value=this.value.replace(/[^\d\.]/g,'')" maxlength=9>
                    </div>
                </div>
                <div class="form-group">
                    <label for="stockCount" class="col-sm-3 control-label">库存量</label>
                    <div class="col-sm-5">
                        <div class="input-spinner">
                            <button type="button" class="btn btn-default btn-sm">-</button>
                            <input type="text" class="form-control size-1" data-min="1" name="stockCount" value="<?= isset($data['stockCount'])?$data['stockCount']:0 ?>">
                            <button type="button" class="btn btn-default btn-sm">+</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="buyLimit" class="col-sm-3 control-label">每日限购量</label>
                    <div class="col-sm-5">
                        <div class="input-spinner">
                            <button type="button" class="btn btn-default btn-sm">-</button>
                            <input type="text" class="form-control size-1" data-min="0" name="limitCount" value="<?= isset($data['limitCount']) ? $data['limitCount'] : 0;?>">
                            <button type="button" class="btn btn-default btn-sm">+</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sortWeight" class="col-sm-3 control-label">权重</label>
                    <div class="col-sm-5">
                        <div class="input-spinner">
                            <button type="button" class="btn btn-default btn-sm">-</button>
                            <input type="text" class="form-control size-1" data-min="1" name="sortWeight" value="<?= isset($data['sortWeight'])?$data['sortWeight']:0 ?>">
                            <button type="button" class="btn btn-default btn-sm">+</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="describe" class="col-sm-3 control-label">商品描述</label>
                    <div class="col-sm-5">
                        <textarea id="leagueDescribe" name="goodsDesc" class="form-control autogrow" rows="5" ><?= isset($data['goodsDesc']) ? $data['goodsDesc'] : '';?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-sm-3 control-label">商品状态</label>
                    <div class="col-sm-5">
                        <?= Html::dropDownList('status',isset($data['status'])?$data['status']:0,[0 => '待发布',1 => '上架',2 => '下架'])?>
                    </div>
                </div>
                <?= isset( $data['id'] ) ? Html::hiddenInput("id" , $data['id']) : '' ?>
            </div>
        </div>
    </div>
</div>
<div class="form-group default-padding form-button">
    <button type="submit" class="btn btn-success">保　存</button>
    <a href="<?= \Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
</div>
<?php
ActiveForm::end();
Pjax::end();
?>
<script>
    var roomCardDesc = [];
    var editor;
    roomCardDesc = '<?= $roomCardDesc?>'
    $(document).ready(function(){
        $('form select').select2({
            minimumResultsForSearch: -1
        })
        KindEditor.ready(function(K) {
                editor = K.create("#leagueDescribe", {
                cssData: 'body {font-size: 14px}',
                uploadJson:'<?= Url::to(['/mall/upload'])?>',
                filePostName: "goodsImg",
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
        });

        getType()
    });
    $(document).on('pjax:complete',function(){
        $('form select').select2({
            minimumResultsForSearch: -1
        })
        KindEditor.ready(function(K) {
                editor = K.create("#leagueDescribe", {
                cssData: 'body {font-size: 14px}',
                uploadJson:'<?= Url::to(['/mall/upload'])?>',
                filePostName: "goodsImg",
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
        });
        getType()
        replaceInputSpinner()
    })

    function getType(){
        var type = $("#type").val()

        if(type != 5){
            $("#display_roomcard").hide()
        }else{
            $("#display_roomcard").show()
            getProp();
        }
    }

    function getProp(){
        var propId = $("#propId").val();
        var desc = eval("("+roomCardDesc+")");
        var leagueDescribe = $("#leagueDescribe").val();

        if( leagueDescribe != desc[propId] ){
            editor.html(desc[propId])
            editor.focus();
        }

    }

    // $("#icon").change(function(){
    //     console.log($("#icon").val())
    // })

</script>

