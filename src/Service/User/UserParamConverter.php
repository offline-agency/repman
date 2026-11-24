<?php

declare(strict_types=1);

namespace Buddy\Repman\Service\User;

use Buddy\Repman\Query\Admin\Model\User;
use Buddy\Repman\Query\Admin\UserQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UserParamConverter implements ArgumentValueResolverInterface
{
    private UserQuery $userQuery;

    public function __construct(UserQuery $userQuery)
    {
        $this->userQuery = $userQuery;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === User::class;
    }

    /**
     * @return iterable<object>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (null === $id = $request->attributes->get('user')) {
            throw new BadRequestHttpException('Missing user parameter in request');
        }

        $request->attributes->set(
            $argument->getName(),
            $this
                ->userQuery
                ->getById($id)
                ->getOrElseThrow(new NotFoundHttpException('User not found'))
        );

        return [$request->attributes->get($argument->getName())];
    }
}
