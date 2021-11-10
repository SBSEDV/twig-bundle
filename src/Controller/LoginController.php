<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class LoginController
{
    public function __construct(
        private Environment $twig
    ) {
    }

    public function indexAction(): Response
    {
        return new Response(
            $this->twig->render('@SBSEDVTwig/login.html.twig'),
            Response::HTTP_OK
        );
    }
}
