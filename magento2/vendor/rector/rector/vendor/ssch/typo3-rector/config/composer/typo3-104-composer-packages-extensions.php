<?php

declare (strict_types=1);
namespace RectorPrefix20220604;

return static function (\Rector\Config\RectorConfig $rectorConfig) : void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $manualAddedComposerExtensions = [new \Rector\Composer\ValueObject\PackageAndVersion('koehlersimon/slug', '^3.0')];
    $rectorConfig->ruleWithConfiguration(\Rector\Composer\Rector\ChangePackageVersionComposerRector::class, \array_merge($composerExtensions, $manualAddedComposerExtensions));
};