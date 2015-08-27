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
 * @category  Application
 * @package   NachoNerdMarkdownBlog
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2015 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      https://github.com/nachonerd/markdownblog
 */

namespace NachoNerd\MarkdownBlog;

/**
 * Aplication Class
 *
 * @category  Application
 * @package   NachoNerdMarkdownBlog
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2015 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      https://github.com/nachonerd/markdownblog
 */
class Application extends \Silex\Application
{
    /**
     * Route Yml Path
     *
     * @var string
     */
    protected $configPath = "";

    /**
     * Views Path
     *
     * @var string
     */
    protected $viewsPath = "";

    /**
     * Instantiate a new Application.
     *
     * Objects and parameters can be passed as argument to the constructor.
     *
     * @param array $values The parameters or objects.
     */
    public function __construct(array $values = array())
    {
        $this->configPath = realpath(__DIR__."/../config/")."/";
        $this->viewsPath = realpath(__DIR__."/../views/")."/";
        parent::__construct($values);
    }

    /**
     * Boots all service providers.
     *
     * This method is automatically called by handle(), but you can use it
     * to boot all service providers when not handling a request.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function boot()
    {
        $this->register(
            new \Silex\Provider\TwigServiceProvider(),
            array(
                'twig.path' => $this->viewsPath
            )
        );
        $this->error(function (\Exception $e, $codeStatus) {
            return $this->errorPage($e, $codeStatus);
        });
        parent::boot();
    }

    /**
     * ParserYaml
     *
     * @param string $filename Filename
     *
     * @return array
     *
     * @throws \NachoNerd\MarkdownBlog\Exceptions\WrongConfig
     */
    protected function parserYaml($filename)
    {
        $filename = $this->configPath.$filename;
        if (!file_exists($filename)) {
            throw new \NachoNerd\MarkdownBlog\Exceptions\WrongConfig(
                "$filename file not exists, verify the manual.",
                1
            );
        }
        $values = array();
        try {
            $yaml = new \Symfony\Component\Yaml\Parser();
            $values = $yaml->parse(file_get_contents($filename));
        } catch (\Symfony\Component\Yaml\Exception\ParseException $e){
            throw new \NachoNerd\MarkdownBlog\Exceptions\WrongConfig(
                sprintf("invalid file %s, verify the manual.", $filename),
                2
            );
        }
        return $values;
    }

    /**
     * Error Page Provider
     *
     * @param Exception $e          Exception
     * @param mixed     $codeStatus HTTP STATUS CODE
     *
     * @return String HTML
     *
     * @throws \NachoNerd\MarkdownBlog\Exceptions\WrongConfig
     */
    protected function errorPage(\Exception $e, $codeStatus)
    {
        $values = $this->parserYaml("error.yml");

        $code = array();
        $key = $codeStatus;
        if (!isset($values[$codeStatus])) {
            $key = "default";
            $code = $values["default"];
        } else {
            $code = $values[$codeStatus];
        }

        if (!isset($code["page"]) || empty($code["page"])) {
            throw new \NachoNerd\MarkdownBlog\Exceptions\WrongConfig(
                sprintf(
                    "atribute page not defined in %s, verify manual.",
                    $key
                ),
                3
            );
        }

        try {
            $html = $this['twig']->render($code["page"]);
        } catch (\Twig_Error $e) {
            throw new \NachoNerd\MarkdownBlog\Exceptions\WrongConfig(
                sprintf(
                    "invalid file %s not found",
                    $code["page"]
                ),
                4
            );
        }
        return $html;
    }
}
