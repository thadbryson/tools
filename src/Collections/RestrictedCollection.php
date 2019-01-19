<?php

declare(strict_types = 1);

namespace Tool\Support\Collections;

use Tool\Support\Collection;
use Tool\Validation\Assert;

/**
 * Class RestrictedCollection
 */
class RestrictedCollection extends Collection
{
    /**
     * @var string[]
     */
    protected $rules = [];

    /**
     * @var string[]
     */
    protected $messages = [];

    /**
     * @var string[]
     */
    protected $customAttributes = [];

    /**
     * RestrictedCollection constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);

        $this->assert();
    }

    /**
     * Set rules on entire Collection as a whole.
     *
     * @param array $rules
     *
     * @return RestrictedCollection
     */
    public function setRules(array $rules): RestrictedCollection
    {
        $this->rules = Assert::allString($rules, '$rules must all be strings.');

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return string[]
     */
    public function getCustomAttributes(): array
    {
        return $this->customAttributes;
    }

    /**
     * @return RestrictedCollection
     */
    public function setMessages(array $messages): RestrictedCollection
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @return RestrictedCollection
     */
    public function setCustomAttributes(array $customAttributes): RestrictedCollection
    {
        $this->customAttributes = $customAttributes;

        return $this;
    }

    /**
     * Set rules for each value in the Collection.
     *
     * @return RestrictedCollection
     */
    public function setRulesToEach(): RestrictedCollection
    {
        $rules = $this->getRules();

        foreach ($rules as $key => $rule) {
            $each = trim("*.{$key}", '. ');

            $rules[$each] = $rule;

            unset($rules[$key]);
        }

        return $this->setRules($rules);
    }

    /**
     * Validate Collection, throw InvalidArgumentException if any data is invalid.
     *
     * @return RestrictedCollection
     * @throws \InvalidArgumentException
     */
    public function assert(): RestrictedCollection
    {
        $this->validate($this->rules, $this->messages, $this->customAttributes)
             ->assert();

        return $this;
    }

    /**
     * Transform each item in the collection using a callback.
     *
     * @param  callable $callback
     *
     * @return $this
     */
    public function transform(callable $callback): RestrictedCollection
    {
        $this->items = parent::transform($callback)->all();

        return $this->assert();
    }

    /**
     * Push an item onto the beginning of the collection.
     *
     * @param  mixed $value
     * @param  mixed $key
     *
     * @return $this
     */
    public function prepend($value, $key = null): RestrictedCollection
    {
        $this->items = parent::prepend($value, $key)->all();

        return $this->assert();
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed $key
     * @param  mixed $value
     *
     * @return void
     */
    public function offsetSet($key, $value): void
    {
        parent::offsetSet($key, $value);

        $this->assert();
    }
}
