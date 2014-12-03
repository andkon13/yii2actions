<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 25.08.14
 * Time: 16:55
 */

namespace andkon\yii2actions;

/**
 * Class ActiveRecord
 *
 * @package andkon\yii2actions
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     * @return bool
     */
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

    /**
     * Ищет модель по атрибутам или инициализирует новую с переданными атрибутами
     *
     * @param array $attributes
     *
     * @return ActiveRecord
     */
    protected static function findOrInit($attributes)
    {
        $class = get_called_class();
        /** @var ActiveRecord $model */
        $model = new $class();
        $model = $model->load($attributes);
        if (!$model) {
            $model = new $class();
            $model->setAttributes($attributes);
        }

        return $model;
    }

    /**
     * Ищет модель или Создает модель с переданными атрибутами
     *
     * @param array $attributes
     *
     * @return ActiveRecord
     */
    public static function findOrCreate($attributes)
    {
        $model = self::findOrInit($attributes);
        if ($model->getIsNewRecord()) {
            $model->save();
        }

        return $model;
    }

    /**
     * Переопределение фцнкции
     *
     * @return mixed|string
     */
    public function __toString()
    {
        if ($this->hasAttribute('name')) {
            return $this->name;
        }

        return 'Объект класса ' . get_called_class();
    }
}
