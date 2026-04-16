<?php

namespace App\Controller;

use App\GraphQL\GraphQLSchema;
use GraphQL\Error\DebugFlag;
use GraphQL\GraphQL as GraphQLBase;
use RuntimeException;
use Throwable;

class GraphQL
{
    public static function handle(): string
    {
        try {
            $schema = GraphQLSchema::create();

            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to read request body.');
            }

            $input = json_decode($rawInput, true);
            $query = $input['query'] ?? null;
            $variables = $input['variables'] ?? null;

            if (!is_string($query) || $query === '') {
                throw new RuntimeException('GraphQL query is required.');
            }

            $result = GraphQLBase::executeQuery($schema, $query, null, null, $variables);
            $output = $result->toArray(DebugFlag::NONE);
        } catch (Throwable $e) {
            $output = ['errors' => [['message' => $e->getMessage()]]];
        }

        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($output, JSON_UNESCAPED_UNICODE);
    }
}
