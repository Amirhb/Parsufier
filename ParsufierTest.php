<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 12/22/13
 * Time: 9:27 AM
 */
include 'Parsufier.php';

class ParsufierTest extends PHPUnit_Framework_TestCase {

	public function testSetNewsCodeRegex() {

        $pf = new Parsufier;
        $this->assertNull( $pf->getNewsCoderegex());
        $pf->setNewsCodeRegex('something');
        $this->assertEquals( $pf->getNewsCoderegex(), 'something');
    }

    public function testSetSource() {

		$pf = new Parsufier;
        $this->assertNull( $pf->getSource());
        $pf->setSource('something');
        $this->assertEquals( $pf->getSource(), 'something');
    }

    public function testSetTitleRegex() {

		$pf = new Parsufier;
        $this->assertNull( $pf->getTitleRegex());
        $pf->setTitleRegex('something');
        $this->assertEquals( $pf->getTitleRegex(), 'something');
    }

    public function testSetLeadContRegex() {

		$pf = new Parsufier;
        $this->assertNull( $pf->getLeadContRegex());
        $pf->setLeadContRegex('something');
        $this->assertEquals( $pf->getLeadContRegex(), 'something');
    }

    public function testSetImageUrlRegex() {

		$pf = new Parsufier;
        $this->assertNull( $pf->getImageUrlRegex());
        $pf->setImageUrlRegex( 'something');
        $this->assertEquals( $pf->getImageUrlRegex(), 'something');
    }
	
	public function testParse() {
	
		$pf = new Parsufier;
        
        $pf->setSource( 'khabaronline');
        $pf->setNewsCodeRegex( '/detail\/([0-9]*)\//');
        $pf->setTitleRegex( '/<h1>(.*?)<\/h1>/');
        $pf->setLeadContRegex( '/<div\sclass="leadCont">(.*?)<\/div>/');
        $pf->setImageUrlRegex( '#<div class="newsPhoto">\s*<img src="([^"]+)"\s\/>\s*<\/div>#m');
		
		//$findings = $pf->parse( 'http://www.khabaronline.ir/detail/327563/sport/premiere-league', 'div[class="newsBodyCont"]', dirname(__FILE__).'\..\..');
		$findings = $pf->parse( 'http://www.khabaronline.ir/detail/327563/sport/premiere-league','div[class="newsBodyCont"]', dirname(__FILE__).'/../..');
		
		$this->assertEquals( trim( $findings['title']), 'اعلام ترکیب استقلال برای بازی با فجر / غلام‌نژاد و باصفا دوباره فیکس شدند');
		$this->assertEquals( trim( $findings['leadCont']), '<span>ورزش&nbsp;&gt;&nbsp;لیگ برتر - </span>امیر قلعه‌نویی ترکیب استقلال را برای بازی با فجر سپاسی اعلام کرد.');
		$this->assertEquals( trim( $findings['image']), 'khabaronline_327563.jpg');
		echo '<br>'.$findings['content'];

	}

} 