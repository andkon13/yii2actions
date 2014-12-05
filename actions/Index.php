<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 11.08.14
 * Time: 10:42
 */

namespace andkon\yii2actions\actions;

use andkon\yii2actions\actions\base\BeforeRun;
use andkon\yii2actions\actions\base\FindViews;
use andkon\yii2actions\ActiveRecord;
use andkon\yii2actions\Controller;
use yii\base\Action;
use yii\data\ActiveDataProvider;

/**
 * Class Index
 *
 * @package andkon\yii2actions\actions
 */
class Index extends Action
{
    use FindViews;
    use BeforeRun;

    /**
     * @inheritdoc
     * @return string
     * @throws \yii\base\Exception
     */
    public function run()
    {
        /** @var Controller $controller */
        $controller = $this->controller;
        if ($this->controllerAction) {
            return $controller->actionIndex();
        }

        $modelClass = $controller->getModelName();
        if (class_exists($modelClass . 'Search')) {
            $modelClass = $modelClass . 'Search';
            /** @var ActiveRecord $model */
            $model = new $modelClass();
            if (method_exists($model, 'search')) {
                $dataProvider = $model->search(\Yii::$app->request->queryParams);
            }
        }

        if (!isset($dataProvider)) {
            /** @var ActiveRecord $model */
            $model        = new $modelClass();
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $model::find(),
                ]
            );
        }

        $view       = $this->getViewPath($model);
        $renderVars = [
            'dataProvider' => $dataProvider,
            'model'        => $model,
            'searchModel'  => $model,
        ];

        return $controller->render(
            $view,
            $renderVars
        );
    }
}
