# Porn Analyzer

Small PHP library to help detect erotic content using two simple heuristics:
- Name analysis: check a normalized title/name for porn-related phrases and terms.
- Score analysis: aggregate an array of numeric scores (e.g., frame or thumbnail nudity confidence) into a single normalized score and classify it.

## Installation (Composer repository/VCS)
This package can be installed directly from a VCS repository (e.g., a Git repo) using Composer's "repositories" section. Add a VCS repository entry to your project's composer.json and require the package by its name.

Example composer.json snippet:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/furst-d/porn-analyzer.git"
    }
  ]
}
```

Then install:

```bash
composer require furstd/porn-analyzer:^1.0
```

This library requires PHP >= 7.4.


## Usage

### 1) Name detection
Use PornNameAnalyzer to detect erotic content by matching phrases or individual terms in a normalized name.

```php
use Furstd\PornAnalyzer\Name\PornNameAnalyzer;

$terms = ['porn', 'xxx', 'lesbian', 'anal'];          // single-word matches
$phrases = ['adult movie', 'teen porn', 'animal porn']; // multi-word phrases (take precedence)

$analyzer = new PornNameAnalyzer($terms, $phrases);

$title = 'My Best ADULT-movie trailer (LESBIÁN vibes)';
$result = $analyzer->analyze($title);

if ($result->isErotic()) {
    echo 'Erotic by name. Found: ' . $result->getFoundMatch();
} else {
    echo 'Not erotic by name';
}
```

Name normalization details:
- The input string is normalized (lowercased, accents removed, special characters replaced with spaces, multiple spaces condensed).
- Phrase checks are performed first; if a phrase is found, it wins. Otherwise, tokenized terms are matched.

### 2) Score detection
Use PornScoreAnalyzer to aggregate multiple numeric scores into a single normalized score and classify it.

Constructor parameters:
- **scoresTopPercent** (int): Percent of top scores to consider (1–100). It is expected that not the entire video has to be erotic from start to finish. For this reason, only the top x% of scores are averaged, e.g., for 25% and 60 scores, the top 15 scores are averaged.
- **eroticBoundaryScore** (int): The boundary score for classifying content as erotic. If the normalized score is greater than or equal to this value, the content is classified as erotic. Default is 100.
- **scoreNormalizationMax** (int, default 100): The maximum value of the normalized score. If a value is e.g., 300, the normalized score is clamped to [0, 300].

Scores input format:
- Array of associative arrays with keys time and score. score may be a float or numeric string in [0,1]. time is not used for computation, but can be any monotonic increasing value.

Example with a provided sample array:

```php
use Furstd\PornAnalyzer\Score\PornScoreAnalyzer;

$scoresData = [
    ["time"=>1,"score"=>"0.0059"],["time"=>2,"score"=>"0.0080"],["time"=>3,"score"=>"0.0038"],
    ["time"=>4,"score"=>"0.0156"],["time"=>5,"score"=>"0.0011"],["time"=>6,"score"=>"0.0003"],
    ["time"=>7,"score"=>"0.0011"],["time"=>8,"score"=>"0.0114"],["time"=>9,"score"=>"0.0012"],
];

$analyzer = new PornScoreAnalyzer(
    25,  // scoresTopPercent -> consider the top 25% (i.e., top 1/4) of scores
    220, // eroticBoundaryScore -> content is erotic if normalized score >= 220
    300  // scoreNormalizationMax -> normalize to 0..300 instead of 0..100
);

$result = $analyzer->analyze($scoresData);

echo 'Score: ' . $result->getScore() . PHP_EOL;

echo 'Selected ' . $result->getSelectedScoreCount() . ' scores';

foreach ($result->getSelectedScores() as $score) {
    echo ' - ' . $score['time'] . ': ' . $score['score'];
}

echo $result->isErotic() ? 'Erotic by score' : 'Not erotic by score';
```

What happens:
- With 9 items and top 25%, the analyzer selects the highest 2 scores (1/4 of 9 rounded down).
- It averages those top scores and multiplies by scoreNormalizationMax (here 300), clamped to [0, scoreNormalizationMax].
- The boundary check compares that normalized score to eroticBoundaryScore.

## Testing
This project uses PHPUnit. After installing dev dependencies, run:

```bash
composer install
composer test
```

## License
MIT License. See LICENSE for details.