<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 06.08.14
 * Time: 10:54
 */

namespace andkon\yii2actions\actions;

use andkon\yii2actions\actions\base\BeforeRun;
use andkon\yii2actions\actions\base\FindViews;
use andkon\yii2actions\actions\base\SaveModel;
use andkon\yii2actions\ActiveRecord;
use andkon\yii2actions\Controller;
use yii\base\Action;

/**
 * Class Create
 *
 * @package andkon\yii2actions\actions
 */
class Create extends Action
{
    use FindViews;
    use SaveModel;
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
            return $controller->actionCreate();
        }

        $model = $controller->getModelName();
        /** @var ActiveRecord $model */
        $model = new $model;
        $post  = $controller->getPost($model->formName());
        if ($post) {
            if ($this->saveModel($model, $post)) {
                return $controller->redirect($controller->createUrl('update', ['id' => $model->id]));
            }
        }

        $view     = $this->getViewPath($model);
        $formView = $this->getFormViewPath();

        return $controller->render($view, ['model' => $model, 'formView' => $formView]);
    }
}
