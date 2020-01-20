<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    // 一覧表示
    $app->get('/tickets', function (Request $request, Response $response) {
    });

    // 新規作成用フォームの表示
    $app->get('/tickets/create', function (Request $request, Response $response) {
    });

    // 新規作成
    $app->post('/tickets', function (Request $request, Response $response) {
    });

    // 表示
    $app->get('/tickets/{id}', function (Request $request, Response $response) {
    });

    // 編集用フォームの表示
    $app->get('/tickets/{id}/edit', function (Request $request, Response $response) {
    });

    // 更新
    $app->put('/tickets/{id}', function (Request $request, Response $response) {
    });

    // 削除
    $app->delete('/tickets/{id}', function (Request $request, Response $response) {
    });

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
};
