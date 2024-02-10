<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Application\Command\CreateTranslation;

use App\Translations\Application\Command\CreateTranslation\CreateTranslationCommandHandler;
use App\Tests\Unit\Shared\TestCase\EventBusMock;
use App\Tests\Unit\Translations\Application\Command\CreateTranslation\CreateTranslationCommandFactory;
use App\Tests\Unit\Translations\Domain\TranslationFactory;
use App\Tests\Unit\Translations\TestCase\CreateTranslationMock;
use PHPUnit\Framework\TestCase;

final class CreateTranslationCommandHandlerTest extends TestCase
{
    private ?CreateTranslationMock $createTranslationMock;
    private ?EventBusMock $eventBusMock;

    protected function setUp(): void
    {
        $this->createTranslationMock = new CreateTranslationMock($this);
        $this->eventBusMock = new EventBusMock($this);
    }

    protected function tearDown(): void
    {
        $this->createTranslationMock = null;
        $this->eventBusMock = null;
    }

    public function testCreateTranslation(): void
    {
        $translation = TranslationFactory::create();
        $command = CreateTranslationCommandFactory::createFromTranslation($translation);

        $this->createTranslationMock->shouldCreateTranslation($translation);
        $this->eventBusMock->shouldPublishEvents($translation);

        $handler = new CreateTranslationCommandHandler(
            createTranslation: $this->createTranslationMock->getMock(),
            eventBus: $this->eventBusMock->getMock()
        );
        $handler->__invoke($command);
    }
}
