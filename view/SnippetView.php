<?php

namespace View;

require_once 'model/Snippet.php';

class SnippetView
{
    private static $snippetForm = 'SnippetView::SnippetForm';
    private static $snippetSubmit = 'SnippetView::SnippetSubmit';
    private static $snippetName = 'SnippetView::SnippetName';
    private static $snippetCode = 'SnippetView::SnippetCode';
    private static $messageId = 'SnippetView::Message';

    private $userSnippets;
    private $message = "";

    public function response()
    {
        return $this->generateSnippetHTML();
    }

    public function userWantsToSaveSnippet(): bool
    {
        return isset($_POST[self::$snippetSubmit]);
    }

    public function getSnippet()
    {
        try {
            return new \Model\Snippet($this->getSnippetName(), $this->getSnippetCode());
        } catch (\Model\EmptySnippetException $e) {
            $this->message = 'Name and code is missing.';
        } catch (\Model\SnippetNameShortException $e) {
            $this->message = 'Snippet name has too few characters, at least 3 characters.';
        } catch (\Model\SnippetCodeMissingException $e) {
            $this->message = 'Snippet code is missing.';
        }
    }

    public function setUserSnippets($snippets)
    {
        foreach ($snippets as $snippet) {
            $this->userSnippets .=
            '
            <p>' . $snippet->snippetname . '</p>
            <div style="width:500px;border:1px solid black;background-color: rgb(231, 231, 231);">
            <pre><code>' . $snippet->snippetcode . '</code></pre>
            </div>
        ';
        }
    }

    private function generateSnippetHTML()
    {
        return '
            <fieldset>
                <form method="post" id="' . self::$snippetForm . '">
                    <legend>Save a new snippet - Write snippet name and some code</legend>
                    <p id="' . self::$messageId . '">' . $this->message . '</p>

                    <label for="' . self::$snippetName . '">Snippet name :</label>
                    <input type="text" id="' . self::$snippetName . '" name="' . self::$snippetName . '" value="" />
                </form>
                <br>
                <textarea name="' . self::$snippetCode . '" form="' . self::$snippetForm . '" wrap="hard" rows="12" cols="60" placeholder="Code here..." ></textarea>
                <br>
                <input type="submit" name="' . self::$snippetSubmit . '" value="Save" form="' . self::$snippetForm . '" />
            </fieldset>

            ' . $this->userSnippets . '
		';
    }

    private function getSnippetName()
    {
        if (isset($_POST[self::$snippetName])) {
            return $_POST[self::$snippetName];
        }
    }

    private function getSnippetCode()
    {
        if (isset($_POST[self::$snippetCode])) {
            return $_POST[self::$snippetCode];
        }
    }
}
