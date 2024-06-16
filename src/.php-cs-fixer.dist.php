<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src')
    ->exclude('PaymentProcessor/Vendor')
    ->notPath('Kernel.php')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'declare_strict_types' => true,
        'yoda_style' => false,
        'phpdoc_align' => false,
        'trailing_comma_in_multiline' => [
            'after_heredoc' => true,
            'elements' => ['arguments', 'arrays', 'match', 'parameters'],
        ],
    ])
    ->setFinder($finder)
;
