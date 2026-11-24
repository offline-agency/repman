<?php

declare(strict_types=1);

namespace Buddy\Repman\Service\Api;

use Buddy\Repman\Query\Api\Model\Package;
use Buddy\Repman\Query\Api\PackageQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PackageParamConverter implements ArgumentValueResolverInterface
{
    private PackageQuery $packageQuery;

    public function __construct(PackageQuery $packageQuery)
    {
        $this->packageQuery = $packageQuery;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === Package::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var Package $package */
        $package = $this->packageQuery
            ->getById($request->attributes->get('organization')->id(), $request->attributes->get('package'))
            ->getOrElseThrow(new NotFoundHttpException());

        $request->attributes->set($argument->getName(), $package);

        return [$request->attributes->get($argument->getName())];
    }
}
