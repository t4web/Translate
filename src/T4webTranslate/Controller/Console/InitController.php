<?php

namespace T4webTranslate\Controller\Console;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Ddl;
use Zend\Db\Sql\Ddl\Column;
use Zend\Db\Sql\Ddl\Constraint;
use Zend\Db\Sql\Sql;

class InitController extends AbstractActionController
{

    protected $tableName = 'words';

    /**
     * @var Adapter
     */
    private $dbAdapter;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function runAction()
    {
        echo "Translate module init" . PHP_EOL;
        echo "Create table" . PHP_EOL;

        $message = "Success completed" . PHP_EOL;

        try {
            $this->createTableWords();
        } catch (\PDOException $e) {
            $message .= $e->getMessage() . PHP_EOL;
            //$message .= $e->getTraceAsString() . PHP_EOL;
        }

        try {
            $this->createTableLanguages();
        } catch (\PDOException $e) {
            $message .= $e->getMessage() . PHP_EOL;
            //$message .= $e->getTraceAsString() . PHP_EOL;
        }

        return $message;
    }

    private function createTableWords()
    {

        $table = new Ddl\CreateTable($this->tableName);

        $id = new Column\Integer('id');
        $id->setOption('autoincrement', true);
        $table->addColumn($id);

        $table->addColumn(new Column\Integer('lang_id'));
        $table->addColumn(new Column\Varchar('key', 255));
        $table->addColumn(new Column\Text('translate', 500));

        $table->addConstraint(new Constraint\PrimaryKey('id'));
        $table->addConstraint(new Constraint\UniqueKey(['key', 'lang_id']));

        $sql = new Sql($this->dbAdapter);

        $this->dbAdapter->query($sql->buildSqlString($table), Adapter::QUERY_MODE_EXECUTE);
        $this->dbAdapter->query("ALTER TABLE {$this->tableName} CHANGE `key` `key` VARCHAR(255) BINARY CHARACTER SET utf8 COLLATE utf8_general_ci;");
    }

    private function createTableLanguages()
    {

        $table = new Ddl\CreateTable('languages');

        $id = new Column\Integer('id');
        $id->setOption('autoincrement', true);
        $table->addColumn($id);

        $table->addColumn(new Column\Varchar('code', 2));
        $table->addColumn(new Column\Varchar('locale', 6));
        $table->addColumn(new Column\Varchar('name', 50));
        $table->addColumn(new Column\Integer('default', false, 0));

        $table->addConstraint(new Constraint\PrimaryKey('id'));
        $table->addConstraint(new Constraint\UniqueKey('code'));
        $table->addConstraint(new Constraint\UniqueKey('locale'));

        $sql = new Sql($this->dbAdapter);

        $this->dbAdapter->query($sql->buildSqlString($table), Adapter::QUERY_MODE_EXECUTE);
    }
}
