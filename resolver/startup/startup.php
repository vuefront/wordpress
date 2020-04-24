<?php

use GraphQL\GraphQL;
use GraphQL\Utils\BuildSchema;

class VFA_ResolverStartupStartup extends VFA_Resolver
{
    public function index()
    {
        if ($this->request->get_param('cors')) {
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
            $schema    = BuildSchema::build(file_get_contents(VFA_DIR_PLUGIN . 'schema.graphql'));
            $rawInput  = file_get_contents('php://input');
            $input     = json_decode($rawInput, true);
            $query     = $input['query'];

            $variableValues = isset($input['variables']) ? $input['variables'] : null;
            $result         = GraphQL::executeQuery($schema, $query, $resolvers, null, $variableValues);
        } catch (\Exception $e) {
            $result = [
                'error' => [
                    'message' => $e->getMessage()
                ]
            ];
        }


        return $result;
    }

    public function playground() {
        header("Content-Type: text/html");
        return '<html><body>123123</body></html>';
    }

    public function determine_current_user($user)
    {
        $this->load->model('common/token');

        $validate_uri = strpos($_SERVER['REQUEST_URI'], 'token/validate');
        if ($validate_uri > 0) {
            return $user;
        }

        $token = $this->model_common_token->validateToken(false);

        if (! $token) {
            return $user;
        }

        return $token->data->user->id;
    }
}
