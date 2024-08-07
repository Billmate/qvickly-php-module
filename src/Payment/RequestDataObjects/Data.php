<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\RequestDataObjects;


class Data extends DataObject
{
    public function __construct(\stdClass|array|null $data = null)
    {
        parent::__construct($data ?? []);
    }

    /**
     * Calculate the hash of the current data object.
     * @param string $secret
     * @param bool $convertToExportFormat
     * @return string
     */
    public function hash(string $secret, bool $convertToExportFormat = false): string
    {
        return hash_hmac('sha512', json_encode($this->export($convertToExportFormat)), $secret);
    }

    /**
     * Add an Article to the current data object. If the Articles object does not exist, create it.
     * @param array|Article $article
     * @return $this
     */
    public function addArticle(array|Article $article): static
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
        return $this;
    }

    /**
     * Check if the current data object has any Articles. If so, then update or create the Cart object with the new totals.
     * @param bool|int|null $roundCart
     * @return $this
     */
    public function updateCart(bool|null|int $roundCart = false): static
    {
        if(array_key_exists('Articles', $this->data)) {
            if(!array_key_exists('Cart', $this->data)) {
                $this->data['Cart'] = new Cart();
            } elseif(is_array($this->data['Cart']) || $this->data['Cart'] instanceof \stdClass) {
                $this->data['Cart'] = new Cart($this->data['Cart']);
            }
            $total = $this->data['Articles']->getTotal(false);
            $withouttax = (int)round($total['withouttax'] + 0.01, 0);
            $tax = (int)round($total['tax'] + 0.01, 0);
            $withtax = (int)round($total['withtax'] + 0.01, 0);
            $this->data['Cart']->updateTotals($withouttax, $tax, $withtax, $roundCart);
        }
        return $this;
    }
}