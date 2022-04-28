<?php

return (new PhpCsFixer\Config())
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__.'/example')
            ->in(__DIR__.'/demo')
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/tests')
            ->append([
                __FILE__,
            ])
    )
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        '@PSR12' => true,
        '@PhpCsFixer' => true,
        PhpCsFixerCustomFixers\Fixer\StringableInterfaceFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\PhpdocArrayStyleFixer::name() => true,
        'phpdoc_to_comment' => false,
        'line_ending' => false
    ])
;
