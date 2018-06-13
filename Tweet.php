<?php

class Tweet {
    protected $table = 'tweets_table';
    private $bdd;

    public function __construct($bdd)
    {
        $this->bdd = $bdd;
    }

    public function insert($records)
    {
        try {
            for ($i = 0; $i < count($records); $i++) {
                $query = $this->makeQuery($records[$i]);
                $result = $this->bdd->execute($query);

                if (! $result) {
                    return false;
                }
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    private function makeQuery($record)
    {
        $params = '';
        foreach ($record as $key => $value) {
            $params .= "'". $value . "',";
        }
        $params = rtrim($params, ',');
        $query = 'INSERT INTO ' . $this->table . ' (tweet_id, text, tweet_id_str, user_id, user_name, tweet_created_at, retweet_count, favorite_count, reply_count, tweet_url, hashtags, created_at, updated_at) VALUES (' . $params . ')';
        return $query;
    }

    public function deleteTweets($date='')
    {
        if (! empty($date)) {
            $d = new DateTime($date);
        } else {
            $d = new DateTime();
            $d->sub(new DateInterval('P2D'));
        }
        $now = $d->format('Y-m-d');
        $after_one_date = $d->add(new DateInterval('P1D'));
        $after_one_date = $after_one_date->format('Y-m-d');

        $query = 'DELETE FROM ' . $this->table . ' WHERE created_at >= "' . $now . ' 00:00:00" AND created_at < "' . $after_one_date . ' 00:00:00"';
        var_dump($query);
        try {
            if (! $this->bdd->execute($query)) {
                return json_encode(['error' => 'Cannot delete data!']);
            } else {
                return json_encode(['message' => 'Delete data success!']);
            }
        } catch (Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }
}
