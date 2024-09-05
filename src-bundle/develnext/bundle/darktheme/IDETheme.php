<?php

namespace develnext\bundle\darktheme;

use ide\Ide;
use ide\project\control\MyProjectControlPane;
use php\desktop\Runtime;
use php\io\ResourceStream;
use php\lang\Thread;
use php\lang\ThreadPool;
use ide\Logger;
use gui;

use ide\project\control\CommonProjectControlPane;
use ide\formats\ProjectFormat;

class IDETheme
{
    /**
     * @var ThreadPool
     */
    private $worker;

    private $cooldown = 1000;

    public function __construct($resource)
    {
        $root = $resource->getPath();

        $themePath = $root . '\dn-dark.dntheme';

        if (!file_exists($themePath)) {
            throw new \Exception(sprintf("Theme file: '%s' not found", $themePath));
        }

        Runtime::addJar($themePath);

        $stylePath = (new ResourceStream('/style/theme.css'))->toExternalForm();

        if ($this->getMainForm()->hasStylesheet($stylePath)) {
            $this->getMainForm()->removeStylesheet($stylePath);
        }

        $this->getMainForm()->addStylesheet($stylePath);

        if ($this->worker == null) {
            $this->worker = ThreadPool::create(4, 4);
        }

        $this->startWorker();
    }


    private function startWorker()
    {
        $this->worker->submit(function () {
            while (true) {
                try {

                    if ($this->getLayout()->lookup("#registerLink") != null) {
                        $this->getLayout()->lookup("#registerLink")->parent->classes->add('syncPane');

                        if ($this->getLayout()->lookup("#registerLink")->parent != null) {
                            $this->getLayout()->lookup("#registerLink")->parent->parent->classes->add('syncPane');

                            if ($this->getLayout()->lookup("#registerLink")->parent->parent != null) {
                                $this->getLayout()->lookup("#registerLink")->parent->parent->children->offsetGet(0)->classes->add('syncPane');
                                break;
                            }
                        }
                    }

                    Thread::sleep($this->cooldown);
                } catch (\Exception $exception) {
                    Logger::error($exception->getLine());
                    Logger::error($exception->getFile());
                    Logger::error($exception->getMessage());
                    Logger::error($exception->getTraceAsString());
                }
            }
        });


        $this->worker->submit(function () {
            while (true) {
                $item = $this->getLayout()->lookup("#content");

                if (array_key_exists('children', get_class_vars($item))) {

                    foreach ($item->children as $key => $node) {
                        if ($key == 3) {
                            if (!$node->classes->has("syncPane")) {
                                $node->style = "-fx-background-color: transparent !important;";
                                if ($node->children->offsetGet(0)->children->count() >= 2) {
                                    break 2;
                                }
                            }
                        }
                    }


                }

                Thread::sleep($this->cooldown);
            }
        });

        $this->worker->submit(function () {
            while (true) {
                $button = $this->getLayout()->lookup('.dn-add-tab-button');

                if ($button !== null) {
                    $button->graphic = null;
                    // $button->parent->style = "-fx-border-color: transparent; -fx-background-color: transparent;";
                    //return;
                }

                sleep(2);
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
     * @return mixed
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
}