<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-19 15:46
 */
namespace Notadd\Foundation\GraphQL\Controllers;

use Notadd\Foundation\Routing\Abstracts\Controller;

/**
 * Class GraphQLController.
 */
class GraphQLController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function query()
    {
        $isBatch = !$this->request->has('query');
        $inputs = $this->request->all();
        $schema = $this->config->get('graphql.schema');
        if (!$isBatch) {
            $data = $this->executeQuery($schema, $inputs);
        } else {
            $data = [];
            foreach ($inputs as $input) {
                $data[] = $this->executeQuery($schema, $input);
            }
        }
        $headers = $this->config->get('graphql.headers', []);
        $options = $this->config->get('graphql.json_encoding_options', 0);
        $errors = !$isBatch ? array_get($data, 'errors', []) : [];
        $authorized = array_reduce($errors, function ($authorized, $error) {
            return !$authorized || array_get($error, 'message') === 'Unauthorized' ? false : true;
        }, true);
        if (!$authorized) {
            return $this->response->json($data, 403, $headers, $options);
        }

        return $this->response->json($data, 200, $headers, $options);
    }

    /**
     * @param $schema
     * @param $input
     *
     * @return array
     */
    protected function executeQuery($schema, $input)
    {
        $variablesInputName = $this->config->get('graphql.variables_input_name', 'variables');
        $query = array_get($input, 'query');
        $variables = array_get($input, $variablesInputName);
        if (is_string($variables)) {
            $variables = json_decode($variables, true);
        }
        $operationName = array_get($input, 'operationName');
        $context = $this->queryContext($query, $variables, $schema);

        return $this->graphql->query($query, $variables, [
            'context'       => $context,
            'schema'        => $schema,
            'operationName' => $operationName,
        ]);
    }

    /**
     * @param $query
     * @param $variables
     * @param $schema
     *
     * @return null
     */
    protected function queryContext($query, $variables, $schema)
    {
        try {
            return $this->auth->guard()->user();
        } catch (\Exception $e) {
            return null;
        }
    }
}
