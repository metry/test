<?php

namespace App\Core;

class View
{
    private $loader;
    private $twig;

    public function render(string $contentView, string $templateView, array $data)
    {
        $content = APPLICATION_PATH . "Views/" . $contentView . ".php";
        $template = APPLICATION_PATH . "Views/" . $templateView . ".php";
        try {
            if (!file_exists($content)) {
                throw new \Exception("Вид отсутсвует");
            }
            if (!file_exists($template)) {
                throw new \Exception("Шаблон отсутсвует");
            } else {
                extract($data); // импортируем переменные из массива в текущую символьную таблицу
                require_once $template;
            }
        } catch (\Exception $e) {
            require APPLICATION_PATH . "errors/showError404.php";
        }
    }

    public function renderTemplate(string $templateView, array $data)
    {
        $template = APPLICATION_PATH . "Views/" . $templateView . ".php";
        try {
            if (!file_exists($template)) {
                throw new \Exception("Шаблон отсутсвует");
            } else {
                extract($data); // импортируем переменные из массива в текущую символьную таблицу
                require_once $template;
            }
        } catch (\Exception $e) {
            require APPLICATION_PATH . "errors/showError404.php";
        }
    }

    public function __construct()
    {
        $this->loader = new \Twig_Loader_Filesystem(APPLICATION_PATH . "Views");
        $this->twig = new \Twig_Environment($this->loader);
    }

    public function twigLoad(String $templateView, array $data)
    {
        echo $this->twig->render($templateView.".twig", $data);
    }
}
