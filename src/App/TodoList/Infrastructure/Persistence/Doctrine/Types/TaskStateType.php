<?php

declare(strict_types=1);


namespace App\TodoList\Infrastructure\Persistence\Doctrine\Types;


use App\TodoList\Domain\ValueObject\TaskState;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\IntegerType;

final class TaskStateType extends IntegerType
{
    private const TYPE = 'task_state';

    /**
     * @param TaskState $value
     * @param AbstractPlatform $platform
     * @return int
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): int
    {
        if (!$value instanceof TaskState) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), [TaskState::class]);
        }

        return $value->getValue();
    }

    /**
     * @param int|TaskState $value
     * @param AbstractPlatform $platform
     * @return TaskState
     * @throws AssertionFailedException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): TaskState
    {
        if ($value instanceof TaskState) {
            return $value;
        }

        return TaskState::create($value);
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