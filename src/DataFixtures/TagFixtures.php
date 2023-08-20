<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public const TAG_PHP_REFERER = 'tag_php_referer';
    public const TAG_JS_REFERER = 'tag_js_referer';
    public const TAG_HTML_REFERER = 'tag_html_referer';
    public const TAG_OS_REFERER = 'tag_os_referer';
    public const TAG_CAREER_IN_IT_REFERER = 'tag_career_in_it_referer';
    public const TAG_GIT_REFERER = 'tag_git_referer';
    public const TAG_DOCKER_REFERER = 'tag_docker_referer';
    public const TAG_PYTHON_REFERER = 'tag_python_referer';
    public const TAG_CSS_REFERER = 'tag_css_referer';
    public const TAG_WEB_DEVELOPMENT_REFERER = 'tag_web_development_referer';
    public const TAG_PROGRAMMING_REFERER = 'tag_programming_referer';
    public const TAG_VUE_REFERER = 'tag_vue_referer';

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getTags() as $value) {
            $tag = new Tag(
                name: $value['name'],
                description: $value['description']
            );

            $manager->persist($tag);

            $this->setReference($value['referer'], $tag);
        }

        $manager->flush();
    }

    private function getTags(): array
    {
        return [
            [
                'referer' => self::TAG_PHP_REFERER,
                'name' => 'PHP',
                'description' => 'PHP (Hypertext Preprocessor) - это язык программирования, который широко используется для разработки веб-приложений и динамических веб-сайтов. Он выполняется на сервере и генерирует HTML-код, который отображается в браузере пользователя.',
            ],
            [
                'referer' => self::TAG_JS_REFERER,
                'name' => 'JavaScript',
                'description' => 'JavaScript — прототипно-ориентированный сценарный язык программирования. Обычно используется как встраиваемый язык для программного доступа к объектам приложений. Наиболее широкое применение находит в браузерах как язык сценариев для придания интерактивности веб-страницам.',
            ],
            [
                'referer' => self::TAG_HTML_REFERER,
                'name' => 'HTML',
                'description' => 'HTML (от англ. HyperText Markup Language — «язык гипертекстовой разметки») — стандартный язык разметки документов во Всемирной паутине. Большинство веб-страниц содержат описание разметки на языке HTML (или XHTML). Язык HTML интерпретируется браузерами и отображается в виде документа в удобной для человека форме.',
            ],
            [
                'referer' => self::TAG_OS_REFERER,
                'name' => 'Операционные системы',
                'description' => 'Операционная система — комплекс программ, обеспечивающий управление аппаратными средствами компьютера, организующий работу с файлами и выполнение прикладных программ, осуществляющий ввод и вывод данных.',
            ],
            [
                'referer' => self::TAG_CAREER_IN_IT_REFERER,
                'name' => 'Карьера в IT',
                'description' => 'Карьера в IT - это профессиональное развитие в области информационных технологий. Включает в себя работу над созданием, развитием и поддержкой программного обеспечения, управлением компьютерными системами, анализом и обработкой данных, обеспечением кибербезопасности и многое другое. Карьера в IT обещает широкий выбор возможностей, высокую востребованность, постоянное обучение и развитие, а также хорошие перспективы карьерного роста и финансовой стабильности.',
            ],
            [
                'referer' => self::TAG_GIT_REFERER,
                'name' => 'Git',
                'description' => 'Git - это распределенная система контроля версий (Version Control System, VCS), которая используется для управления изменениями в исходном коде и файловой системе проекта. Он позволяет разработчикам и командам разрабатывать программное обеспечение совместно и эффективно отслеживать и управлять изменениями в коде.',
            ],
            [
                'referer' => self::TAG_DOCKER_REFERER,
                'name' => 'Docker',
                'description' => 'Docker - это платформа для разработки, доставки и запуска приложений в контейнерах. Она позволяет упаковывать приложения со всеми их зависимостями в контейнеры, которые можно легко переносить на различные операционные системы и среды.',
            ],
            [
                'referer' => self::TAG_PYTHON_REFERER,
                'name' => 'Python',
                'description' => 'Python - это высокоуровневый язык программирования, который изначально был разработан для удобства чтения и написания кода. Он предлагает простой и понятный синтаксис, который облегчает разработку программного обеспечения.',
            ],
            [
                'referer' => self::TAG_CSS_REFERER,
                'name' => 'CSS',
                'description' => 'CSS (Cascading Style Sheets) - это язык стилей, который используется для оформления веб-страниц. Он определяет внешний вид элементов документа, таких как цвета, шрифты, расположение и другие визуальные аспекты.',
            ],
            [
                'referer' => self::TAG_WEB_DEVELOPMENT_REFERER,
                'name' => 'Веб-разработка',
                'description' => 'Веб-разработка - это процесс создания веб-сайтов и веб-приложений. Он включает в себя различные этапы, начиная от проектирования и разработки интерфейса, программирования логики приложения, работы с базами данных, до развертывания и поддержки созданного веб-проекта.',
            ],
            [
                'referer' => self::TAG_PROGRAMMING_REFERER,
                'name' => 'Программирование',
                'description' => 'Программирование - это процесс создания компьютерных программ с использованием языков программирования. Оно включает в себя написание кода, тестирование, отладку и поддержку программного обеспечения.',
            ],
            [
                'referer' => self::TAG_VUE_REFERER,
                'name' => 'Vue.js',
                'description' => 'Vue — это прогрессивный фреймворк для создания пользовательских интерфейсов. В отличие от фреймворков-монолитов, Vue создан пригодным для постепенного внедрения. Его ядро в первую очередь решает задачи уровня представления (view), что упрощает интеграцию с другими библиотеками и существующими проектами.',
            ],
        ];
    }
}
