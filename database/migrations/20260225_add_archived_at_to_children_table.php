<?php
namespace Database\Migrations;

use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20260225_add_archived_at_to_children_table
 * 
 * Add archived_at column to support archiving child profiles
 */
class Migration_20260225_add_archived_at_to_children_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE children ADD COLUMN IF NOT EXISTS archived_at TIMESTAMP WITH TIME ZONE DEFAULT NULL;"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw(
            "ALTER TABLE children DROP COLUMN IF EXISTS archived_at;"
        );
    }
}
