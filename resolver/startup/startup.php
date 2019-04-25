<?php
use GraphQL\GraphQL;
use GraphQL\Utils\BuildSchema;

class ResolverStartupStartup extends Resolver
{
    public function index() {
        $this->load->model('startup/startup');
        
        try {
            $resolvers = $this->model_startup_startup->getResolvers();
            $schema = BuildSchema::build(file_get_contents(DIR_PLUGIN.'schema.graphql'));
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variableValues = isset($input['variables']) ? $input['variables'] : null;
            $result = GraphQL::executeQuery($schema, $query, $resolvers, null, $variableValues);
        } catch (\Exception $e) {
            $result = [
                'error' => [
                    'message' => $e->getMessage()
                ]
            ];
        }
    
        echo json_encode($result);
    }
}