<?php

namespace Symfony\UX\LiveComponent\Hydration;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Jean-Paul van der Wegen <info@jpvdw.nl>
 *
 * @experimental
 *
 * @internal
 */
class ArrayCollectionHydrationExtension extends AbstractDoctrineHydrationExtension implements HydrationExtensionInterface
{

    public function supports(string $className): bool
    {
        return $className === ArrayCollection::class;
    }

    public function hydrate(mixed $value, string $className): ?object
    {
        $output = new ArrayCollection();
        foreach ($value as $object) {
            $object = $this->findObject($object['class'], $object['identifierValues']);

            if($object instanceof $object['class']::class) {
                $output->add($this->findObject($object['class'], $object['identifierValues']));
            }
        }

        return $output;
    }

    public function dehydrate(object $object): mixed
    {
        $output = [];
        foreach ($object as $class) {
            $output[] = [
                'class' => $class::class,
                'identifierValues' => $this->getIdentifierValue($class),
            ];
        }

        return $output;
    }
}
