<?php

namespace GoogleSearch;

use GoogleSearch\Response\Facet;
use GoogleSearch\Response\Item;
use GoogleSearch\Response\Metainformation;
use GoogleSearch\Response\Promotion;

class Response {

    /**
     * @var array
     */
    private $_facets = [];

    /**
     * @var string
     */
    private $_title = '';

    /**
     * @var Query
     */
    private $_request = null;

    /**
     * @var Metainformation
     */
    private $_metaInformation = null;

    /**
     * @var Promotion[]
     */
    private $_promotions = [];

    private $_items = [];

    public function __construct($jsonString, Query $request){
        $this->_request = $request;
        $this->_parse($jsonString);
    }

    protected function _parse($jsonString){
        $data = \GuzzleHttp\json_decode($jsonString);
        if(null === $data)
            throw new Exception('Requests failed. No valid json received');

        foreach ($data->context->facets as $facetArray) {
            foreach($facetArray as $facetObject){
                $this->_facets[] = new Facet($facetObject);
            }
        }
        $this->_title = $data->context->title;
        $this->_metaInformation = new Metainformation($data->queries, $data->searchInformation, $this->_request);

        foreach($data->promotions as $promotion){
            $this->_promotions[] = new Promotion($promotion);
        }

        foreach($data->items as $item){
            $this->_items[] = new Item($item);
        }
    }

    /**
     * Get the corresponding request for this response.
     *
     * @return Query
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @return Metainformation
     */
    public function getMetaInformation()
    {
        return $this->_metaInformation;
    }

    /**
     * @return Promotion[]
     */
    public function getPromotions()
    {
        return $this->_promotions;
    }

    /**
     * @return array
     */
    public function getFacets() {
        return $this->_facets;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
     * @return array
     */
    public function getItems() {
        return $this->_items;
    }


}