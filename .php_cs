<?php

$header = <<<EOF
This file is part of markenzoo/contao-expo-push-notification-bundle.

Copyright (c) 2020 markenzoo eG
 
@package   markenzoo/contao-expo-push-notification-bundle
@author    Felix Kästner <kaestner@markenzoo.de>
@copyright 2020 markenzoo eG
@license   https://github.com/markenzoo/contao-expo-push-notification-bundle/blob/master/LICENSE MIT License
EOF;

$finder = PhpCsFixer\Finder::create()
    ->exclude('Resources')
    ->in([__DIR__.'/src', __DIR__.'/src/Resources/contao', __DIR__.'/tests'])
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP71Migration' => true,
        '@PHP71Migration:risky' => true,
        'align_multiline_comment' => true,
        'array_syntax' => ['syntax' => 'short'],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'comment_to_phpdoc' => true,
        'compact_nullable_typehint' => true,
        'escape_implicit_backslashes' => true,
        'fully_qualified_strict_types' => true,
        'general_phpdoc_annotation_remove' => [
            'expectedException',
            'expectedExceptionMessage',
        ],
        'header_comment' => ['header' => $header],
        'heredoc_to_nowdoc' => true,
        'linebreak_after_opening_tag' => true,
        'list_syntax' => ['syntax' => 'short'],
        'multiline_comment_opening_closing' => true,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'new_line_for_chained_calls',
        ],
        'native_function_invocation' => [
            'include' => ['@compiler_optimized'],
        ],
        'no_alternative_syntax' => true,
        'no_binary_string' => true,
        'no_null_property_initialization' => true,
        'no_superfluous_elseif' => true,
        'no_superfluous_phpdoc_tags' => false,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'php_unit_method_casing' => true,
        'php_unit_strict' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
            'sort_algorithm' => 'none',
        ],
        'phpdoc_var_annotation_correct_order' => true,
        'return_assignment' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'string_line_ending' => true,
        'void_return' => true,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(false)
;