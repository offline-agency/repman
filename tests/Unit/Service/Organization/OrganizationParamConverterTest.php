<?php

declare(strict_types=1);

namespace Buddy\Repman\Tests\Unit\Service\Organization;

use Buddy\Repman\Query\User\OrganizationQuery;
use Buddy\Repman\Service\Organization\OrganizationParamConverter;
use Buddy\Repman\Tests\MotherObject\Query\OrganizationMother;
use Munus\Control\Option;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class OrganizationParamConverterTest extends TestCase
{
    public function testThrowExceptionWhenOrganizationParamIsMissing(): void
    {
        $converter = new OrganizationParamConverter($this->getMockBuilder(OrganizationQuery::class)->getMock());

        $this->expectException(BadRequestHttpException::class);
        $converter->resolve(new Request(), new ArgumentMetadata('organization', null, false, false, null));
    }

    public function testCheckIfOrganizationIsAlreadyConverted(): void
    {
        $queryMock = $this->getMockBuilder(OrganizationQuery::class)->getMock();
        $queryMock->expects(self::never())->method('getByAlias');

        $converter = new OrganizationParamConverter($queryMock);

        $converter->resolve(new Request([], [], [
            'organization' => OrganizationMother::some(),
        ]), new ArgumentMetadata('organization', null, false, false, null));
    }

    public function testConvertOrganization(): void
    {
        $organization = OrganizationMother::some();
        $queryMock = $this->getMockBuilder(OrganizationQuery::class)->getMock();
        $queryMock->expects(self::once())->method('getByAlias')->with('buddy')->willReturn(Option::some($organization));

        $converter = new OrganizationParamConverter($queryMock);

        $converter->resolve($request = new Request([], [], ['organization' => 'buddy']), new ArgumentMetadata('organization', null, false, false, null));

        self::assertEquals($organization, $request->attributes->get('organization'));
    }
}
