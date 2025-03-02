<?php declare(strict_types=1);



namespace DataStructures\String;

use DataStructures\Collection\Collection;
use JetBrains\PhpStorm\Language;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use LogicException;
use Stringable;

class Str implements JsonSerializable, Stringable
{
    public function __construct(private string $value)
    {
    }

    public static function from(string|Stringable $value): Str
    {
        return new self((string)$value);
    }

    #[Pure]
    protected function make(string $value): Str
    {
        return new self($value);
    }

    /**
     * @template TKey of array-key
     * @template TValue
     * @param array<TKey, TValue> $values
     * @return Collection<TKey, TValue>
     */
    #[Pure]
    protected function newCollection(array $values): Collection
    {
        return new Collection($values);
    }

    #[Pure]
    public function isEmpty(): bool
    {
        return $this->value === '';
    }
    /**
     * @param string $separator
     * @return Collection<int, Str>
     */

    #[Pure]
    public function split(string|Stringable $separator): Collection
    {
        // mimic javascript to split by character when empty separator.
        $sep = (string)$separator;
        $values = $sep === '' ? mb_str_split($this->value, 1) : explode($sep, $this->value);

        return $this->newCollection($values)->map(fn($v) => $this->make($v));
    }

    #[Pure]
    public function replace(string|Stringable $find, string|Stringable $replace): Str
    {
        return $this->make(str_replace((string)$find, (string) $replace, $this->value));
    }

    /**
     * @param string|Stringable $regex
     * @param string|Stringable $replacement
     * @return Str
     * @throws LogicException if preg_replace fails, see: https://www.php.net/manual/en/function.preg-last-error.php
     */
    #[Pure]
    public function regReplace(#[Language("RegExp")]string|Stringable $regex, string|Stringable $replacement): Str
    {
        return $this->make(
            preg_replace((string)$regex, (string)$replacement, $this->value)
            ?? throw new LogicException('preg_replace failed: ' . preg_last_error_msg())
        ) ;
    }

    #[Pure]
    public function isMatch(#[Language("RegExp")] string|Stringable $regex): bool
    {
        return preg_match((string)$regex, $this->value) === 1;
    }

    /**
     * @param string|Stringable $regex
     * @return Collection<int, string>
     */
    #[Pure]
    public function matches(#[Language("RegExp")] string|Stringable $regex): Collection
    {
        $matches = [];
        preg_match((string)$regex, $this->value, $matches);

        return $this->newCollection($matches);
    }

    #[Pure]
    public function trim(string|Stringable $characters = " \n\r\t\v\0"): Str
    {
        return $this->make(trim($this->value, (string)$characters));
    }

    #[Pure]
    public function trimLeft(string|Stringable $characters = " \n\r\t\v\0"): Str
    {
        return $this->make(ltrim($this->value, (string)$characters));
    }

    #[Pure]
    public function trimRight(string|Stringable $characters = " \n\r\t\v\0"): Str
    {
        return $this->make(rtrim($this->value, (string)$characters));
    }

    #[Pure]
    public function prepend(string|Stringable $prepend): Str
    {
        return $this->make($prepend . $this->value);
    }

    #[Pure]
    public function append(string|Stringable $append): Str
    {
        return $this->make($this->value . $append);
    }

    #[Pure]
    public function substring(int $start = 0, ?int $length = null): Str
    {
        return $this->make(substr($this->value, $start, $length));
    }

    #[Pure]
    public function contains(string|Stringable $needle): bool
    {
        return str_contains($this->value, (string)$needle) !== false;
    }

    #[Pure]
    public function startsWith(string|Stringable $needle): bool
    {
        return str_starts_with($this->value, (string)$needle);
    }

    #[Pure]
    public function endsWith(string|Stringable $needle): bool
    {
        return str_ends_with($this->value, (string) $needle);
    }

    #[Pure]
    public function toUppercase(): Str
    {
        return $this->make(strtoupper($this->value));
    }

    #[Pure]
    public function toLowercase(): Str
    {
        return $this->make(strtolower($this->value));
    }

    #[Pure]
    public function uppercaseFirst(): Str
    {
        return $this->make(ucfirst($this->value));
    }

    #[Pure]
    public function lowercaseFirst(): Str
    {
        return $this->make(lcfirst($this->value));
    }

    #[Pure]
    public function indexOf(Stringable|string $needle): int|false
    {
        return strpos($this->value, (string)$needle);
    }

    #[Pure]
    public function padLeft(int $expectedLength, string|Stringable $char = ' '): Str
    {
        return $this->make(str_pad($this->value, $expectedLength, (string)$char,STR_PAD_LEFT));
    }

    #[Pure]
    public function padRight(int $expectedLength,string|Stringable $char = ' '): Str
    {
        return $this->make(str_pad($this->value, $expectedLength, (string)$char,STR_PAD_RIGHT));
    }

    #[Pure]
    public function __toString(): string
    {
        return $this->value;
    }

    #[Pure]
    public function value(): string
    {
        return $this->value;
    }

    #[Pure]
    public function jsonSerialize(): string
    {
        return $this->value;
    }

    #[Pure]
    public function slug(string $slugChar = '-', #[Language('RegExp')] string $replacementRegex = '/[^a-z0-9]+/', bool $upperToSlugLowercase = true): Str
    {
        // uppercase add dash in front except for the first character
        $value = $this->value;
        if($upperToSlugLowercase){
            $value = preg_replace_callback('/[A-Z]/', fn($match) => $slugChar . strtolower($match[0]), $this->value)
                ?? throw new LogicException("Regex replace failed: ". preg_last_error_msg());
        }
        $value = trim(
            preg_replace($replacementRegex, $slugChar, $value)
                ?? throw new LogicException("Regex replace failed: ". preg_last_error_msg()),
            $slugChar
        );

        return $this->make($value);
    }
}
