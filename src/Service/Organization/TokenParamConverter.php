<?php

declare(strict_types=1);

namespace Buddy\Repman\Service\Organization;

use Buddy\Repman\Query\User\Model\Organization\Token;
use Buddy\Repman\Query\User\OrganizationQuery;
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

    /**
     * @return iterable<object>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var Token $token */
        $token = $this->organizationQuery->findToken(
            $request->attributes->get('organization')->id(),
            $request->attributes->get('token')
        )->getOrElseThrow(new NotFoundHttpException('Token not found'));

        $request->attributes->set($argument->getName(), $token);

        return [$request->attributes->get($argument->getName())];
    }
}
