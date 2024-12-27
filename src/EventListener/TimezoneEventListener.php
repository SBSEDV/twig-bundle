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
        private readonly string $cookieName,
        private readonly string $headerName,
        private readonly string $sessionName,
        private readonly Environment $twig,
    ) {
    }

    /**
     * Set the users default timezone.
     *
     * @param RequestEvent $event The "kernel.request" event.
     */
    public function __invoke(RequestEvent $event): void
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

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['__invoke', 100],
        ];
    }
}
