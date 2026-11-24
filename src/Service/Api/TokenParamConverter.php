<?php

declare(strict_types=1);

namespace Buddy\Repman\Service\Api;

use Buddy\Repman\Query\Api\Model\Token;
use Buddy\Repman\Query\Api\OrganizationQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class TokenParamConverter implements ArgumentValueResolverInterface
{
    private OrganizationQuery $organizationQuery;

    public function __construct(OrganizationQuery $organizationQuery)
    {
        $this->organizationQuery = $organizationQuery;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === Token::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var Token $token */
        $token = $this->organizationQuery->findToken(
            $request->attributes->get('organization')->id(),
            $request->attributes->get('token')
        )->getOrElseThrow(new NotFoundHttpException());

        $request->attributes->set($argument->getName(), $token);

        return [$request->attributes->get($argument->getName())];
    }
}
