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
use andkon\yii2actions\actions\base\SaveModel;
use andkon\yii2actions\ActiveRecord;
use andkon\yii2actions\Controller;
use yii\base\Action;

class Update extends Action
{
    use FindViews;
    use SaveModel;
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

        $post = $controller->getPost($model->formName());
        if ($post) {
            $this->saveModel($model, $post);
        }

        $view     = $this->getViewPath($model);
        $formView = $this->getFormViewPath();

        return $controller->render($view, ['model' => $model, 'formView' => $formView]);
    }
}
