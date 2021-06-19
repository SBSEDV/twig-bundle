<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;
use Twig\Extension\CoreExtension;

final class TimezoneListener
{
    public function __construct(
        private string $cookieName,
        private string $headerName,
        private string $sessionName,
        private Environment $twig
    ) {
    }

    /**
     * Set the default display timezone.
     *
     * @param RequestEvent $event The request event.
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->headers->has($this->headerName)) {
            $this->setTimezone($request->headers->get($this->headerName));

            return;
        }

        if ($request->cookies->has($this->cookieName)) {
            $this->setTimezone($request->cookies->get($this->cookieName));

            return;
        }

        if ($request->hasPreviousSession()) {
            if ($request->getSession()->has($this->sessionName)) {
                $this->setTimezone($request->getSession()->get($this->sessionName));

                return;
            }
        }
    }

    private function setTimezone(string $timezone): void
    {
        $this->twig->getExtension(CoreExtension::class)
            ->setTimezone($timezone);
    }
}
