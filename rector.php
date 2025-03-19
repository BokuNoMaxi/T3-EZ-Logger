<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\CodeQuality\Rector\FuncCall\SimplifyFuncGetArgsCountRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector;
use Rector\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Classes',
        __DIR__ . '/Configuration',
    ])
    ->withSets([
        LevelSetList::UP_TO_PHP_83,
        Typo3LevelSetList::UP_TO_TYPO3_13
    ])
    ->withImportNames(true, true, true, true)
    ->withTypeCoverageLevel(0)
    // here we can define, what prepared sets of rules will be applied
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        symfonyCodeQuality: true,
    )
    ->withRules([
        TypedPropertyFromStrictConstructorRector::class,
        RemoveUnusedPrivateMethodRector::class,
        SimplifyIfReturnBoolRector::class,
        SimplifyFuncGetArgsCountRector::class,
        RemoveUnusedConstructorParamRector::class,
        RemoveUnusedForeachKeyRector::class
    ]);
