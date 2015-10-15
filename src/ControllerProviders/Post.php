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
use Symfony\Component\HttpFoundation\Response;

/**
 * Post Class
 *
 * @category   ControllerProvider
 * @package    NachoNerdMarkdownBlog
 * @subpackage ControllerProviders
 * @author     Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright  2015 Ignacio R. Galieri
 * @license    GNU GPL v3
 * @link       https://github.com/nachonerd/markdownblog
 */
class Post implements \Silex\ControllerProviderInterface
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
            ->get('/{filename64}/', array($this, 'post'))
            ->bind('post_get');
        $controllers
            ->get('/', array($this, 'index'))
            ->bind('post_index');

        return $controllers;
    }

    /**
     * Last Post Page
     *
     * @param \Silex\Application $app Silex Application
     *
     * @return String
     */
    public function index(Application $app)
    {
        $html = $app['twig']->render(
            "postlast.html.twig"
        );
        return $html;
    }

    /**
     * Post Page
     *
     * @param \Silex\Application $app        Silex Application
     * @param string             $filename64 Filename Base64 Encode
     *
     * @return String
     */
    public function post(Application $app, $filename64)
    {
        $filename = base64_decode($filename64).".md";
        try {
            $html = $app['twig']->render(
                "post.html.twig",
                array( "filename" => $filename)
            );
        } catch (\Exception $e) {
            $app->abort(404, "Post Not Found");
        }
        return $html;
    }
}
