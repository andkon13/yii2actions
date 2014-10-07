<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 08.08.14
 * Time: 11:04
 */

namespace andkon\yii2actions\actions\base;

use app\common\MyActiveRecords;
use app\modules\users\components\UsersFlash;

trait SaveModel
{
    /**
     * СОхраныет модель
     *
     * @param MyActiveRecords $model
     * @param array           $attributes
     *
     * @return bool
     */
    protected function saveModel($model, $attributes)
    {
        $model->setAttributes($attributes);
        if (method_exists($model, 'doSave')) {
            $save = $model->doSave();
        } else {
            $save = $model->save();
        }

        return $save;
    }
}
