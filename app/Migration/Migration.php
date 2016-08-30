<?php
namespace App\Migration;

use DB;
use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration
{
    protected $capsule;
    protected $schema;

    protected function init()
    {
        $this->capsule = new DB;
        $this->schema = $this->capsule->schema();
    }
}
