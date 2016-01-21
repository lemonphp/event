<?php

$header = <<<EOF
This file is part of `lemon/event` project.

(c) 2015-2016 LemonPHP Team

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

Symfony\CS\Fixer\Contrib\HeaderCommentFixer::setHeader($header);

return Symfony\CS\Config\Config::create()
    // use default PSR-2 and extra fixers:
    ->level('psr2')
    ->fixers(array(
        'header_comment',
        'short_array_syntax',
        'ordered_use',
        'php_unit_construct',
        'php_unit_strict',
        'strict',
        'strict_param',
    ))
    ->finder(
        Symfony\CS\Finder\DefaultFinder::create()
            ->exclude('tmp')
            ->in(__DIR__)
    )
;