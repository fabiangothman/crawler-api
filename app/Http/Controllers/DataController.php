<?php

namespace App\Http\Controllers;

use DOMDocument;
use Exception;
use stdClass;

class DataController extends Controller
{
  private string $url = "https://tentreem.mywhc.ca/devtest/products/";
  private string $fileName = "index.html";
  private $data = array();

  private function url_check($url) {
    $headers = @get_headers($url);
    return is_array($headers) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$headers[0]) : false;
  }

  private function clean($text){
    $clean = html_entity_decode(trim(str_replace(';','-',preg_replace('/\s+/S', " ", strip_tags($text)))));// remove everything
    return $clean;
    echo '\n';// throw a new line
  }

  function get_string_before($string, $end){
    $ini = strpos($string, $end);
    return substr($string, 0, $ini);
  }

  public function getFile(string $url) {
    if($this->url_check($url)){
      $dom = new DomDocument;
      $dom->loadHtml(file_get_contents($url));
      return $dom;
    }else{
      return null;
    }
  }

  private function get_url_basename($url){
    return basename(parse_url($url, PHP_URL_PATH));
  }

  public function getListItems(string $url, string $prevPath = "", int $level = 0) {
    $data = array();
    try {
      $dom = $this->getFile($url);
      $pageType = ($this->get_url_basename($url) == $this->fileName) ? "list" : "item";

      if ($pageType === "list") {
        $categories = $dom->getElementsByTagName('li');

        for ($i=0; $i < $categories->length; $i++) {
        //for ($i=1; $i < 2; $i++) {
          $itemRelativeHref = $categories->item($i)->getElementsByTagName('a')->item(0)->getAttribute('href');
          
          $item = new stdClass;
          $item->fileName = $this->get_url_basename($itemRelativeHref);
          $item->prevPath = $prevPath;
          $item->newPath = substr($this->get_string_before($itemRelativeHref, $item->fileName), 2);
          $item->path = $item->prevPath.$item->newPath;
          $item->childUrl = $this->url.$prevPath.$item->newPath.$item->fileName;
          $item->pageType = $pageType;
          $item->name = $this->clean($categories->item($i)->textContent);
          $item->level = $level+1;
          $item->child = $this->getListItems($item->childUrl, $item->path, $item->level);
          
          //var_dump($item);
          array_push($data, $item);
        }
      } else {
        $item = new stdClass;
        $item->pageType = $pageType;
        $item->name = $this->clean($dom->getElementsByTagName('h1')->item(0)->textContent);
        $item->description = $this->clean($dom->getElementsByTagName('p')->item(0)->textContent);
        $item->price = $this->clean($dom->getElementsByTagName('p')->item(1)->getElementsByTagName('span')->item(0)->textContent);
        $item->available = ($item->price=="Sold Out") ? false : true;
        $item->size = $item->available ? $this->clean($dom->getElementsByTagName('p')->item(1)->getElementsByTagName('span')->item(1)->textContent) : null;
        $item->image = $item->available ? $this->clean($dom->getElementsByTagName('p')->item(1)->getElementsByTagName('img')->item(0)->getAttribute('src')) : null;
        $item->level = $level+1;
        return $item;
      }
    }catch(Exception $e) {
      var_dump($e->getMessage());
      array_push($data, null);
    }
    
    return $data;
  }

  public function getCategoryPaths(string $href) {
    $arrayPaths = explode('/', $href);
    return array_slice($arrayPaths, array_search('collections', $arrayPaths)+1, count($categorypaths)-2);
  }

  public function index() {
    $this->data = $this->getListItems($this->url."index.html");
    //var_dump($this->data);
    return response(json_encode($this->data), 200)
      ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token')
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, DELETE, OPTIONS');
  }
}
