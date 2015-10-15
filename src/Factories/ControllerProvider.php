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
 * @category   Factory
 * @package    NachoNerdMarkdownBlog
 * @subpackage Factories
 * @author     Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright  2015 Ignacio R. Galieri
 * @license    GNU GPL v3
 * @link       https://github.com/nachonerd/markdownblog
 */

namespace NachoNerd\MarkdownBlog\Factories;
use \NachoNerd\MarkdownBlog\Exceptions\ControllerProviderNotFound;

/**
 * ControllerProvider Class
 *
 * @category   Factory
 * @package    NachoNerdMarkdownBlog
 * @subpackage Factories
 * @author     Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright  2015 Ignacio R. Galieri
 * @license    GNU GPL v3
 * @link       https://github.com/nachonerd/markdownblog
 */
class ControllerProvider
{
    /**
     * Controllers Namespace
     *
     * @var string
     */
    protected $controllesNamespace = "";
    /**
     * __construct
     */
    public function __construct()
    {
        $this->controllesNamespace = "\NachoNerd\MarkdownBlog\ControllerProviders";
    }
    /**
     * Create
     *
     * @param string $classname ClassName
     *
     * @return \Silex\ControllerProviderInterface
     */
    public function create($classname)
    {
        $classname = $this->controllesNamespace."\\".$classname;
        if (!$this->existsClass($classname)) {
            throw new ControllerProviderNotFound(
                "Class $classname Not Found.",
                7
            );
        }

        $controller = $this->getControllerProvider($classname);

        if (!($controller instanceof \Silex\ControllerProviderInterface)) {
            throw new ControllerProviderNotFound(
                "Class $classname not implement the ".
                "\Silex\ControllerProviderInterface.",
                8
            );
        }

        return $controller;
    }

    /**
     * ExistsClass
     *
     * @param string $classname Class Name
     *
     * @return boolean
     */
    protected function existsClass($classname)
    {
        return class_exists($classname);
    }

    /**
     * GetControllerProvider
     *
     * @param string $classname Class Name
     *
     * @return \Silex\ControllerProviderInterface
     */
    protected function getControllerProvider($classname)
    {
        return new $classname();
    }
}
