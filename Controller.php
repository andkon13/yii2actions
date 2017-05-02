<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 06.06.14
 * Time: 16:50
 */

namespace andkon\yii2actions;

use yii\base\Application;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

class Controller extends \yii\web\Controller
{
    /** @var null|string */
    public $model = null;

    /**
     * Инициализация
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        \Yii::$app->params['menu'] = [];
    }

    /**
     * Возвращает имя модели
     *
     * @param bool|string $model_name
     *
     * @return bool
     */
    protected function model($model_name = false)
    {
        if ($model_name) {
            $this->model = $model_name;
        }

        return $this->model;
    }

    /**
     * Список действий по умолчанию
     *
     * @return array
     */
    public function actions()
    {
        $actions = [
            'create' => 'andkon\yii2actions\actions\Create',
            'update' => 'andkon\yii2actions\actions\Update',
            'index'  => 'andkon\yii2actions\actions\Index',
            'view'   => 'andkon\yii2actions\actions\View',
            'delete' => 'andkon\yii2actions\actions\Delete',
        ];

        return array_merge(parent::actions(), $actions);
    }

    /**
     * Триггер перед действием
     *
     * @param Action $action
     *
     * @return mixed
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['logout', 'delete'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Возвращает данные из $_POST
     *
     * @param null|string $name
     * @param bool        $returnIsNull
     *
     * @return bool
     */
    public function getPost($name = null, $returnIsNull = false)
    {
        if ($name == null) {
            $name = $this->model();
        }

        $post = \Yii::$app->getRequest()->post($name);
        if (empty($post)) {
            return $returnIsNull;
        }

        return $post;
    }

    /**
     * Возвращает url для данного контроллера
     *
     * @param string $view
     * @param array  $param
     *
     * @return mixed
     */
    public function createUrl($view, $param = array())
    {
        if ($this->module instanceof Application) {
            $path = $this->id . '/' . $view;
        } else {
            $path = $this->module->id . '/' . $this->id . '/' . $view;
        }

        $param = array_merge([$path], $param);
        $url   = \Yii::$app->getUrlManager()->createUrl($param);

        return $url;
    }

    /**
     * Возвращает модель связанную с контроллером
     *
     * @return ActiveRecord
     */
    public function getModelName()
    {
        if ($this->model == null) {
            throw new Exception('В контроллере ' . get_called_class() . ' не задана модель ($model)');
        }

        return $this->model;
    }

    /**
     * Возвращает модель по id
     *
     * @param $id
     *
     * @return ActiveRecord
     * @throws \yii\web\NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = $this->getModelName();
        if (($model = $model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
