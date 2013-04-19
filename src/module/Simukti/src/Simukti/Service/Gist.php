<?php
/**
 * Description of Gist
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Service;

use \InvalidArgumentException;
use Zend\EventManager\Event;
use Zend\Http\Request;
use Zend\Json\Decoder as JsonDecoder;

class Gist extends HttpParser
{
    const GIST_HOST     = 'https://gist.github.com';
    const CACHE_PREFIX  = 'gist_';
    
    /**
     * @param   string $content
     * @return  string
     */
    public function parse($content)
    {
        if(! is_string($content)) {
            throw new InvalidArgumentException('Parameter harus tipe string.');
        };
        // [gist]ID_GIST[/gist]
        $regex   = '/\[gist\]([0-9a-z]+)\[\/gist\]/';
        $matches = array();
        
        preg_match_all($regex, $content, $matches);
        
        $matchCount = count($matches[0]);
        // segera kembalikan jika tidak ada tag [gist] yang ditemukan.
        if(! $matchCount) {
            return $content;
        }
        
        for($i = 0; $i < $matchCount; ++$i) {
            $gistTag  = $matches[0][$i];
            $gistId   = $matches[1][$i];
            $gistData = $this->getGist($gistId);
            $content  = str_replace(array('<p>'.$gistTag.'</p>', $gistTag), $gistData, $content);
        }
        return $content;
    }
    
    public function getGist($gistId)
    {
        $params = array(
            'gist_id' => $gistId
        );
        
        $this->events()->attach(self::API_BEFORE, array($this, 'before'));
        $checkCache = $this->events()->triggerUntil(self::API_BEFORE, $this, $params, function($result) {
            // hasil parse di gist.github.com adalah json string berisi <div>
            return is_string($result);
        });
        if($checkCache->stopped()) {
            return $checkCache->last();
        }
        
        $this->events()->attach(self::API_SEND, array($this, 'send'));
        $fetch = $this->events()->trigger(self::API_SEND, $this, $params);
        return $fetch->last();
    }
    
    /**
     * @param   \Zend\EventManager\Event $event
     * @return  string
     * 
     * @todo    Belum di test
     */
    public function send(Event $event)
    {
        $gistId = $event->getParam('gist_id');
        $client = $this->getHttpClient();
        $gistPath = '/' . $gistId . '.json';
        $client->setUri(self::GIST_HOST)
               ->getUri()->setPath($gistPath);
        $request = $client->setMethod(Request::METHOD_GET)
                          ->send();
        
        if($request->isSuccess()) {
            $result   = JsonDecoder::decode($request->getBody());
            $gistData = preg_replace('#<div class=\"gist-meta\">.*?</div>#si', '', $result->div);
        } else {
            $gistData = sprintf("<strong>GIST ID %s SALAH !</strong>", $gistId);
        }
        
        $params = $event->getParams();
        $params[self::RESULT_KEY] = $gistData;
        
        $this->events()->attach(self::API_AFTER, array($this, 'after'))
                       ->call(array($event->setParams($params)));
        
        return $gistData;
    }
    
    public function getCacheKey(Event $event)
    {
        $gistId = $event->getParam('gist_id');
        $key = self::CACHE_PREFIX.$gistId;
        return $key;
    }
}
