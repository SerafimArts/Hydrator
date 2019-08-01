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
     * @return InvalidMappingsException
     */
    protected function propertyNotFoundException(object $context, string $property): InvalidMappingsException
    {
        $message = 'The property $%s not defined it the class %s';

        return new InvalidMappingsException(\sprintf($message, $property, \get_class($context)));
    }

    /**
     * @param object $context
     * @param string $property
     * @return void
     * @throws InvalidMappingsException
     */
    protected function assertPropertyExists(object $context, string $property): void
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
        return function ($value) use ($property): void {
            $this->$property = $value;
        };
    }

    /**
     * @param string $property
     * @return \Closure
     */
    protected function propertyAccessor(string $property): \Closure
    {
        return function () use ($property) {
            return $this->$property;
        };
    }
}
