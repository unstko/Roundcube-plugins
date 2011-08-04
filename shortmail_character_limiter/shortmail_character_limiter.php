<?php
/**
 * Shortmail character limiter
 *
 * Roundcube plugin to limit the characters of an email to 500 if the used IMAP
 * host is shortmail (http://shortmail.com).
 *
 * @version 0.0.1
 * @author Stefan Koch
 * @url https://github.com/unstko/Roundcube-plugins
 * @licence MIT License
 *
 * Copyright (c) 2011 Stefan Koch
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
class shortmail_character_limiter extends rcube_plugin
{
    /**
     * @var string Task to run the plugin in.
     */
    public $task = 'mail';

    /**
     * @var mixed Instance of rcmail.
     */
    protected $rcmail = null;

    /**
     * Mandatory method to initialize the plugin.
     *
     * @return void
     */
    public function init()
    {
        // Get rcmail instance
        $this->rcmail = rcmail::get_instance();

        // Load localization
        //$this->add_texts('localization');

        // Load configuration
        //$this->load_config('config/config.inc.php.dist');
        //$this->load_config('config/config.inc.php');

        // Link hook for the compose step to method message_compose
        $this->add_hook('message_compose', array($this, 'message_compose'));

        // Include javascript file
        $this->include_script('shortmail_character_limiter.js');
    }

    /**
     * Callback method for the compose step.
     *
     * @param mixed $param Array with request parameters.
     * @return mixed Compose parameters like 'to', 'subject', 'body'.
     */
    public function message_compose($param)
    {
        // Check for task mail
        if ($this->rcmail->task != "mail") {
            return $args;
        }

        // TODO: Add functionality to use the javascript file

        // Return (modified) parameters
        return $param;
    }
}
?>
