<?php

namespace App\Models;

use Throwable;

class Order extends AbstractModel
{
    public function toArray(): array
    {
        return [];
    }

    public function placeOrder(array $items): array
    {
        $errors = $this->validateItems($items);

        if ($errors !== []) {
            return [
                'success' => false,
                'orderId' => null,
                'errors' => $errors,
            ];
        }

        $connection = $this->db->getConnection();

        try {
            $connection->beginTransaction();

            $insertOrder = $connection->prepare('INSERT INTO orders (created_at) VALUES (NOW())');
            $insertOrder->execute();
            $orderId = (int) $connection->lastInsertId();

            $insertItem = $connection->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, selected_attributes_json)
                 VALUES (:order_id, :product_id, :quantity, :selected_attributes_json)'
            );

            foreach ($items as $item) {
                $selectedAttributes = array_values(array_unique($item['selectedAttributeItemIds']));
                sort($selectedAttributes);

                $insertItem->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['productId'],
                    'quantity' => (int) $item['quantity'],
                    'selected_attributes_json' => json_encode($selectedAttributes),
                ]);
            }

            $connection->commit();

            return [
                'success' => true,
                'orderId' => $orderId,
                'errors' => [],
            ];
        } catch (Throwable $e) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }

            return [
                'success' => false,
                'orderId' => null,
                'errors' => ['Failed to place order. Please try again.'],
            ];
        }
    }

    private function validateItems(array $items): array
    {
        if ($items === []) {
            return ['Cart is empty.'];
        }

        $db = $this->db->getConnection();
        $errors = [];

        foreach ($items as $index => $item) {
            $lineNumber = $index + 1;

            $productId = is_string($item['productId'] ?? null) ? $item['productId'] : '';
            $quantity = (int) ($item['quantity'] ?? 0);
            $selected = $item['selectedAttributeItemIds'] ?? [];

            if ($productId === '') {
                $errors[] = "Line {$lineNumber}: productId is required.";
                continue;
            }

            if ($quantity <= 0) {
                $errors[] = "Line {$lineNumber}: quantity must be greater than 0.";
                continue;
            }

            if (!is_array($selected)) {
                $errors[] = "Line {$lineNumber}: selectedAttributeItemIds must be an array.";
                continue;
            }

            $selected = array_values(array_unique(array_filter($selected, static fn($id) => is_string($id) && $id !== '')));

            $productStmt = $db->prepare('SELECT product_id FROM products WHERE product_id = :product_id LIMIT 1');
            $productStmt->execute(['product_id' => $productId]);
            $productExists = $productStmt->fetchColumn();

            if ($productExists === false) {
                $errors[] = "Line {$lineNumber}: product '{$productId}' does not exist.";
                continue;
            }

            $requiredSetStmt = $db->prepare(
                'SELECT COUNT(DISTINCT attribute_set_id) AS total_sets
                 FROM product_attribute_values
                 WHERE product_id = :product_id'
            );
            $requiredSetStmt->execute(['product_id' => $productId]);
            $requiredSetCount = (int) $requiredSetStmt->fetchColumn();

            if ($requiredSetCount === 0) {
                continue;
            }

            if ($selected === []) {
                $errors[] = "Line {$lineNumber}: selectedAttributeItemIds are required for this product.";
                continue;
            }

            $placeholders = [];
            $params = ['product_id' => $productId];
            foreach ($selected as $i => $id) {
                $key = "item_{$i}";
                $placeholders[] = ':' . $key;
                $params[$key] = $id;
            }

            $validItemsSql = sprintf(
                'SELECT COUNT(DISTINCT attribute_item_id) FROM product_attribute_values
                 WHERE product_id = :product_id AND attribute_item_id IN (%s)',
                implode(', ', $placeholders)
            );
            $validItemsStmt = $db->prepare($validItemsSql);
            $validItemsStmt->execute($params);
            $validItemsCount = (int) $validItemsStmt->fetchColumn();

            if ($validItemsCount !== count($selected)) {
                $errors[] = "Line {$lineNumber}: one or more selected attributes are invalid for product '{$productId}'.";
                continue;
            }

            $selectedSetsSql = sprintf(
                'SELECT COUNT(DISTINCT attribute_set_id) FROM product_attribute_values
                 WHERE product_id = :product_id AND attribute_item_id IN (%s)',
                implode(', ', $placeholders)
            );
            $selectedSetsStmt = $db->prepare($selectedSetsSql);
            $selectedSetsStmt->execute($params);
            $selectedSetCount = (int) $selectedSetsStmt->fetchColumn();

            if ($selectedSetCount !== $requiredSetCount) {
                $errors[] = "Line {$lineNumber}: all product attribute sets must be selected for '{$productId}'.";
            }
        }

        return $errors;
    }
}
