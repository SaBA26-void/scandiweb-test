<?php


namespace App\Models\Products;


class Product extends AbstractProduct
{


    public static function findAll(?string $categoryName = null): array
    {
        $instance = new self();

        if ($categoryName !== null && $categoryName !== 'all') {
            $rows = $instance->db->query(
                'SELECT product_id, name, brand, is_in_stock, description, category_name
                 FROM products
                 WHERE category_name = :category_name
                 ORDER BY name',
                ['category_name' => $categoryName]
            );
        } else {
            $rows = $instance->db->query(
                'SELECT product_id, name, brand, is_in_stock, description, category_name
                 FROM products
                 ORDER BY name'
            );
        }

        return array_map(
            static fn(array $row) => self::fromProductRow($row),
            $rows
        );
    }


    public static function findById(string $id): ?self
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT product_id, name, brand, is_in_stock, description, category_name
             FROM products
             WHERE product_id = :id
             LIMIT 1',
            ['id' => $id]
        );

        if ($rows === []) {
            return null;
        }

        return self::fromProductRow($rows[0]);
    }


    private static function fromProductRow(array $productRow): self
    {
        $instance = new self();
        $productId = (string) $productRow['product_id'];

        $images = $instance->db->query(
            'SELECT product_image_id, product_id, product_image_url
             FROM product_images
             WHERE product_id = :product_id
             ORDER BY product_image_id',
            ['product_id' => $productId]
        );

        $prices = $instance->db->query(
            'SELECT p.amount, c.label AS currency_label, c.symbol AS currency_symbol
             FROM prices p
             INNER JOIN currencies c ON c.currency_id = p.currency_id
             WHERE p.product_id = :product_id',
            ['product_id' => $productId]
        );


        $attributesSql = "SELECT ast.name AS attribute_set, ast.type 
                        AS attribute_type,
                        ai.attribute_item_id, ai.value, ai.display_value
                        FROM product_attribute_values pav
            INNER JOIN attribute_sets ast ON ast.attribute_set_id = pav.attribute_set_id
            INNER JOIN attribute_items ai ON ai.attribute_item_id = pav.attribute_item_id
            WHERE pav.product_id = :product_id
            ORDER BY ast.attribute_set_id, ai.attribute_item_id";
        $attributes = $instance->db->query($attributesSql, ['product_id' => $productId]);

        return new self([
            'product_id' => $productId,
            'name' => $productRow['name'] ?? '',
            'brand' => $productRow['brand'] ?? '',
            'is_in_stock' => (int) ($productRow['is_in_stock'] ?? 0),
            'description' => $productRow['description'] ?? '',
            'category_name' => $productRow['category_name'] ?? '',
            'product_images' => $images,
            'prices' => $prices,
            'product_attribute_values' => $attributes
        ]);
    }
}
