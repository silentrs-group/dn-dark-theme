<?php

namespace develnext\bundle\darktheme;

use develnext\bundle\customIcons\editor\{CodeEditorCombinedWrapper, CSSEditor, CustomClassEditor, IDEClassEditor};
use develnext\bundle\customIcons\tab\{CSSFileTab,
    FormTab,
    ModuleTab,
    PHPFileTab,
    ProjectTab,
    SpriteTab,
    TabCombinedWrapper
};
use Exception;
use gui;
use ide\Ide;
use ide\Logger;
use ide\project\control\MyProjectControlPane;
use php\desktop\Runtime;
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXForm;
use php\gui\UXTextField;
use php\io\ResourceStream;
use php\lang\Thread;
use php\lang\ThreadPool;
use php\lib\str;

class IDETheme
{
    /**
     * @var ThreadPool
     */
    private $worker;

    private $cooldown = 1000;
    private $stylePath = '/style/theme.css';

    /**
     * @var \develnext\bundle\customIcons\AbstractFindEditor
     */
    private $editor;

    public function __construct($resource)
    {
        $root = $resource->getPath();

        $themePath = $root . '\dn-dark.dntheme';

        if (!file_exists($themePath)) {
            throw new Exception(sprintf("Theme file: '%s' not found", $themePath));
        }

        Runtime::addJar($themePath);

        $path = (new ResourceStream($this->stylePath))->toExternalForm();

        if ($this->getMainForm()->hasStylesheet($path)) {
            $this->getMainForm()->removeStylesheet($path);
        }

        $this->getMainForm()->addStylesheet($path);

        // UXApplication::setTheme($path);

        if ($this->worker == null) {
            $this->worker = ThreadPool::create(4, 4);
        }

        $this->startWorker();
    }


    private function startWorker()
    {
        CodeEditorCombinedWrapper::registerFinder(new CSSEditor());
        CodeEditorCombinedWrapper::registerFinder(new IDEClassEditor());
        CodeEditorCombinedWrapper::registerFinder(new CustomClassEditor());

        TabCombinedWrapper::registerFinder(new ProjectTab());
        TabCombinedWrapper::registerFinder(new PHPFileTab());
        TabCombinedWrapper::registerFinder(new CSSFileTab());
        TabCombinedWrapper::registerFinder(new FormTab());
        TabCombinedWrapper::registerFinder(new ModuleTab());
        TabCombinedWrapper::registerFinder(new SpriteTab());

        $this->worker->submit(function () {
            $run = true;

            while ($run) {
                uiLater(function () use (&$run) {
                    try {

                        if ($this->getLayout()->lookup("#registerLink") != null) {
                            $this->getLayout()->lookup("#registerLink")->parent->classes->add('syncPane');

                            if ($this->getLayout()->lookup("#registerLink")->parent != null) {
                                $this->getLayout()->lookup("#registerLink")->parent->parent->classes->add('syncPane');

                                if ($this->getLayout()->lookup("#registerLink")->parent->parent != null) {
                                    $this->getLayout()->lookup("#registerLink")->parent->parent->children->offsetGet(0)->classes->add('syncPane');
                                    $run = false;
                                }
                            }
                        }

                    } catch (Exception $exception) {
                        Logger::error(sprintf("File: %s#%s %s", $exception->getLine(), $exception->getFile(), $exception->getMessage()));
                        Logger::error($exception->getTraceAsString());
                    }
                });

                Thread::sleep($this->cooldown);
            }
        });


        $this->worker->submit(function () {
            $run = true;
            while ($run) {
                uiLater(function () use (&$run) {
                    $item = $this->getLayout()->lookup("#content");

                    if ($item == null) return;

                    if (array_key_exists('children', get_class_vars($item))) {

                        foreach ($item->children as $key => $node) {
                            if ($key == 3) {
                                if (!$node->classes->has("syncPane")) {
                                    $node->style = "-fx-background-color: transparent !important;";
                                    if ($node->children->offsetGet(0)->children->count() >= 2) {
                                        $run = false;
                                        break;
                                    }
                                }
                            }
                        }

                    }
                });


                Thread::sleep($this->cooldown);
            }
        });

        $this->worker->submit(function () {
            while (true) {
                uiLater(function () {
                    $button = $this->getLayout()->lookup('.dn-add-tab-button');

                    if ($button !== null) {
                        $button->graphic = null;
                    }
                });

                sleep(2);
            }
        });

        $this->worker->submit(function () {

            while (true) {
                try {
                    uiLater(function () {
                        CodeEditorCombinedWrapper::applyStyle();

                        TabCombinedWrapper::applyIcon();

                        Ide::get()->getMainForm()->headRightPane->children[0]->children[0]->graphic = new UXHBox();
                        Ide::get()->getMainForm()->headRightPane->children[0]->children[0]->graphic->classes->add('button-search');
                    });


                    // $this->unbindShortcut($this->getIDETab()->selectedTab->content->items->offsetGet(1)->tabs[1]->content->children[2], 'Ctrl + F');

                    // $node = $this->getIDETab()->selectedTab->content->items[1]->children; // any right panel in project tab

                    // if selected project, module, form
                    // $node = $this->getIDETab()->selectedTab->content->items->offsetGet(0); // node properties
                    // $node = $this->getIDETab()->selectedTab->content->items->offsetGet(1); // tabs design, code

                    // return UXVBox if selected class, css, text any file without properties how module, form
                    // $node = $this->getIDETab()->selectedTab->content;

                    // $node = $this->getIDETab()->selectedTab->content->items->offsetGet(1)->tabs[1]; // code area tab
                    // $node = $this->getIDETab()->selectedTab->content->items->offsetGet(1)->tabs[1]->content->children[1]; // code area scroll pane

                } catch (Exception $exception) {
                    Logger::error($exception->getMessage());
                }


                Thread::sleep(500);
            }
        });


    }


    /**
     * @return UXForm
     */
    private function getMainForm()
    {
        return Ide::get()->getMainForm();
    }

    /**
     * @return \php\gui\layout\UXPane
     */
    private function getLayout()
    {
        return $this->getMainForm()->layout;
    }

    public function off()
    {
        if (!$this->worker->isShutdown())
            $this->worker->shutdownNow();
    }

    public function __destruct()
    {
        $this->off();
    }


    private function makeFinder($target, $pane)
    {
        // dead code >>>

        $this->bindShortcut($target, function () use ($target, $pane) {
            static $container = null;

            if ($container != null) {
                return;
            }

            $container = new UXHBox([
                $searchInput = new UXTextField(),
                $searchNext = new UXButton(),
                $searchPrev = new UXButton(),
                $searchClose = new UXButton()
            ]);

            $searchNext->graphic = new UXHBox();
            $searchPrev->graphic = new UXHBox();
            $searchClose->graphic = new UXHBox();

            $container->classes->add("search-container");
            $searchInput->classes->add("search-input");

            $searchNext->graphic->classes->add("search-next");
            $searchPrev->graphic->classes->add("search-prev");
            $searchClose->graphic->classes->add("search-close");

            $searchInput->promptText = 'Поиск...';

            $pane->add($container);


            $searchInput->observer("text")->addListener(function ($o, $new) use ($searchInput) {
                // $this->trigger('update', []); // from codeEditor
                $text = $this->editor->getCurrentEditor()->text;

                $find = [];
                $index = 0;

                $this->editor->objectDump($this->editor->getCurrentEditor());

                if (empty($new)) return;

                while (($pos = str::pos($text, $new, $index)) != -1) {
                    $find[] = $pos;
                    $index = $pos + 1;
                }

                // $this->editor->getCurrentEditor()->select($find[0], strlen($new));

                $this->editor->objectDump($find);
                $searchInput->data("search", $find);
            });

            $searchInput->requestFocus();

            $unbind = function () use (&$container, $target) {
                $container->free();
                $container = null;

                $this->unbindShortcut($target, 'Esc');
            };

            $searchClose->on("click", $unbind);
            $this->bindShortcut($container, $unbind, 'Esc');
            $this->bindShortcut($target, $unbind, 'Esc');
        }, "Ctrl + F");
    }

    public function bindShortcut($node, $callback, $keys)
    {
        $node->on('keyUp', function ($ev) use ($keys, $callback) {
            if ($ev->matches($keys)) $callback();
        }, $keys);
    }

    public function unbindShortcut($node, $keys)
    {
        if ($keys == null) {
            $node->off('keyUp');
        } else {
            $node->off('keyUp', $keys);
        }
    }

}