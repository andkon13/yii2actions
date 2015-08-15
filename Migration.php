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
class Migration extends \webtoucher\migrate\components\Migration
{
    protected $tablesPrefix = '';
    protected $tables = [];
    protected $table = null;
    protected $foreignKeys = [];
    protected $fields = [];
    protected $vals = [];

    /**
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tables      = $this->setTables();
        $this->fields      = $this->setFields();
        $this->foreignKeys = $this->setForeignKeys();
        $this->vals        = $this->setVals();
    }

    /**
     * Назначает таблицы для их создания при UP/удалени при DOWN
     * <code>
     * [
     *      'table1' =>
     *      [
     *          'id' => 'pk',
     *          'name' => 'varchar(255)',
     *          ...
     *      ]
     *];
     * </code>
     *
     * @return array
     */
    public function setTables()
    {
        return [];
    }

    /**
     * Устанавливает поля которые будт добавлены/удалены при up/down (необходтмо назначить $this->table)
     * <code>
     * [
     *   'name' => 'varchar(255)',
     *   'status' => 'int',
     *   ...
     * ]
     * </code>
     *
     * @return array
     */
    public function setFields()
    {
        return [];
    }

    /**
     * Устанавливает внешние ключи которые буут добавлены/удалены при up/down
     * <code>
     * [
     *      [
     *          'tableFrom', 't2_id',
     *          'tableTo', 'id'
     *      ],
     *      [
     *          'tableFrom2', 't3_id',
     *          'tableTo1', 'id',
     *          'delete' => 'CASCADE',// default
     *          'update' => 'NO ACTION'// default
     *      ],
     * ]
     * </code>
     *
     * @return array
     */
    public function setForeignKeys()
    {
        return [];
    }

    /**
     * Устанавливает значения которые будут добавлены в БД (при откате не удаляются)
     * <code>
     * [
     *      'table_name' =>
     *      [
     *          ['id' => 1, 'name' => 'xxx', 'status' => 1],
     *          ['id' => 2, 'name' => 'yyy'],
     *      ]
     * ]
     * </code>
     * @return array
     */
    public function setVals()
    {
        return [];
    }

    /**
     * переопределение safeUp
     *
     * @return bool
     */
    public function safeUp()
    {

        $this->upTables();
        $this->upFields();
        $this->upKeys();
        $this->upVals();

        return (false !== parent::safeUp());
    }

    /**
     * переопределение safeDown
     *
     * @return bool
     */
    public function safeDown()
    {
        if (false !== parent::safeDown()) {
            $result = true;
            $result = ($result && $this->downKeys());
            $result = ($result && $this->downFields());
            $result = ($result && $this->downTable());

            return $result;
        }

        return false;
    }

    /**
     * Создание таблиц
     *
     * @return bool
     */
    protected function upTables()
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
    protected function upFields()
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
    protected function upKeys()
    {
        if (empty($this->foreignKeys)) {
            return true;
        }

        foreach ($this->foreignKeys as $key) {
            $name = $this->getFkName($key);
            $key  = array_merge(['delete' => 'CASCADE', 'update' => 'NO ACTION'], $key);
            $this->addForeignKey(
                $name,
                $key['from'][0],
                $key['from'][1],
                $key['to'][0],
                $key['to'][1],
                $key['delete'],
                $key['update']
            );
        }

        return true;
    }

    /**
     * добавление записей в тиблицы
     *
     * @return bool
     */
    protected function upVals()
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
    protected function downKeys()
    {
        if (empty($this->foreignKeys)) {
            return true;
        }

        foreach ($this->foreignKeys as $key) {
            $name = $this->getFkName($key);
            $this->dropForeignKey($name, $key['from'][0]);
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
    protected function getFkName($key)
    {
        $name = implode('_', array_merge($key['from'], $key['to']));

        return $name;
    }

    /**
     * Удаление полей
     *
     * @return bool
     */
    protected function downFields()
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
    protected function downTable()
    {
        if (empty($this->tables)) {
            return true;
        }

        foreach (array_keys($this->tables) as $table) {
            $this->dropTable($this->tablesPrefix . $table);
        }

        return true;
    }

    /**
     * Возвращает название внешнего ключа по таблице и
     *
     * @param string $table
     * @param string $column
     *
     * @return string|null
     * @throws Exception
     * @throws \yii\base\NotSupportedException
     */
    protected function getFKExistName($table, $column)
    {
        $db = \Yii::$app->getDb();
        preg_match('/dbname=(.+)/', $db->getSchema()->db->dsn, $m);
        if (isset($m[1])) {
            $schema = $m[1];
        } else {
            throw new Exception('scheme name not found');
        }

        $sql = "
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA ='$schema' AND TABLE_NAME ='$table' AND
            CONSTRAINT_NAME <>'PRIMARY' AND REFERENCED_TABLE_NAME is not null
            and COLUMN_NAME = '$column'
        ";

        return $db->createCommand($sql)->queryScalar();
    }
}
