<?php

namespace Quiz\Tests\UserEmailVerification\Infrastructure\Mailer;

use Quiz\Shared\Domain\Models\DateTimeUtils;
use Quiz\Tests\Shared\Infrastructure\PhpUnit\IntegrationTestCase;
use Quiz\Tests\UserEmailVerification\Domain\UserObjectMother;
use Quiz\Tests\UserEmailVerification\Domain\VerifiedObjectMother;
use Quiz\UserEmailVerification\Infrastructure\Mailer\DoctrineEmailSender;

class DoctrineEmailSenderTest extends IntegrationTestCase
{
    private DoctrineEmailSender $emailSender;

    protected function setUp(): void
    {
        parent::setUp();

        $this->emailSender ??= $this->get(DoctrineEmailSender::class);
    }

    public function testSendEmail(): void
    {
        $user = UserObjectMother::make(verified: VerifiedObjectMother::makeNotVerified(
            bin2hex(random_bytes(64)),
            DateTimeUtils::fromRelative('+15 min'),
        ));

        $this->emailSender->send($user);

        $this->assertEmailCount(1);
        $email = $this->getMailerMessage();
        $this->assertEmailAddressContains($email, 'To', $user->email()->value);
        $this->assertEmailHtmlBodyContains($email, $user->name());
        $this->assertEmailHtmlBodyContains($email, $user->verified()->token);
    }
}
