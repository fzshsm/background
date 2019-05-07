<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [
    [
        'label' => '商城管理',
        'url' => Url::to([
            '/mall'
        ] )
    ],
    [
        'label' => '房卡列表',
        'url' => Url::to([
            '/mall/roomcard'
        ] )
    ],
    [
        'label' => '房卡发放'
    ]
];

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this, '@web/plugin/fileinput.js');
AppAsset::registerJs($this , '@web/js/common.js');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
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

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    房卡发放
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name" class="col-sm-1 control-label"><b>用户搜索:</b></label>
                    <div class="col-sm-8">
                        <div class="col-sm-1 searchType">
                            <input type="hidden" id="searchType" name="searchType" value="userNo">
                            <button type="button" class="btn btn-success dropdown-toggle" style="width: 70px"  data-toggle="dropdown">
                                用户ID<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-green">
                                <li><a href="javascript:void(0);" data-searchtype="userNo">用户ID</a></li>
                                <li><a href="javascript:void(0);" data-searchtype="qq">QQ</a></li>
                                <li><a href="javascript:void(0);" data-searchtype="mobile">手机</a></li>
                                <li><a href="javascript:void(0);" data-searchtype="nickname">昵称</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="content" placeholder="" value="">
                        </div>
                        <div class="col-sm-1">
                            <button  type="submit" class="btn btn-success" onclick="searchUser()">
                                <i class="entypo-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
<?php
$form = ActiveForm::begin([
    'id' => 'season-form',
    'method' => 'post',
    'options' => [
        'data-pjax' => true,
        'role' => 'form',
        'class' => 'form-horizontal form-groups-bordered validate'
    ]
] );
?>
                <div class="col-sm-11" style="margin-top: 10px">
                    <table class="table table-striped">
                        <thead>
                            <th style="display: none"></th>
                            <th>用户ID</th>
                            <th>用户昵称</th>
                            <th>房卡选择</th>
                            <th>房卡数量</th>
                            <th>操作</th>
                        </thead>
                        <tbody id="user-table">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group default-padding form-button">
    <input type="hidden" id="select-option" value="<?= isset($roomCardList)? $roomCardList : '' ?>">
    <button type="submit" class="btn btn-success"  onclick="return check()">保　存</button>
    <a href="<?= \Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
</div>
<?php
ActiveForm::end();
Pjax::end();
?>
<script>
    var selectOption = '<?= $roomCardList?>';
    var tableNum = 1;

    $(document).ready(function(){
        $('.searchType .dropdown-menu a').click(function () {
            $('#searchType').val($(this).attr('data-searchtype'));
            $('button.btn-success.dropdown-toggle').attr('data-searchtype', $(this).attr('data-searchtype'));
            $('button.btn-success.dropdown-toggle').html($(this).text() + '　<span class="caret"></span>');
        });
    })

    function searchUser(){
        var userNo = $("#userNo").val();
        var content = $("#content").val();
        var searchType = $("#searchType").val();

        $.ajax({
            url:'<?= Url::to(['/mall/roomcard/user'])?>',
            data:{searchType:searchType,content:content},
            dataType:'json',
            success:function(res){
                if(res.status == 'success'){
                    var data = res.result;
                    if(data.length != 0){
                        var userId = data.userNo+'-'+tableNum;
                        var selectId = 'select-'+tableNum+'-'+data.userNo;
                        var addTr = "<tr id="+userId+"><td style='display: none'><input type='hidden' name='userNo[]' value="+data.id+"></td>" +
                            "<td>"+data.userNo+"</td><td>"+data.nickName+"</td><td><select name='roomCardId[]' id="+selectId+"></select></td>" +
                            "<td><input type='text' style='text-align: center;width: 50px;' name='roomCardNum[]' value='1'></td><td><a href='#'  class='btn btn-danger btn-sm radius-4' onclick='removeTr(\""+userId+"\")'><i class='entypo-minus'></i>" +
                            "</a></td></tr>";

                        $("#user-table").append(addTr)
                        initializeSelect(selectId)
                        tableNum = tableNum + 1;
                    }
                }else{
                    toastr.error(res.message , '' , toastrOpts);
                }
            }
        })
    }

    function initializeSelect(selectId){
        var option = eval(selectOption);

        for (var i=0;i<option.length;i++){
            $("#"+selectId).append("<option value="+option[i]['id']+">"+option[i]['name']+"</option>")
        }

        $("#"+selectId).select2({

        })
    }

    function removeTr(delId){

        $("#"+delId).remove()
    }

    function check(){
        var roomCardNum =[];
        $("input[name='roomCardNum[]']").each(function(){
             roomCardNum.push($(this).val());

        })

        for(var i=0;i<roomCardNum.length;i++){
            if(roomCardNum[i] === ''){
                layer.alert('房卡数量不能为空！')
                return false;
            }
        }
        return true;
    }


</script>

