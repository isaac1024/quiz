<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class PreviewController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('@UserEmailVerification/welcome.html.twig', [
            'name' => 'Random Name',
            'homeUrl' => '',
            'verifyUrl' => '',
        ]);
    }
}
