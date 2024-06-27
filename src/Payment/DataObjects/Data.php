<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\DataObjects;


class Data extends DataObject
{
    public function __construct(array|null $data = null)
    {
        parent::__construct($data ?? []);
    }

    public function hash(string $secret)
    {
        return hash_hmac('sha512', json_encode($this->export()), $secret);
    }

    public function addArticle(array|Article $article)
    {
        if(is_array($article)) {
            $article = new Article($article);
        }
        if(!array_key_exists('Articles', $this->data)) {
            $this->data['Articles'] = new Articles();
        } else {
            if(!$this->data['Articles'] instanceof Articles) {
                $this->data['Articles'] = new Articles($this->data['Articles']);
            }
        }
        $this->data['Articles'][] = $article;
    }

    public function updateCart()
    {
        if(array_key_exists('Articles', $this->data)) {
            if(!array_key_exists('Cart', $this->data)) {
                $this->data['Cart'] = new Cart();
            } elseif(is_array($this->data['Cart'])) {
                $this->data['Cart'] = new Cart($this->data['Cart']);
            }
            $total = $this->data['Articles']->getTotal();
            $withouttax = $total['withouttax'];
            $tax = $total['tax'];
            $withtax = $total['withtax'];

            $this->data['Cart']->updateTotals($withouttax, $tax, $withtax);
        }
    }
}