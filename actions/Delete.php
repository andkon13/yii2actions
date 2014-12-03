<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 11.08.14
 * Time: 17:53
 */

namespace andkon\yii2actions\actions;

use andkon\yii2actions\actions\base\BeforeRun;
use andkon\yii2actions\ActiveRecord;
use andkon\yii2actions\Controller;
use yii\base\Action;

/**
 * Class Delete
 *
 * @package andkon\yii2actions\actions
 */
class Delete extends Action
{
    use BeforeRun;

    /**
     * @inheritdoc
     *
     * @param int $id
     *
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \HttpException
     * @throws \yii\base\Exception
     */
    public function run($id)
    {
        /** @var Controller $controller */
        $controller = $this->controller;
        if ($this->controllerAction) {
            return $controller->actionDelete($id);
        }

        $model = $controller->getModelName();
        /** @var ActiveRecord $model */
        $model = new $model();
        $model = $model->findOne($id);
        if (!$model) {
            throw new \HttpException(404);
        }

        $model->delete();

        return $controller->redirect(['index']);
    }
}
