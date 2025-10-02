<?php
declare(strict_types=1);

namespace Furstd\PornAnalyzer\Name;

class PornNameAnalyzerResult
{
    /**
     * @var string|null
     */
    private ?string $foundMatch;

    /**
     * @param string|null $foundMatch
     */
    public function __construct(?string $foundMatch = null)
    {
        $this->foundMatch = $foundMatch;
    }

    /**
     * @return bool
     */
    public function isErotic(): bool
    {
        return $this->foundMatch !== null;
    }

    /**
     * @return string|null
     */
    public function getFoundMatch(): ?string
    {
        return $this->foundMatch;
    }

    /**
     * @param string|null $foundMatch
     * @return void
     */
    public function setFoundMatch(?string $foundMatch): void
    {
        $this->foundMatch = $foundMatch;
    }
}