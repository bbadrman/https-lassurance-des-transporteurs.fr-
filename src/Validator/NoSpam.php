<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class NoSpam extends Constraint
{
    public const SPAM_URL = 'spam-url';
    public const SPAM_PATTERN = 'spam-pattern';
    public const SPAM_EMOJI = 'spam-emoji';
    public const SPAM_SQL_INJECTION = 'spam-sql-injection';

    public string $message = 'Contenu non autorisé.';
    public string $messageUrl = 'Les liens et URLs ne sont pas autorisés.';
    public string $messagePattern = 'Contenu suspect détecté.';
    public string $messageEmoji = 'Les emojis ne sont pas autorisés dans ce champ.';
    public string $messageSqlInjection = 'Des caractères ou un format suspects ont été détectés.';

    public string $mode = 'strict';
    public bool $alphaOnly = false;

    public function __construct(
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
        ?string $message = null,
        ?string $messageUrl = null,
        ?string $messagePattern = null,
        ?string $messageEmoji = null,
        ?string $messageSqlInjection = null,
        ?string $mode = null,
        ?bool $alphaOnly = null,
    ) {
        $namedArgs = [
            'message' => $message,
            'messageUrl' => $messageUrl,
            'messagePattern' => $messagePattern,
            'messageEmoji' => $messageEmoji,
            'messageSqlInjection' => $messageSqlInjection,
            'mode' => $mode,
            'alphaOnly' => $alphaOnly,
        ];

        $namedArgs = array_filter($namedArgs, static fn ($v) => null !== $v);

        if (is_array($options)) {
            $options = array_merge($options, $namedArgs);
        } elseif (is_string($options)) {
            $options = array_merge(['message' => $options], $namedArgs);
        } else {
            $options = $namedArgs;
        }

        $allowed = [
            'message',
            'messageUrl',
            'messagePattern',
            'messageEmoji',
            'messageSqlInjection',
            'mode',
            'alphaOnly',
        ];

        $options = array_intersect_key(
            $options,
            array_flip($allowed)
        );

        parent::__construct($options, $groups, $payload);
    }

    public function validatedBy(): string
    {
        return static::class . 'Validator';
    }
}
