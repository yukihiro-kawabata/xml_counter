<?php

class anq_xml {
    const ANSER_GOOD = 'good'; // 良い評価
    const ANSER_BAT  = 'bad';  // 悪い評価
    const ANSER_BAT_RANK = [
        0 => '',
        1 => '少し悪い',
        2 => '悪い',
        3 => 'かなり悪い',
    ];

    /**
     * xmlファイルのPathを取得する
     */
    protected function get_xml_filepath() : string
    {
        return $this->xml_filepath();
    }
    
    /**
     * xmlファイルの読み込み
     */
    protected function read_file()
    {        
        return simplexml_load_file(self::xml_filepath());
    }

    /**
     * xmlファイルの場所
     */
    private static function xml_filepath() : string
    {
        return __DIR__ . '/anqCounter.xml';
    }
}

class read_xml extends anq_xml {

    /**
     * アンケートの集計結果を取得する
     */
    public function get_type_count() : array
    {
        $good_cnt = 0;
        $bad_cnt  = 0;
        foreach ($this->read_file() as $n => $xml) {
            if ((string)$xml->type === self::ANSER_GOOD) {
                $good_cnt++;
            }
            if ((string)$xml->type === self::ANSER_BAT) {
                $bad_cnt++;
            }
        }
        return [
            'good' => $good_cnt,
            'bad'  => $bad_cnt,
        ];
    }
}

class write_xml extends anq_xml {

    public function register_anq()
    {
        return $this->write_file('good', 0);
    }

    private function write_file(string $type, int $rank) : ?string
    {
        $xml = $this->read_file();
        if ($xml) {
            $addNode = $xml->addChild('ans');
            $addNode->addChild('type', $type);
            $addNode->addChild('rank', $rank);
            $addNode->addChild('time', date("Y-m-d H:i:s"));
            // 保存する
            $xml->asXml($this->get_xml_filepath());
        } else {
            return 'ファイル書き込みに失敗しました';
        }

        return NULL;
    }
}

function dx($param = '')
{
    var_dump($param);
    exit();
}

$read_xml = new read_xml();
$data = $read_xml->get_type_count();

dx($data);

?>