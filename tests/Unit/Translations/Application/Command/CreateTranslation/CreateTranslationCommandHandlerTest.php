<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Application\Command\CreateTranslation;

use App\Tests\Unit\Shared\TestCase\EventBusMock;
use App\Tests\Unit\Translations\Domain\TranslationFactory;
use App\Tests\Unit\Translations\TestCase\CreateTranslationMock;
use App\Translations\Application\Command\CreateTranslation\CreateTranslationCommandHandler;
use PHPUnit\Framework\TestCase;

final class CreateTranslationCommandHandlerTest extends TestCase
{
    private ?CreateTranslationMock $createTranslation;
    private ?EventBusMock $eventBus;

    protected function setUp(): void
    {
        $this->createTranslation = new CreateTranslationMock($this);
        $this->eventBus = new EventBusMock($this);
    }

    protected function tearDown(): void
    {
        $this->createTranslation = null;
        $this->eventBus = null;
    }

    public function testCreateTranslation(): void
    {
        $translation = TranslationFactory::create();
        $command = CreateTranslationCommandFactory::createFromTranslation($translation);

        $this->createTranslation->shouldCreateTranslation($translation);
        $this->eventBus->shouldPublishEvents($translation);

        $handler = new CreateTranslationCommandHandler(
            createTranslation: $this->createTranslation->getMock(),
            eventBus: $this->eventBus->getMock()
        );
        $handler->__invoke($command);
    }
}
