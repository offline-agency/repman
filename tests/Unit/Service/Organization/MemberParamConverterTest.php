<?php

declare(strict_types=1);

namespace Buddy\Repman\Tests\Unit\Service\Organization;

use Buddy\Repman\Query\User\Model\Organization\Member;
use Buddy\Repman\Service\Organization\MemberParamConverter;
use Buddy\Repman\Tests\MotherObject\Query\OrganizationMother;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class MemberParamConverterTest extends TestCase
{
    public function testThrowExceptionWhenMemberParamIsMissing(): void
    {
        $converter = new MemberParamConverter();

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Missing member parameter in request');
        $converter->resolve(new Request(), new ArgumentMetadata('member', null, false, false, null));
    }

    public function testThrowExceptionWhenOrganizationParamIsMissing(): void
    {
        $converter = new MemberParamConverter();

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Missing organization parameter in request');
        $converter->resolve(new Request([], [], ['member' => 'f949bd57-3e25-468b-b542-1931781cbf91']), new ArgumentMetadata('member', null, false, false, null));
    }

    public function testCheckIfMemberIsAlreadyConverted(): void
    {
        $member = new Member('9a1c9f23-23bf-4dc0-8d10-03848867d7f4', 'email', 'owner');

        $converter = new MemberParamConverter();
        $converter->resolve($request = new Request([], [], [
            'organization' => OrganizationMother::some(),
            'member' => $member,
        ]), new ArgumentMetadata('member', null, false, false, null));

        self::assertEquals($member, $request->attributes->get('member'));
    }

    public function testConvertMember(): void
    {
        $organization = OrganizationMother::withMember(
            $member = new Member($memberId = '9a1c9f23-23bf-4dc0-8d10-03848867d7f4', 'email', 'owner')
        );

        $converter = new MemberParamConverter();
        $converter->resolve($request = new Request([], [], ['organization' => $organization, 'member' => $memberId]), new ArgumentMetadata('member', null, false, false, null));

        self::assertEquals($member, $request->attributes->get('member'));
    }

    public function testThrowNotFoundExceptionWhenNoMember(): void
    {
        $organization = OrganizationMother::some();

        $this->expectException(NotFoundHttpException::class);

        $converter = new MemberParamConverter();
        $converter->resolve($request = new Request([], [], ['organization' => $organization, 'member' => 'invalid']), new ArgumentMetadata('member', null, false, false, null));
    }
}
