<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                     $this
 * @var \andkon\yii2actions\ActiveRecord $model
 * @var yii\widgets\ActiveForm           $form
 */

$modelName = Yii::t('app', $model->formName());

echo Html::beginTag('div', ['class' => 'page-form']);

$form = ActiveForm::begin();
foreach ($model->getTableSchema()->getColumnNames() as $fieldName) {
    if (in_array($fieldName, ['id', 'updated', 'created'])) {
        continue;
    }

    $field = $model->getTableSchema()->getColumn($fieldName);
    switch ($field->type) {
        case 'smallint':
            echo $form->field($model, $fieldName)->checkbox();
            break;
        case 'timestamp':
        case 'date':
            echo Html::beginTag('div', ['class' => 'form-group field-page-' . $fieldName]);
            echo Html::activeLabel($model, $fieldName, ['class' => 'control-label']);
            echo Html::beginTag('div');
            echo \yii\jui\DatePicker::widget(
                [
                    'language'      => 'ru',
                    'model'         => $model,
                    'attribute'     => $fieldName,
                    'clientOptions' => [
                        'dateFormat' => 'yy-mm-dd',
                    ],
                ]
            );
            echo Html::endTag('div');
            echo Html::endTag('div');
            break;
        default:
            echo $form->field($model, $fieldName)->textInput(['maxlength' => 255]);
            break;
    }
}

echo Html::beginTag('div', ['class' => 'form-group']);
echo Html::submitButton(
    $model->isNewRecord ? 'Create' : 'Update',
    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
);
echo Html::endTag('div');
ActiveForm::end();
echo Html::endTag('div');
