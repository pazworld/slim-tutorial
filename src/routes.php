<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    // 一覧表示
    $app->get('/tickets', function (Request $request, Response $response) {
        $sql = 'SELECT * FROM tickets';
        $stmt = $this->db->query($sql);
        $tickets = [];
        while ($row = $stmt->fetch()) {
            $tickets[] = $row;
        }
        $data = ['tickets' => $tickets];
        return $this->renderer->render($response, 'tickets/index.phtml', $data);
    });

    // 新規作成用フォームの表示
    $app->get('/tickets/create', function (Request $request, Response $response) {
        return $this->renderer->render($response, 'tasks/create.phtml');
    });

    // 新規作成
    $app->post('/tickets', function (Request $request, Response $response) {
        $subject = $request->getParsedBodyParam('subject');
        
        // 保存処理
        $sql = 'INSERT INTO tickets (subject) values (:subject)';
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(['subject' => $subject]);
        if (!$result) {
            throw new \Exception('could not save the ticket');
        }
        
        // 保存が正常にできたら一覧ページへリダイレクトする
        return $response->withRedirect('/tickets');
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
