<?php

namespace Controller;

class SnippetController
{
    private $snippetView;
    private $sessionModel;
    private $snippetDAL;

    public function __construct(\view\SnippetView $snippetView, \model\SessionModel $sessionModel, \model\SnippetDAL $snippetDAL)
    {
        $this->snippetView = $snippetView;
        $this->sessionModel = $sessionModel;
        $this->snippetDAL = $snippetDAL;
    }

    public function manageSnippets(): void
    {
        if ($this->sessionModel->isLoggedIn()) {
            $sessionUser = $this->sessionModel->getSessionUser();

            if ($this->snippetView->userWantsToSaveSnippet()) {
                $snippet = $this->snippetView->getSnippet();

                if ($snippet) {
                    $this->snippetDAL->storeSnippet($snippet, $sessionUser);
                }
            }
            $this->snippetView->setUserSnippets($this->snippetDAL->getSnippets($sessionUser));
        }
    }
}
