<?php

declare(strict_types = 1);

namespace Tool\Traits\Collection;

use Tool\Collection;
use Tool\Validation\Assert;

/**
 * Class RestrictedCollection
 *
 * @mixin Collection
 */
trait RestrictedTrait
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
     * @throws \Tool\Validation\Exceptions\ValidationException
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);

        $this->assert();
    }

    /**
     * Create RestrictedCollection with a certain 'type'.
     *
     * @param string $type
     * @param array  $items = []
     *
     * @return Collection
     * @throws \Tool\Validation\Exceptions\ValidationException
     */
    public static function makeType(string $type, array $items = []): Collection
    {
        return static::make($items)
            ->setRules(['*' => $type])
            ->assert();
    }

    /**
     * Create RestrictedCollection with a certain 'type'.
     *
     * @param string $class
     * @param array  $items = []
     *
     * @return Collection
     * @throws \Tool\Validation\Exceptions\ValidationException
     */
    public static function makeObject(string $class, array $items = []): Collection
    {
        Assert::classExists($class, sprintf('Class %s does not exist', $class));

        return static::make($items)
            ->setRules(['*' => 'object:' . $class])
            ->assert();
    }

    /**
     * Validate Collection, throw InvalidArgumentException if any data is invalid.
     *
     * @return Collection
     * @throws \Tool\Validation\Exceptions\ValidationException
     */
    public function assert(): Collection
    {
        $this->validate($this->rules, $this->messages, $this->customAttributes)
            ->assert();

        return $this;
    }

    /**
     * Get validation messages.
     *
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Set validation messages.
     *
     * @return Collection
     */
    public function setMessages(array $messages): Collection
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Get validation custom attributes.
     *
     * @return string[]
     */
    public function getCustomAttributes(): array
    {
        return $this->customAttributes;
    }

    /**
     * Set validation custom attributes.
     *
     * @return Collection
     */
    public function setCustomAttributes(array $customAttributes): Collection
    {
        $this->customAttributes = $customAttributes;

        return $this;
    }

    /**
     * Set rules for each value in the Collection.
     *
     * @return Collection
     */
    public function setRulesToEach(): Collection
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
     * Get validation rules.
     *
     * @return string[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * Set rules on entire Collection as a whole.
     *
     * @param string[] $rules
     *
     * @return Collection
     */
    public function setRules(array $rules): Collection
    {
        $this->rules = Assert::allString($rules, '$rules must all be strings.');

        return $this;
    }

    /**
     * Transform each item in the collection using a callback with assertion.
     *
     * @param  callable $callback
     *
     * @return $this
     * @throws \Tool\Validation\Exceptions\ValidationException
     */
    public function transform(callable $callback): Collection
    {
        $this->items = parent::transform($callback)->all();

        return $this->assert();
    }

    /**
     * Push an item onto the beginning of the collection with assertion.
     *
     * @param  mixed $value
     * @param  mixed $key = null
     *
     * @return $this
     * @throws \Tool\Validation\Exceptions\ValidationException
     */
    public function prepend($value, $key = null): Collection
    {
        $this->items = parent::prepend($value, $key)->all();

        return $this->assert();
    }

    /**
     * Set the item at a given offset with assertion.
     *
     * @param  mixed $key
     * @param  mixed $value
     *
     * @return void
     * @throws \Tool\Validation\Exceptions\ValidationException
     */
    public function offsetSet($key, $value): void
    {
        parent::offsetSet($key, $value);

        $this->assert();
    }
}
