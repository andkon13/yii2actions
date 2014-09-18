<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 25.08.14
 * Time: 16:55
 */

namespace andkon\yii2actions;


class ActiveRecord extends \yii\db\ActiveRecord
{
    public function beforeValidate()
    {
        if ($this->getIsNewRecord()) {
            if ($this->hasAttribute('created')) {
                $this->setAttribute('created', date('Y-m-d H:i:s'));
            }
        } else {
            if ($this->hasAttribute('updated')) {
                $this->setAttribute('updated', date('Y-m-d H:i:s'));
            }
        }

        return parent::beforeValidate();
    }

    protected static function loadOrInit($attr)
    {
        $class = get_called_class();
        /** @var ActiveRecord $model */
        $model = new $class();
        $model = $model->load($attr);
        if (!$model) {
            $model = new $class();
            $model->setAttributes($attr);
        }

        return $model;
    }

    /**
     * Переопределение фцнкции
     *
     * @return mixed|string
     */
    function __toString()
    {
        if ($this->hasAttribute('name')) {
            return $this->name;
        }

        return 'Объект класса ' . get_called_class();
    }
}
