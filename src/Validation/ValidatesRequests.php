<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 14:37
 */
namespace Notadd\Foundation\Validation;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Class ValidatesRequests.
 */
trait ValidatesRequests
{
    /**
     * @var bool
     */
    protected $onlyValues = false;

    /**
     * Run the validation routine against the given validator.
     *
     * @param \Illuminate\Contracts\Validation\Validator|array $validator
     * @param \Illuminate\Http\Request|null                    $request
     *
     * @return array
     */
    public function validateWith($validator, Request $request = null)
    {
        $request = $request ?: request();
        if (is_array($validator)) {
            $validator = $this->getValidationFactory()->make($request->all(), $validator);
        }
        $validator->validate();

        return $request->only(collect($validator->getRules())->keys()->map(function ($rule) {
            return str_contains($rule, '.') ? explode('.', $rule)[0] : $rule;
        })->unique()->toArray());
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param \Illuminate\Http\Request $request
     * @param array                    $rules
     * @param array                    $messages
     * @param array                    $customAttributes
     *
     * @return array
     */
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $this->getValidationFactory()
            ->make($request->all(), $rules, $messages, $customAttributes)
            ->validate();
        $data = $request->only(collect($rules)->keys()->map(function ($rule) {
            return str_contains($rule, '.') ? explode('.', $rule)[0] : $rule;
        })->unique()->toArray());
        if ($this->onlyValues) {
            return array_values($data);
        } else {
            return $data;
        }
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param string                   $errorBag
     * @param \Illuminate\Http\Request $request
     * @param array                    $rules
     * @param array                    $messages
     * @param array                    $customAttributes
     *
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateWithBag(
        $errorBag,
        Request $request,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ) {
        try {
            return $this->validate($request, $rules, $messages, $customAttributes);
        } catch (ValidationException $e) {
            $e->errorBag = $errorBag;
            throw $e;
        }
    }

    /**
     * Get a validation factory instance.
     *
     * @return \Illuminate\Contracts\Validation\Factory
     */
    protected function getValidationFactory()
    {
        return app(Factory::class);
    }
}
