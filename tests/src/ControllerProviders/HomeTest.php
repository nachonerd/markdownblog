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
 * HomeTest Class
 *
 * @category  TestCase
 * @package   Tests
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2015 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      https://github.com/nachonerd/markdownblog
 */
class HomeTest extends \Silex\WebTestCase
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
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $path = realpath(__DIR__."/../../resources/home/views/")."/";
        $rp1 = $reflectedObject->getProperty('viewsPath');
        $rp1->setAccessible(true);
        $rp1->setValue($app, $path);

        return $app;
    }

    /**
     * TestGetPostsEmpty
     *
     * @return void
     */
    public function testGetPostsNoExist()
    {
        $app = $this->createApplication();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );
        $path = realpath(__DIR__."/../../resources/home/noexist_config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($app, $path);

        $post = new \NachoNerd\MarkdownBlog\ControllerProviders\Home();
        $method = new ReflectionMethod(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Home',
            'getPosts'
        );
        $method->setAccessible(true);
        $app->boot();

        $message = "";
        try {
            $method->invoke($post, $app, 10);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\FileNotFound $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            "Not Found Any Post",
            $message
        );
    }

    /**
     * TestGetPostsEmpty
     *
     * @return void
     */
    public function testGetPostsEmpty()
    {
        $app = $this->createApplication();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );
        $path = realpath(__DIR__."/../../resources/home/empty_config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($app, $path);

        $post = new \NachoNerd\MarkdownBlog\ControllerProviders\Home();
        $method = new ReflectionMethod(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Home',
            'getPosts'
        );
        $method->setAccessible(true);
        $app->boot();

        $message = "";
        try {
            $method->invoke($post, $app, 10);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\FileNotFound $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            "Not Found Any Post",
            $message
        );
    }

    /**
     * ProviderTestGetPosts
     *
     * @return Array
     */
    public function providerTestGetPosts()
    {
        $one = array(
            "title" => "Post 1",
            "brief" => "Last Post description",
            "author" => "Some Guy",
            "date" => "20150714T14:20:20",
            "file" => "post1.md"
        );
        $two = array(
            "title" => "Post 2",
            "brief" => "Last Post description",
            "author" => "Some Guy",
            "date" => "20150714T14:20:20",
            "file" => "post2.md"
        );
        $three = array(
            "title" => "Post 3",
            "brief" => "Last Post description",
            "author" => "Some Guy",
            "date" => "20150714T14:20:20",
            "file" => "post3.md"
        );
        $four = array(
            "title" => "Post 4",
            "brief" => "Last Post description",
            "author" => "Some Guy",
            "date" => "20150714T14:20:20",
            "file" => "post4.md"
        );
        $five = array(
            "title" => "Post 5",
            "brief" => "Last Post description",
            "author" => "Some Guy",
            "date" => "20150714T14:20:20",
            "file" => "post5.md"
        );

        return array(
            array(1, 0, array($one)),
            array(2, 2, array(2 => $three, 3 => $four)),
            array(3, 3, array(3 => $four, 4 => $five)),
            array(5, 4, array(4 => $five)),
            array(15, 0, array($one, $two, $three, $four, $five))
        );
    }

    /**
     * TestGetPosts
     *
     * @param integer $limit  Limit
     * @param integer $offset Start Element
     * @param array   $return Array
     *
     * @return void
     *
     * @dataProvider providerTestGetPosts
     */
    public function testGetPosts($limit, $offset, $return)
    {
        $app = $this->createApplication();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );
        $path = realpath(__DIR__."/../../resources/home/config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($app, $path);

        $post = new \NachoNerd\MarkdownBlog\ControllerProviders\Home();
        $method = new ReflectionMethod(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Home',
            'getPosts'
        );
        $method->setAccessible(true);
        $app->boot();

        $message = "";
        $result = array();
        try {
            $result = $method->invoke($post, $app, $limit, $offset);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\FileNotFound $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            $return,
            $result
        );
    }

    /**
     * ProviderTestNotGet
     *
     * @return array
     */
    public function providerTestNotGet()
    {
        return array(
            array("POST", '/home/'),
            array("POST", '/home//'),
            array("POST", '/home'),
            array("POST", '/'),
            array("PUT", '/home/'),
            array("PUT", '/home//'),
            array("PUT", '/home'),
            array("PUT", '/'),
            array("PATCH", '/home/'),
            array("PATCH", '/home//'),
            array("PATCH", '/home'),
            array("PATCH", '/'),
            array("DELETE", '/home/'),
            array("DELETE", '/home//'),
            array("DELETE", '/home'),
            array("DELETE", '/')
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

        $path = realpath(__DIR__."/../../resources/home/config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($this->app, $path);

        $path = realpath(__DIR__."/../../resources/home/views/")."/";
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
            array('/home/'),
            array('/home/2')
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
        $path = realpath(__DIR__."/../../resources/home/empty_config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($this->app, $path);

        $path = realpath(__DIR__."/../../resources/home/views/")."/";
        $rp1 = $reflectedObject->getProperty('viewsPath');
        $rp1->setAccessible(true);
        $rp1->setValue($this->app, $path);

        $client = $this->createClient();
        $client->request($method, $endpoint);
        $response = $client->getResponse();

        $this->assertEquals(
            503,
            $response->getStatusCode()
        );
    }

    /**
     * TestSuccess
     *
     * @param string $endpoint Endpoint
     *
     * @return void
     *
     * @dataProvider providerTestPostEmpty
     */
    public function testPostNoExist($endpoint)
    {
        $method = "GET";
        $this->app = $this->createApplication();

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );
        $path = realpath(__DIR__."/../../resources/home/noexist_config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($this->app, $path);

        $path = realpath(__DIR__."/../../resources/home/views/")."/";
        $rp1 = $reflectedObject->getProperty('viewsPath');
        $rp1->setAccessible(true);
        $rp1->setValue($this->app, $path);

        $client = $this->createClient();
        $client->request($method, $endpoint);
        $response = $client->getResponse();

        $this->assertEquals(
            503,
            $response->getStatusCode()
        );
    }

    /**
     * ProviderTestSuccess
     *
     * @return array
     */
    public function providerTestSuccess()
    {
        $html1 = "<lu><li>post1.md</li><li>post2.md</li></lu>";
        $html2 = "<lu><li>post3.md</li><li>post4.md</li></lu>";
        return array(
            array("/home/", $html1),
            array("/home/2", $html2)
        );
    }

    /**
     * TestPostNoExist
     *
     * @param string $endpoint Endpoint
     * @param string $html     HTML Content
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
        $path = realpath(__DIR__."/../../resources/home/config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($this->app, $path);

        $path = realpath(__DIR__."/../../resources/home/views/")."/";
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
            str_replace("\n", "", $html),
            str_replace(" ", "", str_replace("\n", "", $response->getContent()))
        );
    }
}
