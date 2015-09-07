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
     * TestMarkdownPostsPath
     *
     * @return void
     */
    public function testMarkdownPostsPath()
    {
        $post = new \NachoNerd\MarkdownBlog\ControllerProviders\Post();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post'
        );
        $rp = $reflectedObject->getProperty('postPath');
        $rp->setAccessible(true);

        $this->assertEquals(
            realpath(__DIR__."/../../../markdowns/posts/")."/",
            $rp->getValue($post)
        );
    }

    /**
     * TestGetLastPostEmpty
     *
     * @return void
     */
    public function testGetLastPostEmpty()
    {
        $post = new \NachoNerd\MarkdownBlog\ControllerProviders\Post();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post'
        );
        $rp = $reflectedObject->getProperty('postPath');
        $rp->setAccessible(true);
        $rp->setValue(
            $post,
            realpath(__DIR__."/../../resources/post/empty/")."/"
        );

        $method = new ReflectionMethod(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post',
            'getLastPost'
        );
        $method->setAccessible(true);

        $message = "";
        try {
            $method->invoke($post);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\FileNotFound $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            "Not Found Last Post",
            $message
        );
    }

    /**
     * TestGetLastPostUncorrect
     *
     * @return void
     */
    public function testGetLastPostUncorrect()
    {
        $post = new \NachoNerd\MarkdownBlog\ControllerProviders\Post();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post'
        );
        $rp = $reflectedObject->getProperty('postPath');
        $rp->setAccessible(true);
        $rp->setValue(
            $post,
            realpath(__DIR__."/../../resources/post/postsuncorrect/")."/"
        );

        $method = new ReflectionMethod(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post',
            'getLastPost'
        );
        $method->setAccessible(true);

        $message = "";
        try {
            $method->invoke($post);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\FileNotFound $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            "Not Found Last Post",
            $message
        );
    }

    /**
     * TestGetLastPostSuccess
     *
     * @return void
     */
    public function testGetLastPostSuccess()
    {
        $post = new \NachoNerd\MarkdownBlog\ControllerProviders\Post();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post'
        );
        $rp = $reflectedObject->getProperty('postPath');
        $rp->setAccessible(true);
        $rp->setValue(
            $post,
            realpath(__DIR__."/../../resources/post/posts/")."/"
        );

        $method = new ReflectionMethod(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post',
            'getLastPost'
        );
        $method->setAccessible(true);

        $message = "";
        try {
            $message = $method->invoke($post);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\FileNotFound $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            "second_20150103.md",
            $message
        );
    }

    /**
     * TestPreparePostContectFaild
     *
     * @return void
     */
    public function testPreparePostContectFaild()
    {
        $filename = "second_20150103.md";
        $post = new \NachoNerd\MarkdownBlog\ControllerProviders\Post();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post'
        );
        $rp = $reflectedObject->getProperty('postPath');
        $rp->setAccessible(true);
        $rp->setValue(
            $post,
            realpath(__DIR__."/../../resources/post/empty/")."/"
        );

        $method = new ReflectionMethod(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post',
            'preparePostContect'
        );
        $method->setAccessible(true);

        $message = "";
        try {
            $message = $method->invoke($post, $filename);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\FileNotFound $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            "Not Found File $filename",
            $message
        );
    }

    /**
     * TestPreparePostContectSuccess
     *
     * @return void
     */
    public function testPreparePostContectSuccess()
    {
        $filename = "second_20150103.md";

        $parser = new \cebe\markdown\MarkdownExtra();
        $html = $parser->parse(
            file_get_contents(
                realpath(__DIR__."/../../resources/post/posts/")."/".$filename
            )
        );
        $post = new \NachoNerd\MarkdownBlog\ControllerProviders\Post();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post'
        );
        $rp = $reflectedObject->getProperty('postPath');
        $rp->setAccessible(true);
        $rp->setValue(
            $post,
            realpath(__DIR__."/../../resources/post/posts/")."/"
        );

        $method = new ReflectionMethod(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post',
            'preparePostContect'
        );
        $method->setAccessible(true);

        $message = "";
        try {
            $message = $method->invoke($post, $filename);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\FileNotFound $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            $html,
            $message
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
            array('/post/'),
            array('/post/nofile/')
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
        $post = new \NachoNerd\MarkdownBlog\ControllerProviders\Post();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post'
        );
        $rp = $reflectedObject->getProperty('postPath');
        $rp->setAccessible(true);
        $rp->setValue(
            $post,
            realpath(__DIR__."/../../resources/post/empty/")."/"
        );

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $this->app = $this->getMock(
            '\NachoNerd\MarkdownBlog\Application',
            array('getControllerProviderFactory')
        );

        $factory = $this->getMockBuilder(
            "\NachoNerd\MarkdownBlog\Factories\ControllerProvider"
        )->setMethods(array('create'))
            ->getMock();

        $factory->expects($this->any())
            ->method('create')
            ->willReturn($post);

        $this->app->expects($this->any())
            ->method('getControllerProviderFactory')
            ->willReturn($factory);

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
            404,
            $response->getStatusCode()
        );
    }

    /**
     * ProviderTestPost
     *
     * @return array
     */
    public function providerTestPost()
    {
        return array(
            array('/post/'),
            array('/post/c2Vjb25kXzIwMTUwMTAz/')
        );
    }

    /**
     * TestPost
     *
     * @param string $endpoint Endpoint
     *
     * @return void
     *
     * @dataProvider providerTestPost
     */
    public function testPost($endpoint)
    {
        $method = "GET";

        $filename = "second_20150103.md";

        $parser = new \cebe\markdown\MarkdownExtra();
        $html = $parser->parse(
            file_get_contents(
                realpath(__DIR__."/../../resources/post/posts/")."/".$filename
            )
        );

        $post = new \NachoNerd\MarkdownBlog\ControllerProviders\Post();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post'
        );
        $rp = $reflectedObject->getProperty('postPath');
        $rp->setAccessible(true);
        $rp->setValue(
            $post,
            realpath(__DIR__."/../../resources/post/posts/")."/"
        );

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $this->app = $this->getMock(
            '\NachoNerd\MarkdownBlog\Application',
            array('getControllerProviderFactory')
        );

        $factory = $this->getMockBuilder(
            "\NachoNerd\MarkdownBlog\Factories\ControllerProvider"
        )->setMethods(array('create'))
            ->getMock();

        $factory->expects($this->any())
            ->method('create')
            ->willReturn($post);

        $this->app->expects($this->any())
            ->method('getControllerProviderFactory')
            ->willReturn($factory);

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
            str_replace("\n", "", $html),
            str_replace("\n", "", $response->getContent())
        );
    }

    /**
     * ProviderTestPostRedirect
     *
     * @return array
     */
    public function providerTestPostRedirect()
    {
        return array(
            array('/post'),
            array('/post/c2Vjb25kXzIwMTUwMTAz')
        );
    }

    /**
     * TestPost
     *
     * @param string $endpoint Endpoint
     *
     * @return void
     *
     * @dataProvider providerTestPostRedirect
     */
    public function testPostRedirect($endpoint)
    {
        $method = "GET";

        $post = new \NachoNerd\MarkdownBlog\ControllerProviders\Post();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\ControllerProviders\Post'
        );
        $rp = $reflectedObject->getProperty('postPath');
        $rp->setAccessible(true);
        $rp->setValue(
            $post,
            realpath(__DIR__."/../../resources/post/posts/")."/"
        );

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $this->app = $this->getMock(
            '\NachoNerd\MarkdownBlog\Application',
            array('getControllerProviderFactory')
        );

        $factory = $this->getMockBuilder(
            "\NachoNerd\MarkdownBlog\Factories\ControllerProvider"
        )->setMethods(array('create'))
            ->getMock();

        $factory->expects($this->any())
            ->method('create')
            ->willReturn($post);

        $this->app->expects($this->any())
            ->method('getControllerProviderFactory')
            ->willReturn($factory);

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
            301,
            $response->getStatusCode()
        );
    }
}
