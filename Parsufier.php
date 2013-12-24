<?php
/**
 * Developed by: Amir Hossein Babaeian
 * github: Amirhb/Parsufier/
 * Email: amirh.babaeian@gmail.com

 */

include '/SimpleHTMLDOM/SimpleHTMLDOM.php';
 
class Parsufier {

    private $source;
    private $newsCode;
    private $title;
    private $leadCont;
    private $imageFileUrl;
    private $imageFileName;
    private $newsCodeRegex;
    private $titleRegex;
    private $leadContRegex;
    private $imageUrlRegex;
    private $url;
    private $newsClass;
    private $content;
    private $folderPath;
    private $items = array();

    public function setNewsCodeRegex( $regex) {

        $this->newsCodeRegex = $regex;
    }

    public function getNewsCodeRegex() {

        return $this->newsCodeRegex;
    }

    public function setSource( $site) {

        $this->source = $site;
    }
	
	public function getSource() {

        return $this->source;
    }

    public function setTitleRegex( $regex) {

        $this->titleRegex = $regex;
    }
	
	public function getTitleRegex() {

        return $this->titleRegex;
    }

    public function setLeadContRegex( $regex) {

        $this->leadContRegex = $regex;
    }
	
	public function getLeadContRegex() {

        return $this->leadContRegex;
    }

    public function setImageUrlRegex( $regex) {

        $this->imageUrlRegex = $regex;
    }
	
	public function getImageUrlRegex() {

        return $this->imageUrlRegex;
    }

    private function cssEliminator( $text) {

        while(preg_match( '/(<[^>]+) style=".*?"/i', $text == 1)) {
            preg_replace( '/(<[^>]+) style=".*?"/i', '$1', $text);

        }

    }

    public function parse(  $url, $newsClass, $filesAddr) {

		$this->items = array();
		
		$this->url = $url;
		$this->newsClass = $newsClass;
        	$this->folderPath = $filesAddr;
		
		$shd = new SimpleHTMLDOM;
		$html = $shd->file_get_html( $this->url);
		$body = $html->find( $this->newsClass);
		$body = implode( '', $body);	
		$this->content = $body;

        $this->cssEliminator( $this->content);

        if( isset( $this->titleRegex)) {
            if (preg_match($this->titleRegex, $this->content, $matches) == 1) {

                $this->title = $matches[1];
                $this->content = preg_replace( $this->titleRegex, '', $this->content);
            }

            $this->items['title'] = $this->title;
        }

        if( isset( $this->leadContRegex)) {
            if (preg_match( $this->leadContRegex, $this->content, $matches) == 1) {

                $this->leadCont = $matches[1];
                $this->content = preg_replace( $this->leadContRegex, '', $this->content);
            }

            $this->items['leadCont'] = $this->leadCont;
        }

        if( isset( $this->imageUrlRegex) && isset( $this->newsCodeRegex)) {    
            if( preg_match( $this->imageUrlRegex, $this->content, $matches) == 1) {

                $this->imageFileUrl = $matches[1];
                $this->items['image'] = $this->imageFileUrl;
				
		$this->content = preg_replace( $this->imageUrlRegex, '', $this->content);

                if ( preg_match( $this->newsCodeRegex, $this->url, $matches) == 1) {
                    
                    $this->newsCode = $matches[1];
                    if ( preg_match('/[^"]+\.(\w{3,})/', $this->imageFileUrl, $matches) == 1) {

                        $extension = $matches[1];
                        $this->imageFileName = $this->source.'_'.$this->newsCode.'.'.$extension;
			copy( $this->imageFileUrl, $this->folderPath.'/../files/'.$this->imageFileName);
                        $this->items['image'] = $this->imageFileName;
                    }
            }
        }

        $this->items['content'] = $this->content;

        return $this->items;
    }

    }
}



