<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */
use Jsyqw\BusinessGii\api\Generator;

echo $form->field($generator, 'moduleName');
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'searchType')->dropDownList([
    Generator::searchTypeWhereFilter => 'WhereFilter查询模式',
    Generator::searchTypeSearchMatch => 'SearchMatch的查询模式',
]);
echo $form->field($generator, 'controllerNS');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'baseControllerClass');

?>
<?php $this->beginBlock('autoControllerClass') ?>
    $(function(){
        $("#generator-modelclass").blur(function () {
            let modelClass = $(this).val();
            modelClass = modelClass.split("\\")
            let className = modelClass[modelClass.length-1]
            className = firstWordUpper(className)

            let controllerClass = $("#generator-controllerclass").val()
            controllerClass = controllerClass.split("\\")
            controllerClass[controllerClass.length - 1] = className+"Controller"
            controllerClassName = controllerClass.join("\\")
            $("#generator-controllerclass").val(controllerClassName)
        })

        $("#generator-modulename").blur(function () {
            let moduleName = $(this).val();
            let controllerNS = "";
            if(moduleName!=""){
                controllerNS = "app\\modules\\"+moduleName+"\\controllers"
            }
            $("#generator-controllerns").val(controllerNS)
        })

        $("#generator-modulename").blur(function () {
            let moduleName = $(this).val();
            let baseControllerClass = "";
            if(moduleName!=""){
                baseControllerClass = "app\\core\\controllers\\"+moduleName+"\\BaseApiController"
            }
            $("#generator-basecontrollerclass").val(baseControllerClass)
        })
    })
    //首字母转大写
    function firstWordUpper(str){
        return str.substring(0, 1).toUpperCase() + str.substring(1)
    }
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['autoControllerClass']); ?>