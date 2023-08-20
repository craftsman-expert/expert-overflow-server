<?php

namespace App\DataFixtures;

use App\Entity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getQuestions() as $questionItem) {
            $question = new Entity\Question(
                author: $this->getReference(UserFixtures::USERS_LINK_REFERENCES[array_rand(UserFixtures::USERS_LINK_REFERENCES, 1)]),
                title: $questionItem['title'],
                text: $questionItem['text']
            );

            $question->setCommentCount(count($questionItem['comments']));
            $question->setViewsCount(random_int(1, 1000));

            foreach ($questionItem['comments'] as $questionComments) {
                $questionComment = new Entity\QuestionComment(
                    author: $questionComments['user'],
                    question: $question,
                    text: $questionComments['text']
                );

                $manager->persist($questionComment);
            }

            $question->setAnswerCount(count($questionItem['answers']));

            // Ответы на каждый вопрос
            foreach ($questionItem['answers'] as $answerItem) {
                $answer = new Entity\Answer(
                    user: $answerItem['user'],
                    question: $question,
                    text: $answerItem['text']
                );
                $answer->setScore(random_int(4, 32));

                foreach ($answerItem['comments'] as $answerItemComments) {
                    $answerComment = new Entity\AnswerComment(
                        author: $answerItemComments['user'],
                        answer: $answer,
                        text: $answerItemComments['text']
                    );

                    $manager->persist($answerComment);
                }

                $manager->persist($answer);
            }

            /** @var Entity\Tag $tagItem */
            foreach ($questionItem['tags'] as $tagItem) {
                $tagItem->getQuestions()->add($question);
                $question->getTags()->add($tagItem);
            }

            // Подписчики
            /** @var Entity\User $subscriberItem */
            foreach ($questionItem['subscribers'] as $subscriberItem) {
                $question->subscribe($subscriberItem);
            }

            $manager->persist($question);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TagFixtures::class,
        ];
    }

    private function getQuestions(): array
    {
        return [
            [
                // https://qna.habr.com/q/1300416
                'title' => 'Как правильно рендерить контент?',
                'text' => <<<HTML
<p>Меню в компоненте с контентом:</p>
<pre class="language-html"><code>&lt;div class="px-3 py-3 mt-2"&gt;
    &lt;div class="flex items-stretch w-full bg-gray-200 h-auto rounded-lg px-0.5 py-0.5"&gt;
     &lt;MenuSection :name="menu.item1.name" :label="menu.item1.label" extraClass="rounded-lg" defaultClass="my-1.5 border-transparent" /&gt;
     &lt;MenuSection :name="menu.item2.name" :label="menu.item2.label" defaultClass="my-1.5 border-transparent border-x-gray-300" /&gt;
     &lt;MenuSection :name="menu.item3.name" :label="menu.item3.label" extraClass="rounded-lg" defaultClass="my-1.5 border-transparent" /&gt;
   &lt;/div&gt;
&lt;/div&gt;</code></pre>
<p>MenuSection:</p>
<pre class="language-html"><code>&lt;button type="submit" class="flex justify-center w-full bg-gray-200" :class="extraClass"&gt;
    &lt;p class="no-underline text-gray-800"&gt;{{ item.label }}&lt;/a&gt;
&lt;/button&gt;</code></pre>
<p>При нажатии на определённый элемент меню, должно отображаться определённый контент (активный первый элемент - выводиться контент первого, второй - второй).<br><br>Как такое реализовать?<br>Куда прописывать&nbsp;<em>@click</em>?<br>Нужно ли дополнительное свойство&nbsp;<em>active</em>? куда прописывать и как менять его?</p>
<p>&nbsp;</p>
HTML,
                'comments' => [
                    [
                        'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                        'text' => 'Можно сделать компонент Menu который всегда работает с MenuSection и тогда сверху делаешь privide, внизу inject и работаешь как будто это один компонент.',
                    ],
                ],
                'answers' => [],
                'tags' => [
                    $this->getReference(TagFixtures::TAG_JS_REFERER),
                    $this->getReference(TagFixtures::TAG_VUE_REFERER),
                ],
                'subscribers' => [
                    $this->getReference(UserFixtures::USER_IPRUS_REFERENCE),
                    $this->getReference(UserFixtures::USER_TILL_LINDEMANN_REFERENCE),
                    $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                ],
            ],
            [
                'title' => 'Как установить пароль на установщик программ?',
                'text' => 'Win10, нужно запретить установку приложений всем кроме администратора.<br><br>В гугле нашёл какие-то полу-меры без пароля, тупа ограничение через политику с разрешением запуска из определённых папок. - кто помешает перенести туда файл, знает только автор этой лабуды.<br><br>Я точно помню что раньше ещё на win7 и меньше, можно было устанавливать всё только от админа, а если юзер не админ, запрашивало учётку админа.',
                'comments' => [],
                'answers' => [],
                'tags' => [
                    $this->getReference(TagFixtures::TAG_OS_REFERER),
                ],
                'subscribers' => [
                    $this->getReference(UserFixtures::USER_IPRUS_REFERENCE),
                ],
            ],
            [
                'title' => 'Как аутентифицироваться в git?',
                'text' => 'У меня есть аккаунт на битбакет, там же создал репозиторий, хочу туда загрузить свой код.<br>Закомитил код и делаю <blockquote>git push origin main</blockquote><br>После этого открывается поле для ввода пароля - <br><blockquote>Password for <a href="https://bitbucket.org" rel="nofollow">https://username@bitbucket.org</a>: </blockquote><br>Ввожу пароль от аккаунта на битбакет но пишет - <br><blockquote>remote: Invalid credentials<br>fatal: Authentication failed for <a href="https://bitbucket.org/.git/" rel="nofollow">https://bitbucket.org/.git/</a><br><br></blockquote><br>Незнаю что делать, и даже гайда нет по этому поводу нормально в интернете, у всех работает сразу пуш и не требует аутефикации, <br>Буду благодарен заа любую помощь, за ссылку на хорошую статью тоже',
                'comments' => [
                    [
                        'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                        'text' => 'Документация в помощь <a href="https://git-scm.com/doc">https://git-scm.com/doc</a>',
                    ],
                ],
                'answers' => [],
                'tags' => [
                    $this->getReference(TagFixtures::TAG_GIT_REFERER),
                ],
                'subscribers' => [
                    $this->getReference(UserFixtures::USER_IPRUS_REFERENCE),
                    $this->getReference(UserFixtures::USER_TILL_LINDEMANN_REFERENCE),
                ],
            ],
            [
                'title' => 'Как развернуть docker nginx+php+mysql без compose?',
                'text' => 'Хочу построить docker 3 в 1 : nginx+php+mysql без compose. После установки нарисовались 2 проблемы: 1) слушается только порт 80, порты 9000 и 3306 -нет',
                'comments' => [
                    [
                        'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                        'text' => 'EXPOSE 80 - ну так ты расшарил только 80 порт,<br>docker run --name nginx -d -p 80:80 -v ${PWD}/data:/data:rw nginx.test здесь тоже, какого результата ты ожидал',
                    ],
                ],
                'answers' => [],
                'tags' => [
                    $this->getReference(TagFixtures::TAG_DOCKER_REFERER),
                ],
                'subscribers' => [
                    $this->getReference(UserFixtures::USER_IPRUS_REFERENCE),
                    $this->getReference(UserFixtures::USER_TILL_LINDEMANN_REFERENCE),
                ],
            ],
            [
                'title' => 'Какую работу выбрать?',
                'text' => 'Работаю, по сути сис админом инженером тех поддержки, уже 5 лет стажа. захотелось мне поменять направление, не хочу заниматься технической поддержкой, хочу работать удаленно и что то разрабатывать. Все что я знаю кроме технической работы, это sql, python, bash, linux, docker, c++, html. Могу разработать программу, а могу базу разработать, также администрировать сервера, но удаленно. Куда мне идти с такими знаниями, как называются должности? и что мне еще лучше подучить? Заглядываюсь на Data Engineer, Dba Developer - в Чем их отличие, SoftWare Engineer, Инженер программист, DevOps Engineer - Тоже в чем отличие, Python Developer. Может какие то есть клубы специальные для них?',
                'comments' => [
                    [
                        'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                        'text' => 'очень странно столько работать 5 лет, знать с++ докер, питон и не иметь понятия кто чем занимается. какая-то каша и дба и девопс...',
                    ],
                    [
                        'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                        'text' => 'просто сисадмины всемогущие волшебники в нашей стране) а хочется чем то одним заниматься)',
                    ],
                    [
                        'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                        'text' => 'мне бы хотя бы понять разницу между этими должностями Data Engineer, Dba Developer - в Чем их отличие, SoftWare Engineer, Инженер программист, DevOps Engineer - Тоже в чем отличие,. такое ощущение что одинакомые. а оказывается нет',
                    ],
                    [
                        'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                        'text' => 'Что значит "знаю"? Что конкретно знаете? Какие проекты написали?',
                    ],
                    [
                        'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                        'text' => <<<HTML
<p><strong>Software engineer</strong> и инженер-программист - это одно и то же. Под <strong>DevOps </strong>Engineer в разных компаниях имеют в виду разное. <strong>DBA </strong>занимается проектированием баз данных, написанием сложных запросов и хранимых процедур, а также вопросами производительности <strong>СУБД</strong>. Что за хрень data engineer понятия не имею, опять понапридумывали новых названий старых должностей скорее всего.&nbsp;</p>
<div id="gtx-trans" style="position: absolute; left: 412px; top: 106.399px;">
<div class="gtx-trans-icon">&nbsp;</div>
</div>
HTML,
                    ],
                ],
                'answers' => [
                    [
                        'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                        'text' => 'выбирай Python Developer<br>потом Go Developer или DevOps<br><br>вот Питона ты выбрал, открыл вакансии - а там список сразу - и ты понимаешь, что ничего из списка <b>не знаешь и не умееешь</b>, ну и ничего - Питона быстро учить и джуном идти на работу<br><br>а вот ДевОпсом  -это сходу Амазон, Ажур, Гугл - тут уже чуть придется потратиться и  - самое плохое - джуном трудно, все хотят опытного<br><br>за DBA забудь - это Оракл, ПЛСКЛ - там нужен огроменный опыт на больших данных с очень гнутой кривой обратной связи + куча специальных знаний и лексикона<br><br>а инженер - программист - это ты сейчас, ни рыба ни мясо проще говоря, без специализации',
                        'comments' => [
                            [
                                'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                                'text' => 'Чойта вдруг? У нас DBA постгресом занимаются.',
                            ],
                            [
                                'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                                'text' => 'Тоже нет. В нашем случае Kubernetes, GitLab и прочее, работающее на своих серваках, настройка пайплайнов, автоматизация тестирования и деплоя.',
                            ],
                            [
                                'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                                'text' => 'это хорошо ) у Постгре есть претензия на взрослую базу ) ради нее можно и польстить ) хотя, наверно, все уже настолько усложнилось что и Постгресу нужен ДБА',
                            ],
                        ],
                    ],
                ],
                'tags' => [
                    $this->getReference(TagFixtures::TAG_CAREER_IN_IT_REFERER),
                ],
                'subscribers' => [
                    $this->getReference(UserFixtures::USER_IPRUS_REFERENCE),
                    $this->getReference(UserFixtures::USER_TILL_LINDEMANN_REFERENCE),
                ],
            ],
            [
                'title' => 'Как настроить API Docker?',
                'text' => 'Пытаюсь сынтегрировать Jenkins и Docker, но что-то идет не так. Docker работает на Centos 7. По инструкциям настроила докер, подняла по порту 4243. Также есть два Дженкинса. Один поднят в контейнере докера, второй поднят на другом сервере. Цель все же настроить на второй дженкинс. Первый при тестировании доступа к докеру возвращает: Version = 1.11.2-rc1. Второй - Something went wrong, cannot connect to x.x.x.x:4243, cause: null',
                'comments' => [],
                'answers' => [
                    [
                        'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                        'text' => 'да, проблема решилась установкой более новой версии jenkins a. Была 1.5.. ',
                        'comments' => [
                            [
                                'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                                'text' => 'Сам задал, сам ответил',
                            ],
                        ],
                    ],
                ],
                'tags' => [
                    $this->getReference(TagFixtures::TAG_DOCKER_REFERER),
                ],
                'subscribers' => [
                    $this->getReference(UserFixtures::USER_IPRUS_REFERENCE),
                    $this->getReference(UserFixtures::USER_TILL_LINDEMANN_REFERENCE),
                ],
            ],
            [
                'title' => 'Почему может зависать docker-compose?',
                'text' => 'При запуске контейнеров через docker-compose почему-то ничего не происходит и даже завершить docker-compose через "ctrl + c" нельзя, процесс как-будто замораживается, вырубается docker-compose только через "kill -9". <br> Правда такое происходит не всегда, бывает docker-compose стартует адекватно, не могу понять в чем причина, подскажите куда копать?',
                'comments' => [],
                'answers' => [
                    [
                        'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                        'text' => '<b>Решение:</b><br>1. Отключить интернет на момент запуска<br>2. Устновить Exclude simple hostnames',
                        'comments' => [],
                    ],
                ],
                'tags' => [
                    $this->getReference(TagFixtures::TAG_DOCKER_REFERER),
                ],
                'subscribers' => [
                    $this->getReference(UserFixtures::USER_IPRUS_REFERENCE),
                    $this->getReference(UserFixtures::USER_TILL_LINDEMANN_REFERENCE),
                ],
            ],
            [
                'title' => 'Ошибка CORS в Yii2 — react?',
                'text' => 'При попытке авторизации возвращается данная ошибка. Как её исправить?',
                'comments' => [
                    [
                        'user' => $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                        'text' => 'Дык посмотрите отдает ли он вам эти заголовки',
                    ],
                    [
                        'user' => $this->getReference(UserFixtures::USER_IPRUS_REFERENCE),
                        'text' => 'Конкретно про Yii2 ничего сказать не могу, зато могу сказать следующее:<br> 1. У всех систем сборки есть devserver proxy, настраиваешь чтобы xhr запросы к dev версии клиента по особому урлу(например <code class="hljs">/api/*</code>) проксировались на dev бэка(точно также как это будет на реальном сайте с одним доменом) и забываешь о ненужном тебе cors. Делается это обычно одной строчкой конфига.<br> <br> 2. Если таки говорить о cors: обычно простые методы добавления хэдеров работают для простых запросов(POST\GET), а для запроса cors(OPTIONS) надо отдельно извращаться. Так что гугли <code class="hljs">Yii2 OPTIONS Access-Control-Allow-Origin</code> или типа того.',
                    ],
                ],
                'answers' => [],
                'tags' => [
                    $this->getReference(TagFixtures::TAG_PHP_REFERER),
                    $this->getReference(TagFixtures::TAG_JS_REFERER),
                ],
                'subscribers' => [
                    $this->getReference(UserFixtures::USER_EXPERT_REFERENCE),
                    $this->getReference(UserFixtures::USER_IPRUS_REFERENCE),
                    $this->getReference(UserFixtures::USER_TILL_LINDEMANN_REFERENCE),
                ],
            ],
        ];
    }
}
