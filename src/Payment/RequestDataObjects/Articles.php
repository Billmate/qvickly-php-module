<?php

namespace Qvickly\Api\Payment\RequestDataObjects;

class Articles extends DataObject
{

    public function __construct(array $data = [])
    {
        foreach ($data as $value) {
            if (is_array($value) || $value instanceof \stdClass) {
                $this->data[] = new Article($value);
            } elseif ($value instanceof Article) {
                $this->data[] = $value;
            }
        }
    }

    public function addArticle(array|Article $data, bool|null|int $roundCart = null): static
    {
        if(is_array($data)) {
            $data = new Article($data);
        }
        $this->data[] = $data;

        return $this;
    }

    public function getTotal(bool $useRounding = false): array
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
        if($useRounding) {
            $newWithTax = round(($total['withtax'] / 100) + 0.5, 2) * 100;
            $total['rounding'] = $newWithTax - $total['withouttax'] - $total['tax'];
            $total['withtax'] = $newWithTax;
        }
        return $total;
    }
}