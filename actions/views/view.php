<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View                     $this
 * @var \andkon\yii2actions\ActiveRecord $model
 */
$modelName                     = Yii::t('app', $model->formName());
$this->title                   = $model->__toString();
$this->params['breadcrumbs'][] = ['label' => $modelName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$fields                        = [];
foreach (array_keys($model->getAttributes()) as $field) {
    if (in_array($field, ['id', 'updated', 'created'])) {
        continue;
    }

    $fields[] = $field;
}

echo Html::beginTag('div', ['class' => $modelName . '-view']);
echo Html::tag('h1', Html::encode($this->title));
echo Html::beginTag('p');
echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
echo Html::a(
    Yii::t('app', 'Delete'),
    ['delete', 'id' => $model->id],
    [
        'class' => 'btn btn-danger',
        'data'  => [
            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
            'method'  => 'post',
        ],
    ]
);
echo Html::endTag('p');
echo DetailView::widget(
    [
        'model'      => $model,
        'attributes' => $fields,
    ]
);
echo Html::endTag('div');
