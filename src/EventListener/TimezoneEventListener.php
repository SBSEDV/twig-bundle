<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;
use Twig\Extension\CoreExtension;

final class TimezoneEventListener implements EventSubscriberInterface
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

        $header = $request->headers->get($this->headerName);
        if (\is_string($header)) {
            $this->setTimezone($header, $request);

            return;
        }

        $cookie = $request->cookies->get($this->cookieName);
        if (\is_string($cookie)) {
            $this->setTimezone($cookie, $request);

            return;
        }

        if ($request->hasSession(true)) {
            $session = $request->getSession()->get($this->sessionName);
            if (\is_string($session)) {
                $this->setTimezone($session, $request);

                return;
            }
        }
    }

    private function setTimezone(string $timezone, Request $request): void
    {
        try {
            $this->twig->getExtension(CoreExtension::class)
                ->setTimezone($timezone)
            ;
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
            RequestEvent::class => ['onKernelRequest', 100],
        ];
    }
}
