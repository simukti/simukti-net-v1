<?php
/**
 * PHP API Client for siMukti Network - readOnly-mode
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace SimuktiNetwork;

use Zend\Json\Json;
use Zend\Json\Decoder;

class Api extends AbstractApi
{
    public function findAllArticles(array $params = array()) 
    {
        $path    = '/article/list';
        $request = $this->_get($path, $params);
        return $request->isSuccess() ? Decoder::decode($request->getBody(), Json::TYPE_ARRAY) : false;
    }
    
    public function findAllTags(array $params = array())
    {
        $path    = '/article/tags';
        $request = $this->_get($path, $params);
        return $request->isSuccess() ? Decoder::decode($request->getBody(), Json::TYPE_ARRAY) : false;
    }
    
    public function findOneArticle(array $params = array())
    {
        $path    = '/article';
        $request = $this->_get($path, $params);
        return $request->isSuccess() ? Decoder::decode($request->getBody(), Json::TYPE_ARRAY) : false;
    }
    
    public function findArticleArchive(array $params = array())
    {
        $path    = '/article/archive';
        $request = $this->_get($path, $params);
        return $request->isSuccess() ? Decoder::decode($request->getBody(), Json::TYPE_ARRAY) : false;
    }
    
    public function findAllPageCategory(array $params = array())
    {
        $path    = '/page/category/list';
        $request = $this->_get($path, $params);
        return $request->isSuccess() ? Decoder::decode($request->getBody(), Json::TYPE_ARRAY) : false;
    }
    
    public function findAllPages(array $params = array())
    {
        $path    = '/page/list';
        $request = $this->_get($path, $params);
        return $request->isSuccess() ? Decoder::decode($request->getBody(), Json::TYPE_ARRAY) : false;
    }
    
    public function findOnePage(array $params = array())
    {
        $path    = '/page';
        $request = $this->_get($path, $params);
        return $request->isSuccess() ? Decoder::decode($request->getBody(), Json::TYPE_ARRAY) : false;
    }
}
