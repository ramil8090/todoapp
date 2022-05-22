<?php

declare(strict_types=1);


namespace App\TodoList\Infrastructure\Persistence\Doctrine\Types;


use App\TodoList\Domain\ValueObject\TodoListTitle;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

final class TodoListTitleType extends StringType
{
    private const TYPE = 'todolist_title';

    /**
     * @param TodoListTitle $value
     * @param AbstractPlatform $platform
     * @return string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof TodoListTitle) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), [TodoListTitle::class]);
        }

        return $value->toString();
    }

    /**
     * @param string|TodoListTitle $value
     * @param AbstractPlatform $platform
     * @return TodoListTitle
     * @throws AssertionFailedException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): TodoListTitle
    {
        if ($value instanceof TodoListTitle) {
            return $value;
        }

        return new TodoListTitle($value);
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