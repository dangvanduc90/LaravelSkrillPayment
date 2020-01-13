<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Article;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use App\Articles\ArticlesRepository;

Route::get('/', function () {
    $logger = new Logger('elasticquent_log');
    $logger->pushHandler(new StreamHandler(storage_path('logs/elasticquent_log.log'), Logger::WARNING));
    $client = Elasticsearch\ClientBuilder::create()
    ->setLogger($logger)
    ->build();

    // #_index_a_document
    // $params = [
    //     'type' => config('elasticquent.default_index'),
    //     'index' => 'my_index',
    //     'id'    => 'my_id',
    //     'body'  => ['testField' => 'abc']
    // ];
    // $response = $client->index($params);
    // dd($response);

    # _get_a_document
    $params = [
        'type' => config('elasticquent.default_index'),
        'index' => 'my_index',
        'id'    => 'my_id11',
        'client' => [
            'ignore' => [404],
            'timeout' => 10,        // ten second timeout
            // 'verbose' => true,
        ],
    ];
    $response = $client->get($params);
    dd($response);

    // #_search_for_a_document
    // $params = [
    //     'index' => 'my_index',
    //     'body'  => [
    //         'query' => [
    //             'match' => [
    //                 'testField' => 'abc'
    //             ]
    //         ]
    //     ]
    // ];
    // $response = $client->search($params);
    // dd($response);

    // #_delete_a_document
    // $params = [
    //     'type' => config('elasticquent.default_index'),
    //     'index' => 'my_index',
    //     'id'    => 'my_id'
    // ];
    // $response = $client->delete($params);
    // dd($response);

    // #_delete_an_index
    // $deleteParams = [
    //     'index' => 'my_index'
    // ];
    // $response = $client->indices()->delete($deleteParams);
    // dd($response);

    // #_create_an_index
    // $params = [
    //     'index' => 'my_index',
    //     'body' => [
    //         'settings' => [
    //             'number_of_shards' => 2,
    //             'number_of_replicas' => 0
    //         ]
    //     ]
    // ];
    // $response = $client->indices()->create($params);
    // dd($response);
    return view('welcome');
});

Route::get('/search', function() {

    $articles = Article::searchByQuery(['match' => ['title' => 'Sed']]);

    return $articles;
});

Route::get('/search', function (ArticlesRepository $repository) {
    $articles = $repository->search((string) request('q'));

    return view('articles.index', [
        'articles' => $articles,
    ]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
