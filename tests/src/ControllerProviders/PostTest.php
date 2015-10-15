<?php
/**
 * NachoNerd MarkdownBlog, a minimalist Markdown Blog
 * Copyright (C) 2015  Ignacio R. Galieri
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP VERSION 5.4
 *
 * @category  TestCase
 * @package   Tests
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2015 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      https://github.com/nachonerd/markdownblog
 */

require_once realpath(__DIR__."/../../resources/")."/DummyProvider.php";

/**
 * PostTest Class
 *
 * @category  TestCase
 * @package   Tests
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2015 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      https://github.com/nachonerd/markdownblog
 */
class PostTest extends \Silex\WebTestCase
{
    /**
     * Sets up the fixture. This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        // To no call de parent setUp.
    }
    /**
     * Tears down the fixture. This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown()
    {
    }

    /**
     * CreateApplication
     *
     * @return \Silex\Application
     */
    public function createApplication()
    {
        $app = new NachoNerd\MarkdownBlog\Application();
        return $app;
    }
    /**
     * ProviderTestNotGet
     *
     * @return array
     */
    public function providerTestNotGet()
    {
        return array(
            array("POST", '/post/'),
            array("POST", '/post//'),
            array("POST", '/post'),
            array("PUT", '/post/'),
            array("PUT", '/post//'),
            array("PUT", '/post'),
            array("PATCH", '/post/'),
            array("PATCH", '/post//'),
            array("PATCH", '/post'),
            array("DELETE", '/post/'),
            array("DELETE", '/post//'),
            array("DELETE", '/post')
        );
    }

    /**
     * TestNotAbout
     *
     * @param string $method   HTTP METHOD
     * @param string $endpoint Endpoint
     *
     * @return void
     *
     * @dataProvider providerTestNotGet
     */
    public function testNotMethodGet($method, $endpoint)
    {
        $this->app = $this->createApplication();

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $path = realpath(__DIR__."/../../resources/post/config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($this->app, $path);

        $path = realpath(__DIR__."/../../resources/post/views/")."/";
        $rp1 = $reflectedObject->getProperty('viewsPath');
        $rp1->setAccessible(true);
        $rp1->setValue($this->app, $path);

        $client = $this->createClient();
        $client->request($method, $endpoint);
        $response = $client->getResponse();
        $this->assertEquals(
            'Error Some Custom Error',
            str_replace("\n", "", $response->getContent())
        );
    }

    /**
     * ProviderTestPostEmpty
     *
     * @return array
     */
    public function providerTestPostEmpty()
    {
        return array(
            array('/post/bm9maWxl/')
        );
    }

    /**
     * TestPostEmpty
     *
     * @param string $endpoint Endpoint
     *
     * @return void
     *
     * @dataProvider providerTestPostEmpty
     */
    public function testPostEmpty($endpoint)
    {
        $method = "GET";
        $this->app = $this->createApplication();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $path = realpath(__DIR__."/../../resources/post/empty_config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($this->app, $path);

        $path = realpath(__DIR__."/../../resources/post/views/")."/";
        $rp1 = $reflectedObject->getProperty('viewsPath');
        $rp1->setAccessible(true);
        $rp1->setValue($this->app, $path);

        $client = $this->createClient();
        $client->request($method, $endpoint);
        $response = $client->getResponse();

        $this->assertEquals(
            404,
            $response->getStatusCode()
        );
    }

    /**
     * ProviderTestPostEmpty
     *
     * @return array
     */
    public function providerTestSuccess()
    {
        $html1 = "<h1>Toto bella ense ubi</h1>";
        $html2 = <<<HTML
        <h1>Totobellaenseubi</h1>
        <h2>Etdominitellus</h2>
        <p>Loremmarkdownumfiliasigramen,fecundaperquirereunamSoliuvenesgenerosa.
        Mittereperetdinumeratpinuquequivellentveteris:proculaltaetantum
        <ahref="http://seenly.com/">operisquehuncexcipit</a>
        hastam.Suaiuvenciquippepraesigniapetens.Luctuomen,
        virisinemanusetnoviferarstabis.</p>
HTML;
        return array(
            array('/post/', $html1),
            array('/post/Zmlyc3RfMjAxNTAxMDI=/', $html2),
            array('/post/c2Vjb25kXzIwMTUwMTAz/', $html1)
        );
    }

    /**
     * TestSuccess
     *
     * @param string $endpoint Endpoint
     * @param string $html     HTML
     *
     * @return void
     *
     * @dataProvider providerTestSuccess
     */
    public function testSuccess($endpoint, $html)
    {
        $method = "GET";
        $this->app = $this->createApplication();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $path = realpath(__DIR__."/../../resources/post/config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($this->app, $path);

        $path = realpath(__DIR__."/../../resources/post/views/")."/";
        $rp1 = $reflectedObject->getProperty('viewsPath');
        $rp1->setAccessible(true);
        $rp1->setValue($this->app, $path);

        $client = $this->createClient();
        $client->request($method, $endpoint);
        $response = $client->getResponse();

        $this->assertEquals(
            200,
            $response->getStatusCode()
        );

        $this->assertEquals(
            str_replace(" ", "", str_replace("\n", "", $html)),
            str_replace(" ", "", str_replace("\n", "", $response->getContent()))
        );
    }
}
