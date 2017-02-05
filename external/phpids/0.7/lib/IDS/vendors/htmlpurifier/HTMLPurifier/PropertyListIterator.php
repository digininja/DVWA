<?php

/**
 * Property list iterator. Do not instantiate this class directly.
 */
class HTMLPurifier_PropertyListIterator extends FilterIterator
{

    protected $l;
    protected $filter;

    /**
     * @param $data Array of data to iterate over
     * @param $filter Optional prefix to only allow values of
     */
    public function __construct(Iterator $iterator, $filter = null) {
        parent::__construct($iterator);
        $this->l = strlen($filter);
        $this->filter = $filter;
    }

    public function accept() {
        $key = $this->getInnerIterator()->key();
        if( strncmp($key, $this->filter, $this->l) !== 0 ) {
            return false;
        }
        return true;
    }

}

// vim: et sw=4 sts=4
