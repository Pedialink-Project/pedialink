<?php
namespace Database\Migrations;

use Library\Framework\Database\QueryBuilder;

class Migration_20260419103000_add_archive_reason_and_is_deceased_to_children_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw("ALTER TABLE children ADD COLUMN archive_reason VARCHAR(50) NULL DEFAULT NULL");
        QueryBuilder::raw("ALTER TABLE children ADD COLUMN is_deceased BOOLEAN NOT NULL DEFAULT FALSE");
    }

    public function down(): void
    {
        QueryBuilder::raw("ALTER TABLE children DROP COLUMN is_deceased");
        QueryBuilder::raw("ALTER TABLE children DROP COLUMN archive_reason");
    }
}
