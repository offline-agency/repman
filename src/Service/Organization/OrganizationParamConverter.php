<?php

declare(strict_types=1);

namespace Buddy\Repman\Service\Organization;

use Buddy\Repman\Query\User\Model\Organization;
use Buddy\Repman\Query\User\OrganizationQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class OrganizationParamConverter implements ArgumentValueResolverInterface
{
    private OrganizationQuery $organizationQuery;

    public function __construct(OrganizationQuery $organizationQuery)
    {
        $this->organizationQuery = $organizationQuery;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === Organization::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (null === $alias = $request->attributes->get($argument->getName())) {
            throw new BadRequestHttpException('Missing organization parameter in request');
        }

        if ($request->attributes->get($argument->getName()) instanceof Organization) {
            return [$request->attributes->get($argument->getName())];
        }

        $request->attributes->set(
            $argument->getName(),
            $this->organizationQuery->getByAlias($alias)->getOrElseThrow(new NotFoundHttpException(sprintf('Organization %s not found', $alias)))
        );

        return [$request->attributes->get($argument->getName())];
    }
}
