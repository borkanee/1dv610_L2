<?php

namespace Controller;

class SnippetController
{
    private $snippetView;
    private $loginModel;
    private $snippetModel;

    public function __construct(\view\SnippetView $snippetView, \model\Login $loginModel, \model\Snippets $snippetModel)
    {
        $this->snippetView = $snippetView;
        $this->loginModel = $loginModel;
        $this->snippetModel = $snippetModel;
    }

    public function manageSnippets(): void
    {
        if ($this->loginModel->isLoggedIn()) {

            if ($this->snippetView->userWantsToSaveSnippet()) {
                $snippetName = $this->snippetView->getSnippetName();
                $snippetCode = $this->snippetView->getSnippetCode();
                $this->snippetModel->storeSnippet($this->loginModel->getSessionUser(), $snippetName, $snippetCode);
            }
            $this->snippetView->setUserSnippets($this->snippetModel->getSnippets($this->loginModel->getSessionUser()));
        }
    }
}
