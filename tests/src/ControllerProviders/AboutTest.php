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
 * ControllerProviderTest Class
 *
 * @category  TestCase
 * @package   Tests
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2015 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      https://github.com/nachonerd/markdownblog
 */
class AboutTest extends \Silex\WebTestCase
{
    /**
     * Sets up the fixture. This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }
    /**
     * Tears down the fixture. This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * CreateApplication
     *
     * @return \Silex\Application
     */
    public function createApplication()
    {
        $app = new NachoNerd\MarkdownBlog\Application();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $path = realpath(__DIR__."/../../resources/about/config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($app, $path);

        $path = realpath(__DIR__."/../../resources/about/views/")."/";
        $rp1 = $reflectedObject->getProperty('viewsPath');
        $rp1->setAccessible(true);
        $rp1->setValue($app, $path);

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
            array("POST"),
            array("PUT"),
            array("PATCH"),
            array("DELETE")
        );
    }

    /**
     * TestNotAbout
     *
     * @param string $method HTTP METHOD
     *
     * @return void
     *
     * @dataProvider providerTestNotGet
     */
    public function testNotMethodGet($method)
    {
        $client = $this->createClient();
        $client->request($method, '/about/');
        $response = $client->getResponse();
        $this->assertEquals(
            'Error Some Custom Error',
            str_replace("\n", "", $response->getContent())
        );
    }
}
