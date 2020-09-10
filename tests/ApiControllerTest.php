<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $catArticles = $crawler->filter("section.cat-list article");
        $this->assertGreaterThan(10, $catArticles->count(), 'homepage should show at least 10 kitten');
    }

    /**
     * @dataProvider provideUrl
     */
    public function testAllPagesAreSuccessfull($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        $this->assertResponseIsSuccessful("$url should return a 200 code!");
    }

    public function provideUrl()
    {
        return [
            ['/'],
            ['/details/38'],
            ['/login'],
            //....
        ];
    }
}
