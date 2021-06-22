<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
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
            $this->setTimezone($request->headers->get($this->headerName), $request);

            return;
        }

        if ($request->cookies->has($this->cookieName)) {
            $this->setTimezone($request->cookies->get($this->cookieName), $request);

            return;
        }

        if ($request->hasPreviousSession()) {
            if ($request->getSession()->has($this->sessionName)) {
                $this->setTimezone((string) $request->getSession()->get($this->sessionName), $request);

                return;
            }
        }
    }

    private function setTimezone(string $timezone, Request $request): void
    {
        if (!in_array($timezone, \DateTimeZone::listIdentifiers())) {
            return;
        }

        try {
            $this->twig->getExtension(CoreExtension::class)
                ->setTimezone($timezone);
        } catch (\Throwable) {
            return;
        }

        $request->attributes->set('timezone', $timezone);
    }
}
