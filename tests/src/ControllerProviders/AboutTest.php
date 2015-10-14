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
            array("POST"),
            array("PUT"),
            array("PATCH"),
            array("DELETE")
        );
    }

    /**
     * TestHeaderReander
     *
     * @return void
     */
    public function testMarkdownAboutPath()
    {
        $about = new \NachoNerd\MarkdownBlog\ControllerProviders\About();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\ControllerProviders\About'
        );
        $rp = $reflectedObject->getProperty('aboutFile');
        $rp->setAccessible(true);

        $this->assertEquals(
            realpath(__DIR__."/../../../markdowns/misc/about.md"),
            $rp->getValue($about)
        );
    }

    /**
     * TestMarkdownAboutGeneratorFail
     *
     * @return void
     */
    public function testMarkdownAboutGeneratorFail()
    {
        $about = new \NachoNerd\MarkdownBlog\ControllerProviders\About();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\ControllerProviders\About'
        );
        $rp = $reflectedObject->getProperty('aboutFile');
        $rp->setAccessible(true);
        $rp->setValue(
            $about,
            realpath(__DIR__."/../../resources/about/about.md")
        );

        $method = new ReflectionMethod(
            '\NachoNerd\MarkdownBlog\ControllerProviders\About',
            'prepareAboutContect'
        );
        $method->setAccessible(true);

        $message = "";
        try {
            $method->invoke($about);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\FileNotFound $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            realpath(__DIR__."/../../resources/about/about.md")." not found",
            $message
        );
    }

    /**
     * TestMarkdownAboutGeneratorSuccess
     *
     * @return void
     */
    public function testMarkdownAboutGeneratorSuccess()
    {
        $about = new \NachoNerd\MarkdownBlog\ControllerProviders\About();
        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\ControllerProviders\About'
        );
        $mardown = file_get_contents(
            realpath(__DIR__."/../../../markdowns/misc/about.md")
        );

        $method = new ReflectionMethod(
            '\NachoNerd\MarkdownBlog\ControllerProviders\About',
            'prepareAboutContect'
        );
        $method->setAccessible(true);

        $message = "";
        try {
            $message = $method->invoke($about);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\FileNotFound $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            $message,
            $mardown
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
        $this->app = $this->createApplication();

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $path = realpath(__DIR__."/../../resources/about/config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($this->app, $path);

        $path = realpath(__DIR__."/../../resources/about/views/")."/";
        $rp1 = $reflectedObject->getProperty('viewsPath');
        $rp1->setAccessible(true);
        $rp1->setValue($this->app, $path);

        $client = $this->createClient();
        $client->request($method, '/about/');
        $response = $client->getResponse();
        $this->assertEquals(
            'Error Some Custom Error',
            str_replace("\n", "", $response->getContent())
        );
    }

    /**
     * TestUnderContruct
     *
     * @return void
     */
    public function testUnderContruct()
    {
        $className = "\NachoNerd\MarkdownBlog\Application";
        $className1 = "\NachoNerd\MarkdownBlog\Factories\ControllerProvider";

        $about = new \NachoNerd\MarkdownBlog\ControllerProviders\About();

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\ControllerProviders\About'
        );
        $rp = $reflectedObject->getProperty('aboutFile');
        $rp->setAccessible(true);
        $rp->setValue(
            $about,
            realpath(__DIR__."/../../resources/about/about.md")
        );

        $this->app = $this->getMock(
            $className,
            array('getControllerProviderFactory')
        );

        $factory = $this->getMockBuilder($className1)
            ->setMethods(array('create'))
            ->getMock();

        $factory->expects($this->any())
            ->method('create')
            ->willReturn($about);

        $this->app->expects($this->any())
            ->method('getControllerProviderFactory')
            ->willReturn($factory);

        $reflectedObject = new \ReflectionClass(
            $className
        );

        $path = realpath(__DIR__."/../../resources/about/config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($this->app, $path);

        $path = realpath(__DIR__."/../../resources/about/views/")."/";
        $rp1 = $reflectedObject->getProperty('viewsPath');
        $rp1->setAccessible(true);
        $rp1->setValue($this->app, $path);

        $client = $this->createClient();
        $client->request('GET', '/about/');
        $response = $client->getResponse();
        $this->assertEquals(
            'Under Construct',
            str_replace("\n", "", $response->getContent())
        );
    }

    /**
     * TestSuccess
     *
     * @return void
     */
    public function testSuccess()
    {
        $className = "\NachoNerd\MarkdownBlog\Application";
        $className1 = "\NachoNerd\MarkdownBlog\Factories\ControllerProvider";

        $about = new \NachoNerd\MarkdownBlog\ControllerProviders\About();

        $parser = new \cebe\markdown\MarkdownExtra();
        $html = $parser->parse(
            file_get_contents(
                realpath(__DIR__."/../../../markdowns/misc/about.md")
            )
        );

        $this->app = $this->getMock(
            $className,
            array('getControllerProviderFactory')
        );

        $factory = $this->getMockBuilder($className1)
            ->setMethods(array('create'))
            ->getMock();

        $factory->expects($this->any())
            ->method('create')
            ->willReturn($about);

        $this->app->expects($this->any())
            ->method('getControllerProviderFactory')
            ->willReturn($factory);

        $reflectedObject = new \ReflectionClass(
            $className
        );

        $path = realpath(__DIR__."/../../resources/about/config/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($this->app, $path);

        $path = realpath(__DIR__."/../../resources/about/views/")."/";
        $rp1 = $reflectedObject->getProperty('viewsPath');
        $rp1->setAccessible(true);
        $rp1->setValue($this->app, $path);

        $client = $this->createClient();
        $client->request('GET', '/about/');
        $response = $client->getResponse();
        $this->assertEquals(
            str_replace("\n", "", $html),
            str_replace("\n", "", $response->getContent())
        );
    }
}
