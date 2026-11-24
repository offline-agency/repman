<?php

declare(strict_types=1);

namespace Buddy\Repman\Tests\Unit\Service\User;

use Buddy\Repman\Query\Admin\UserQuery;
use Buddy\Repman\Service\User\UserParamConverter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class UserParamConverterTest extends TestCase
{
    public function testThrowExceptionWhenUserParamIsMissing(): void
    {
        $converter = new UserParamConverter($this->getMockBuilder(UserQuery::class)->getMock());

        $this->expectException(BadRequestHttpException::class);
        $converter->resolve(new Request(), new ArgumentMetadata('argument', null, false, false, null));
    }
}
