<?php

namespace View;

class SnippetView
{
    private static $snippetForm = 'SnippetView::SnippetForm';
    private static $snippetSubmit = 'SnippetView::SnippetSubmit';
    private static $snippetName = 'SnippetView::SnippetName';
    private static $messageId = 'SnippetView::Message';

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
                    <legend>Post a new snippet - Write snippet name and some code</legend>
                    <p id="' . self::$messageId . '">' . $message . '</p>

                    <label for="' . self::$snippetName . '">Snippet name :</label>
                    <input type="text" id="' . self::$snippetName . '" name="' . self::$snippetName . '" value="" />
                </form>
                <br>
                <textarea form="' . self::$snippetForm . '" wrap="hard" rows="12" cols="60" placeholder="Code here..."></textarea>
                <br>
                <input type="submit" name="' . self::$snippetSubmit . '" value="Save" form="' . self::$snippetForm . '" />
            </fieldset>
		';
    }
}