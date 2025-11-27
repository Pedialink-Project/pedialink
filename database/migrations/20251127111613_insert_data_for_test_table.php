<?php
namespace Database\Migrations;
use Library\Framework\Database\QueryBuilder;

/**
 * Migration: 20251127111613_insert_data_for_test_table
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class Migration_20251127111613_insert_data_for_test_table implements \Library\Framework\Database\Migration
{
    public function up(): void
    {
        QueryBuilder::raw(
            "INSERT INTO test (name, category, stock, price)
                VALUES
                ('BedSheet', 'bedding', 100, 29.99),
                ('Shoe', 'footwear', 50, 49.99),
                ('Sofa', 'furniture', 20, 399.99),
                ('Ceiling Light', 'lighting', 75, 89.99),
                ('Cookware Set', 'kitchen', 40, 129.99),
                ('Knife', 'kitchen', 100, 19.99),
                ('Pillow', 'bedding', 200, 15.99),
                ('Blanket', 'bedding', 150, 59.99),
                ('Dining Table', 'furniture', 10, 499.99),
                ('Chair', 'furniture', 60, 79.99),
                ('Desk Lamp', 'lighting', 80, 34.99),
                ('Toaster', 'kitchen', 45, 24.99),
                ('Blender', 'kitchen', 30, 89.50),
                ('Microwave', 'kitchen', 12, 149.99),
                ('Area Rug', 'furniture', 25, 129.00),
                ('Curtains', 'bedding', 90, 39.99),
                ('Mattress', 'bedding', 15, 699.00),
                ('Desk', 'furniture', 22, 229.99),
                ('Bookshelf', 'furniture', 18, 119.99),
                ('Coffee Table', 'furniture', 28, 149.99),
                ('Wardrobe', 'furniture', 8, 899.99)
                ;"
        );
    }

    public function down(): void
    {
        QueryBuilder::raw("DELETE FROM test;");
    }
}