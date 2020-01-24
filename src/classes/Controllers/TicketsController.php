<?php

namespace Classes\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class TicketsController extends Controller
{
    // 一覧表示
    public function index(Request $request, Response $response)
    {
        $sql = 'SELECT * FROM tickets';
        $stmt = $this->db->query($sql);
        $tickets = [];
        while ($row = $stmt->fetch()) {
            $tickets[] = $row;
        }
        $data = ['tickets' => $tickets];
        return $this->renderer->render($response, 'tasks/index.phtml', $data);
    }

    // 新規作成用フォームの表示
    public function create(Request $request, Response $response) {
        return $this->renderer->render($response, 'tasks/create.phtml');
    }
    
    // 新規作成
    public function store(Request $request, Response $response) {
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
    }
    
    // 表示
    public function show(Request $request, Response $response, array $args) {
        try {
            $ticket = $this->fetchTicket($args['id']);
        } catch (\Exception $e) {
            return $response->withStatus(404)->write($e->getMessage());
        }
        $data = ['ticket' => $ticket];
        return $this->renderer->render($response, 'tasks/show.phtml', $data);
    }

    // 編集用フォームの表示
    public function edit(Request $request, Response $response, array $args) {
        try {
            $ticket = $this->fetchTicket($args['id']);
        } catch (\Exception $e) {
            return $response->withStatus(404)->write($e->getMessage());
        }
        $data = ['ticket' => $ticket];
        return $this->renderer->render($response, 'tasks/edit.phtml', $data);
    }

    // 更新
    public function update(Request $request, Response $response, array $args) {
        try {
            $ticket = $this->fetchTicket($args['id']);
        } catch (\Exception $e) {
            return $response->withStatus(404)->write($e->getMessage());
        }
        $ticket['subject'] = $request->getParsedBodyParam('subject');
        $stmt = $this->db->prepare('UPDATE tickets SET subject = :subject WHERE id = :id');
        $stmt->execute($ticket);
        return $response->withRedirect('/tickets');
    }

    // 削除
    public function delete(Request $request, Response $response, array $args) {
        try {
            $ticket = $this->fetchTicket($args['id']);
        } catch (\Exception $e) {
            return $response->withStatus(404)->write($e->getMessage());
        }
        $stmt = $this->db->prepare('DELETE FROM tickets WHERE id = :id');
        $stmt->execute(['id' => $ticket['id']]);
        return $response->withRedirect('/tickets');
    }

    // idによる検索
    private function fetchTicket($id): array {
        $sql = 'SELECT * FROM tickets WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $ticket = $stmt->fetch();
        if (!$ticket) {
            throw new \Exception('not found');
        }
        return $ticket;
    }
}
