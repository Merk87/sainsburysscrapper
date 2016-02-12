<?php
/**
 * Created by Merkury (Víctor Moreno)
 * Date: 10/02/2016
 * Time: 23:58
 */

namespace App\Tests;

use ReflectionClass;
use App\SaintsburysScrapper\Classes\FruitScrapper;

class FruitScrapperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test of the run scrap function, this test will cover the run and the crawl function.
     */
    public function testRunScrap(){
        $fruitScrapper = new FruitScrapper();
        $res = $fruitScrapper->runScrap();

        $this->assertJson($res);

        json_decode($res);
        $valid = json_last_error() === JSON_ERROR_NONE;
        $this->assertTrue($valid);

        $arrayData = json_decode($res, true);
        $productData = $arrayData['results'];

        $this->assertArrayHasKey('results', $arrayData);
        $this->assertArrayHasKey('total', $arrayData);

        foreach($productData as $product){
            $this->assertArrayHasKey('title', $product);
            $this->assertArrayHasKey('size', $product);
            $this->assertArrayHasKey('unit_price', $product);
            $this->assertArrayHasKey('description', $product);
        }

    }

    /**
     * Test private getData function to retrieval of the page content
     */
    public function testGetData(){
        $url = 'http://www.sainsburys.co.uk/webapp/wcs/stores/servlet/CategoryDisplay?listView=true&orderBy=FAVOURITES_FIRST&parent_category_rn=12518&top_category=12518&langId=44&beginIndex=0&pageSize=20&catalogId=10137&searchTerm=&categoryId=185749&listId=&storeId=10151&promotionId=#langId=44&storeId=10151&catalogId=10137&categoryId=185749&parent_category_rn=12518&top_category=12518&pageSize=20&orderBy=FAVOURITES_FIRST&searchTerm=&beginIndex=0&hideFilters=true';
        $reflected = new ReflectionClass(FruitScrapper::class);

        $getDataMethod = $reflected->getMethod('getData');
        $getDataMethod->setAccessible(true);

        $res = $getDataMethod->invokeArgs(new FruitScrapper(), [$url]);

        $this->assertArrayHasKey('content', $res);
        $this->assertArrayHasKey('request_size', $res);

    }

    /**
     * Test private function to retrieve information from product page.
     */
    public function testProductExtraInfo(){
        $url = 'http://www.sainsburys.co.uk/shop/gb/groceries/ripe---ready/sainsburys-avocado-xl-pinkerton-loose-300g';
        $reflected = new ReflectionClass(FruitScrapper::class);

        $getDataMethod = $reflected->getMethod('getProductExtraInfo');
        $getDataMethod->setAccessible(true);

        $res = $getDataMethod->invokeArgs(new FruitScrapper(), [$url]);

        $this->assertArrayHasKey('description', $res);
        $this->assertArrayHasKey('size', $res);

    }

}