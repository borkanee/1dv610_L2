<?php

namespace View;

class SnippetView
{
    private static $snippetForm = 'SnippetView::SnippetForm';
    private static $snippetSubmit = 'SnippetView::SnippetSubmit';
    private static $snippetName = 'SnippetView::SnippetName';
    private static $snippetCode = 'SnippetView::SnippetCode';
    private static $messageId = 'SnippetView::Message';

    private $userSnippets;

    public function __construct()
    {
    }

    public function response()
    {
        $message = "";
        $response = $this->generateSnippetHTML($message);

        return $response;
    }

    /**
     * Generate HTML code on the output buffer for the snippet view
     * @param $message, String output message
     * @return void, BUT writes to standard output!
     */
    private function generateSnippetHTML($message)
    {
        return '
            <fieldset>
                <form method="post" id="' . self::$snippetForm . '">
                    <legend>Save a new snippet - Write snippet name and some code</legend>
                    <p id="' . self::$messageId . '">' . $message . '</p>

                    <label for="' . self::$snippetName . '">Snippet name :</label>
                    <input type="text" id="' . self::$snippetName . '" name="' . self::$snippetName . '" value="" required/>
                </form>
                <br>
                <textarea name="' . self::$snippetCode . '" form="' . self::$snippetForm . '" wrap="hard" rows="12" cols="60" placeholder="Code here..." required></textarea>
                <br>
                <input type="submit" name="' . self::$snippetSubmit . '" value="Save" form="' . self::$snippetForm . '" />
            </fieldset>

            ' . $this->userSnippets . '
		';
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

    public function getSnippetName()
    {
        if (isset($_POST[self::$snippetName])) {
            return $_POST[self::$snippetName];
        }
    }

    public function getSnippetCode()
    {
        if (isset($_POST[self::$snippetCode])) {
            return $_POST[self::$snippetCode];
        }
    }

    public function userWantsToSaveSnippet(): bool
    {
        return isset($_POST[self::$snippetSubmit]);
    }

    public function getSnippet() {}
}
