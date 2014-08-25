<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                     $this
 * @var yii\data\ActiveDataProvider      $dataProvider
 * @var \andkon\yii2actions\ActiveRecord $model
 */

$this->title                   = Yii::t('app', ucfirst($model->formName()));
$this->params['breadcrumbs'][] = $this->title;

echo Html::beginTag('div', ['class' => $model->formName() . '-index']);
echo Html::tag('h1', $this->title);
echo Html::tag('p', Html::a(Yii::t('app', 'Create') . ' ' . $this->title, ['create'], ['class' => 'btn btn-success']));
$fields = [['class' => 'yii\grid\SerialColumn']];
foreach (array_keys($model->getAttributes()) as $fieldName) {
    if (in_array($fieldName, ['id', 'updated', 'created'])) {
        continue;
    }

    $fields[] = $fieldName;
}

$fields[] = ['class' => 'yii\grid\ActionColumn'];

echo GridView::widget(
    [
        'dataProvider' => $dataProvider,
        'columns'      => $fields,
    ]
);

echo Html::endTag('div');
