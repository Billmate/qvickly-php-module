<?php

namespace Payment\DataObjects;

use Qvickly\Api\Payment\RequestDataObjects\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{

    public function testvalidateArticle()
    {
        $article = new Article();
        $article->taxrate = 25.00;
        $article->withouttax = 100.00;
        $article->artnr = '123456';
        $article->title = 'Test Product';
        $article->quantity = 1.00;
        $article->aprice = 125.00;
        $article->discount = 0.00;

        $this->assertEquals($article->validate(), true);
        $exported = $article->export(true);

        $this->assertIsString($exported['taxrate']);
        $this->assertIsString($exported['withouttax']);
        $this->assertIsString($exported['artnr']);
        $this->assertIsString($exported['title']);
        $this->assertIsString($exported['quantity']);
        $this->assertIsString($exported['aprice']);
        $this->assertIsString($exported['discount']);

    }
}
