<?php

declare(strict_types=1);

namespace Buddy\Repman\Service\Organization;

use Buddy\Repman\Query\User\Model\Organization;
use Buddy\Repman\Query\User\Model\Organization\Member;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class MemberParamConverter implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === Member::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (null === $userId = $request->attributes->get($argument->getName())) {
            throw new BadRequestHttpException('Missing member parameter in request');
        }
        $organization = $request->attributes->get('organization');
        if (!$organization instanceof Organization) {
            throw new BadRequestHttpException('Missing organization parameter in request');
        }

        if ($request->attributes->get($argument->getName()) instanceof Member) {
            return [$request->attributes->get($argument->getName())];
        }

        $request->attributes->set(
            $argument->getName(),
            $organization->getMember($userId)->getOrElseThrow(new NotFoundHttpException(sprintf('User %s not found in %s organization', $userId, $organization->name())))
        );

        return [$request->attributes->get($argument->getName())];
    }
}
