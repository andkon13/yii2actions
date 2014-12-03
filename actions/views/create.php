<?php

use yii\helpers\Html;

/**
 * @var  yii\web\View                     $this
 * @var  \andkon\yii2actions\ActiveRecord $model
 * @var bool|string                       $formView
 */
$modelName = Yii::t('app', $model->formName());

$this->title                   = 'Create ' . $modelName;
$this->params['breadcrumbs'][] = ['label' => $modelName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= strtolower($modelName) ?>-create">

    <h1><?= Html::encode($this->title) ?></h1>

<?php if ($formView) {
    echo $this->render($formView, ['model' => $model]);
} else {
    echo $this->render('_form', ['model' => $model]);
} ?>

</div>
