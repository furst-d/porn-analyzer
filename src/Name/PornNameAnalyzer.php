<?php
declare(strict_types=1);

namespace Furstd\PornAnalyzer\Name;

final class PornNameAnalyzer
{
    /**
     * @var array $pornTerms
     */
    private array $pornTerms;

    /**
     * @var array $pornPhrases
     */
    private array $pornPhrases;

    /**
     * @param array $pornTerms
     * @param array $pornPhrases
     */
    public function __construct(array $pornTerms = [], array $pornPhrases = [])
    {
        $this->pornTerms = $pornTerms;
        $this->pornPhrases = $pornPhrases;
    }

    /**
     * @param string $name
     * @return PornNameAnalyzerResult
     */
    public function analyze(string $name): PornNameAnalyzerResult
    {
        $name = PornNameNormalizer::normalize($name);

        $result = new PornNameAnalyzerResult();

        foreach ($this->pornPhrases as $phrase) {
            if (stripos($name, $phrase) !== false) {
                $result->setFoundMatch($phrase);
                return $result;
            }
        }

        foreach (explode(' ', $name) as $term) {
            if (in_array($term, $this->pornTerms)) {
                $result->setFoundMatch($term);
                return $result;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getPornTerms(): array
    {
        return $this->pornTerms;
    }

    /**
     * @param array $pornTerms
     * @return void
     */
    public function setPornTerms(array $pornTerms): void
    {
        $this->pornTerms = $pornTerms;
    }

    /**
     * @return array
     */
    public function getPornPhrases(): array
    {
        return $this->pornPhrases;
    }

    /**
     * @param array $pornPhrases
     * @return void
     */
    public function setPornPhrases(array $pornPhrases): void
    {
        $this->pornPhrases = $pornPhrases;
    }


    /**
     * @param string $term
     * @return void
     */
    public function addPornTerm(string $term): void
    {
        if (!in_array($term, $this->pornTerms, true)) {
            $this->pornTerms[] = $term;
        }
    }

    /**
     * @param string $phrase
     * @return void
     */
    public function addPornPhrase(string $phrase)
    {
        if (!in_array($phrase, $this->pornPhrases, true)) {
            $this->pornPhrases[] = $phrase;
        }
    }

    /**
     * @param string $term
     * @return void
     */
    public function removePornTerm(string $term)
    {
        $this->pornTerms = array_filter($this->pornTerms, fn($t) => $t !== $term);
    }

    /**
     * @param string $phrase
     * @return void
     */
    public function removePornPhrase(string $phrase)
    {
        $this->pornPhrases = array_filter($this->pornPhrases, fn($p) => $p !== $phrase);
    }
}