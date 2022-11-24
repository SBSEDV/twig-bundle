<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\CoreExtension;

final class LocalizationEventListener implements EventSubscriberInterface
{
    public function __construct(
        private Environment $twig,
        private ?TranslatorInterface $translator,
    ) {
    }

    /**
     * Set locale specific default options.
     *
     * @param RequestEvent $event The "kernel.request" event.
     */
    public function __invoke(RequestEvent $event): void
    {
        if (null === $this->translator) {
            return;
        }

        /** @var CoreExtension */
        $coreExtension = $this->twig->getExtension(CoreExtension::class);

        $coreExtension->setNumberFormat(
            (int) $this->translator->trans('number_format.decimal_places', domain: 'sbsedv_twig'),
            $this->translator->trans('number_format.decimal_point', domain: 'sbsedv_twig'),
            $this->translator->trans('number_format.thousand_seperator', domain: 'sbsedv_twig'),
        );

        $coreExtension->setDateFormat(
            $this->translator->trans('date_format.date', domain: 'sbsedv_twig'),
            $this->translator->trans('date_format.date_interval', domain: 'sbsedv_twig')
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['__invoke', 100],
        ];
    }
}