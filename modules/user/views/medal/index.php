<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\Pjax;
$this->params['breadcrumbs'] = [
    [
        'label' => '用户管理',
        'url' => Url::to([
            '/user'
        ] )
    ],
    ['label' => '颁发勋章']
];
AppAsset::registerJs($this, '@web/plugin/icheck/icheck.min.js');
AppAsset::registerCss($this , '@web/plugin/icheck/skins/minimal/_all.css');
AppAsset::registerJs($this , '@web/js/common.js');
?>
<div class="row">
    <div class="col-md-8">
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
<div class="gallery-env">
    <div class="row col-sm-12 mb-15">
        <div class="col-sm-12">
            <span class="font-16"><?= Yii::$app->request->get('name')?> 的勋章</span>
        </div>
    </div>
    <div class="row col-sm-10">
        <?php
            if(empty($haveMedal)){
                echo '<p class="ml-15 font-18">无</p>';
            }else{
                foreach($haveMedal as $data){
                    ?>
                    <div class="col-sm-1 col-xs-1 medal-image">
                        <article class="image-thumb">
                            <div class="image">
                                <img height="80" src="<?= $data['url'] ?>">
                                <p class="mt-15"><?= $data['leagueName'] . $data['seasonName'] . "<br />" . $data['name'] ?></p>
                            </div>
                            <div class="image-options">
                                <a data-name="<?= $data['leagueName'] . $data['seasonName'] . $data['name'] ?>"
                                   href="<?= Url::to([
                                       '/user/medal/delete' ,
                                       'id' => $data['id']
                                   ]) ?>" class="delete"><i class="entypo-cancel"></i></a>
                            </div>
                        </article>
                    </div>
                    <?php
                }
            }
        ?>
    </div>
</div>
<div class="form-group default-padding form-button">
    <button id="medal" type="button" class="btn btn-success">颁  发</button>
    <a href="<?= \Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
</div>

<div class="modal fade" id="select-medal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <span>颁发勋章</span>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modalbody">
                <div class="row">
                    <?php if(!empty($medalList)){?>
                    <div class="btn-group season-filter pull-left col-sm-12 mb-15 ml-10">
                        <button id="season" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" data-seasonid="<?=$medalList['gameLeagues'][0]['seasons'][0]['id']?>">
                            <?=$medalList['gameLeagues'][0]['seasons'][0]['name']?>　<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-infoblue" role="menu">
                            <?php foreach($medalList['gameLeagues'][0]['seasons'] as $season){ ?>
                                <li>
                                    <a data-seasonid="<?= $season['id']?>" href="javascript:void(0);"><?= $season['name']?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="col-sm-12 medal-list">
                        <ul class="icheck-list">
                            <?php foreach($medalList['medals'] as $key => $data){ ?>
                                <li class="col-sm-3 list-inline">
                                    <p><input tabindex="<?=$key?>" type="checkbox" class="icheck checkbox-inline" id="<?= "medal-{$key}"?>" value="<?=$data['id']?>" ><label for="<?= "medal-{$key}"?>" class="ml-10"><?=$data['name']?></label></p>
                                    <p><label for="<?= "medal-{$key}"?>"><img width="100" height="80" src="<?= $data['url']?>" alt="<?=$data['remark']?>" title="<?=$data['remark']?>" ></label></p>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default cancel">取消</button>
                <button type="button" class="btn btn-info confirm">确定</button>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $('.season-filter .dropdown-menu a').click(function(){
        $('#seasonId').val($(this).attr('data-seasonid'));
        $('.season-filter button.dropdown-toggle').attr('data-seasonid' , $(this).attr('data-seasonid'));
        $('.season-filter button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
    });
    $('input.icheck').iCheck({
        checkboxClass: 'icheckbox_minimal-green'
    });
    $('#medal').click(function(){
        jQuery('#select-medal').modal('show');
        jQuery('#select-medal .confirm').off('click');
        jQuery('#select-medal .confirm').on('click' , function(){
            var seasonId = $('#season').attr('data-seasonid');
            var medalIds = '';
            $.each($('input:checkbox:checked'),function(){
                medalIds = medalIds + $(this).val() + ',';
            });
            console.log(seasonId);
            console.log(medalIds);
            $.ajax({
                url : '<?= Url::to(['/user/medal/add'])?>',
                type : 'get',
                dataType : 'json',
                data : 'userId=<?=Yii::$app->request->get('id')?>&seasonId=' + seasonId + '&medalIds=' + medalIds,
                success : function(response){
                    if(response.status == 'success'){
                        toastr.success('颁发勋章成功！' , '' , toastrOpts);
                        setTimeout(function(){
                            window.location.reload();
                        } , 600);
                    }else{
                        toastr.error(response.message , '' , toastrOpts);
                    }
                }
            });
            jQuery('#select-medal').modal('hide');
        });
    });
    $('a.delete').click(function(){
        var $that = $(this);
        var medalName = $(this).attr('data-name');
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除 " + medalName + " 的这个勋章吗？");
        var successText = "删除" + medalName + "成功！";
        showConfirmModal(this , confirmText , successText , function(){
            $that.closest('.medal-image').remove();
        });
        return false;
    });
});
</script>