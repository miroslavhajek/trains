<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity\Page;
use DateTimeImmutable;
use Gedmo\Sluggable\Util\Urlizer;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use function random_int;
use function sprintf;
use const PHP_INT_MAX;

/**
 * @extends PersistentProxyObjectFactory<Page>
 */
final class PageFactory extends PersistentProxyObjectFactory
{

    public static function class(): string
    {
        return Page::class;
    }


    protected function defaults(): array|callable
    {
        return [
            'title' => self::faker()->sentence(),
            'content' => self::faker()->text(600),
            'createdAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }


    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(static function (Page $page): void {
                $slug = Urlizer::urlize((string) $page->getTitle());

                $page->setSlug(sprintf('%s-%d', $slug, random_int(1, PHP_INT_MAX)));
            });
    }
}
