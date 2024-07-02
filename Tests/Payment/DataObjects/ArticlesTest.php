<?php

namespace Payment\DataObjects;

use Qvickly\Api\Payment\RequestDataObjects\Article;
use Qvickly\Api\Payment\RequestDataObjects\Articles;
use PHPUnit\Framework\TestCase;

class ArticlesTest extends TestCase
{
    public function testArticles()
    {
        $artList = [];
        $artList[] = [
                'taxrate' => 25.00,
                'withouttax' => 100.00,
                'artnr' => '123456',
                'title' => 'Test Product',
                'quantity' => 1.00,
                'aprice' => 125.00,
                'discount' => 0.00
            ];
         $artList[] = new Article([
                'taxrate' => 25.00,
                'withouttax' => 100.00,
                'artnr' => '123456',
                'title' => 'Test Product',
                'quantity' => 1.00,
                'aprice' => 125.00,
                'discount' => 0.00
            ]);
        $articles = new Articles($artList);
        $this->assertEquals($articles->validate(), true);
    }

}
