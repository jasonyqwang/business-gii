<?php

use yii\gii\generators\model\Generator;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var yii\gii\generators\model\Generator $generator */

echo $form->field($generator, 'db');
echo $form->field($generator, 'useTablePrefix')->checkbox();
echo $form->field($generator, 'useSchemaName')->checkbox();
echo $form->field($generator, 'tableName')->textInput([
    'autocomplete' => 'on',
    'data' => [
        'table-prefix' => $generator->getTablePrefix(),
        'action' => Url::to(['default/action', 'id' => 'model', 'name' => 'GenerateClassName'])
    ]
]);
echo $form->field($generator, 'onlyInitBaseModel')->checkbox();
echo $form->field($generator, 'standardizeCapitals')->checkbox();
echo $form->field($generator, 'singularize')->checkbox();
echo $form->field($generator, 'baseModelClass');
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'ns');
echo $form->field($generator, 'baseClass');
echo $form->field($generator, 'generateRelations')->dropDownList([
    Generator::RELATIONS_NONE => 'No relations',
    Generator::RELATIONS_ALL => 'All relations',
    Generator::RELATIONS_ALL_INVERSE => 'All relations with inverse',
]);
echo $form->field($generator, 'generateJunctionRelationMode')->dropDownList([
    Generator::JUNCTION_RELATION_VIA_TABLE => 'Via Table',
    Generator::JUNCTION_RELATION_VIA_MODEL => 'Via Model',
]);
echo $form->field($generator, 'generateRelationsFromCurrentSchema')->checkbox();
echo $form->field($generator, 'useClassConstant')->checkbox();
echo $form->field($generator, 'generateLabelsFromComments')->checkbox();
echo $form->field($generator, 'generateQuery')->checkbox();
echo $form->field($generator, 'queryNs');
echo $form->field($generator, 'queryClass');
echo $form->field($generator, 'queryBaseClass');
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'messageCategory');

?>


<?php $this->beginBlock('autoModelClass') ?>
    var tablePrefix = '<?php echo $generator->tablePrefix; ?>'
    $(function(){
        $("#generator-tablename").blur(function () {
            let length = tablePrefix.length
            let table = $(this).val();
            table = table.substring(length)
            let tableName = firstWordUpper(toHump(table))

            //驼峰的表名
            let baseTableName = "Base"+tableName;
            $("#generator-basemodelclass").val(baseTableName)

            $("#generator-modelclass").val(tableName)

        })

    })

    //下划线转驼峰
    function toHump(name) {
        return name.toLocaleLowerCase().replace(/\_(\w)/g, function(all, letter){
            return letter.toUpperCase();
        });
    }
    //首字母转大写
    function firstWordUpper(str){
       return str.substring(0, 1).toUpperCase() + str.substring(1)
    }
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['autoModelClass']); ?>

