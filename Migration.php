<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 16.06.14
 * Time: 14:20
 */

namespace andkon\yii2actions;

class Migration extends \yii\db\Migration
{
    protected $tables = [];
    protected $table = null;
    protected $foreignKeys = [];
    protected $fields = [];
    protected $vals = [];

    public function safeUp()
    {
        $this->_upTables();
        $this->_upFields();
        $this->_upKeys();
        $this->_upVals();

        parent::safeUp();
    }

    public function safeDown()
    {
        parent::safeDown();

        $this->_downKeys();
        $this->_downFields();
        $this->_downTable();
    }

    private function _upTables()
    {
        if (empty($this->tables)) {
            return true;
        }

        $options = 'ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci';
        foreach ($this->tables as $table => $fields) {
            $this->createTable($table, $fields, $options);
        }

        return true;
    }

    private function _upFields()
    {
        if (empty($this->fields) || $this->table == null) {
            return true;
        }

        foreach ($this->fields as $field => $type) {
            $this->addColumn($this->table, $field, $type);
        }

        return true;
    }

    private function _upKeys()
    {
        if (empty($this->foreignKeys)) {
            return true;
        }

        foreach ($this->foreignKeys as $key) {
            $name = $this->_getFkName($key);
            $key  = array_merge(['delete' => 'CASCADE', 'update' => 'NO ACTION'], $key);
            $this->addForeignKey(
                $name, $key['from'][0], $key['from'][1],
                $key['to'][0], $key['to'][1],
                $key['delete'], $key['update']
            );
        }

        return true;
    }

    private function _upVals()
    {
        if (empty($this->vals)) {
            return true;
        }

        foreach ($this->vals as $table => $rows) {
            foreach ($rows as $row) {
                $this->insert($table, $row);
            }
        }

        return true;
    }

    private function _downKeys()
    {
        if (empty($this->foreignKeys)) {
            return true;
        }

        foreach ($this->foreignKeys as $key) {
            $name = $this->_getFkName($key);
            $this->dropForeignKey($name, $key['from'][0]);
        }

        return true;
    }

    private function _getFkName($key)
    {
        $name = implode('_', array_merge($key['from'], $key['to']));

        return $name;
    }

    private function _downFields()
    {
        if (empty($this->fields) || $this->table == null) {
            return true;
        }

        foreach (array_keys($this->fields) as $field) {
            $this->dropColumn($this->table, $field);
        }

        return true;
    }

    private function _downTable()
    {
        if (empty($this->tables)) {
            return true;
        }

        foreach (array_keys($this->tables) as $table) {
            $this->dropTable($table);
        }

        return true;
    }
}