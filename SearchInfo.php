<?php
class SearchInfo
{
    protected $table = 'searched_info';
    const ERROR_MESSAGE = '検索履歴を保存できません。';
    const ERROR_STATUS = 'error';
    const SUCCESS_MESSAGE = '検索履歴を保存できました。';
    const SUCCESS_STATUS = 'success';

    private $bdd;
    public function __construct($bdd) 
    {
        $this->bdd = $bdd;
    }

    public function savesearchinfo($request)
    {
        try {
            $now = new DateTime();
            $now->setTimezone(new DateTimeZone('Asia/Tokyo'));
            $record = array(
                'user_name' => $this->userName($request), 
                'searched_at' => $this->searchedAt($request), 
                'keyword' => $this->keyword($request),
                'start' => $this->start($request),
                'end' => $this->end($request),
                'status' => $this->status($request), 
                'created_at' => $now->format('Y-m-d H:i:s'), 
                'updated_at' => $now->format('Y-m-d H:i:s')
            );

            if (! $this->save($record)) {
                return json_encode([
                    'status' => self::ERROR_STATUS,
                    'message' => self::ERROR_MESSAGE
                ]);
            }

            return json_encode([
                'status' => self::SUCCESS_STATUS,
                'message' => self::SUCCESS_MESSAGE
            ]);
        } catch (Exception $e) {
            return json_encode([
                'status' => self::ERROR_STATUS,
                'message' => self::ERROR_MESSAGE
            ]);
        }
    }

    private function userName($request)
    {
        return ! empty($request['user_name']) ? $request['user_name'] : '';
    }

    private function searchedAt($request)
    {
        $now = new DateTime();
        $now->setTimezone(new DateTimeZone('Asia/Tokyo'));
        return ! empty($request['searched_at']) ? $request['searched_at'] : $now->format('Y-m-d H:i:s');
    }

    private function keyword($request)
    {
        return ! empty($request['keyword']) ? $request['keyword'] : '';
    }

    private function start($request)
    {
        return ! empty($request['start_time']) ? $request['start_time'] : '';
    }

    private function end($request)
    {
        return ! empty($request['end_time']) ? $request['end_time'] : '';
    }

    private function status($request)
    {
        return ! empty($request['status']) ? $request['status'] : '';
    }

    public function save($record)
    {
        $query = $this->makeQuery($record);
        $result = $this->bdd->execute($query);
    }

    private function makeQuery($record)
    {
        $params = '';
        foreach ($record as $key => $value) {
            $params .= "'". $value . "',";
        }
        $params = rtrim($params, ',');
        $query = 'INSERT INTO ' . $this->table . ' (user_name, searched_at, keyword, start, end, status, created_at, updated_at) VALUES (' . $params . ')';
        return $query;
    }
}
