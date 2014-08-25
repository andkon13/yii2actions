<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 06.08.14
 * Time: 18:35
 */

namespace andkon\yii2actions\actions;

use andkon\yii2actions\actions\base\BeforeRun;
use andkon\yii2actions\actions\base\FindViews;
use andkon\yii2actions\ActiveRecord;
use andkon\yii2actions\Controller;
use yii\base\Action;

class View extends Action
{
    use FindViews;
    use BeforeRun;

    public function run($id)
    {
        /** @var Controller $controller */
        $controller = $this->controller;
        $model      = $controller->getModelName();
        /** @var ActiveRecord $model */
        $model = new $model();
        $model = $model->findOne(['id' => $id]);
        if (!$model) {
            throw new \HttpException(404);
        }

        $view = $this->getViewPath($model);

        return $controller->render($view, ['model' => $model]);
    }
}
