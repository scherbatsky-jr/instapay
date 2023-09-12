<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->notPath('bootstrap/cache')
    ->notPath('storage')
    ->notPath('vendor')
    ->notPath('node_modules')
    ->notPath('infrastructure/Permissions/Permissions.php')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$rules = [
    '@PSR2' => true,
    '@Symfony' => true,
    'array_syntax' => ['syntax' => 'short'],
    'phpdoc_no_package' => false,
    'phpdoc_no_empty_return' => false,
    'ordered_imports' => true,
    'ordered_class_elements' => ['order' => ['use_trait', 'constant_public', 'constant_protected', 'constant_private', 'property_public', 'property_protected', 'property_private', 'construct', 'destruct', 'magic', 'phpunit', 'method_public', 'method_protected', 'method_private'], 'sort_algorithm' => 'alpha']
];

$config = new Config();

return $config
    ->setRules($rules)
    ->setFinder($finder)
    ->setUsingCache(false);
