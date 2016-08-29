<?php
namespace App\Migration;

use Phinx\Migration\AbstractMigration;
use DB;

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