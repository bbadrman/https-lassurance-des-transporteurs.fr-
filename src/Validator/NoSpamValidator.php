<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NoSpamValidator extends ConstraintValidator
{
    private array $violationMessages = [];

    private function addViolationOnce(string $message): void
    {
        if (in_array($message, $this->violationMessages, true)) {
            return;
        }

        $this->violationMessages[] = $message;
        $this->context->buildViolation($message)
            ->addViolation();
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof NoSpam) {
            throw new UnexpectedTypeException($constraint, NoSpam::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value) && !is_numeric($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $keywords = $constraint->spamKeywords;
        $domains = $constraint->suspiciousDomains;
        $emojis = $constraint->spamEmojis;

        $this->checkUrl($value, $constraint);
        $this->checkDomains($value, $domains, $constraint);
        $this->checkKeywords($value, $keywords, $constraint);
        $this->checkPatterns($value, $constraint);
        $this->checkGibberish($value, $constraint);
        $this->checkRepeatedLetters($value, $constraint);
        $this->checkEmojis($value, $emojis, $constraint);
    }

    private function checkUrl(string $value, NoSpam $constraint): void
    {
        if (preg_match('#https?://#i', $value) || preg_match('#www\.#i', $value)) {
            $this->addViolationOnce($constraint->messageUrl);
        }
    }

    private function checkDomains(string $value, array $domains, NoSpam $constraint): void
    {
        $lower = mb_strtolower($value);

        foreach ($domains as $domain) {
            if (str_contains($lower, mb_strtolower($domain))) {
                $this->addViolationOnce($constraint->messageDomain);

                return;
            }
        }
    }

    private function checkKeywords(string $value, array $keywords, NoSpam $constraint): void
    {
        $lower = mb_strtolower($value);

        foreach ($keywords as $keyword) {
            $pattern = '#' . preg_quote($keyword, '#') . '#iu';

            if (preg_match($pattern, $lower)) {
                $this->context->buildViolation($constraint->messageKeyword)
                    ->addViolation();

                return;
            }
        }
    }

    private function checkPatterns(string $value, NoSpam $constraint): void
    {
        if (preg_match('#([\$\>\|\!\.\,\;\:\-\_ ])\1{4,}#', $value)) {
            $this->addViolationOnce($constraint->messagePattern);
        }

        if (preg_match('#(.)\1\1#u', $value)) {
            $this->addViolationOnce($constraint->messagePattern);
        }
    }

    private function checkGibberish(string $value, NoSpam $constraint): void
    {
        if (!preg_match('#(.)\1{2,}#u', $value)) {
            return;
        }

        $letters = preg_replace('#[^a-zA-ZàâäéèêëïîôùûüÿçÀÂÄÉÈÊËÏÎÔÙÛÜŸÇ]#u', '', $value);

        if ($letters === '' || preg_match('#^([a-zA-ZàâäéèêëïîôùûüÿçÀÂÄÉÈÊËÏÎÔÙÛÜŸÇ])\1{3,}$#u', $letters)) {
            $this->addViolationOnce($constraint->messagePattern);
        }
    }

    private function checkRepeatedLetters(string $value, NoSpam $constraint): void
    {
        if (preg_match('#[a-zA-ZàâäéèêëïîôùûüÿçÀÂÄÉÈÊËÏÎÔÙÛÜŸÇ]([a-zA-ZàâäéèêëïîôùûüÿçÀÂÄÉÈÊËÏÎÔÙÛÜŸÇ])\1{2,}#u', $value)) {
            $this->addViolationOnce('Séquences de lettres répétées non autorisées (ex: aaaa, pppp).');
        }
    }

    private function checkEmojis(string $value, array $emojis, NoSpam $constraint): void
    {
        $escapedEmojis = array_map(fn($emoji) => preg_quote($emoji, '#'), $emojis);
        $emojiList = implode('|', $escapedEmojis);

        preg_match_all('#' . $emojiList . '#u', $value, $matches);

        if (!empty($matches[0])) {
            $this->addViolationOnce($constraint->messageEmoji);
        }
    }
}