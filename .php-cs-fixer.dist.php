<?php

$finder = PhpCsFixer\Finder::create()->in(__DIR__);

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PSR12' => true,
    '@PhpCsFixer' => true,
    '@PhpCsFixer:risky' => true,
    'phpdoc_separation' => false,
    'phpdoc_align' => ['align' => 'left'],
    'phpdoc_to_comment' => false,
    'concat_space' => ['spacing' => 'one'],
    'static_lambda' => true
])->setFinder($finder);
