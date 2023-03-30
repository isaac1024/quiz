<?php

declare(strict_types=1);

namespace Quiz\UserEmailVerification\Infrastructure\Mailer;

use Quiz\UserEmailVerification\Domain\EmailSender;
use Quiz\UserEmailVerification\Domain\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final readonly class DoctrineEmailSender implements EmailSender
{
    public const SUBJECT = 'Welcome to Quiz';

    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private UrlGeneratorInterface $router,
        private string $appHome,
    ) {
    }

    public function send(User $user): void
    {
        $html = $this->twig->render('@UserEmailVerification/welcome.html.twig', [
            'name' => $user->name(),
            'homeUrl' => $this->appHome,
            'verifyUrl' => $this->router->generate(
                'user_email_verify',
                ['token' => $user->verified()->token],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ]);

        $email = new Email();
        $email->to($user->email()->value)
            ->subject(self::SUBJECT)
            ->html($html);

        $this->mailer->send($email);
    }
}
