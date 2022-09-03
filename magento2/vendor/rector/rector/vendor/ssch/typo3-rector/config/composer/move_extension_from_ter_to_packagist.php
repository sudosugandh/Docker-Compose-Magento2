<?php

declare (strict_types=1);
namespace RectorPrefix20220604;

return static function (\Rector\Config\RectorConfig $rectorConfig) : void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $additionalComposerExtensions = [new \Rector\Composer\ValueObject\RenamePackage('typo3-ter/social_auth', 'kalypso63/social_auth')];
    $allComposerExtensions = \array_merge($composerExtensions, $additionalComposerExtensions);
    $rectorConfig->ruleWithConfiguration(\Rector\Composer\Rector\RenamePackageComposerRector::class, $allComposerExtensions);
};