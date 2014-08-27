<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 16.06.14
 * Time: 14:20
 */

namespace andkon\yii2actions;
/**
 * Class Migration
 * Надстройка над миграциями
 *
 * @package andkon\yii2actions
 */
class Migration extends \yii\db\Migration
{
    protected $tablesPrefix = '';
    protected $tables = [];
    protected $table = null;
    protected $foreignKeys = [];
    protected $fields = [];
    protected $vals = [];

    /**
     * переопределение safeUp
     *
     * @return void
     */
    public function safeUp()
    {
        $this->upTables();
        $this->upFields();
        $this->upKeys();
        $this->upVals();

        parent::safeUp();
    }

    /**
     * переопределение safeDown
     *
     * @return void
     */
    public function safeDown()
    {
        parent::safeDown();

        $this->downKeys();
        $this->downFields();
        $this->downTable();
    }

    /**
     * Создание таблиц
     *
     * @return bool
     */
    private function upTables()
    {
        if (empty($this->tables)) {
            return true;
        }

        $options = 'ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci';
        foreach ($this->tables as $table => $fields) {
            $this->createTable($this->tablesPrefix . $table, $fields, $options);
        }

        return true;
    }

    /**
     * добавление полей
     *
     * @return bool
     */
    private function upFields()
    {
        if (empty($this->fields) || $this->table == null) {
            return true;
        }

        foreach ($this->fields as $field => $type) {
            $this->addColumn($this->tablesPrefix . $this->table, $field, $type);
        }

        return true;
    }

    /**
     * создание внешних ключей
     *
     * @return bool
     */
    private function upKeys()
    {
        if (empty($this->foreignKeys)) {
            return true;
        }

        foreach ($this->foreignKeys as $key) {
            $name = $this->getFkName($key);
            $key  = array_merge(['delete' => 'CASCADE', 'update' => 'NO ACTION'], $key);
            $this->addForeignKey(
                $name, $this->tablesPrefix . $key['from'][0], $key['from'][1],
                $this->tablesPrefix . $key['to'][0], $key['to'][1],
                $key['delete'], $key['update']
            );
        }

        return true;
    }

    /**
     * добавление записей в тиблицы
     *
     * @return bool
     */
    private function upVals()
    {
        if (empty($this->vals)) {
            return true;
        }

        foreach ($this->vals as $table => $rows) {
            foreach ($rows as $row) {
                $this->insert($this->tablesPrefix . $table, $row);
            }
        }

        return true;
    }

    /**
     * удаление внешних ключей
     *
     * @return bool
     */
    private function downKeys()
    {
        if (empty($this->foreignKeys)) {
            return true;
        }

        foreach ($this->foreignKeys as $key) {
            $name = $this->getFkName($key);
            $this->dropForeignKey($name, $this->tablesPrefix . $key['from'][0]);
        }

        return true;
    }

    /**
     * генерирует имя внешнего ключа
     *
     * @param string $key
     *
     * @return string
     */
    private function getFkName($key)
    {
        $name = implode('_', array_merge($key['from'], $key['to']));

        return $name;
    }

    /**
     * Удаление полей
     *
     * @return bool
     */
    private function downFields()
    {
        if (empty($this->fields) || $this->table == null) {
            return true;
        }

        foreach (array_keys($this->fields) as $field) {
            $this->dropColumn($this->tablesPrefix . $this->table, $field);
        }

        return true;
    }

    /**
     * удаление таблиц
     *
     * @return bool
     */
    private function downTable()
    {
        if (empty($this->tables)) {
            return true;
        }

        foreach (array_keys($this->tables) as $table) {
            $this->dropTable($this->tablesPrefix . $table);
        }

        return true;
    }
}