<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateDataFieldForTablePolygon extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $table = $this->table('polygon');

        $table->addColumn('created_date', 'timestamp', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('renameColumn', 'string');
        $table->save();
    }
}
