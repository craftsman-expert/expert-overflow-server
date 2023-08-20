<?php

$directory = __DIR__.'/cache';

if (!file_exists($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
    throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
}

$finder = PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->path([
        'src',
        'config',
        'resources/dictionary',
        'tests',
    ])
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
return $config
    ->setRules(
        [
            '@Symfony' => true,
            'no_whitespace_before_comma_in_array' => true,
            'array_indentation' => true,
            'echo_tag_syntax' => [
                'format' => 'short'
            ],
            'function_declaration' => [
                'closure_function_spacing' => 'none'
            ],
            'full_opening_tag' => true,
            'phpdoc_annotation_without_dot' => true,
            'phpdoc_to_comment' => false,
            'phpdoc_summary' => false,
            'phpdoc_align' => [
                'align' => 'left',
            ],
            'yoda_style' => true,
            'concat_space' => [
                'spacing' => 'one',
            ],
            'cast_spaces' => [
                'space' => 'none',
            ],
            'ordered_imports' => [
                'sort_algorithm' => 'none',
                'imports_order' => null
            ]
        ]
    )
    ->setCacheFile($directory . '/.php-cs-fixer.cache')
    ->setFinder($finder);
