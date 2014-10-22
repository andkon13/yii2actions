<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 07.08.14
 * Time: 19:35
 */

namespace andkon\yii2actions\actions\base;

use andkon\yii2actions\ActiveRecord;

/**
 * Class FindViews
 *
 * @package app\common\actions\base
 */
trait FindViews
{
    /**
     * @var null|bool|string
     */
    public $formViewPath = null;

    /**
     * @return bool|null|string
     */
    public function getFormViewPath()
    {
        return $this->formViewPath;
    }

    /**
     * Определяет какое представление будет использовано для формы
     *
     * @param ActiveRecord $model
     *
     * @return void
     */
    private function setFormViewPath($model)
    {
        $formView = $this->controller->getViewPath() . '/_form';
        if (file_exists($formView . '.php')) {
            $formView = '/' . strtolower($model->formName()) . '/_form';
        } else {
            $formView = '_form';
        }

        $this->formViewPath = $formView;
    }

    /**
     * Возвращает путь к представлению
     *
     * @param ActiveRecord $model
     * @param null|string  $actionName
     *
     * @return string
     */
    protected function getViewPath($model, $actionName = null)
    {
        if ($actionName == null) {
            $reflection = new \ReflectionClass($this);
            $actionName = strtolower($reflection->getShortName());
        }

        $view = $this->controller->getViewPath();
        if (file_exists($view . '/' . $actionName . '.php')) {
            $view = $actionName;
        } else {
            $view = '@vendor/andkon/yii2actions/actions/views/' . $actionName;
            $this->setFormViewPath($model);
        }

        return $view;
    }
}
