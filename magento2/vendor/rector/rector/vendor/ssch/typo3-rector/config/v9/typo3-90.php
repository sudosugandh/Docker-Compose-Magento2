<?php

declare (strict_types=1);
namespace RectorPrefix20220604;

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Ssch\TYPO3Rector\FileProcessor\Composer\Rector\RemoveCmsPackageDirFromExtraComposerRector;
use Ssch\TYPO3Rector\FileProcessor\FlexForms\Rector\RenderTypeFlexFormRector;
use Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionInfoRector;
use Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionVersionRector;
use Ssch\TYPO3Rector\Rector\v9\v0\FindByPidsAndAuthorIdRector;
use Ssch\TYPO3Rector\Rector\v9\v0\GeneratePageTitleRector;
use Ssch\TYPO3Rector\Rector\v9\v0\IgnoreValidationAnnotationRector;
use Ssch\TYPO3Rector\Rector\v9\v0\InjectAnnotationRector;
use Ssch\TYPO3Rector\Rector\v9\v0\MetaTagManagementRector;
use Ssch\TYPO3Rector\Rector\v9\v0\MoveRenderArgumentsToInitializeArgumentsMethodRector;
use Ssch\TYPO3Rector\Rector\v9\v0\QueryLogicalOrAndLogicalAndToArrayParameterRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RefactorBackendUtilityGetPagesTSconfigRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RefactorDeprecationLogRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RefactorMethodsFromExtensionManagementUtilityRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RemoveMethodInitTCARector;
use Ssch\TYPO3Rector\Rector\v9\v0\RemovePropertiesFromSimpleDataHandlerControllerRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RemoveSecondArgumentGeneralUtilityMkdirDeepRector;
use Ssch\TYPO3Rector\Rector\v9\v0\ReplaceAnnotationRector;
use Ssch\TYPO3Rector\Rector\v9\v0\ReplacedGeneralUtilitySysLogWithLogginApiRector;
use Ssch\TYPO3Rector\Rector\v9\v0\ReplaceExtKeyWithExtensionKeyRector;
use Ssch\TYPO3Rector\Rector\v9\v0\SubstituteCacheWrapperMethodsRector;
use Ssch\TYPO3Rector\Rector\v9\v0\SubstituteConstantParsetimeStartRector;
use Ssch\TYPO3Rector\Rector\v9\v0\SubstituteGeneralUtilityDevLogRector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseExtensionConfigurationApiRector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseLogMethodInsteadOfNewLog2Rector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseNewComponentIdForPageTreeRector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseRenderingContextGetControllerContextRector;
return static function (\Rector\Config\RectorConfig $rectorConfig) : void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\MoveRenderArgumentsToInitializeArgumentsMethodRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\InjectAnnotationRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\IgnoreValidationAnnotationRector::class);
    $rectorConfig->ruleWithConfiguration(\Ssch\TYPO3Rector\Rector\v9\v0\ReplaceAnnotationRector::class, ['lazy' => 'TYPO3\\CMS\\Extbase\\Annotation\\ORM\\Lazy', 'cascade' => 'TYPO3\\CMS\\Extbase\\Annotation\\ORM\\Cascade("remove")', 'transient' => 'TYPO3\\CMS\\Extbase\\Annotation\\ORM\\Transient']);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionInfoRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\RefactorMethodsFromExtensionManagementUtilityRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\MetaTagManagementRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\FindByPidsAndAuthorIdRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\UseRenderingContextGetControllerContextRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\RemovePropertiesFromSimpleDataHandlerControllerRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\RemoveMethodInitTCARector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\SubstituteCacheWrapperMethodsRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\UseLogMethodInsteadOfNewLog2Rector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\GeneratePageTitleRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\SubstituteConstantParsetimeStartRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\RemoveSecondArgumentGeneralUtilityMkdirDeepRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionVersionRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\RefactorDeprecationLogRector::class);
    $rectorConfig->ruleWithConfiguration(\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class, [new \Rector\Renaming\ValueObject\MethodCallRename('TYPO3\\CMS\\Core\\Utility\\GeneralUtility', 'getUserObj', 'makeInstance')]);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\UseNewComponentIdForPageTreeRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\RefactorBackendUtilityGetPagesTSconfigRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\UseExtensionConfigurationApiRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\ReplaceExtKeyWithExtensionKeyRector::class);
    $rectorConfig->ruleWithConfiguration(\Ssch\TYPO3Rector\FileProcessor\Composer\Rector\RemoveCmsPackageDirFromExtraComposerRector::class, ['foo' => 'bar']);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\SubstituteGeneralUtilityDevLogRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\ReplacedGeneralUtilitySysLogWithLogginApiRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\FileProcessor\FlexForms\Rector\RenderTypeFlexFormRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v9\v0\QueryLogicalOrAndLogicalAndToArrayParameterRector::class);
};
