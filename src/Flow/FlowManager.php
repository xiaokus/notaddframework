<?php

// +----------------------------------------------------------------------+
// | The Notadd Framework.                                                |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016-2017 Shanxi Benchu Network Technology Co,.Ltd     |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the Apache license,    |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.apache.org/licenses/LICENSE-2.0.html                      |
// +----------------------------------------------------------------------+
// | Author: TwilRoad <heshudong@ibenchu.com>                             |
// |         Seven Du <shiweidu@outlook.com>                              |
// +----------------------------------------------------------------------+

namespace Notadd\Foundation\Flow;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use Notadd\Foundation\Database\Model;
use Notadd\Foundation\Flow\Abstracts\Entity;
use Notadd\Foundation\Flow\Contracts\SupportStrategy;
use Notadd\Foundation\Routing\Traits\Helpers;
use Symfony\Component\Workflow\Exception\InvalidDefinitionException;
use Symfony\Component\Workflow\MarkingStore\SingleStateMarkingStore;

/**
 * Class FlowManager.
 */
class FlowManager
{
    use Helpers;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $flows;

    /**
     * FlowManager constructor.
     */
    public function __construct()
    {
        $this->flows = new Collection();
    }

    /**
     * @param \Notadd\Foundation\Flow\Flow $flow
     * @param                              $supportStrategy
     *
     * @throws \Exception
     */
    public function add(Flow $flow, $supportStrategy)
    {
        if ($this->flows->has($flow->getName())) {
            throw new \Exception('The same named flow is added!');
        }
        if (!$supportStrategy instanceof SupportStrategy) {
            @trigger_error('Support of class name string was deprecated after version 3.2 and won\'t work anymore in 4.0.', E_USER_DEPRECATED);
            $supportStrategy = new ClassInstanceSupportStrategy($supportStrategy);
        }
        $this->flows->put($flow->getName(), [$flow, $supportStrategy]);
    }

    /**
     * @param $flow
     *
     * @return bool
     */
    public function exists($flow)
    {
        if (is_object($flow) && $flow instanceof Flow) {
            return $this->flows->has($flow->getName());
        }

        return $this->flows->has($flow);
    }

    /**
     * @param      $subject
     * @param null $name
     *
     * @return \Notadd\Foundation\Flow\Flow
     */
    public function get($subject, $name = null)
    {
        $matched = null;
        foreach ($this->flows as list($workflow, $supportStrategy)) {
            if ($this->supports($workflow, $supportStrategy, $subject, $name)) {
                if ($matched) {
                    throw new InvalidArgumentException('At least two workflows match this subject. Set a different name on each and use the second (name) argument of this method.');
                }
                $matched = $workflow;
            }
        }
        if (!$matched) {
            throw new InvalidArgumentException(sprintf('Unable to find a workflow for class "%s".', get_class($subject)));
        }

        return $matched;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function list()
    {
        return $this->flows;
    }

    /**
     * @param $definition
     *
     * @throws \Exception
     */
    public function register($definition)
    {
        if (is_string($definition)) {
            $definition = $this->container->make($definition);
        }
        if ($definition instanceof Model) {
            if (method_exists($definition, 'name')) {
                $definition->setFlowName($definition->{'name'}());
            }
            if (method_exists($definition, 'places')) {
                $definition->addFlowPlaces($definition->{'places'}());
            }
            if (method_exists($definition, 'transitions')) {
                $definition->addFlowTransitions($definition->{'transitions'}());
            }
            $events = $definition->registerFlowEvents();
            foreach ((array)$events as $event => $handler) {
                method_exists($definition, $handler) && $this->event->listen($event, [
                    $definition,
                    $handler,
                ]);
            }
            $flow = new Flow($definition->buildFlow(), $definition->getMarking(), $definition->getFlowName());
            $this->add($flow, get_class($definition));
        } elseif ($definition instanceof Entity || $definition instanceof FlowBuilder) {
            if (method_exists($definition, 'entity')) {
                $definition->setEntity($definition->{'entity'}());
            } else {
                if ($definition instanceof Entity) {
                    $definition->setEntity(get_class($definition));
                } else {
                    throw new InvalidDefinitionException('Entity is not defined');
                }
            }
            if (method_exists($definition, 'events')) {
                $events = $definition->{'events'}();
                foreach ((array)$events as $event => $handler) {
                    method_exists($definition, $handler) && $this->event->listen($event, [
                        $definition,
                        $handler,
                    ]);
                }
            }
            if (method_exists($definition, 'marking')) {
                $definition->setMarking($definition->{'marking'}());
            } else {
                $definition->setMarking(new SingleStateMarkingStore());
            }
            if (method_exists($definition, 'name')) {
                $definition->setName($definition->{'name'}());
            }
            if (method_exists($definition, 'places')) {
                $definition->addPlaces($definition->{'places'}());
            }
            if (method_exists($definition, 'transitions')) {
                $definition->addTransitions($definition->{'transitions'}());
            }
            $flow = new Flow($definition->build(), $definition->getMarking(), $definition->getName());
            $this->add($flow, $definition->getEntity());
        } else {
            throw new InvalidDefinitionException('instance must instanceof ' . FlowBuilder::class . ' or ' . Entity::class);
        }
    }

    /**
     * @param \Notadd\Foundation\Flow\flow                      $workflow
     * @param \Notadd\Foundation\Flow\Contracts\SupportStrategy $supportStrategy
     * @param                                                   $subject
     * @param                                                   $workflowName
     *
     * @return bool
     */
    private function supports(flow $workflow, SupportStrategy $supportStrategy, $subject, $workflowName)
    {
        if (null !== $workflowName && $workflowName !== $workflow->getName()) {
            return false;
        }

        return $supportStrategy->supports($workflow, $subject);
    }
}
