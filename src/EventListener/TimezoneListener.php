<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;
use Twig\Extension\CoreExtension;

final class TimezoneListener implements EventSubscriberInterface
{
    public function __construct(
        private string $cookieName,
        private string $headerName,
        private string $sessionName,
        private Environment $twig
    ) {
    }

    /**
     * Handle the "kernel.request" event.
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

        if ($request->hasPreviousSession() && $request->getSession()->has($this->sessionName)) {
            $this->setTimezone((string) $request->getSession()->get($this->sessionName), $request);

            return;
        }
    }

    private function setTimezone(string $timezone, Request $request): void
    {
        try {
            $this->twig->getExtension(CoreExtension::class)
                ->setTimezone($timezone);
        } catch (\Throwable) {
            return;
        }

        $request->attributes->set('timezone', $timezone);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 100],
        ];
    }
}
