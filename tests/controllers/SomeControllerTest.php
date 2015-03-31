<?php

use Symfony\Component\DomCrawler\Crawler;

/**
 * @group Controller
 */
class SomeControllerTest extends CIUnit_TestCase {

    public function setUp() {
        // Set the tested controller
        $this->CI = set_controller('welcome');
    }

    public function testWelcomeController() {

        $this->CI->index();

        $out = output();

        $crawler = New Crawler($out);

        // Check if the content is OK
        $this->assertCount(0, $crawler->filter('html:contains("PHP Error")'));

        // Check if we got the expected view
        $this->assertCount(1, $crawler->filter('title:contains("Welcome to CodeIgniter")'));
    }

}
