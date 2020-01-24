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
    $app->get('/tickets/{id}', function (Request $request, Response $response, array $args) {
        $sql = 'SELECT * FROM tickets WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $args['id']]);
        $ticket = $stmt->fetch();
        if (!$ticket) {
            return $response->withStatus(404)->write("not found");
        }
        $data = ['ticket' => $ticket];
        return $this->renderer->render($response, 'tasks/show.phtml', $data);
    });

    // 編集用フォームの表示
    $app->get('/tickets/{id}/edit', function (Request $request, Response $response, array $args) {
        $sql = 'SELECT * FROM tickets WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $args['id']]);
        $ticket = $stmt->fetch();
        if (!$ticket) {
            return $response->withStatus(404)->write('not found');
        }
        $data = ['ticket' => $ticket];
        return $this->renderer->render($response, 'tasks/edit.phtml', $data);
    });

    // 更新
    $app->put('/tickets/{id}', function (Request $request, Response $response, array $args) {
        $sql = 'SELECT * FROM tickets WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $args['id']]);
        $ticket = $stmt->fetch();
        if (!$ticket) {
            return $response->withStatus(404)->write('not found');
        }
        $ticket['subject'] = $request->getParsedBodyParam('subject');
        $stmt = $this->db->prepare('UPDATE tickets SET subject = :subject WHERE id = :id');
        $stmt->execute($ticket);
        return $response->withRedirect('/tickets');
    });

    // 削除
    $app->delete('/tickets/{id}', function (Request $request, Response $response, array $args) {
        $sql = 'SELECT * FROM tickets WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $args['id']]);
        $ticket = $stmt->fetch();
        if (!$ticket) {
            return $response->withStatus(404)->write('not found');
        }
        $stmt = $this->db->prepare('DELETE FROM tickets WHERE id = :id');
        $stmt->execute(['id' => $ticket['id']]);
        return $response->withRedirect('/tickets');
    });

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
};
