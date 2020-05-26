# barchart-api
PHP package integration with [Barchart API](https://www.barchart.com/ondemand/api) stock market data.


## Instalation

To use  repository add to your composer.json: 

```
"require": {
	"gieroj/barchart-api": "dev-master"
},
"repositories": [
    {
        "type": "vcs",
    	"url": "https://github.com/gieroj/barchart-api"
	}
]
```

and then 

```
composer install
```

If you are using Laravel version older than 5.7 or any other Framework then you need to update your provider's list:

```
'providers' => [
    // Add this on the end of list providers
    Gieroj\BarchartApi\BarchartApiServiceProvider::class,
]
```


## How to use it 

First, we need to declare that we will use this package:
```
use Gieroj\BarchartApi\BarchartApi;
```

Now we are able to call API. For example to getQuote:
```
$barchart = new BarchartApi(env('BARCHART_KEY'), env('BARCHART_URL'));
$symbol = 'DRW';
$quote = $barchart->getQuote($symbol)->getResponse();
```

We can actually call to all available API from barchart. 
Lets call getHistory but we will use getCustom function to do that, in that way even if I did not cover specific endpoint you are still able to call to it.
```
$barchart = new BarchartApi(env('BARCHART_KEY'), env('BARCHART_URL'));
$options = ['symbol' => 'GOOG', 'type' => 'daily', 'startDate' => '2020-05-01'];
$response = $barchart->getCustom('getHistory', $options )->getResponse();
```
By changing query name and provide correct options we are able to call all available endpoints.

:chart_with_upwards_trend: enjoy
