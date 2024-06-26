<?php

namespace Qvickly\Api\Payment\DataObjects;

class Articles extends DataObject
{

    public function __construct(array $data = [])
    {
        parent::__construct();
        foreach ($data as $value) {
            if (is_array($value)) {
                $this->data[] = new Article($value);
            } elseif ($value instanceof Article) {
                $this->data[] = $value;
            }
        }
    }

    public function getTotal(): array
    {
        $total = [
            'withouttax' => 0,
            'tax' => 0,
            'withtax' => 0,
            'rounding' => 0
        ];
        foreach ($this->data as $article) {
            $total['withouttax'] += $article->withouttax;
            $total['tax'] += $article->taxrate * $article->withouttax / 100;
            $total['withtax'] += ($article->taxrate + 100) * $article->withouttax / 100;
        }
        return $total;
    }
}