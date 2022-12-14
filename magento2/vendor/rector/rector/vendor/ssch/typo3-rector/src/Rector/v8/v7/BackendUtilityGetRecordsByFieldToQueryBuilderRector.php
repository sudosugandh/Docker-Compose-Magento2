<?php

declare (strict_types=1);
namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-79122-DeprecateBackendUtilitygetRecordsByField.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v7\BackendUtilityGetRecordsByFieldToQueryBuilderRector\BackendUtilityGetRecordsByFieldToQueryBuilderRectorTest
 */
final class BackendUtilityGetRecordsByFieldToQueryBuilderRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const MAKE_INSTANCE = 'makeInstance';
    /**
     * @var string
     */
    private const LIMIT_OFFSET_AND_MAX = 'limitOffsetAndMax';
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\StaticCall::class];
    }
    /**
     * @param StaticCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, new \PHPStan\Type\ObjectType('TYPO3\\CMS\\Backend\\Utility\\BackendUtility'))) {
            return null;
        }
        if (!$this->isName($node->name, 'getRecordsByField')) {
            return null;
        }
        $parentNode = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        $positionNode = $node;
        if ($parentNode instanceof \PhpParser\Node\Stmt\Return_) {
            $positionNode = $parentNode;
        }
        $this->addQueryBuilderNode($node, $positionNode);
        $queryBuilderVariableName = $this->extractQueryBuilderVariableName($node);
        $this->addQueryBuilderBackendWorkspaceRestrictionNode($queryBuilderVariableName, $positionNode);
        $this->addQueryBuilderDeletedRestrictionNode($queryBuilderVariableName, $node, $positionNode);
        $this->addQueryBuilderSelectNode($queryBuilderVariableName, $node, $positionNode);
        $this->addQueryWhereNode($queryBuilderVariableName, $node, $positionNode);
        $this->addQueryGroupByNode($queryBuilderVariableName, $node, $positionNode);
        $this->addOrderByNode($queryBuilderVariableName, $node, $positionNode);
        $this->addLimitNode($queryBuilderVariableName, $node, $positionNode);
        return $this->nodeFactory->createMethodCall($this->nodeFactory->createMethodCall($queryBuilderVariableName, 'execute'), 'fetchAll');
    }
    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('BackendUtility::getRecordsByField to QueryBuilder', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Utility\BackendUtility;

$rows = BackendUtility::getRecordsByField('table', 'uid', 3);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\BackendWorkspaceRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('table');
$queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(BackendWorkspaceRestriction::class));
$queryBuilder->getRestrictions()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
$queryBuilder->select('*')->from('table')->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter(3)));
$rows = $queryBuilder->execute()->fetchAll();
CODE_SAMPLE
)]);
    }
    private function addQueryBuilderNode(\PhpParser\Node\Expr\StaticCall $staticCall, \PhpParser\Node $positionNode) : void
    {
        $queryBuilderArgument = $staticCall->args[8] ?? null;
        if ($this->isVariable($queryBuilderArgument)) {
            return;
        }
        $tableArgument = $staticCall->args[0];
        if (!$queryBuilderArgument instanceof \PhpParser\Node\Arg || 'null' === $this->valueResolver->getValue($queryBuilderArgument->value)) {
            $table = $this->valueResolver->getValue($tableArgument->value);
            if (null === $table) {
                $table = $tableArgument;
            }
            $queryBuilder = $this->nodeFactory->createMethodCall($this->nodeFactory->createStaticCall('TYPO3\\CMS\\Core\\Utility\\GeneralUtility', self::MAKE_INSTANCE, [$this->nodeFactory->createClassConstReference('TYPO3\\CMS\\Core\\Database\\ConnectionPool')]), 'getQueryBuilderForTable', [$table]);
        } else {
            $queryBuilder = $queryBuilderArgument->value;
        }
        $queryBuilderAssign = new \PhpParser\Node\Expr\Assign(new \PhpParser\Node\Expr\Variable('queryBuilder'), $queryBuilder);
        $this->nodesToAddCollector->addNodeBeforeNode($queryBuilderAssign, $positionNode);
    }
    private function isVariable(?\PhpParser\Node\Arg $queryBuilderArgument) : bool
    {
        return null !== $queryBuilderArgument && $queryBuilderArgument->value instanceof \PhpParser\Node\Expr\Variable;
    }
    private function extractQueryBuilderVariableName(\PhpParser\Node\Expr\StaticCall $staticCall) : string
    {
        $queryBuilderArgument = $staticCall->getArgs()[8] ?? null;
        $queryBuilderVariableName = 'queryBuilder';
        if (null !== $queryBuilderArgument && $this->isVariable($queryBuilderArgument)) {
            $queryBuilderVariableName = $this->getName($queryBuilderArgument->value);
        }
        return (string) $queryBuilderVariableName;
    }
    private function addQueryBuilderBackendWorkspaceRestrictionNode(string $queryBuilderVariableName, \PhpParser\Node $positionNode) : void
    {
        $newNode = $this->nodeFactory->createMethodCall($this->nodeFactory->createMethodCall($this->nodeFactory->createMethodCall($queryBuilderVariableName, 'getRestrictions'), 'removeAll'), 'add', [$this->nodeFactory->createStaticCall('TYPO3\\CMS\\Core\\Utility\\GeneralUtility', self::MAKE_INSTANCE, [$this->nodeFactory->createClassConstReference('TYPO3\\CMS\\Core\\Database\\Query\\Restriction\\BackendWorkspaceRestriction')])]);
        $this->nodesToAddCollector->addNodeBeforeNode($newNode, $positionNode);
    }
    private function addQueryBuilderDeletedRestrictionNode(string $queryBuilderVariableName, \PhpParser\Node\Expr\StaticCall $node, \PhpParser\Node $positionNode) : void
    {
        $useDeleteClauseArgument = $node->args[7] ?? null;
        $useDeleteClause = null !== $useDeleteClauseArgument ? $this->valueResolver->getValue($useDeleteClauseArgument->value) : \true;
        if (\false === $useDeleteClause) {
            return;
        }
        $deletedRestrictionNode = $this->nodeFactory->createMethodCall($this->nodeFactory->createMethodCall($queryBuilderVariableName, 'getRestrictions'), 'add', [$this->nodeFactory->createStaticCall('TYPO3\\CMS\\Core\\Utility\\GeneralUtility', self::MAKE_INSTANCE, [$this->nodeFactory->createClassConstReference('TYPO3\\CMS\\Core\\Database\\Query\\Restriction\\DeletedRestriction')])]);
        if ($useDeleteClause) {
            $this->nodesToAddCollector->addNodeBeforeNode($deletedRestrictionNode, $positionNode);
            return;
        }
        if (!$useDeleteClauseArgument instanceof \PhpParser\Node\Arg) {
            return;
        }
        $if = new \PhpParser\Node\Stmt\If_($useDeleteClauseArgument->value);
        $if->stmts[] = new \PhpParser\Node\Stmt\Expression($deletedRestrictionNode);
        $this->nodesToAddCollector->addNodeBeforeNode($if, $positionNode);
    }
    private function addQueryBuilderSelectNode(string $queryBuilderVariableName, \PhpParser\Node\Expr\StaticCall $node, \PhpParser\Node $positionNode) : void
    {
        $queryBuilderWhereExpressionNode = $this->nodeFactory->createMethodCall($this->nodeFactory->createMethodCall($queryBuilderVariableName, 'expr'), 'eq', [$node->args[1]->value, $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'createNamedParameter', [$node->args[2]->value])]);
        $queryBuilderWhereNode = $this->nodeFactory->createMethodCall($this->nodeFactory->createMethodCall($this->nodeFactory->createMethodCall($queryBuilderVariableName, 'select', ['*']), 'from', [$node->args[0]->value]), 'where', [$queryBuilderWhereExpressionNode]);
        $this->nodesToAddCollector->addNodeBeforeNode($queryBuilderWhereNode, $positionNode);
    }
    private function addQueryWhereNode(string $queryBuilderVariableName, \PhpParser\Node\Expr\StaticCall $staticCall, \PhpParser\Node $positionNode) : void
    {
        $whereClauseArgument = $staticCall->args[3] ?? null;
        $whereClause = null !== $whereClauseArgument ? $this->valueResolver->getValue($whereClauseArgument->value) : '';
        if ('' === $whereClause) {
            return;
        }
        $whereClauseNode = $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'andWhere', [$this->nodeFactory->createStaticCall('TYPO3\\CMS\\Core\\Database\\Query\\QueryHelper', 'stripLogicalOperatorPrefix', [$staticCall->args[3]])]);
        if ($whereClause) {
            $this->nodesToAddCollector->addNodeBeforeNode($whereClauseNode, $positionNode);
            return;
        }
        if (!$whereClauseArgument instanceof \PhpParser\Node\Arg) {
            return;
        }
        $if = new \PhpParser\Node\Stmt\If_($whereClauseArgument->value);
        $if->stmts[] = new \PhpParser\Node\Stmt\Expression($whereClauseNode);
        $this->nodesToAddCollector->addNodeBeforeNode($if, $positionNode);
    }
    private function addQueryGroupByNode(string $queryBuilderVariableName, \PhpParser\Node\Expr\StaticCall $staticCall, \PhpParser\Node $positionNode) : void
    {
        $groupByArgument = $staticCall->args[4] ?? null;
        $groupBy = null !== $groupByArgument ? $this->valueResolver->getValue($groupByArgument->value) : '';
        if ('' === $groupBy) {
            return;
        }
        $groupByNode = $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'groupBy', [$this->nodeFactory->createStaticCall('TYPO3\\CMS\\Core\\Database\\Query\\QueryHelper', 'parseGroupBy', [$staticCall->args[4]])]);
        if ($groupBy) {
            $this->nodesToAddCollector->addNodeBeforeNode($groupByNode, $positionNode);
            return;
        }
        if (!$groupByArgument instanceof \PhpParser\Node\Arg) {
            return;
        }
        $if = new \PhpParser\Node\Stmt\If_(new \PhpParser\Node\Expr\BinaryOp\NotIdentical($groupByArgument->value, new \PhpParser\Node\Scalar\String_('')));
        $if->stmts[] = new \PhpParser\Node\Stmt\Expression($groupByNode);
        $this->nodesToAddCollector->addNodeBeforeNode($if, $positionNode);
    }
    private function addOrderByNode(string $queryBuilderVariableName, \PhpParser\Node\Expr\StaticCall $staticCall, \PhpParser\Node $positionNode) : void
    {
        $orderByArgument = $staticCall->args[5] ?? null;
        $orderBy = null !== $orderByArgument ? $this->valueResolver->getValue($orderByArgument->value) : '';
        if ('' === $orderBy || 'null' === $orderBy) {
            return;
        }
        if (!$orderByArgument instanceof \PhpParser\Node\Arg) {
            return;
        }
        $orderByForeach = new \PhpParser\Node\Stmt\Foreach_($this->nodeFactory->createStaticCall('TYPO3\\CMS\\Core\\Database\\Query\\QueryHelper', 'parseOrderBy', [$orderByArgument->value]), new \PhpParser\Node\Expr\Variable('orderPair'));
        $orderByForeach->stmts[] = new \PhpParser\Node\Stmt\Expression(new \PhpParser\Node\Expr\Assign($this->nodeFactory->createFuncCall('list', [new \PhpParser\Node\Expr\Variable('fieldName'), new \PhpParser\Node\Expr\Variable('order')]), new \PhpParser\Node\Expr\Variable('orderPair')));
        $orderByForeach->stmts[] = new \PhpParser\Node\Stmt\Expression($this->nodeFactory->createMethodCall($queryBuilderVariableName, 'addOrderBy', [new \PhpParser\Node\Expr\Variable('fieldName'), new \PhpParser\Node\Expr\Variable('order')]));
        if ($orderBy) {
            $this->nodesToAddCollector->addNodeBeforeNode($orderByForeach, $positionNode);
            return;
        }
        $if = new \PhpParser\Node\Stmt\If_(new \PhpParser\Node\Expr\BinaryOp\NotIdentical($orderByArgument->value, new \PhpParser\Node\Scalar\String_('')));
        $if->stmts[] = $orderByForeach;
        $this->nodesToAddCollector->addNodeBeforeNode($if, $positionNode);
    }
    private function addLimitNode(string $queryBuilderVariableName, \PhpParser\Node\Expr\StaticCall $staticCall, \PhpParser\Node $positionNode) : void
    {
        $limitArgument = $staticCall->args[6] ?? null;
        $limit = null !== $limitArgument ? $this->valueResolver->getValue($limitArgument->value) : '';
        if ('' === $limit) {
            return;
        }
        if (!$limitArgument instanceof \PhpParser\Node\Arg) {
            return;
        }
        $limitIf = new \PhpParser\Node\Stmt\If_($this->nodeFactory->createFuncCall('strpos', [$limitArgument->value, ',']));
        $limitIf->stmts[] = new \PhpParser\Node\Stmt\Expression(new \PhpParser\Node\Expr\Assign(new \PhpParser\Node\Expr\Variable(self::LIMIT_OFFSET_AND_MAX), $this->nodeFactory->createStaticCall('TYPO3\\CMS\\Core\\Utility\\GeneralUtility', 'intExplode', [new \PhpParser\Node\Scalar\String_(','), new \PhpParser\Node\Expr\Variable('limit')])));
        $limitIf->stmts[] = new \PhpParser\Node\Stmt\Expression($this->nodeFactory->createMethodCall($queryBuilderVariableName, 'setFirstResult', [new \PhpParser\Node\Expr\Cast\Int_(new \PhpParser\Node\Expr\ArrayDimFetch(new \PhpParser\Node\Expr\Variable(self::LIMIT_OFFSET_AND_MAX), new \PhpParser\Node\Scalar\LNumber(0)))]));
        $limitIf->stmts[] = new \PhpParser\Node\Stmt\Expression($this->nodeFactory->createMethodCall($queryBuilderVariableName, 'setMaxResults', [new \PhpParser\Node\Expr\Cast\Int_(new \PhpParser\Node\Expr\ArrayDimFetch(new \PhpParser\Node\Expr\Variable(self::LIMIT_OFFSET_AND_MAX), new \PhpParser\Node\Scalar\LNumber(1)))]));
        $limitIf->else = new \PhpParser\Node\Stmt\Else_();
        $limitIf->else->stmts[] = new \PhpParser\Node\Stmt\Expression($this->nodeFactory->createMethodCall($queryBuilderVariableName, 'setMaxResults', [new \PhpParser\Node\Expr\Cast\Int_(new \PhpParser\Node\Expr\Variable('limit'))]));
        if ($limit) {
            $this->nodesToAddCollector->addNodeBeforeNode($limitIf, $positionNode);
            return;
        }
        $if = new \PhpParser\Node\Stmt\If_(new \PhpParser\Node\Expr\BinaryOp\NotIdentical($limitArgument->value, new \PhpParser\Node\Scalar\String_('')));
        $if->stmts[] = $limitIf;
        $this->nodesToAddCollector->addNodeBeforeNode($if, $positionNode);
    }
}
