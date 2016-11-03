<?php

namespace Test\functional;

use Audiens\AppnexusClient\entity\Category;
use Prophecy\Argument;
use Test\FunctionalTestCase;

/**
 * Class CategoryRepositoryTest
 */
class CategoryRepositoryTest extends FunctionalTestCase
{

    /**
     * @test
     */
    public function find_all_will_return_multiple_categories()
    {

        $categories = $this
            ->getCategoryRepository()
            ->findAll();

        $this->assertCount(100, $categories);

        foreach ($categories as $segment) {
            $this->assertInstanceOf(Category::class, $segment);
        }

    }

}
