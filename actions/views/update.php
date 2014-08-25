<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var  \andkon\yii2actions\ActiveRecord $model */
$modelName = Yii::t('app', $model->name);

$this->title = 'Update ' . $modelName;
$this->params['breadcrumbs'][] = ['label' => $modelName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= strtolower($modelName) ?>-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
