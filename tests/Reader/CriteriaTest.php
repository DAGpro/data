<?php

namespace Yiisoft\Data\Tests\Reader;

use Yiisoft\Data\Reader\Criterion\All;
use Yiisoft\Data\Reader\Criterion\Any;
use Yiisoft\Data\Reader\Criterion\Compare;
use Yiisoft\Data\Reader\Criterion\CriteronInterface;
use Yiisoft\Data\Reader\Criterion\GreaterThan;
use Yiisoft\Data\Reader\Criterion\GreaterThanOrEqual;
use Yiisoft\Data\Reader\Criterion\LessThan;
use Yiisoft\Data\Reader\Criterion\LessThanOrEqual;
use Yiisoft\Data\Reader\Criterion\Like;
use Yiisoft\Data\Reader\Criterion\Not;
use Yiisoft\Data\Tests\TestCase;

class CriteriaTest extends TestCase
{
    public function criteriaDataProvider(): array
    {
        return [
            'Compare' => [
                new Compare('test', 42),
                ['eq', 'test', 42],
            ],
            'GreaterThan' => [
                new GreaterThan('test', 42),
                ['gt', 'test', 42],
            ],
            'GreaterThanOrEqual' => [
                new GreaterThanOrEqual('test', 42),
                ['gte', 'test', 42],
            ],
            'LessThan' => [
                new LessThan('test', 42),
                ['lt', 'test', 42],
            ],
            'LessThanOrEqual' => [
                new LessThanOrEqual('test', 42),
                ['lte', 'test', 42],
            ],
            'Like' => [
                new Like('test', 42),
                ['like', 'test', '42'],
            ],
            'Not' => [
                new Not(new Compare('test', 42)),
                ['not', ['eq', 'test', 42]],
            ],
            'Any' => [
                new Any(
                    new Compare('test', 1),
                    new Compare('test', 2)
                ),
                ['any', [
                    ['eq', 'test', 1],
                    ['eq', 'test', 2],
                ]]
            ],
            'All' => [
                new All(
                    new LessThan('test', 3),
                    new GreaterThan('test', 2)
                ),
                ['all', [
                    ['lt', 'test', 3],
                    ['gt', 'test', 2],
                ]]
            ],
            'NestedGroup' => [
                new All(
                    new Compare('test', 42),
                    new Any(
                        new Compare('test', 1),
                        new Compare('test', 2)
                    )
                ),
                [
                    'all',
                    [
                        ['eq', 'test', 42],
                        [
                            'any',
                            [
                                ['eq', 'test', 1],
                                ['eq', 'test', 2],
                            ]
                        ]
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider criteriaDataProvider
     */
    public function testCriteria(CriteronInterface $criteron, array $criterionArray): void
    {
        $this->assertSame($criterionArray, $criteron->toArray());
    }
}
