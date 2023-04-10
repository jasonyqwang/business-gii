<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;
use Jsyqw\BusinessGii\api\Generator;

/* @var $this yii\web\View */
/* @var $generator Generator */
$controllerClass = StringHelper::basename($generator->controllerNS."\\".$generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$pk = $pks?$pks[0] : 'id';
$searchType = $generator->searchType;
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();
echo "<?php\n";
?>

namespace <?= ltrim($generator->controllerNS, '\\') ?>;

use <?= ltrim($generator->modelClass, '\\') ?>;
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use app\core\db\Query;
use app\core\db\ActiveDataProvider;
use app\core\helpers\RequestHelper;
<?php
if($searchType == Generator::searchTypeSearchMatch){
echo "use Jsyqw\ParamValidate\filters\ActiveDataFilter;".PHP_EOL;
echo "use Jsyqw\ParamValidate\\filters\SearchMatch;".PHP_EOL;
}
?>
use Jsyqw\ParamValidate\ParamsValidate;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
     * 列表
     * 默认分页参数
     *      每页条数：per_page
     *      第几页： page
     * @return array
     */
    public function actionIndex(){
        $reqData = RequestHelper::get();
        //验证
        $validateModel = ParamsValidate::validate($reqData, [
            [['page','per_page'], 'integer'],
<?php
foreach ($generator->generateValidateSearchRules() as $rule){
echo "            ";
echo $rule.','.PHP_EOL;
}
?>
        ]);
        if($validateModel->hasErrors()){
            return $this->paramsError($validateModel->getFirstErrorMsg());
        }
        <?php
        $columns = '$columns = ['.PHP_EOL;
        foreach ($generator->getColumnNames() as $name){
            $columns .= '           "t.'.$name.'",'.PHP_EOL;
        }
        $columns .= '        ];'.PHP_EOL;
        echo $columns;
        ?>
        $query = new Query();
        $query->select($columns);
        $query->from(<?= $modelClass ?>::tableName() . ' t');
<?php
$search = '';
if(Generator::searchTypeSearchMatch==$searchType){
$conditionDesc = "";
$conditions = $generator->generateSearchSearchMatchConditions();
$num = count($conditions);
foreach ($generator->generateSearchSearchMatchConditions() as $index => $condition){
    $suffix = PHP_EOL;
    if($index == $num-1){
        $suffix = "";
    }
$conditionDesc .= "            ".$condition.$suffix;
}
$search = <<<EOF
        \$dataFilter = new ActiveDataFilter([
            'searchModel' => $modelClass::className()
        ]);
        \$dataFilter->loadValidateModel(\$validateModel,[
$conditionDesc
        ]);
        \$filterCondition = \$dataFilter->build(false);
        \$query->andFilterWhere(\$filterCondition);
EOF;
    echo $search.PHP_EOL;
}else{
    foreach ($generator->generateSearchWhereFilterConditions() as $condition){
        echo "        ";
        print_r($condition);
    }
    echo PHP_EOL;
}
?>
        $query->orderBy('t.id desc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $list = $dataProvider->getModels();
        $total = $dataProvider->getTotalCount();

        $result = [
            'list' => $list,
            'total' => $total,
        ];

        return $this->success($result);
    }

    /**
     * 创建
     * @return array
     */
    public function actionCreate()
    {
        $reqData = RequestHelper::post();
        //验证
        $validateModel = ParamsValidate::validate($reqData, [
<?php
foreach ($generator->generateFormValidateRules() as $column => $rule){
    if(in_array($column,['id'])){
        continue;
    }
    echo "            ".$rule.','.PHP_EOL;
}
?>
        ]);
        if($validateModel->hasErrors()){
            return $this->paramsError($validateModel->getFirstErrorMsg());
        }
        $model = new <?= $modelClass ?>();
        $model->load($reqData);
        <?php
        if(in_array("create_time",$generator->getColumnNames())){
            echo "\$model->create_time = time();".PHP_EOL;
        }
        ?>
        <?php
        if(in_array("update_time",$generator->getColumnNames())){
            echo "\$model->update_time = time();".PHP_EOL;
        }
        ?>

        $model->save();
        if($model->hasErrors()){
            return $this->error($model->getFirstErrorMsg());
        }
        return $this->success();
    }


    /**
     * 修改
     * @return array
     */
    public function actionUpdate()
    {
        $reqData = RequestHelper::post();
        //验证
        $validateModel = ParamsValidate::validate($reqData, [
<?php
foreach ($generator->generateFormValidateRules() as $column => $rule){
    echo "            ".$rule.','.PHP_EOL;
}
?>
        ]);
        if($validateModel->hasErrors()){
            return $this->paramsError($validateModel->getFirstErrorMsg());
        }
        $model = <?= $modelClass ?>::findOne($validateModel-><?= $pk ?>);
        if(!$model){
            return $this->error('信息不存在!');
        }
        $model->load($reqData);
        <?php
        if(in_array("update_time",$generator->getColumnNames())){
            echo "\$model->update_time = time();".PHP_EOL;
        }
        ?>

        $model->save();
        if($model->hasErrors()){
            return $this->error($model->getFirstErrorMsg());
        }
        return $this->success();
    }

    /**
     * 详情
     * @param $id
     * @return array
     */
    public function actionView($id)
    {
        $model = <?= $modelClass ?>::findOne($id);
        if(!$model){
            return $this->error('信息不存在!');
        }
        $data = $model->toArray();
        return $this->success($data);
    }

    /**
     * 删除,默认是软删除
     */
    public function actionDelete()
    {
        $id = RequestHelper::post('id');
        if(!$id){
            return $this->error('参数有误');
        }
        //模型
        $model = <?= $modelClass ?>::findOne($id);
        if(!$model){
            return $this->success();
        }
        <?php
        if(in_array("status",$generator->getColumnNames())){
            echo "\$model->status = -1;".PHP_EOL;
        }else{
            echo "//todo 实现删除逻辑".PHP_EOL;
        }
        ?>
        <?php
        if(in_array("update_time",$generator->getColumnNames())){
            echo "\$model->update_time = time();".PHP_EOL;
        }
        ?>
        $model->save();
        if($model->hasErrors()){
            return $this->error($model->getFirstErrorMsg());
        }
        return $this->success();
    }
}