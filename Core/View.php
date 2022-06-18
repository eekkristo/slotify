<?php

namespace Core;

/**
 * View
 *
 * PHP version 8
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderEmpty($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = "../App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($template, $args = [])
    {
        echo static::getTemplate($template, $args);
    }

    /**
     * Return a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function getTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader('../App/Views');
            $twig = new \Twig\Environment($loader);
            //Custom defined twig global variables
            $twig->addGlobal('current_user', \App\Auth::getUser());
            $twig->addGlobal('flash_message', \App\Flash::getMessage());
            //$twig->addGlobal('album', \App\)
            //$twig->addGlobal('seo_url', Controller::generateSeoURL());
            $twig->addGlobal('random_songs', \App\Models\Song::playRandomSong());
            $twig->addGlobal('ajax_request', Controller::isAjaxRequest());
        }

        return $twig->render($template, $args);
    }
}
