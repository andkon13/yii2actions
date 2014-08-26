<?php

use yii\helpers\Html;


/**
 * @var yii\web\View                     $this
 * @var \andkon\yii2actions\ActiveRecord $model
 * @var string                           $formView
 */
$modelName = Yii::t('app', $model->formName());

$this->title = 'Update ' . $model;
$this->params['breadcrumbs'][] = ['label' => $modelName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= strtolower($modelName) ?>-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render($formView, [
        'model' => $model,
    ])
    ?>

</div>
