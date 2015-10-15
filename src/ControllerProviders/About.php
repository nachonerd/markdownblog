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
 * @category   ControllerProvider
 * @package    NachoNerdMarkdownBlog
 * @subpackage ControllerProvider
 * @author     Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright  2015 Ignacio R. Galieri
 * @license    GNU GPL v3
 * @link       https://github.com/nachonerd/markdownblog
 */

namespace NachoNerd\MarkdownBlog\ControllerProviders;
use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * DummyProvider Class
 *
 * @category   ControllerProvider
 * @package    NachoNerdMarkdownBlog
 * @subpackage ControllerProviders
 * @author     Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright  2015 Ignacio R. Galieri
 * @license    GNU GPL v3
 * @link       https://github.com/nachonerd/markdownblog
 */
class About implements \Silex\ControllerProviderInterface
{
    /**
     * About File Path
     *
     * @var string
     */
    protected $aboutFile = "";

    /**
     * About
     */
    public function __construct()
    {
        $this->aboutFile = realpath(__DIR__."/../../markdowns/misc/about.md");
    }

    /**
     * Connect
     *
     * @param Application $app Silex Application
     *
     * @return voidt
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers
            ->get('/', array($this, 'index'))
            ->bind('about_index');
        return $controllers;
    }

    /**
     * Index About Page
     *
     * @param \Silex\Application $app Silex Application
     *
     * @return String
     */
    public function index(Application $app)
    {
        try {
            $about = $this->prepareAboutContect();
        } catch (\NachoNerd\MarkdownBlog\Exceptions\FileNotFound $e) {
            return $app['twig']->render("misc/underconstruct.html.twig");
        }

        return $app['twig']->render(
            "about.html.twig",
            array('content' => $about)
        );
    }

    /**
     * PrepareAboutContect
     *
     * @return String
     *
     * @throws \NachoNerd\MarkdownBlog\Exceptions\FileNotFound
     */
    protected function prepareAboutContect()
    {
        if (!file_exists($this->aboutFile)) {
            throw new \NachoNerd\MarkdownBlog\Exceptions\FileNotFound(
                $this->aboutFile." not found",
                9
            );
        }
        return file_get_contents(
            $this->aboutFile
        );
    }
}
