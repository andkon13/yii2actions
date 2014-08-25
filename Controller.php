<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 06.06.14
 * Time: 16:50
 */

namespace andkon\yii2actions;

use app\models\User;
use Guzzle\Common\Exception\ExceptionCollection;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class Controller extends \yii\web\Controller
{
    private $_usersAccess = [
        'users' => ['settings', 'index', 'forget', 'registry'],
        'site'  => ['index', 'error', 'about', 'logout', 'login'],
        'cron'  => ['test', 'index'],
    ];

    protected $_model = null;

    public function init()
    {
        parent::init();
        \Yii::$app->params['menu'] = [];
    }

    protected function model($model_name = false)
    {
        if ($model_name) {
            $this->_model = $model_name;
        }

        return $this->_model;
    }

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

    public function beforeAction($action)
    {
        if (in_array($action->id, ['logout', 'delete'])) {
            $this->enableCsrfValidation = false;
        }

        if (parent::beforeAction($action)) {
            if (!User::isAdmin()) {
                $ctrId = $action->controller->id;
                if (isset($this->_usersAccess[$ctrId]) && in_array($action->id, $this->_usersAccess[$ctrId])) {
                    return true;
                }

                throw new HttpException('403 - У вас нехватает прав для этого действия');
            }

            return true;
        }

        return false;
    }

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

    public function createUrl($view, $param = array())
    {
        $path  = $this->module->id . '/' . $this->id . '/' . $view;
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
        if ($this->_model == null) {
            throw new ExceptionCollection('В контроллере ' . get_called_class() . ' не задана модель ($_model)');
        }

        return $this->_model;
    }

    protected function findModel($id)
    {
        $model = $this->getModelName();
        if (($model = $model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
