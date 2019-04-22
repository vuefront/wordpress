<?php
use Youshido\GraphQL\Execution\Processor;
use Youshido\GraphQL\Schema\Schema;
use Youshido\GraphQL\Type\Object\ObjectType;

class ResolverStartupStartup extends Resolver
{
    public function index() {
        $this->load->model('startup/startup');
        
        $processor = new Processor(
            new Schema(
                    array(
                    'query' => new ObjectType(
                        array(
                            'name'   => 'RootQueryType',
                            'fields' => $this->model_startup_startup->getQueries()
                        )
                    ),
                    'mutation' => new ObjectType(
                        array(
                            'name' => 'RootMutationType',
                            'fields' => $this->model_startup_startup->getMutations()
                        )
                    )
                )
            )
        );
    
        $rawInput = file_get_contents('php://input');
        $params = json_decode($rawInput, true);
    
        if (! empty($params['variables'])) {
            $processor->processPayload($params['query'], $params['variables']);
        } else {
            $processor->processPayload($params['query']);
        }
    
        echo json_encode($processor->getResponseData());
    }
}