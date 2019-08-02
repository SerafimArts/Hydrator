<?php
/**
 * This file is part of Hydrator package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Rds\Hydrator\Mapper;

use Rds\Hydrator\Exception\InvalidMappingsException;

/**
 * Trait PropertyHelpersTrait
 */
trait PropertyHelpersTrait
{
    /**
     * @param object $context
     * @param string $property
     * @return mixed
     */
    protected function access(object $context, string $property)
    {
        return $this->propertyAccessor($property)->call($context);
    }

    /**
     * @param object $context
     * @param string $property
     * @param mixed $value
     * @return mixed
     */
    protected function mutate(object $context, string $property, $value)
    {
        return $this->propertyMutator($property)->call($context, $value);
    }

    /**
     * @param object $context
     * @param string $property
     * @return InvalidMappingsException
     */
    protected function propertyNotFoundException(object $context, string $property): InvalidMappingsException
    {
        $message = 'The property $%s not defined in the class %s';

        return new InvalidMappingsException(\sprintf($message, $property, \get_class($context)));
    }

    /**
     * @param object $context
     * @param string $property
     * @return void
     * @throws InvalidMappingsException
     */
    public function assertPropertyExists(object $context, string $property): void
    {
        if (! \property_exists($context, $property)) {
            throw $this->propertyNotFoundException($context, $property);
        }
    }

    /**
     * @param string $property
     * @return \Closure
     */
    protected function propertyMutator(string $property): \Closure
    {
        $self = $this;

        return function ($value) use ($property, $self): void {
            $self->assertPropertyExists($this, $property);

            $this->$property = $value;
        };
    }

    /**
     * @param string $property
     * @return \Closure
     */
    protected function propertyAccessor(string $property): \Closure
    {
        $self = $this;

        return function () use ($property, $self) {
            $self->assertPropertyExists($this, $property);

            return $this->$property;
        };
    }
}
