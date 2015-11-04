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
 * Home Class
 *
 * @category   ControllerProvider
 * @package    NachoNerdMarkdownBlog
 * @subpackage ControllerProviders
 * @author     Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright  2015 Ignacio R. Galieri
 * @license    GNU GPL v3
 * @link       https://github.com/nachonerd/markdownblog
 */
class Home implements \Silex\ControllerProviderInterface
{
    /**
     * Connect
     *
     * @param Application $app Silex Application
     *
     * @return void
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers
            ->get('/{offset}', array($this, 'index'))
            ->bind('home_page');
        $controllers
            ->get('/', array($this, 'index'))
            ->bind('home');
        return $controllers;
    }

    /**
     * Home Index
     *
     * @param Application $app    Application
     * @param integer     $offset Start element
     *
     * @return String
     */
    public function index(Application $app, $offset = 0)
    {
        try {
            $post = $this->getPosts(
                $app,
                $app["config"]["post"]["limmit"],
                $offset
            );
        } catch (\NachoNerd\MarkdownBlog\Exceptions\FileNotFound $e) {
            $app->abort(503, "Under Construction");
        }
        return $app['twig']->render(
            "home.html.twig",
            array('posts' => $post)
        );
    }

    /**
     * GetPosts
     *
     * @param \Silex\Application $app    Application current Instance
     * @param integer            $limit  Maximun size of post
     * @param integer            $offset Start element
     *
     * @return array
     *
     * @throws \NachoNerd\MarkdownBlog\Exceptions\FileNotFound
     */
    protected function getPosts(Application $app, $limit = 10, $offset = 0)
    {
        try {
            $files = $app->parserYaml("posts.yml");
        } catch (\NachoNerd\MarkdownBlog\Exceptions\WrongConfig $e) {
            throw new \NachoNerd\MarkdownBlog\Exceptions\FileNotFound(
                "Not Found Any Post", 12
            );
        }
        if (count($files) <= 0) {
            throw new \NachoNerd\MarkdownBlog\Exceptions\FileNotFound(
                "Not Found Any Post", 12
            );
        }
        return array_slice($files, $offset, $limit, true);
    }
}
