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

/**
 * Aplication Class
 *
 * @category  TestCase
 * @package   Tests
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2015 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      https://github.com/nachonerd/markdownblog
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
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
     * Test Route Yaml File Path
     *
     * Check if the path of Config Folder was Correctly setup.
     *
     * @return void
     */
    public function testConfigFolderPath()
    {
        $app = new \NachoNerd\MarkdownBlog\Application();

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);

        $this->assertEquals(
            realpath(__DIR__."/../../config/")."/",
            $rp->getValue($app)
        );
    }

    /**
     * ProviderYamlFileNotExist
     *
     * @return array
     */
    public function providerYamlFileNotExist()
    {
        return array(
            array("errors.yml", "errors.yml file not exists, verify the manual."),
            array("routes.yml", "routes.yml file not exists, verify the manual."),
            array("fruta.yml", "fruta.yml file not exists, verify the manual.")
        );
    }

    /**
     * Test Yaml File No Exist
     *
     * When the file routes.yml not exist Application Class throws a
     * WrongConfigException with message: routes.yml file not exists,
     * verify the manual.
     * When the file errors.yml not exist Application Class throws a
     * WrongConfigException with message: errors.yml file not exists,
     * verify the manual.
     *
     * @param string $filename        YAML File Name
     * @param string $expectedMessage Exception Expected Message
     *
     * @return void
     *
     * @dataProvider providerYamlFileNotExist
     */
    public function testYamlFileNotExist($filename, $expectedMessage)
    {
        $app = new \NachoNerd\MarkdownBlog\Application();

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $path = realpath(__DIR__."/../resources/")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($app, $path);

        $method = new ReflectionMethod(
            '\NachoNerd\MarkdownBlog\Application',
            'parserYaml'
        );
        $method->setAccessible(true);

        $message = "";
        try {
            $method->invoke($app, $filename);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\WrongConfig $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            $message,
            $path.$expectedMessage
        );
    }

    /**
     * ProviderYamlFileNotExist
     *
     * @return array
     */
    public function providerYamlParseYaml()
    {
        return array(
            array("errors.yml", "invalid file %s, verify the manual."),
            array("routes.yml", "invalid file %s, verify the manual.")
        );
    }

    /**
     * TestParseYaml
     *
     * When fail the parser of routes.yml, Application Class throws a
     * WrongConfigException with message: invalid file routes.yml,
     * verify the manual.
     * When fail the parser of errors.yml, Application Class throws a
     * WrongConfigException with message: invalid file errors.yml,
     * verify the manual.
     *
     * @param string $filename        Filename
     * @param string $expectedMessage Exception Expected Message
     *
     * @return void
     *
     * @dataProvider providerYamlParseYaml
     */
    public function testParseYaml($filename, $expectedMessage)
    {
        $app = new \NachoNerd\MarkdownBlog\Application();

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $path = realpath(__DIR__."/../resources/wrong")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($app, $path);

        $method = new ReflectionMethod(
            '\NachoNerd\MarkdownBlog\Application',
            'parserYaml'
        );
        $method->setAccessible(true);

        $message = "";
        try {
            $method->invoke($app, $filename);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\WrongConfig $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            $message,
            sprintf($expectedMessage, $path.$filename)
        );
    }

    /**
     * ProviderYamlFileNotExist
     *
     * @return array
     */
    public function providerSuccessParseYaml()
    {
        return array(
            array(
                "errors.yml",
                array(
                    "default" => array("page" => "other.html.twig"),
                    404 => array("page" => "404.html.twig")
                )
            ),
            array(
                "routes.yml",
                array(
                    "post" => array(
                        "paths" => array("/post"),
                        "_provider" => "Post"
                    ),
                    "home" => array(
                        "paths" => array("/home", "/"),
                        "_provider" => "Home"
                    )
                )
            )
        );
    }

    /**
     * TestSuccessParseYaml
     *
     * Parse Yaml Success
     *
     * @param string $filename      Filename
     * @param array  $expectedArray Array
     *
     * @return void
     *
     * @dataProvider providerSuccessParseYaml
     */
    public function testSuccessParseYaml($filename, $expectedArray)
    {
        $app = new \NachoNerd\MarkdownBlog\Application();

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $path = realpath(__DIR__."/../resources/success")."/";
        $rp = $reflectedObject->getProperty('configPath');
        $rp->setAccessible(true);
        $rp->setValue($app, $path);

        $method = new ReflectionMethod(
            '\NachoNerd\MarkdownBlog\Application',
            'parserYaml'
        );
        $method->setAccessible(true);

        $values = array();
        try {
            $values = $method->invoke($app, $filename);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\WrongConfig $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            $expectedArray,
            $values
        );
    }

    /**
     * ProviderTestVerifyPageAttribute
     *
     * @return array
     */
    public function providerTestVerifyPageAttribute()
    {
        $yamlErrorDefault = <<<YAML
default:
    pages: other.html.twg
YAML;
        $yaml404Default = <<<YAML
404:
    page:
default:
    page: other.html.twig
YAML;
        $yaml500Default = <<<YAML
404:
    page: 404.html.twig
500:
    page: ""
default:
    page: other.html.twig
YAML;
        return array(
            array(
                303,
                $yamlErrorDefault,
                "atribute page not defined in default, verify manual."
            ),
            array(
                404,
                $yaml404Default,
                "atribute page not defined in 404, verify manual."
            ),
            array(
                500,
                $yaml500Default,
                "atribute page not defined in 500, verify manual."
            )
        );
    }

    /**
     * Test Verify Page Attribute
     *
     * For each Items in errors.yml file verify if the page attribute was
     * defined, if It wasn't defined, Application class throws
     * WrongConfigException with message: invalid errors.yml
     * (atribute pages not defined in #some code status#), verify manual.
     *
     * @param mixed  $code            HTTP STATUS CODE
     * @param string $yaml            Yaml String
     * @param string $expectedMessage Exception Expected Message
     *
     * @return void
     *
     * @dataProvider providerTestVerifyPageAttribute
     */
    public function testVerifyPageAttribute($code, $yaml, $expectedMessage)
    {
        $className = "\NachoNerd\MarkdownBlog\Application";
        $app = $this->getMock(
            $className, array('parserYaml')
        );

        $yamlObj = new \Symfony\Component\Yaml\Parser();
        $values = $yamlObj->parse($yaml);

        $app->expects($this->any())
            ->method('parserYaml')
            ->willReturn($values);

        $method = new ReflectionMethod(
            $className,
            'errorPage'
        );
        $method->setAccessible(true);

        $message = "";
        try {
            $values = $method->invoke($app, new \Exception("test"), $code);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\WrongConfig $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            $expectedMessage,
            $message
        );
    }

    /**
     * TestViewsPaths
     *
     * @return void
     */
    public function testViewsPaths()
    {
        $app = new \NachoNerd\MarkdownBlog\Application();

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $rp = $reflectedObject->getProperty('viewsPath');
        $rp->setAccessible(true);

        $this->assertEquals(
            realpath(__DIR__."/../../views/")."/",
            $rp->getValue($app)
        );
    }

    /**
     * ProviderTestVerifyPageExists
     *
     * @return array
     */
    public function providerTestVerifyPageExists()
    {
        $yamlDefault = <<<YAML
default:
    page: other.html.twig
YAML;
        $yaml400 = <<<YAML
404:
    page: 404.html.twig
default:
    page: other.html.twig
YAML;
        return array(
            array(
                303,
                $yamlDefault,
                "invalid file other.html.twig not found"
            ),
            array(
                404,
                $yaml400,
                "invalid file 404.html.twig not found"
            )
        );
    }

    /**
     * Test Verify Page Exists
     *
     * For each Items in errors.yml verify if the file, denifed on the attribute
     * page, exists, if its not exists, Application Class throws
     * WrongConfigException with message: invalid file #File Name# not found
     *
     * @param mixed  $code            HTTP STATUS CODE
     * @param string $yaml            Yaml String
     * @param string $expectedMessage Exception Expected Message
     *
     * @return void
     *
     * @dataProvider providerTestVerifyPageExists
     */
    public function testVerifyPageExists($code, $yaml, $expectedMessage)
    {
        $className = "\NachoNerd\MarkdownBlog\Application";
        $app = $this->getMock(
            $className, array('parserYaml', 'prepareControllerProviders')
        );

        $yamlObj = new \Symfony\Component\Yaml\Parser();
        $values = $yamlObj->parse($yaml);

        $app->expects($this->any())
            ->method('parserYaml')
            ->willReturn($values);

        $app->expects($this->any())
            ->method('prepareControllerProviders')
            ->willReturn(true);

        $method = new ReflectionMethod(
            $className,
            'errorPage'
        );
        $method->setAccessible(true);

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $rp = $reflectedObject->getProperty('viewsPath');
        $rp->setAccessible(true);

        $rp->setValue(
            $app,
            realpath(__DIR__."/../resources/")."/"
        );

        $message = "";
        try {
            $app->boot();
            $values = $method->invoke($app, new \Exception("test"), $code);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\WrongConfig $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            $expectedMessage,
            $message
        );
    }

    /**
     * ProviderTestSuccessErrorPage
     *
     * @return array
     */
    public function providerTestSuccessErrorPage()
    {
        $yamlDefault = <<<YAML
default:
    page: success/other.html.twig
YAML;
        $yaml400 = <<<YAML
404:
    page: success/404.html.twig
default:
    page: other.html.twig
YAML;
        return array(
            array(
                303,
                $yamlDefault,
                "Error 303"
            ),
            array(
                404,
                $yaml400,
                "Error 404"
            )
        );
    }

    /**
     * Test Success Error Page
     *
     * When you call the method errorPag(\Exception $e, $code)
     * return the page defined en the errors.yml file.
     *
     * @param mixed  $code            HTTP STATUS CODE
     * @param string $yaml            Yaml String
     * @param string $expectedMessage Expected Message
     *
     * @return void
     *
     * @dataProvider providerTestSuccessErrorPage
     */
    public function testSuccessErrorPage($code, $yaml, $expectedMessage)
    {
        $className = "\NachoNerd\MarkdownBlog\Application";
        $app = $this->getMock(
            $className, array('parserYaml', 'prepareControllerProviders')
        );

        $yamlObj = new \Symfony\Component\Yaml\Parser();
        $values = $yamlObj->parse($yaml);

        $app->expects($this->any())
            ->method('parserYaml')
            ->willReturn($values);

        $app->expects($this->any())
            ->method('prepareControllerProviders')
            ->willReturn(true);

        $method = new ReflectionMethod(
            $className,
            'errorPage'
        );
        $method->setAccessible(true);

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $rp = $reflectedObject->getProperty('viewsPath');
        $rp->setAccessible(true);

        $rp->setValue(
            $app,
            realpath(__DIR__."/../resources/")."/"
        );

        $message = "";
        $values = "";
        try {
            $app->boot();
            $values = $method->invoke($app, new \Exception("test"), $code);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\WrongConfig $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            $expectedMessage,
            str_replace("\n", "", $values)
        );
    }

    /**
     * Provider Test Atributes Provider Error
     *
     * @return array
     */
    public function providerTestAtributesProviderError()
    {
        $yaml1 = <<<YAML
YAML;
        $yaml2 = <<<YAML
home:
    paths:
    _provider: 'Home'
YAML;

        $yaml3 = <<<YAML
home:
    path:
        - /
    _provider: 'Home'
YAML;

        $yaml4 = <<<YAML
home:
    _provider: 'Home'
YAML;
        $yaml5 = <<<YAML
home:
    paths:
        - /home
        - /
    _provider: 'Home'
post:
    paths:
    _provider: 'Post'
YAML;

        $yaml6 = <<<YAML
home:
    paths:
        - /home
        - /
    _provider: 'Home'
post:
    path:
        - /
    _provider: 'Post'
YAML;

        $yaml7 = <<<YAML
home:
    paths:
        - /home
        - /
    _provider: 'Home'
post:
    _provider: 'Post'
YAML;

        $yaml8 = <<<YAML
home:
    paths:
        - /home
        - /
YAML;

        $yaml9 = <<<YAML
home:
    paths:
        - /home
        - /
    _provider: ''
YAML;

        $yaml10 = <<<YAML
home:
    paths:
        - /home
        - /
    _providers: 'Post'
YAML;

        $yaml11 = <<<YAML
home:
    paths:
        - /home
        - /
    _provider:
YAML;

        $yaml12 = <<<YAML
home:
    paths:
        - /home
        - /
    _provider: 'Home'
post:
    paths:
        - /home
        - /
    _provider: ''
YAML;

        return array(
            array(
                $yaml1,
                "Invalid routes.yml, verify manual."
            ),
            array(
                $yaml2,
                "atribute paths not defined in home, verify manual."
            ),
            array(
                $yaml3,
                "atribute paths not defined in home, verify manual."
            ),
            array(
                $yaml4,
                "atribute paths not defined in home, verify manual."
            ),
            array(
                $yaml5,
                "atribute paths not defined in post, verify manual."
            ),
            array(
                $yaml6,
                "atribute paths not defined in post, verify manual."
            ),
            array(
                $yaml7,
                "atribute paths not defined in post, verify manual."
            ),
            array(
                $yaml8,
                "atribute provider not defined in home, verify manual."
            ),
            array(
                $yaml9,
                "atribute provider not defined in home, verify manual."
            ),
            array(
                $yaml10,
                "atribute provider not defined in home, verify manual."
            ),
            array(
                $yaml11,
                "atribute provider not defined in home, verify manual."
            ),
            array(
                $yaml12,
                "atribute provider not defined in post, verify manual."
            )
        );
    }

    /**
     * Test Atributes Provider Error
     *
     * For each Items in routes.yml file verify if the attributes (provider and
     * paths) were defined, if they weren't defined, Application class throws
     * WrongConfigException with message: invalid routes.yml
     * (atributes provider and paths not defined in #some section#),
     * verify manual.
     *
     * @param string $yaml            Yaml String
     * @param string $expectedMessage Expected Message
     *
     * @return void
     *
     * @dataProvider providerTestAtributesProviderError
     */
    public function testAtributesProviderError($yaml, $expectedMessage)
    {
        $className = "\NachoNerd\MarkdownBlog\Application";
        $className1 = "\NachoNerd\MarkdownBlog\Factories\ControllerProvider";

        $app = $this->getMock(
            $className,
            array('parserYaml', 'getControllerProviderFactory', 'mount')
        );

        $yamlObj = new \Symfony\Component\Yaml\Parser();
        $values = $yamlObj->parse($yaml);

        $app->expects($this->any())
            ->method('mount')
            ->willReturn(true);

        $app->expects($this->any())
            ->method('parserYaml')
            ->willReturn($values);

        $factory = $this->getMockBuilder($className1)
            ->setMethods(array('create'))
            ->getMock();

        $factory->expects($this->any())
            ->method('create')
            ->willReturn(null);

        $app->expects($this->any())
            ->method('getControllerProviderFactory')
            ->willReturn($factory);

        $method = new ReflectionMethod(
            $className,
            'prepareControllerProviders'
        );
        $method->setAccessible(true);

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $rp = $reflectedObject->getProperty('viewsPath');
        $rp->setAccessible(true);

        $rp->setValue(
            $app,
            realpath(__DIR__."/../resources/")."/"
        );

        $message = "";
        $values = "";
        try {
            $values = $method->invoke($app);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\WrongConfig $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            $expectedMessage,
            str_replace("\n", "", $message)
        );
    }

    /**
     * ProviderTestControllerProviderNotFoundError
     *
     * @return array
     */
    public function providerTestControllerProviderNotFoundError()
    {
        $yaml1 = <<<YAML
home:
    paths:
        - /home
        - /
    _provider: 'Home'
YAML;

        return array(
            array(
                $yaml1,
                "ControllerProvider Not Found in section home"
            )
        );
    }

    /**
     * Test ControllerProvider Not Found Error
     *
     * For each Items in routes.yml verify if the ControllerProvider, denifed on
     * the attribute _provider, exists, if its not exists, Application Class
     * throws WrongConfigException with message: invalid file #Provider Name#
     * not found
     *
     * @param string $yaml            Yaml String
     * @param string $expectedMessage Expected Message
     *
     * @return void
     *
     * @dataProvider providerTestControllerProviderNotFoundError
     */
    public function testControllerProviderNotFoundError($yaml, $expectedMessage)
    {
        $className = "\NachoNerd\MarkdownBlog\Application";
        $className1 = "\NachoNerd\MarkdownBlog\Factories\ControllerProvider";

        $app = $this->getMock(
            $className,
            array('parserYaml', 'getControllerProviderFactory', 'mount')
        );

        $yamlObj = new \Symfony\Component\Yaml\Parser();
        $values = $yamlObj->parse($yaml);

        $app->expects($this->any())
            ->method('mount')
            ->willReturn(true);

        $app->expects($this->any())
            ->method('parserYaml')
            ->willReturn($values);

        $ex = new \NachoNerd\MarkdownBlog\Exceptions\ControllerProviderNotFound(
            "Not Found",
            1223
        );

        $factory = $this->getMockBuilder($className1)
            ->setMethods(array('create'))
            ->getMock();

        $factory->expects($this->any())
            ->method('create')
            ->will($this->throwException($ex));

        $app->expects($this->any())
            ->method('getControllerProviderFactory')
            ->willReturn($factory);

        $method = new ReflectionMethod(
            $className,
            'prepareControllerProviders'
        );
        $method->setAccessible(true);

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $rp = $reflectedObject->getProperty('viewsPath');
        $rp->setAccessible(true);

        $rp->setValue(
            $app,
            realpath(__DIR__."/../resources/")."/"
        );

        $message = "";
        $values = "";
        try {
            $values = $method->invoke($app);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\WrongConfig $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            $expectedMessage,
            str_replace("\n", "", $message)
        );
    }

    /**
     * Test ControllerProvider Success
     *
     *  For each ControllerProvider do mount example:
     *  $app->mount(
     *      '/events',
     *      new NachoNerd\MarkdownBlog\Controllers\EventsController()
     *  );
     *
     * @return void
     */
    public function testControllerProviderSuccess()
    {
        $yaml = <<<YAML
home:
    paths:
        - /home
        - /
    _provider: 'Home'
post:
    paths:
        - /post
    _provider: 'Post'
YAML;
        $className = "\NachoNerd\MarkdownBlog\Application";
        $className1 = "\NachoNerd\MarkdownBlog\Factories\ControllerProvider";
        $className2 = "\NachoNerd\MarkdownBlog\Controllers\DummyProvider";

        $app = $this->getMock(
            $className,
            array('parserYaml', 'getControllerProviderFactory', 'mount')
        );

        $yamlObj = new \Symfony\Component\Yaml\Parser();
        $values = $yamlObj->parse($yaml);

        $app->expects($this->any())
            ->method('parserYaml')
            ->willReturn($values);

        $factory = $this->getMockBuilder($className1)
            ->setMethods(array('create'))
            ->getMock();

        $provider = $this->getMockBuilder($className2)
            ->setMethods(array('connect'))
            ->getMock();

        $factory->expects($this->any())
            ->method('create')
            ->willReturn($provider);

        $app->expects($this->any())
            ->method('getControllerProviderFactory')
            ->willReturn($factory);

        $app->expects($this->exactly(3))
            ->method('mount')->willReturn($app);

        $method = new ReflectionMethod(
            $className,
            'prepareControllerProviders'
        );
        $method->setAccessible(true);

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Application'
        );

        $rp = $reflectedObject->getProperty('viewsPath');
        $rp->setAccessible(true);

        $rp->setValue(
            $app,
            realpath(__DIR__."/../resources/")."/"
        );

        $message = "";
        $values = "";
        try {
            $method->invoke($app);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\WrongConfig $e) {
            $message = $e->getMessage();
        }
    }
}
