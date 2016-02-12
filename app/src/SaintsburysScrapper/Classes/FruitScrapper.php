<?php
/**
 * Created by Merkury (Víctor Moreno)
 * Date: 10/02/2016
 * Time: 21:17
 */

namespace App\SaintsburysScrapper\Classes;

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class FruitScrapper
{

    /**
     * Private variable to perform HTTP Request
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function runScrap($targetUrl = null){
        return json_encode($this->crawlInfo($this->getData($targetUrl)['content']), JSON_PRETTY_PRINT);
    }

    /**
     * Function to scrap all the information from the given resource. This fucntion will return the array with all
     * the requested information in the exercise.
     * @param $body
     * @return array
     * @throws \Exception
     */
    private function crawlInfo($body){

        $crawler = new Crawler($body);
        $selected = $crawler->filter('.listView .product .productInner');

        if (count($selected) < 1){
            throw new \Exception('Empty set');
        }

        $res = [
            'results' => [],
            'total' => 0
        ];

        foreach($selected as $productKey => $product){
            $innerCrawler = new Crawler($product);
            $description = $this->getProductExtraInfo($innerCrawler ->filter('a')->attr('href'));
            $unitPrice = substr($innerCrawler ->filter('p.pricePerUnit')->text(), 7, 4);
            $res['results'][$productKey] = [
                'title' => trim($innerCrawler->filter('h3')->text()),
                'description' => $description['description'],
                'size' => sprintf("%s Kb",number_format($description['size'] / 1024, 2)),
                'unit_price' =>money_format("%i", $unitPrice)
            ];
            $res['total'] +=  $unitPrice;
        }
        $res['total'] = money_format("%i", $res['total']);
        return $res;
    }

    /**
     * Auxiliar function to manage and retrieve the product description from the product page and the size of the linked HTML
     * @param $link
     * @return array
     */
    private function getProductExtraInfo($link)
    {
        $req = $this->getData($link);
        $crawler = new Crawler($req['content']);
        $data = [];
        $data['description'] = trim($crawler->filter('.productText')->first()->text());
        $data['size'] = $req['request_size'];
        return $data;
    }

    /**
     * Auxiliar function to manage and retrieve information from the different urls.
     * @param null
     * @return array
     */
    private function getData($targetUrl = null)
    {
        /**
         * variable to define the url to scrap, based in the optional parameter of the fucntion
         * @var string
         */
        $url = $targetUrl != null ? $targetUrl : 'http://www.sainsburys.co.uk/webapp/wcs/stores/servlet/CategoryDisplay?listView=true&orderBy=FAVOURITES_FIRST&parent_category_rn=12518&top_category=12518&langId=44&beginIndex=0&pageSize=20&catalogId=10137&searchTerm=&categoryId=185749&listId=&storeId=10151&promotionId=#langId=44&storeId=10151&catalogId=10137&categoryId=185749&parent_category_rn=12518&top_category=12518&pageSize=20&orderBy=FAVOURITES_FIRST&searchTerm=&beginIndex=0&hideFilters=true';

        $request = $this->client->request('GET', $url);

        return [
            'content' => $request->getBody()->getContents(),
            'request_size' => $request->getBody()->getSize()
        ];
    }

}