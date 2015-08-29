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
class ControllerProviderTest extends \PHPUnit_Framework_TestCase
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
     * Test Controller Namespace
     *
     * Check if the path of Config Folder was Correctly setup.
     *
     * @return void
     */
    public function testContollerNamespace()
    {
        $factory = new \NachoNerd\MarkdownBlog\Factories\ControllerProvider();

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\MarkdownBlog\Factories\ControllerProvider'
        );

        $rp = $reflectedObject->getProperty('controllesNamespace');
        $rp->setAccessible(true);

        $this->assertEquals(
            "\NachoNerd\MarkdownBlog\ControllerProviders",
            $rp->getValue($factory)
        );
    }
    /**
     * ProviderTestClassNotExistError
     *
     * @return array
     */
    public function providerTestClassNotExistError()
    {
        return array(
            array(
                'NoExists',
                'Class \NachoNerd\MarkdownBlog\ControllerProviders'.
                '\NoExists Not Found.'
            ),
            array(
                'AnotherNoExists',
                'Class \NachoNerd\MarkdownBlog\ControllerProviders'.
                '\AnotherNoExists Not Found.'
            )
        );
    }
    /**
     * Test Class Not Exist Error
     *
     * Given the string NoExists in the method create, then ControllerProvider
     * Factory throws the exceptions ControllerProviderNotFound with message:
     * Class \NachoNerd\MarkdownBlog\ControllersProviders\NoExists Not Found.
     * Given the string AnotherNoExists in the method create, then
     * ControllerProvider Factory throws the exceptions ControllerProviderNotFound
     * with message:
     * Class \NachoNerd\MarkdownBlog\ControllersProviders\AnotherNoExists Not Found.
     *
     * @param string $class   Class Name
     * @param string $message Expectred Message
     *
     * @return void
     *
     * @dataProvider providerTestClassNotExistError
     */
    public function testClassNotExistError($class, $message)
    {
        $factory = new \NachoNerd\MarkdownBlog\Factories\ControllerProvider();

        $messageGiven = "";
        try {
            $factory->create($class);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\ControllerProviderNotFound $e) {
            $messageGiven = $e->getMessage();
        }

        $this->assertEquals(
            $messageGiven,
            $message
        );
    }
    /**
     * ProviderTestClassNotImplement
     *
     * @return array
     */
    public function providerTestClassNotImplement()
    {
        return array(
            array(
                'NotCorrectInstance',
                'Class \NachoNerd\MarkdownBlog\ControllerProviders'.
                '\NotCorrectInstance not implement the '.
                '\Silex\ControllerProviderInterface.'
            ),
            array(
                'AnotherNotCorrectInstance',
                'Class \NachoNerd\MarkdownBlog\ControllerProviders'.
                '\AnotherNotCorrectInstance not implement the '.
                '\Silex\ControllerProviderInterface.'
            )
        );
    }
    /**
     * Test Class No Implement ControllerProviderInterface
     *
     * Given the string NotCorrectInstance in the method create, then
     * ControllerProvider Factory throws the exceptions ControllerProviderNotFound
     * with message:
     * Class \NachoNerd\MarkdownBlog\ControllersProviders\NotCorrectInstance
     * not implement the \Silex\ControllerProviderInterface.
     * Given the string AnotherNotCorrectInstance in the method create, then
     * ControllerProvider Factory throws the exceptions ControllerProviderNotFound
     * with message:
     * Class \NachoNerd\MarkdownBlog\ControllersProviders\AnotherNotCorrectInstance
     * not implement the \Silex\ControllerProviderInterface.
     *
     * @param string $class   Class Name
     * @param string $message Expectred Message
     *
     * @return void
     *
     * @dataProvider providerTestClassNotImplement
     */
    public function testClassNotImplement($class, $message)
    {
        $className = "\NachoNerd\MarkdownBlog\Factories\ControllerProvider";

        $factory = $this->getMockBuilder($className)
            ->setMethods(array('existsClass', 'getControllerProvider'))
            ->getMock();

        $factory->expects($this->any())
            ->method('existsClass')
            ->willReturn(true);

        $factory->expects($this->any())
            ->method('getControllerProvider')
            ->willReturn(new stdClass());

        $messageGiven = "";
        try {
            $factory->create($class);
        } catch (\NachoNerd\MarkdownBlog\Exceptions\ControllerProviderNotFound $e) {
            $messageGiven = $e->getMessage();
        }

        $this->assertEquals(
            $messageGiven,
            $message
        );
    }

    /**
     * Test Success
     *
     * @return voidt
     */
    public function testSuccess()
    {
        $factory = new \NachoNerd\MarkdownBlog\Factories\ControllerProvider();

        $messageGiven = "";
        try {
            $controller = $factory->create("DummyProvider");
        } catch (\NachoNerd\MarkdownBlog\Exceptions\ControllerProviderNotFound $e) {
            $messageGiven = $e->getMessage();
        }

        $this->assertEquals(
            ($controller instanceof \Silex\ControllerProviderInterface),
            true
        );
    }
}
