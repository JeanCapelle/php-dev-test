<?php
namespace PhpTest\Services;
use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\HttpCache\Store;

/**
 * Class WeatherService
 *
 * Open Weather Api doc : https://openweathermap.org/api
 *
 * @package PhpTest
 */
class WeatherService
{
    /**
     * @var string
     */
    private string $apiKey;

    /**
     * @var string
     */
    private string $apiUrl;

    /**
     * @var array
     */
    private array $queryParams;

    /**
     * WeatherService constructor.
     * @param string $apiKey
     * @param string $apiUrl
     */
    public function __construct(string $apiKey = OPENWEATHER_API_KEY, string $apiUrl = OPENWEATHER_API_URL)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
        $this->queryParameters = [
                'appid' =>  $this->apiKey,
                'lang' =>  'fr',
                'mode' =>  'html',
        ];
    }
    
    /**
     * buildQueryParams
     * @param  array $params
     * @return array $queryParams
     */
    public function buildQueryParams(array $params)
    {
        try 
        {
            foreach($params as $key => $value)
            {
                switch($key) 
                {
                    case 'city_name':
                    case 'q':
                        $this->queryParameters += ['q' => $value];
                        break;
                    case 'id':
                        $this->queryParameters += [$key => $value];
                        break;
                    case 'lat':
                        if(!isset($params['lon'])){
                            throw new \Exception("Missing latitude");
                        }
                        $this->queryParameters += [$key => $value];
                        break;
                    case 'lon':
                        if(!isset($params['lat'])){
                            throw new \Exception("Missing long");
                        }
                        $this->queryParameters += [$key => $value];
                        break;
                    default:
                        throw new \Exception("Invalids parameters");
                        break;
                }
            }
            return $this->queryParameters;
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
    
    /**
     * fetchCityWeather
     * @param  array $params
     * @return string $content
     */
    public function fetchCityWeather(array $params):string
    {
        $store  = new Store(__DIR__.'cache/');
        $client = HttpClient::create();
        $client = new CachingHttpClient($client, $store);
        
        $queryParameters = $this->buildQueryParams($params);
        if(!is_array($queryParameters)){
            return $queryParameters;
        }

        $response = $client->request('GET', $this->apiUrl,['query' => $queryParameters]);
        if($response->getStatusCode() !== 200)
        {
            return 'City not found';
        }else{
            $content = $response->getContent();
            return $content;
            
        }
        return $response;
    }

}
