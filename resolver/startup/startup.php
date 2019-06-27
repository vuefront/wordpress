<?php
use GraphQL\GraphQL;
use GraphQL\Utils\BuildSchema;

class ResolverStartupStartup extends Resolver
{
    public function index()
    {
        if (is_plugin_active('d_vuefront/plugin.php')) {
            if (! empty($_GET['cors'])) {
                if (! empty($_SERVER['HTTP_ORIGIN'])) {
                    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                } else {
                    header('Access-Control-Allow-Origin: *');
                }
                header('Access-Control-Allow-Methods: POST, OPTIONS');
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Allow-Headers: DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,Token,token,Cookie,cookie,content-type');
            }

            $this->load->model('startup/startup');
        
            try {
                $resolvers = $this->model_startup_startup->getResolvers();
                $schema = BuildSchema::build(file_get_contents(VF_DIR_PLUGIN.'schema.graphql'));
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
}
