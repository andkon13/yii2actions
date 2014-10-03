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

class Index extends Action
{
    use FindViews;
    use BeforeRun;

    public function run()
    {
        /** @var Controller $controller */
        $controller = $this->controller;
        if($this->controllerAction){
            return $controller->actionIndex();
        }

        $model      = $controller->getModelName();
        /** @var ActiveRecord $model */
        $model        = new $model();
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $model::find(),
            ]
        );
        $view         = $this->getViewPath($model);

        return $controller->render(
            $view,
            [
                'dataProvider' => $dataProvider,
                'model'        => $model,
            ]
        );
    }
}
