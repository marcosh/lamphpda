<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

$config = require __DIR__ . '/vendor/drupol/php-conventions/config/php73/php_cs_fixer.config.php';

$config
    ->getFinder()
    ->ignoreDotFiles(false)
    ->exclude(['.git', 'spec', 'spec-old']);

$rules = $config->getRules();

$rules = array_diff_key(
    $rules,
    [
    // @todo: Disabling this rule, it creates issues with PSalm
    // see @file src/Typeclass/DefaultInstance/DefaultTraversable.php
    'ordered_interfaces' => 'ordered_interfaces',
    ]
);

$config->setRules($rules);

return $config;
