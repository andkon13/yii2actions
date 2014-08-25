<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 11.08.14
 * Time: 18:13
 */

namespace andkon\yii2actions\actions\base;

trait BeforeRun
{
    public function beforeRun()
    {
        $actionName = 'action' . ucfirst($this->id);
        if (method_exists($this->controller, $actionName)) {
            return $this->controller->$actionName();
        }

        return parent::beforeRun();
    }
}
