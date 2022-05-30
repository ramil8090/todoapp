<?php

declare(strict_types=1);


namespace App\TodoList\Infrastructure\Persistence\Doctrine\Types;


use App\TodoList\Domain\ValueObject\TaskTitle;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

final class TaskTitleType extends StringType
{
    private const TYPE = 'task_title';

    /**
     * @param TaskTitle $value
     * @param AbstractPlatform $platform
     * @return string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof TaskTitle) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), [TaskTitle::class]);
        }

        return $value->toString();
    }

    /**
     * @param string|TaskTitle $value
     * @param AbstractPlatform $platform
     * @return TaskTitle
     * @throws AssertionFailedException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): TaskTitle
    {
        if ($value instanceof TaskTitle) {
            return $value;
        }

        return TaskTitle::fromString($value);
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