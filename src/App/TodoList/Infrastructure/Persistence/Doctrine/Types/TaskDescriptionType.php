<?php

declare(strict_types=1);


namespace App\TodoList\Infrastructure\Persistence\Doctrine\Types;


use App\TodoList\Domain\ValueObject\TaskDescription;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

final class TaskDescriptionType extends StringType
{
    private const TYPE = 'task_description';

    /**
     * @param TaskDescription $value
     * @param AbstractPlatform $platform
     * @return string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof TaskDescription) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), [TaskDescription::class]);
        }

        return $value->toString();
    }

    /**
     * @param string|TaskDescription $value
     * @param AbstractPlatform $platform
     * @return TaskDescription
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): TaskDescription
    {
        if ($value instanceof TaskDescription) {
            return $value;
        }

        return TaskDescription::fromString($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function getName(): string
    {
        return self::TYPE;
    }
}