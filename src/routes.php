<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Controllers\TicketsController;

return function (App $app) {
    $container = $app->getContainer();

    // 一覧表示
    $app->get('/tickets', TicketsController::class . ':index');
    //$app->get('/tickets', TicketsController:index);

    // 新規作成用フォームの表示
    $app->get('/tickets/create', TicketsController::class . ':create');

    // 新規作成
    $app->post('/tickets', TicketsController::class . ':store');

    // 表示
    $app->get('/tickets/{id}', TicketsController::class . ':show');

    // 編集用フォームの表示
    $app->get('/tickets/{id}/edit', TicketsController::class . ':edit');

    // 更新
    $app->put('/tickets/{id}', TicketsController::class . ':update');

    // 削除
    $app->delete('/tickets/{id}', TicketsController::class . ':delete');

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
};
