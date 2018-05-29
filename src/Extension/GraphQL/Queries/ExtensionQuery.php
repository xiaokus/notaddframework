<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-19 18:04
 */
namespace Notadd\Foundation\Extension\GraphQL\Queries;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use Notadd\Foundation\Extension\Extension;
use Notadd\Foundation\GraphQL\Abstracts\Query;

/**
 * Class ConfigurationQuery.
 */
class ExtensionQuery extends Query
{
    /**
     * @param $root
     * @param $args
     *
     * @return array
     */
    public function resolve($root, $args)
    {
        return $this->extension->repository()->map(function (Extension $extension) {
            $authors = (array)$extension->get('authors');
            foreach ($authors as $key => $author) {
                $string = $author['name'] ?? '';
                $string .= ' <';
                $string .= $author['email'] ?? '';
                $string .= '>';
                $authors[$key] = $string;
            }
            $extension->offsetSet('authors', implode(',', $authors));
            $require = $extension->get('require');
            $extension->offsetSet('requireInstall', $require['install']);
            $extension->offsetSet('requireUninstall', $require['uninstall']);

            return $extension;
        })->toArray();
    }

    /**
     * @return \GraphQL\Type\Definition\ListOfType
     */
    public function type(): ListOfType
    {
        return Type::listOf($this->graphql->type('extension'));
    }
}
